<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("../secret/key.txt");
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; object-src 'none'">
    <meta name="viewport" content="width=device-width">
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>CourseReview</h1>
        <h2>&nbsp;</h2>
    </div>
    <?php include 'includes/menu.php' ?>
    <div id="content">
        <div id="columnA">

            <?php
            $url = $_SERVER["REQUEST_URI"];
            $url = substr($url, strpos($url, "coursereview"), strlen($url));
            $url = str_replace("coursereview/", "", $url);
            $url = substr($url, 0, strpos($url, "/"));
            $url = trim($url);

            $db = new SQLite3('../CourseReviews.db');
            $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
            $stmt->bindParam(':course', $url, SQLITE3_TEXT);
            $result = $stmt->execute();
            if ($course = $result->fetchArray()) {
                print "<b>$url $course[0]</b><br>";
                $db->close();

                function getReviews(String $url, String $token)
                {
                    $ducky = "https://rubberducky.vsos.ethz.ch:1855/course/";
                    $ducky = $ducky . $url;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $ducky);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CAINFO, "../cacert-2022-04-26.pem");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));

                    $result = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($code == 401) {
                        return true;
                    }
                    $js = json_decode($result, false);
                    $js = json_decode($js, false);

                    foreach ($js as $key => $val) {
                        foreach ($val as $nkey => $review) {
                            echo "<hr>" . nl2br(htmlspecialchars($review));
                        }
                    }
                    return false;
                }
                if (getReviews($url, $token)) {
                    //get new token
                    require_once('../newToken.php');
                    $token = newToken();
                    getReviews($url, $token);
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