<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("../secret/key.txt");
$api = trim(file_get_contents("../secret/api.txt"));
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy">
    <title>CourseReview</title>
    <link rel="icon" href="../icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include '../includes/menu.php' ?>
    <div id="content">
        <div id="columnA">
            <p><b>Add a CourseReview here.</b><br>

                <?php
                if (isset($_POST["course"])) {
                    $exist = true;

                    //check DB if Course exists
                    $course = $_POST["course"] . " ";
                    $course = substr($course, 0, strpos($course, " "));

                    $db = new SQLite3('../secret/CourseReviews.db');

                    $stmt = $db->prepare("SELECT * FROM COURSES WHERE COURSE=:course");
                    $stmt->bindParam(':course', $course, SQLITE3_TEXT);
                    $result = $stmt->execute();
                    if (!$row = $result->fetchArray()) {
                        print "<p>Are you sure this course (" . htmlspecialchars($course) . ") exists? If it does, contact me: lteufelbe@ethz.ch <br>";
                        $exist = false;
                    }
                    $db->close();
                    if ($exist) {

                        function submitReview(String $ducky, String $token)
                        {
                            $ch = curl_init($ducky);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_CAINFO, "../cacert-2022-04-26.pem");
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

                            // Set HTTP Header for POST request
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $token));

                            $result = curl_exec($ch);
                            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            // Close cURL session handle
                            curl_close($ch);

                            if ($code == 401) {
                                print $result;
                                return true;
                            }
                            // handle curl error
                            if ($code != 200) {
                                print "Error Code: $code <br>";
                                print "Something went wrong I am sorry.";
                            } else {
                                if ($result == '"inserted"') {
                                    print "<p>We will verify your review to make sure it isn't attacking anyones honour.</p>";
                ?>
                                    <script>
                                        if (localStorage.text) {
                                            localStorage.removeItem("text");
                                        }
                                    </script>
                <?php
                                } else {
                                    print 'You already have a review for this course. Go under <a href="https://n.ethz.ch/~lteufelbe/coursereview/edit/">Edit</a> to change it.<br>';
                                }
                            }
                            return false;
                        }

                        //check if there was even anything they submitted
                        $empty = true;

                        //submit a review if exists
                        if (isset($_POST["review"]) && trim($_POST["review"]) != "") {
                            $empty = false;
                            $data = array(
                                'course_id' => $course,
                                'user_id' => $user_id,
                                'review' => $_POST["review"]
                            );
                            $ducky = $api . "insertReview?";
                            $ducky = $ducky . http_build_query($data);

                            $course = $_POST["course"] . " ";
                            if (submitReview($ducky, $token)) {
                                //get new token
                                require_once('../newToken.php');
                                $token = newToken();
                                submitReview($ducky, $token);
                            }
                        }

                        require_once("../submitRating.php");
                        $ratings = ["Recommended", "Interesting", "Difficulty", "Effort", "Resources"];
                        $ratingApi = $api . "insertRating?";
                        //submit each rating
                        foreach ($ratings as $val) {
                            if (isset($_POST[$val]) && $_POST[$val] != 0) {
                                if ($_POST[$val] == 0) {
                                    continue;
                                }
                                $empty = false;
                                $rating = $_POST[$val];
                                $data = array(
                                    'course_id' => $course,
                                    'user_id' => $user_id,
                                    'rating_id' => $val,
                                    'rating' => $_POST[$val]
                                );

                                $ducky = $ratingApi . http_build_query($data);

                                if (submitRating($ducky, $token)) {
                                    //get new token
                                    require_once('../newToken.php');
                                    $token = newToken();
                                    submitRating($ducky, $token);
                                }
                            }
                        }


                        if ($empty) {
                            print "You neither submitted a review nor any ratings.<br>";
                        }
                    }
                }
                ?>

            <form method="post" action="#">
                <fieldset>
                    <legend>Review</legend>
                    <p>
                        <label for="course">Course number:</label><br>
                        <input list="courses" id="course" name="course" placeholder="252-0027-00L" pattern="[A-Z0-9]{3}-[A-Z0-9]{4}-[A-Z0-9]{3}( .*)?" <?php if (isset($_GET["course"])) {
                                                                                                                                                            echo "value=\"" . $_GET["course"] . "\"";
                                                                                                                                                        } ?> required>
                        <datalist id="courses">
                            <?php
                            $db = new SQLite3('../secret/CourseReviews.db');
                            $stmt = $db->prepare("SELECT * FROM COURSES");
                            $result = $stmt->execute();

                            while ($row = $result->fetchArray()) {
                            ?>
                                <option value="<?php echo "$row[0] $row[1]"; ?>">
                                <?php
                            }
                            $db->close();
                                ?>
                        </datalist>
                    </p>
                    <p>
                        <label>Review:</label><br>
                        <textarea name="review" id="text" rows="4" placeholder="For some very hard, while others who already have knowledge about the content it is very easy."></textarea>
                    </p>
                    <script>
                        if (localStorage.text) {
                            document.getElementById('text').innerHTML = localStorage.text;
                        }
                        const input = document.querySelector('textarea');
                        input.addEventListener('input', updateValue);

                        function updateValue(e) {
                            localStorage.text = e.target.value;
                        }
                    </script>
                    <?php
                    require_once("../rating.php");
                    includeRating(null, 0);
                    ?>
                    <p>
                        <button type="submit">Submit</button>
                    </p>
                </fieldset>
            </form>

        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>