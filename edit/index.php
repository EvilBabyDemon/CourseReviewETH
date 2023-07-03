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
    <meta name="description" content="Edit reviews and ratings for courses on CourseReview." />
    <link href="../main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include '../includes/menu.php' ?>
    <div id="content">
        <div id="columnA">

            <b>Here will you be able to edit your Reviews.</b><br>
            <p>Just change the text in the fields and press on the button. Submitting a blank review will delete it.</p>

            <?php
            require_once("../submitRating.php");

            function review(string $ducky, string $token)
            {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_CAINFO, "../cacert-2022-04-26.pem");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                // Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $token));

                curl_setopt($ch, CURLOPT_URL, $ducky);
                $result = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                // Close cURL session handle
                curl_close($ch);
                // handle curl error

                if ($code == 401) {
                    return true;
                }
                if ($code != 200) {
                    print "Something went wrong I am sorry. Here you can copy your text again as I did not save it:</p> <br>";
                    echo htmlspecialchars($_POST["review"]);
                } else {
                    if ($result == "fail") {
                        print "This course review doesn't exist in my database. Not sure where the problem lies. Maybe contact me if you think this is an error on my side. Here you can copy your text again as I did not save it:</p> <br>";
                        echo htmlspecialchars($_POST["review"]);
                    } else {
                        echo "<br><b>Entry updated!</b> ";
                        if ("" == trim($_POST['review'])) {
                            print "<br>Review of " . htmlspecialchars($_POST["course"]) . " got removed.";
                        } else {
                            echo "Your Review for " . htmlspecialchars($_POST["course"]) . " got updated.";
                            echo "It must be verified again, before it will show up. Give it some time.<br>";
                            echo htmlspecialchars($_POST["course"]);
                            print "<br>";
                            echo htmlspecialchars($_POST["review"]);
                        }
                        print "<br>";
                    }
                }
                return false;
            }
            if (isset($_POST["course"])) {
                $ducky = $api;
                if (isset($_POST["update"])) {
                    $ratings = ["Recommended", "Interesting", "Difficulty", "Effort", "Resources"];

                    $review = trim($_POST["review"]);
                    $old_review = trim($_POST["old_review"]);
                    $rat = [$_POST["Recommended"], $_POST["Interesting"], $_POST["Difficulty"], $_POST["Effort"], $_POST["Resources"]];
                    $old_rat = explode(',', $_POST["old_rating"]);
                    $semester = trim($_POST["semester"]);
                    $old_semester = trim($_POST["old_semester"]);

                    if ($review == $old_review && $rat == $old_rat && $semester == $old_semester) {
            ?>
                        You haven't changed anything. If you wanted to change something try again, if this persists please tell so.
                    <?php
                    }

                    if ($review != $old_review) {
                        //Edit entry
                        if ("" == trim($_POST['review'])) {
                            $data = array(
                                'course_id' => $_POST["course"],
                                'user_id' => $user_id,
                            );
                            $ducky = $ducky . "removeReview?" . http_build_query($data);
                        } else {
                            $data = array(
                                'course_id' => $_POST["course"],
                                'user_id' => $user_id,
                                'review' => $_POST["review"],
                                'semester' => $_POST["semester"],
                            );
                            $ducky = $ducky . "updateReview?" . http_build_query($data);
                        }
                        if (review($ducky, $token)) {
                            require_once('../newToken.php');
                            $token = newToken();
                            review($ducky, $token);
                        }
                    }

                    if ($rat != $old_rat) {
                        $ratings = ["Recommended", "Interesting", "Difficulty", "Effort", "Resources"];
                        $ratingApi = $api . "insertRating?";
                        //submit each rating
                        foreach ($ratings as $val) {
                            if (isset($_POST[$val])) {
                                $rating = $_POST[$val];
                                $data = array(
                                    'course_id' => $_POST["course"],
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
                    ?>
                        <b>Thanks for rating the course!</b><br>
                        <?php
                    }

                    if ($semester != $old_semester) {
                        $pattern = "/^[HF]S[12]\d$/";
                        if ($semester != "" && preg_match($pattern, $semester) != 1) {
                        ?>
                            <b>Wrong semester input!</b>
                        <?php
                        } else {
                            $data = array(
                                'course_id' => $_POST["course"],
                                'user_id' => $user_id,
                                'semester' => $semester,
                            );
                            $ducky = $ducky . "updateSemester?" . http_build_query($data);
                            if (submitRating($ducky, $token)) {
                                //get new token
                                require_once('../newToken.php');
                                $token = newToken();
                                submitRating($ducky, $token);
                            }
                        ?>
                            <b>We changed your semester.</b><br>
                    <?php
                        }
                    }
                } else if (isset($_POST["clear"])) {
                    $ratings = ["Recommended", "Interesting", "Difficulty", "Effort", "Resources"];
                    $ratingApi = $api . "removeRating?";
                    foreach ($ratings as $rating_id) {

                        $data = array(
                            'course_id' => $_POST["course"],
                            'user_id' => $user_id,
                            'rating_id' => $rating_id
                        );
                        $ducky = $ratingApi . http_build_query($data);

                        if (submitRating($ducky, $token)) {
                            //get new token
                            require_once('../newToken.php');
                            $token = newToken();
                            submitRating($ducky, $token);
                        }
                    }
                    ?>
                    <b>We removed the rating of the course.</b><br>
            <?php
                }
            }
            ?>

            <?php

            function getUserReviews(String $user_id, String $token, String $api)
            {
                $ducky = $api . "userStuff/";
                $ducky = $ducky . $user_id;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ducky);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CAINFO, "../cacert-2022-04-26.pem");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));

                $result = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($code == 401) {
                    return false;
                }

                if (strlen($result) > 2) {
                    $js = json_decode(json_decode($result, false), true);
                    return $js;
                }
                return true;
            }

            if (!$reviews = getUserReviews($user_id, $token, $api)) {
                //get new token
                require_once('../newToken.php');
                $token = newToken();
                $reviews = getUserReviews($user_id, $token, $api);
            }


            class Review
            {
                // Properties
                public $course_id;
                public $review;
                public $rating;
                public $semester;

                // Methods
                function printCourse($count)
                {
                    if ($this->rating == null) {
                        $this->rating = [0, 0, 0, 0, 0];
                    }
                    $dbc = new SQLite3('../secret/CourseReviews.db');
                    $stmtc = $dbc->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                    $stmtc->bindParam(':course', $this->course_id, SQLITE3_TEXT);
                    $resultc = $stmtc->execute();
                    $rowc = $resultc->fetchArray();

                    echo "<br>";
                    if ($this->review[1] != null && $this->review[1] == 0) {
            ?>
                        <b>Not yet verified!</b><br>
                    <?php
                    } elseif ($this->review[1] != null && $this->review[1] == -1) {
                    ?>
                        <div style='color:red;'>Review was rejected! Edit it and remove anything that's attacking a person or anything else that might have gotten it rejected.</div><br>
                    <?php
                    }
                    ?>
                    <form method="post" action="">
                        <fieldset>
                            <textarea name="old_review" hidden><?php print htmlspecialchars($this->review[0]); ?></textarea>
                            <input name="old_semester" value="<?php print htmlspecialchars($this->semester); ?>" hidden>
                            <input name="old_rating" value="<?php print htmlspecialchars(implode(', ', $this->rating)); ?>" hidden>

                            <legend><?php echo htmlspecialchars("$rowc[0]"); ?></legend>
                            <label>
                                <input style="color:red" name="course" value="<?php echo htmlspecialchars($this->course_id); ?>" readonly>
                                <br>
                                <textarea name="review" rows="4"><?php print htmlspecialchars($this->review[0]); ?></textarea>
                            </label>
                            <p>
                                Took it in Semester: <br>
                                <select name="semester">
                                    <option <?php if ($this->semester == null || $this->semester == "") {
                                                print "selected";
                                            } ?>></option>
                                    <?php
                                    for ($i = 23; $i > 17;) {
                                        $fs = "FS" . $i;
                                        $hs = "HS" . --$i;
                                    ?>
                                        <option <?php if ($this->semester == $fs) {
                                                    print "selected";
                                                } ?>> <?php print $fs ?> </option>
                                        <option <?php if ($this->semester == $hs) {
                                                    print "selected";
                                                } ?>> <?php print $hs ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </p>
                            <fieldset>
                                <?php
                                require_once("../rating.php");
                                includeRating($this->rating, $count);
                                ?>
                            </fieldset>
                            <p>
                                <input name="clear" type="submit" value="Clear rating">
                                <input name="update" type="submit" value="Update changes">
                            </p>
                        </fieldset>
                    </form>
            <?php
                }
            }

            $reviewArr = [];
            foreach ($reviews as $arr) {
                if ($arr["Recommended"] == null && $arr["Interesting"] == null && $arr["Difficulty"] == null && $arr["Effort"] == null && $arr["Resources"] == null && $arr["Review"] == null) {
                    continue;
                }
                $tmp_review = new Review();
                $tmp_review->course_id = $arr["CourseNumber"];
                $tmp_review->review = [$arr["Review"], $arr["VerificationStatus"]];
                $tmp_review->semester = $arr["Semester"];
                $tmp_review->rating = [$arr["Recommended"], $arr["Interesting"], $arr["Difficulty"], $arr["Effort"], $arr["Resources"]];
                array_push($reviewArr, $tmp_review);
            }
            $counter = 0;
            foreach ($reviewArr as $review) {
                $review->printCourse($counter++);
            }

            ?>

        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>