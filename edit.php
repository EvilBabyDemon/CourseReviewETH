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

            <b>Here will you be able to edit your Reviews.</b><br>
            Just change the text in the fields and press on the button. Submitting a blank review will delete it.<br>

            <?php
            if (isset($_POST["course"])) {

                //Edit db entry
                if ("" == trim($_POST['review'])) {
                    $db = new SQLite3('CourseReviews.db');
                    $stmt = $db->prepare("DELETE FROM REVIEWS WHERE COURSE = :course AND ID = :id");
                    $stmt->bindParam(':id', $val, SQLITE3_TEXT);
                    $stmt->bindParam(':course', $_POST["course"], SQLITE3_TEXT);
                    $stmt->execute();
                    $db->close();
                } else {
                    $db = new SQLite3('CourseReviews.db');
                    $stmt = $db->prepare("UPDATE REVIEWS SET REVIEW = :review WHERE COURSE = :course AND ID = :id");
                    $stmt->bindParam(':id', $val, SQLITE3_TEXT);
                    $stmt->bindParam(':course', $_POST["course"], SQLITE3_TEXT);
                    $stmt->bindParam(':review', $_POST["review"], SQLITE3_TEXT);
                    $stmt->execute();
                    $db->close();
                }
                echo "<b>Entry updated</b>";
            }
            ?>

            <?php
            $db = new SQLite3('CourseReviews.db');

            $stmt = $db->prepare("SELECT * FROM REVIEWS WHERE ID=:id");
            $stmt->bindParam(':id', $val, SQLITE3_TEXT);
            $result = $stmt->execute();

            $test = true;
            while ($row = $result->fetchArray()) {
                $dbc = new SQLite3('CourseReviews.db');
                $stmtc = $dbc->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                $stmtc->bindParam(':course', $row[1], SQLITE3_TEXT);
                $resultc = $stmtc->execute();
                $rowc = $resultc->fetchArray();
            ?>
                <form method="post" action="edit.php">
                    <fieldset>
                        <legend>Review</legend>
                        <label>
                            <textarea style="color:red" name="course" cols="30" rows="2" readonly><?php echo "$row[1] $rowc[0]"; ?></textarea>
                            <br>
                            <textarea name="review" cols="50" rows="3"><?php echo $row[2]; ?></textarea>
                        </label>
                        <p>
                            <button type="submit">Edit</button>
                        </p>
                    </fieldset>
                </form>

            <?php
                $dbc->close();
                $test = false;
            }
            if ($test) {
                print "You didn't review anything yet.";
            }
            $db->close();
            ?>

        </div>
    </div>
    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: lteufelbe@ethz.ch</p>
    </div>

</body>

</html>