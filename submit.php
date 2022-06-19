<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; object-src 'none'">
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../default.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>CourseReview</h1>
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
            <p><b>Thx for your submission!</b></p>

            <?php
            //check DB if Course exists
            $course = $_POST["course"] . " ";
            $course = substr($course, 0, strpos($course, " "));

            $db = new SQLite3('CourseReviews.db');

            $stmt = $db->prepare("SELECT * FROM COURSES WHERE COURSE=:course");
            $stmt->bindParam(':course', $course, SQLITE3_TEXT);
            $result = $stmt->execute();
            if (!$row = $result->fetchArray()) {
                print "<p>But are you sure this course (" . htmlspecialchars($course) . ") exists? If it does, contact me: lteufelbe@ethz.ch <br> I didn't save it. But here you can copy your text again:</p> <br>";
                echo htmlspecialchars($_POST["review"]);
                $db->close();
                exit();
            }
            $db->close();

            function submitReview(String $ducky, String $token)
            {
                $ch = curl_init($ducky);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

                // Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $token));

                $result = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                // Close cURL session handle
                curl_close($ch);

                if ($code == 401) {
                    return true;
                }
                // handle curl error
                if ($code != 200) {
                    print "Error Code: $code <br>";
                    print "Something went wrong I am sorry. Here you can copy your text again as I did not save it:<br>";
                    echo htmlspecialchars($_POST["review"]);
                } else {
                    if ($result == '"inserted"') {
                        print "<p>We will verify your review to make sure it isn't attacking anyones honour.</p>";
                    } else {
                        print 'You already inserted a review for this course. Go under <a href="https://n.ethz.ch/~lteufelbe/coursereview/edit.php">Edit</a> to change it.<br>';
                    }
                    echo htmlspecialchars($_POST["course"]);
                    print "<br>";
                    echo htmlspecialchars($_POST["review"]);
                }
                return false;
            }
            $data = array(
                'course_id' => $course,
                'nethz' => $val,
                'review' => $_POST["review"],
            );
            $ducky = "https://rubberducky.vsos.ethz.ch:1855/insert?";
            $ducky = $ducky . http_build_query($data);
            if (submitReview($ducky, $token)) {
                //get new token
                require_once('newToken.php');
                $token = newToken();
                submitReview($ducky, $token);
            }
            ?>
        </div>
    </div>


    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:lteufelbe@ethz.ch">lteufelbe@ethz.ch</a></p>
    </div>

</body>

</html>