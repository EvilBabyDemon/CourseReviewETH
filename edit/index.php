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
                if (isset($_POST["submit"])) {
                    $ratings = ["Recommended", "Interesting", "Difficulty", "Effort", "Resources"];
                    $ratingApi = $api . "insertRating?";
                    //submit each rating
                    foreach ($ratings as $val) {
                        if (isset($_POST[$val])) {
                            $empty = false;
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
                    print "<b>Thanks for rating the course!</b>";
                } else if (isset($_POST["clear"])) {
                    $ratings = ["Recommended", "Interesting", "Difficulty", "Effort", "Resources"];
                    $ratingApi = $api . "removeRating?";
                    //submit each rating
                    foreach ($ratings as $val) {
                        if (isset($_POST[$val])) {
                            $empty = false;
                            $data = array(
                                'course_id' => $_POST["course"],
                                'user_id' => $user_id,
                                'rating_id' => $val,
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
                    print "<b>We removed the rating of the course.</b>";
                } else if (isset($_POST["edit"])) {

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
                        );
                        $ducky = $ducky . "updateReview?" . http_build_query($data);
                    }
                    if (review($ducky, $token)) {
                        require_once('../newToken.php');
                        $token = newToken();
                        review($ducky, $token);
                    }
                }
            }
            ?>

            <?php

            function getUserRatings(String $user_id, String $token, String $api)
            {

                $ducky = $api . "userRating/";
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
                if ($code != 200) {
                    return false;
                }
                if (strlen($result) > 2) {
                    $js = json_decode($result, false);
                    return $js;
                }
                return true;
            }

            function getUserReviews(String $user_id, String $token, String $api)
            {
                $ducky = $api . "userReview/";
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
                    $js = json_decode($result, false);
                    return $js;
                }
                return true;
            }

            if (!$ratings = getUserRatings($user_id, $token, $api)) {
                //get new token
                require_once('../newToken.php');
                $token = newToken();
                $ratings = getUserRatings($user_id, $token, $api);
            }

            if (!$reviews = getUserReviews($user_id, $token, $api, $ratings)) {
                //get new token
                require_once('../newToken.php');
                $token = newToken();
                $reviews = getUserReviews($user_id, $token, $api, $ratings);
            }


            class Review
            {
                // Properties
                public $course_id;
                public $review;
                public $rating;

                // Methods
                function printCourse($count)
                {
                    $dbc = new SQLite3('../secret/CourseReviews.db');
                    $stmtc = $dbc->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                    $stmtc->bindParam(':course', $this->course_id, SQLITE3_TEXT);
                    $resultc = $stmtc->execute();
                    $rowc = $resultc->fetchArray();

                    echo "<br>";
                    if ($this->review[1] == 0) {
                        echo "<b>Not yet verified!</b><br>";
                    } elseif ($this->review[1] == -1) {
                        echo "<div style='color:red;'>Review was rejected! Edit it and remove anything that's attacking a person or anything else that might have gotten it rejected.</div><br>";
                    }

            ?>
                    <form method="post" action="index.php">
                        <fieldset>
                            <legend><?php echo htmlspecialchars("$rowc[0]"); ?></legend>
                            <label>
                                <input style="color:red" name="course" value="<?php echo htmlspecialchars($this->course_id); ?>" readonly>
                                <br>
                                <textarea name="review" rows="4"><?php echo htmlspecialchars($this->review[0]); ?></textarea>
                            </label>

                            <div name="old_review" value="<?php print htmlspecialchars($this->review[0]) ?>"></div>
                            <div name="old_rating" value="<?php print $this->rating ?>"></div>
                            <p>
                                <input name="edit" type="submit" value="Submit">
                            </p>
                            <fieldset>
                                <?php
                                require_once("../rating.php");
                                includeRating($this->rating, $count);
                                ?>
                            </fieldset>
                            <p>
                                <input name="submit" type="submit" value="Submit">
                                <input name="clear" type="submit" value="Clear">
                            </p>
                        </fieldset>
                    </form>
            <?php
                }
            }

            $reviewArr = [];
            foreach ($reviews as $arr) {
                $tmp_review = new Review();
                $tmp_review->course_id = $arr[0];
                $tmp_review->review = [$arr[1], $arr[2]];
                array_push($reviewArr, $tmp_review);
            }

            foreach ($ratings as $arr) {
                if ($arr[1] == null && $arr[2] == null && $arr[3] == null && $arr[4] == null && $arr[5] === null) {
                    continue;
                }
                $found = false;
                foreach ($reviewArr as $rev) {
                    if ($rev->course_id == $arr[0]) {
                        $rev->rating = [$arr[1], $arr[2], $arr[3], $arr[4], $arr[5]];
                        $found = true;
                        break;
                    }
                }

                if ($found) {
                    continue;
                }
                $tmp_review = new Review();
                $tmp_review->course_id = $arr[0];
                $tmp_review->rating = [$arr[1], $arr[2], $arr[3], $arr[4], $arr[5]];
                $tmp_review->review = ["", 1];
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