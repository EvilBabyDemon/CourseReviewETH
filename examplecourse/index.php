<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../../default.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>CourseReview coming up</h1>
        <h2>&nbsp;</h2>
    </div>
    <div id="menu">
        <ul>
            <li><a href="https://n.ethz.ch/~lteufelbe/coursereview/" onFocus="if(this.blur)this.blur()">CourseReview</a></li>
            <li><a href="https://n.ethz.ch/~lteufelbe/coursereview/add.php" onFocus="if(this.blur)this.blur()">Add</a></li>
            <li><a href="https://n.ethz.ch/~lteufelbe/coursereview/edit.php" onFocus="if(this.blur)this.blur()">Edit</a></li>
        </ul>
    </div>
    <div id="content">
        <div id="columnA">

            <?php
            $db = new SQLite3('../CourseReviews.db');

            $url = $_SERVER["REQUEST_URI"];
            $url = substr($url, strpos($url, "coursereview"), strlen($url));
            $url = str_replace("coursereview/", "", $url);
            $url = substr($url, 0, strpos($url, "/"));

            $stmt = $db->prepare("SELECT * FROM REVIEWS WHERE COURSE=:course");
            $stmt->bindParam(':course', $url, SQLITE3_TEXT);
            $result = $stmt->execute();

            $empty = true;
            while ($row = $result->fetchArray()) {
                print $row[2];
                print "<hr>";
                $empty = false;
            }

            if ($empty) {
                echo 'There was a review once here, but sadly it is gone';
            }
            $db->close();
            ?>

        </div>
        <div id="footer">
            <p>Nothing interesting to see here</p>
        </div>
    </div>
</body>

</html>