<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
$api = trim(file_get_contents("secret/api.txt"));
?>

<?php
if(!isset($_GET["course"])) {
    exit();
}

$course_nr = $_GET["course"];

$db = new SQLite3('secret/CourseReviews.db');
$stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
$stmt->bindParam(':course', $course_nr, SQLITE3_TEXT);
$result = $stmt->execute();
$course = $result->fetchArray();
$course_url = "";
if ($course) {
    $course_url = "?course=" . $course_nr;
}
?>

<?php
function getAvgHead(String $course_nr, String $token, String $api)
{
    $ducky = $api . "rating/";
    $ducky = $ducky . $course_nr;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ducky);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));

    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code == 401) {
        return true;
    }
    if ($code != 200) {
        return false;
    }
    $js = json_decode($result, false);
    $js = json_decode($js, false);
    $sum = 0.0;
    $acc = 0.0;
    foreach ($js[0] as $nkey => $stars) {
        if ($stars == null) {
            continue;
        }
        $sum += doubleval($stars);
        $acc += 1;
    }
    if($acc != 0) {
        $res = round($sum/$acc, 2);
        print ": Average rating: $res/5";
    }

    return false;
}
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy">
    <title>CourseReview</title>
    <link rel="icon" href="icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="main.css" rel="stylesheet" type="text/css" />
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="https://n.ethz.ch/~lteufelbe/coursereview/"/>
    <meta property="og:title" content="CourseReview"/>
    <meta property="og:description" content="<?php
    print "Reviews for $course[0]"; 
    if ($course) {
        if (getAvgHead($course_nr, $token, $api, false)) {
            //get new token
            require_once('newToken.php');
            $token = newToken();
            getAvgHead($course_nr, $token, $api, false);
        }
    }
    ?>"/>
    <meta property="og:image" content="https://n.ethz.ch/~lteufelbe/coursereview/icon.png"/>
</head>

<body>
    <?php include 'includes/menu.php' ?>
    <div id="content">
        <div id="columnA">

            <?php
            if ($course) {
                print "<b>$course_nr $course[0]</b><br>";
                $db->close();

                function getAvg(String $course_nr, String $token, String $api)
                {
                    $ducky = $api . "rating/";
                    $ducky = $ducky . $course_nr;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $ducky);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));

                    $result = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($code == 401) {
                        return true;
                    }
                    if ($code != 200) {
                        return false;
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

                        print $rating_names[$nkey];
            ?>
                        <div class="stars-outer">
                            <div class="stars-inner" style="width: <?php echo (intval(doubleval($stars) * 20)) ?>%;"></div>
                        </div>
                        <br>
            <?php
                    }
                    return false;
                }

                function getReviews(String $course_nr, String $token, String $api)
                {
                    $ducky = $api . "course/";
                    $ducky = $ducky . $course_nr;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $ducky);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));

                    $result = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($code == 401) {
                        return true;
                    }

                    $js = json_decode($result, false);
                    $js = json_decode($js, false);

                    if (sizeof($js) == 0) {
                        echo "There is no review here yet. Would be nice if you add one if you took this course.";
                    }

                    foreach ($js as $val) {
                        foreach ($val as $nkey => $review) {
                            echo "<br> <div class=\"box\">" . nl2br(htmlspecialchars($review)) . "</div>";
                        }
                    }
                    return false;
                }
                if (getAvg($course_nr, $token, $api)) {
                    //get new token
                    require_once('newToken.php');
                    $token = newToken();
                    getAvg($course_nr, $token, $api);
                }

                if (getReviews($course_nr, $token, $api)) {
                    //get new token
                    require_once('newToken.php');
                    $token = newToken();
                    getReviews($course_nr, $token, $api);
                }
            } else {
                echo 'This is no course nor does this page exist. So here you have an error code: <b>404</b>';
            }
            ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>