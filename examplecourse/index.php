<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../default.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>CourseReview coming up</h1>
        <h2>&nbsp;</h2>
    </div>
    <div id="menu">
        <ul>
            <li><a href="../" onFocus="if(this.blur)this.blur()">Home</a></li>
            <li><a href="../download/" onFocus="if(this.blur)this.blur()">Download</a></li>
            <li><a href="#" onFocus="if(this.blur)this.blur()">Private</a></li>
        </ul>
    </div>
    <div id="content">
        <div id="columnA">

            <?php
            //check DB if Course exists
            $db = new SQLite3('CourseReviews.db');
            
            $url = str_replace("/~lteufelbe/coursereview/", "", $_SERVER["REQUEST_URI"]);
            $url = substr($url, 0, strpos($url, "/"));  
            
            $stmt = $db->prepare("SELECT FROM REVIEWS WHERE COURSE=:course)");
            $stmt->bindParam(':course', $url, SQLITE3_TEXT);
            $result = $stmt->execute();
            if (!$result->numColumns()) {
                system("echo 'There was a review once here, but sadly it is gone';");
                exit();
            }
            var_dump($result->fetchArray());
            $db->close()
            ?>

        </div>
        <div id="footer">
            <p>Nothing interesting to see here</p>
        </div>
    </div>
</body>

</html>