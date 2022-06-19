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
            <p><b>Thx for your submission!</b></p>

            <?php
            //check DB if Course exists

            $course = substr($_POST["course"], 0, strpos($_POST["course"], " "));

            $db = new SQLite3('CourseReviews.db');

            $stmt = $db->prepare("SELECT * FROM COURSES WHERE COURSE=:course");
            $stmt->bindParam(':course', $course, SQLITE3_TEXT);
            $result = $stmt->execute();
            if (!$row = $result->fetchArray()) {
                
                print "<p>But are you sure this course (". htmlspecialchars($course) .") exists? If it does, contact me: $nethz@ethz.ch <br> I didn't save it. But here you can copy your text again:</p> <br>";
                echo htmlspecialchars($_POST["review"]);
                $db->close();
                exit();
            }
            $db->close();

            $db = new SQLite3('CourseReviews.db');
            $stmt = $db->prepare("INSERT INTO REVIEWS (ID, COURSE, REVIEW) VALUES (:id, :course, :review)");
            $stmt->bindParam(':id', $val, SQLITE3_TEXT);
            $stmt->bindParam(':course', $course, SQLITE3_TEXT);
            $stmt->bindParam(':review', $_POST["review"], SQLITE3_TEXT);
            $stmt->execute();
            $db->close();

            echo htmlspecialchars($_POST["course"]);
            print "<br>";
            echo htmlspecialchars($_POST["review"]);
            ?>
        </div>
    </div>


    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:<?php echo $nethz; ?>@ethz.ch"><?php echo $nethz; ?>@ethz.ch</a></p>
    </div>

</body>

</html>