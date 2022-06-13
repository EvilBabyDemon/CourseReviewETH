<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="/~lteufelbe/default.css" rel="stylesheet" type="text/css" />
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

                $ducky = "https://rubberducky.vsos.ethz.ch:1855/course/";
                $ducky = $ducky . $url;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ducky);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CAINFO, "../cacert-2022-04-26.pem");

                $result = curl_exec($ch);
                $info = curl_getinfo($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $js = json_decode($result, false);
                $js = json_decode($js, false);
                
                foreach ($js as $key => $val) {
                    foreach ($val as $nkey => $review) {
                        echo "<hr>" . $review ;
                    }
                }
                print $code;

                /*
                $db = new SQLite3('../CourseReviews.db');
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
                    echo 'There is no review here yet, please add one if you visited the course already!';
                }
                $db->close();
                */
            } else {
                echo 'This is no course nor does this page exist. So here you have an error code: <b>404</b>';
            }
            ?>

        </div>
        <div id="footer">
            <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:lteufelbe@ethz.ch">lteufelbe@ethz.ch</a></p>
        </div>
    </div>
</body>

</html>