<!DOCTYPE html>
<html lang="en">
<?php
if (!isset($_GET["course"])) {
    exit();
}

$course_nr = $_GET["course"];

$db = new SQLite3('secret/CourseReviews.db');
$stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
$stmt->bindParam(':course', $course_nr, SQLITE3_TEXT);
$result = $stmt->execute();
$course = $result->fetchArray();
$course_url = "";
$db->close();
if ($course) {
    $course_url = "?course=" . $course_nr;
}
?>

<?php
function getRatingsHead(String $course_nr)
{
    $ducky = "https://rubberducky.vsos.ethz.ch:1855/rating/";
    $ducky = $ducky . $course_nr;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ducky);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");

    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code != 200) {
        print($code);
        return;
    }
    $js = json_decode($result, false);
    $js = json_decode($js, false);

    $rating_names = [
        "AVG(Recommended)" => "Would <b>recommend</b> it",
        "AVG(Interesting)" => "<b>Interesting</b> content",
        "AVG(Difficulty)" => "Approriate <b>difficulty</b>",
        "AVG(Effort)" => "Approriate amount of <b>effort</b>",
        "AVG(Resources)" => "Amount and quality of <b>resources</b>"
    ];


    foreach ($js[0] as $nkey => $stars) {
        if ($stars == null) {
            continue;
        }
        //Printing star amount with respective Description
        print "[" . $rating_names[$nkey] . ": " . round(doubleval($stars), 2) . "] ";
    }
    return;
}
?>
<script>
    //add ratings body
    function addRatings() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            if (this.status == 200) {

                var ratings = {
                    "AVG(Recommended)": "Would <b>recommend</b> it",
                    "AVG(Interesting)": "<b>Interesting</b> content",
                    "AVG(Difficulty)": "Approriate <b>difficulty</b>",
                    "AVG(Effort)": "Approriate amount of <b>effort</b>",
                    "AVG(Resources)": "Amount and quality of <b>resources</b>"
                };

                var resp = JSON.parse(JSON.parse(this.responseText))[0];
                var stars = document.createElement("div");

                for (var key in ratings) {
                    if (resp[key] == null) {
                        continue;
                    }
                    //body
                    var div = document.createElement("div");
                    div.innerHTML = ratings[key];
                    var starsOuter = document.createElement("div");
                    starsOuter.classList.add("stars-outer");
                    var starsInner = document.createElement("div");
                    starsInner.classList.add("stars-inner");
                    starsInner.style = "width: " + Math.round(resp[key] * 20) + "%;";

                    div.appendChild(starsOuter);
                    starsOuter.appendChild(starsInner);

                    stars.appendChild(div);
                }
                document.getElementById("columnA").insertBefore(stars, document.getElementById("reviews"));
            }
        }
        xmlhttp.open("GET", "https://rubberducky.vsos.ethz.ch:1855/rating/" + window.location.href.split("?course=")[1], true);
        xmlhttp.send();
    }
</script>


<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy">
    <title><?php if ($course) { print $course[0] . " - "; } ?>CourseReview</title>
    <link rel="icon" href="icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="Reviews and ratings on CourseReview of a specific course from ETHZ." />
    <link href="main.css" rel="stylesheet" type="text/css" />
    <?php
    if ($course) {
    ?>
        <meta property="og:image" content="https://n.ethz.ch/~lteufelbe/coursereview/icon.png" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="CourseReview" />
        <meta property="og:title" content="<?php print "$course_nr: $course[0]"; ?>" />
        <meta property="og:description" content="<?php getRatingsHead($course_nr); ?>" />
        <meta property="og:url" content="https://n.ethz.ch/~lteufelbe/coursereview/?course=<?php print htmlspecialchars($course_nr) ?>" />
    <?php
    } else {
        require("meta.php");
    ?>
        <meta property="og:url" content="https://n.ethz.ch/~lteufelbe/coursereview/">
        <meta property="og:title" content="CourseReview Homepage">
    <?php
    }

    ?>
</head>

<script>
    //add reviews
    function addReviews() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            if (this.status == 200) {

                var resp = JSON.parse(JSON.parse(this.responseText));

                var reviews = document.createElement("div");
                reviews.id = "reviews";
                if (resp.length == 0) {
                    reviews.textContent = "There is no review here yet. Would be nice if you add one if you took this course.";
                } else {
                    for (var rev of resp) {
                        reviews.appendChild(document.createElement("br"));
                        var box = document.createElement("p");
                        box.classList.add("box");
                        box.innerText = rev["Review"];

                        if (rev["Semester"] != null && rev["Semester"].trim() != "") {
                            var semester = document.createElement("div");
                            semester.classList.add("semester");
                            semester.classList.add("box");
                            semester.textContent = rev["Semester"];
                            box.appendChild(semester);
                        }

                        reviews.appendChild(box);
                    }
                }
                document.getElementById("columnA").appendChild(reviews);
            }
        }
        xmlhttp.open("GET", "https://rubberducky.vsos.ethz.ch:1855/course/" + window.location.href.split("?course=")[1], true);
        xmlhttp.send();
    }
</script>


<body>
    <?php include 'includes/menu.php' ?>
    <div id="content">
        <div id="columnA">

            <?php

            if ($course) {
                print "<b>$course_nr $course[0]</b><br>";
            ?>
                <script>
                    addRatings();
                    addReviews();
                </script>
            <?php
            } else {
                echo 'This course number is not correct! If you think there should be a course with that number please contact me.';
            }
            ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>