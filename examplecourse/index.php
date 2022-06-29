<!DOCTYPE html>
<html lang="en">

<?php
$nethz = $_SERVER["REQUEST_URI"];
if (strstr($nethz, "/student") and strstr($nethz, "/student", true) == "") {
    $nethz = substr($nethz, 9, strlen($nethz));
} else {
    $nethz = substr($nethz, 2, strlen($nethz));
}
$nethz = substr($nethz, 0, strpos($nethz, "/"));
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
    <?php include 'includes/menu.php' ?>
    <div id="content">
        <div id="columnA">

            <?php
            $url = $_SERVER["REQUEST_URI"];
            $url = substr($url, strpos($url, "coursereview"), strlen($url));
            $url = str_replace("coursereview/", "", $url);
            $url = substr($url, 0, strpos($url, "/"));

            $db = new SQLite3('../CourseReviews.db');
            $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
            $stmt->bindParam(':course', $url, SQLITE3_TEXT);
            $result = $stmt->execute();
            if ($course = $result->fetchArray()) {
                print "<b>$url $course[0]</b><br>";
                $db->close();

                $db = new SQLite3('../CourseReviews.db');
                $stmt = $db->prepare("SELECT * FROM REVIEWS WHERE COURSE=:course");
                $stmt->bindParam(':course', $url, SQLITE3_TEXT);
                $result = $stmt->execute();

                $empty = true;
                while ($row = $result->fetchArray()) {
                    echo "<br> <div class=\"box\">" . nl2br(htmlspecialchars($row[2])) . "</div>";
                    $empty = false;
                }

                if ($empty) {
                    echo 'There is no review here yet, please add one if you visited the course already!';
                }
                $db->close();
            } else {
                echo 'This is no course nor does this page exist. So here you have an error code: <b>404</b>';
            }
            ?>

        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>

</html>