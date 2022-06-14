<!DOCTYPE html>
<html lang="en">

<?php
$nethz = $_SERVER["REQUEST_URI"];
if (strstr($nethz, "/student") and strstr($nethz, "/student", true) == "") {
    $nethz = substr($nethz, 9, strlen($nethz));
} else {
    $nethz = substr($nethz, 2, strlen($nethz));
}
$nethz = substr($nethz, 0, strpos($nethz, "/"));
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
            <li><a href="https://n.ethz.ch/~<?php echo $nethz; ?>/coursereview/" onFocus="if(this.blur)this.blur()">CourseReview</a></li>
            <li><a href="https://n.ethz.ch/~<?php echo $nethz; ?>/coursereview/add.php" onFocus="if(this.blur)this.blur()">Add</a></li>
            <li><a href="https://n.ethz.ch/~<?php echo $nethz; ?>/coursereview/edit.php" onFocus="if(this.blur)this.blur()">Edit</a></li>
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

            $noreview = true;
            while ($row = $result->fetchArray()) {
                $dbc = new SQLite3('CourseReviews.db');
                $stmtc = $dbc->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                $stmtc->bindParam(':course', $row[1], SQLITE3_TEXT);
                $resultc = $stmtc->execute();
                $rowc = $resultc->fetchArray();
                echo "<hr>";
                echo htmlspecialchars("$row[1] $rowc[0]")
            ?>
                <form method="post" action="edit.php">
                    <fieldset>
                        <legend>Review</legend>
                        <label>
                            <input style="color:red" name="course" size="10" value="<?php echo htmlspecialchars("$rowc[0]"); ?>" readonly>
                            <br>
                            <textarea name="review" cols="50" rows="3"><?php echo htmlspecialchars($row[2]); ?></textarea>
                        </label>
                        <p>
                            <button type="submit">Edit</button>
                        </p>
                    </fieldset>
                </form>

            <?php
                $dbc->close();
                $noreview = false;
            }
            if ($noreview) {
                print "You didn't review anything yet.";
            }
            $db->close();
            ?>

        </div>
    </div>
    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:<?php echo $nethz; ?>@ethz.ch"><?php echo $nethz; ?>@ethz.ch</a></p>
    </div>

</body>

</html>