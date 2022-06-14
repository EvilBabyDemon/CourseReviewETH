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
        <h1>CourseReviev</h1>
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

            <b>Here will you be able to edit your Reviews.</b><br>
            Just change the text in the fields and press on the button. Submitting a blank review will delete it.<br>

            <?php
            if (isset($_POST["course"])) {

                //change this to fit

                $ducky = "https://rubberducky.vsos.ethz.ch:1855/";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                // Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

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
                    echo $_POST["review"];
                } else {
                    if ($result == "fail") {
                        print "This course review doesn't exist in my database. Not sure where the problem lies. Maybe contact me if you think this is an error on my side. Here you can copy your text again as I did not save it:</p> <br>";
                        echo $_POST["review"];
                    } else {
                        echo "<br><b>Entry updated</b> It must be verified again, before it will show up. Give it some time.<br>";
                        if ("" == trim($_POST['review'])) {
                            print "Review of " . $_POST["course"] . " got removed.";
                        } else {
                            echo $_POST["course"];
                            print "<br>";
                            echo $_POST["review"];
                            print "<br>";
                        }
                    }
                }
            }
            ?>

            <?php
            $ducky = "https://rubberducky.vsos.ethz.ch:1855/user/";
            $ducky = $ducky . $val;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ducky);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CAINFO, "cacert-2022-04-26.pem");

            $result = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

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

                    echo "<hr>";
                    if ($val[2] == 0) {
                        echo "<b>Not yet verified!</b><br>";
                    } elseif ($val[2] == -1) {
                        echo "<div style='color:red;'>Review was rejected! Edit it and remove anything thats attacking a person or anything else that might got it rejected.</div><br>";
                    }

                    echo "$val[1] $rowc[0]";
            ?>
                    <form method="post" action="edit.php">
                        <fieldset>
                            <legend>Review</legend>
                            <label>
                                <input style="color:red" name="course" size="10" value="<?php echo $val[1]; ?>" readonly>
                                <br>
                                <textarea name="review" cols="50" rows="3"><?php echo $val[0]; ?></textarea>
                            </label>
                            <p>
                                <button type="submit">Edit</button>
                            </p>
                        </fieldset>
                    </form>
            <?php
                }
            }
            ?>

        </div>
    </div>
    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:lteufelbe@ethz.ch">lteufelbe@ethz.ch</a></p>
    </div>

</body>

</html>