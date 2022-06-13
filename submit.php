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
            <li><a href="https://n.ethz.ch/~lteufelbe/coursereview/" onFocus="if(this.blur)this.blur()">CourseReview</a></li>
            <li><a href="https://n.ethz.ch/~lteufelbe/coursereview/add.php" onFocus="if(this.blur)this.blur()">Add</a></li>
            <li><a href="https://n.ethz.ch/~lteufelbe/coursereview/edit.php" onFocus="if(this.blur)this.blur()">Edit</a></li>
        </ul>
    </div>
    <?php
    $surname = $_SERVER["surname"];
    $name = $_SERVER["givenName"];
    $val = $_SERVER["uniqueID"];
    ?>
    <div id="content">
        <div id="columnA">
            <p><b>Thx for your submission!</b><br>

                <?php
                //check DB if Course exists

                $course = substr($_POST["course"], 0, strpos($_POST["course"], " "));

                $db = new SQLite3('CourseReviews.db');

                $stmt = $db->prepare("SELECT * FROM COURSES WHERE COURSE=:course");
                $stmt->bindParam(':course', $course, SQLITE3_TEXT);
                $result = $stmt->execute();
                if (!$row = $result->fetchArray()) {
                    print "<p>But are you sure this course ($course) exists? If it does contact me: lteufelbe@ethz.ch <br> I didn't save it. But here you can copy your text again:</p> <br>";
                    echo $_POST["review"];
                    $db->close();
                    exit();
                }
                $db->close();

                $data = array(
                    'course_id' => $course,
                    'nethz' => $val,
                    'review' => $_POST["review"],
                );
                $post_data = json_encode($data);
                print var_dump($post_data);
                $ch = curl_init("https://rubberducky.vsos.ethz.ch:1855/insert");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

                // Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

                $result = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                // Close cURL session handle
                curl_close($ch);
                print var_dump($code);
                // handle curl error
                if ($code != 200) {
                    print "Something went wrong I am sorry. Here you can copy your text again as I did not save it:</p> <br>";
                    echo $_POST["review"];
                    print var_dump($result);
                } else {

                    print $result;
                    echo $_POST["course"];
                    print "<br>";
                    echo $_POST["review"];
                }
                ?>
        </div>
    </div>


    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:lteufelbe@ethz.ch">lteufelbe@ethz.ch</a></p>
    </div>

</body>

</html>