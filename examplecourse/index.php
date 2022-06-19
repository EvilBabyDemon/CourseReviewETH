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
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="/~<?php echo $nethz;?>/default.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>CourseReview</h1>
        <h2>&nbsp;</h2>
    </div>
    <div id="menu">
        <ul>
            <li><a href="https://n.ethz.ch/~<?php echo $nethz;?>/coursereview/" onFocus="if(this.blur)this.blur()">CourseReview</a></li>
            <li><a href="https://n.ethz.ch/~<?php echo $nethz;?>/coursereview/add.php" onFocus="if(this.blur)this.blur()">Add</a></li>
            <li><a href="https://n.ethz.ch/~<?php echo $nethz;?>/coursereview/edit.php" onFocus="if(this.blur)this.blur()">Edit</a></li>
        </ul>
    </div>
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
                    echo "<hr>" . nl2br(htmlspecialchars($row[2]));
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
        <div id="footer">
            <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:<?php echo $nethz;?>@ethz.ch"><?php echo $nethz;?>@ethz.ch</a></p>
        </div>
    </div>
</body>

</html>