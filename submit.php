<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
$api = trim(file_get_contents("secret/api.txt"));
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; object-src 'none'">
    <title>Review</title>
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include 'includes/menu.php' ?>
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

            $db = new SQLite3('secret/CourseReviews.db');

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
            $ducky = $api . "insert?";
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
    <?php include 'includes/footer.php'; ?>
</body>

</html>