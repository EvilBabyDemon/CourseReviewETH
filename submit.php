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
    <?php include 'includes/footer.php'; ?>
</body>

</html>