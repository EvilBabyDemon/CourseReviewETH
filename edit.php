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

            <b>Here will you be able to edit your Reviews.</b><br>
            <p>Just change the text in the fields and press on the button. Submitting a blank review will delete it.</p>

            <?php
            if (isset($_POST["course"])) {

                //change this to fit

                $ducky = $api;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                // Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $token));

                //Edit entry
                if ("" == trim($_POST['review'])) {
                    $data = array(
                        'course_id' => $_POST["course"],
                        'nethz' => $val,
                    );
                    $ducky = $ducky . "remove?" . http_build_query($data);
                } else {
                    $data = array(
                        'course_id' => $_POST["course"],
                        'nethz' => $val,
                        'review' => $_POST["review"],
                    );
                    $ducky = $ducky . "update?" . http_build_query($data);
                }

                curl_setopt($ch, CURLOPT_URL, $ducky);
                $result = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                // Close cURL session handle
                curl_close($ch);
                // handle curl error
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
            }
            ?>

            <?php
            function getUserReviews(String $val, String $token, String $api)
            {
                $ducky = $api . "user/";
                $ducky = $ducky . $val;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ducky);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));

                $result = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($code == 401) {
                    return true;
                }

                if (strlen($result) == 2) {
                    echo "You didn't submit anything yet.";
                } else {

                    $js = json_decode($result, false);

                    foreach ($js as $key => $val) {
                        $dbc = new SQLite3('CourseReviews.db');
                        $stmtc = $dbc->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                        $stmtc->bindParam(':course', $val[1], SQLITE3_TEXT);
                        $resultc = $stmtc->execute();
                        $rowc = $resultc->fetchArray();

                        echo "<br>";
                        if ($val[2] == 0) {
                            echo "<b>Not yet verified!</b><br>";
                        } elseif ($val[2] == -1) {
                            echo "<div style='color:red;'>Review was rejected! Edit it and remove anything that's attacking a person or anything else that might have gotten it rejected.</div><br>";
                        }

            ?>
                        <form method="post" action="edit.php">
                            <fieldset>
                                <legend><?php echo htmlspecialchars("$rowc[0]"); ?></legend>
                                <label>
                                    <input style="color:red" name="course" value="<?php echo htmlspecialchars($val[1]); ?>" readonly>
                                    <br>
                                    <textarea name="review" rows="4"><?php echo htmlspecialchars($val[0]); ?></textarea>
                                </label>
                                <p>
                                    <button type="submit">Edit</button>
                                </p>
                            </fieldset>
                        </form>
            <?php
                    }
                }
                return false;
            }
            if (getUserReviews($val, $token, $api)) {
                //get new token
                require_once('newToken.php');
                $token = newToken();
                getUserReviews($val, $token, $api);
            }

            ?>

        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>