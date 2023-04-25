<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
$api = trim(file_get_contents("secret/api.txt"));
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy">
    <title>CourseReview</title>
    <?php include "meta.php" ?>
    <meta property="og:url" content="https://n.ethz.ch/~lteufelbe/coursereview/">
    <meta property="og:title" content="CourseReview Homepage">
    <link rel="icon" href="icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="main.css" rel="stylesheet" type="text/css" />
    <?php
    if (isset($_POST["course"])) {
        $course = $_POST["course"] . " ";
        $course = substr($course, 0, strpos($course, " "));
        $db = new SQLite3('secret/CourseReviews.db');
        $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
        $stmt->bindParam(':course', $course, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result->fetchArray()) {
            echo "<meta http-equiv=\"Refresh\" content=\"0; url='?course=$course'\" />)";
            $db->close();
            exit();
        }
        $db->close();
    }
    ?>

</head>

<body>
    <?php include 'includes/menu.php' ?>
    <div id="content">
        <div id="columnA">
            <?php
            if (isset($_POST["course"])) {
                $db = new SQLite3('secret/CourseReviews.db');
                $stmt = $db->prepare("SELECT * FROM COURSES WHERE NAME Like '%' || REPLACE(:input, ' ', '%') || '%' limit 10;");
                $stmt->bindParam(':input', $_POST["course"], SQLITE3_TEXT);
                $result = $stmt->execute();
            ?>
                Your search didn't find an exact result, so here are the closest ones: <br>
                <ol>
                    <?php
                    while ($row = $result->fetchArray()) {
                    ?>
                        <li><a href="<?php echo "?course=" . htmlspecialchars($row[0]); ?>"><?php echo htmlspecialchars($row[0]) . " <b>" . htmlspecialchars($row[1]) . "</b>"; ?></a></li>
                    <?php
                    }
                    $db->close();
                    ?>
                </ol>
            <?php
            }
            ?>


            <form method="post" action="#">
                <input id="search" list="courses" name="course" placeholder="Search for Reviews">
                <datalist id="courses">
                    <?php
                    $db = new SQLite3('secret/CourseReviews.db');
                    $stmt = $db->prepare("SELECT * FROM COURSES;");
                    $result = $stmt->execute();

                    while ($row = $result->fetchArray()) {
                    ?>
                        <option value="<?php print htmlspecialchars($row[0]) . " " . htmlspecialchars($row[1]); ?>">
                        <?php
                    }
                    $db->close();
                        ?>
                </datalist>
                <input id="searchbutton" type="submit" value="Search">
            </form>


            <script>
                //get stats
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onload = function() {
                        if (this.status == 200) {
                            var total = document.getElementById("total");
                            var percourse = document.getElementById("percourse");
                            var resp = JSON.parse(JSON.parse(this.responseText))[0];
                            total.textContent = resp.total;
                            percourse.textContent = resp.percourse;
                        }
                    }
                    xmlhttp.open("GET", "https://rubberducky.vsos.ethz.ch:1855/stats", true);
                    xmlhttp.send();

                }

                //get latest
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onload = function() {
                        if (this.status == 200) {
                            var latest = document.getElementById("latest");
                            var resp = JSON.parse(JSON.parse(this.responseText));
                            for (row of resp) {
                                var li = document.createElement("li");
                                var link = document.createElement("a");
                                link.textContent = row.CourseName;
                                link.href = "?course=" + row.CourseNumber;
                                li.appendChild(link);
                                latest.appendChild(li);
                            }
                        }
                    }
                    xmlhttp.open("GET", "https://rubberducky.vsos.ethz.ch:1855/latestReviews", true);
                    xmlhttp.send();
                }
            </script>

            <h3>Welcome!</h3>
            <p>Here you can add and read reviews of courses from ETHZ!</p>
            <a href="add/">Add a review!</a> <br>
            <a href="edit/">Edit your existing reviews!</a> <br>
            <a href="all.php">All courses with reviews!</a> <br>
            <a href="https://ergebnisseub.sp.ethz.ch/" target="_blank">Results of the Teaching evaluation</a> <br>


            <b id="total"> </b> reviews for <b id="percourse"> </b> courses have been published so far.
            <br> Courses with the newest Reviews:
            <ul id="latest">
            </ul>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>