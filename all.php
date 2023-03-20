<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
$api = trim(file_get_contents("secret/api.txt"));
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; object-src 'none'">
    <title>CourseReview</title>
    <?php include "meta.php" ?>
    <link rel="icon" href="icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include 'includes/menu.php' ?>
    <div id="content">
        <div id="columnA">
            <p><b>All courses with Reviews</b></p>

            <?php

            function getAll(String $ducky, String $token)
            {
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
                $js = json_decode($result);
                $js = json_decode($js);
            ?>
                <ul>
                    <?php
                    $db = new SQLite3('secret/CourseReviews.db');
                    foreach ($js as $value) {
                        $coursename = "";
                        $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                        $stmt->bindParam(':course', $value->CourseNumber, SQLITE3_TEXT);
                        $qresult = $stmt->execute();
                        if ($row = $qresult->fetchArray()) {
                            $coursename = $row[0];
                        }
                        echo '<li><a href="https://n.ethz.ch/~lteufelbe/coursereview/course/' . htmlspecialchars($value->CourseNumber) . '/">' .
                            htmlspecialchars($value->CourseNumber) . ' <b>' . htmlspecialchars($coursename) . '</b></a></li>';
                    }
                    $db->close();
                    ?>
                </ul>
            <?php
                return false;
            }
            $ducky = $api . "allReviews";
            if (getAll($ducky, $token)) {
                //get new token
                require_once('newToken.php');
                $token = newToken();
                getAll($ducky, $token);
            }
            ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>