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
    <meta name="viewport" content="width=device-width">
    <title>CourseReview</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="main.css" rel="stylesheet" type="text/css" />
    <?php
    if (isset($_POST["course"])) {
        $course = $_POST["course"] . " ";
        $course = substr($course, 0, strpos($course, " "));
        $db = new SQLite3('CourseReviews.db');
        $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
        $stmt->bindParam(':course', $course, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result->fetchArray()) {
            echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://n.ethz.ch/~" . htmlspecialchars($nethz) . "/coursereview/" . htmlspecialchars($course) . "/'\" />)";
            $db->close();
            exit();
        }
        $db->close();
    }
    ?>

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
            <?php
            if (isset($_POST["course"])) {
                $db = new SQLite3('CourseReviews.db');
                $stmt = $db->prepare("SELECT * FROM SEARCH WHERE SEARCH MATCH ':input' ORDER BY rank limit 10;");
                $stmt->bindParam(':input', $_POST["course"], SQLITE3_TEXT);
                $result = $stmt->execute();
                echo "Your search didn't find an exact result, so here are the closest: <br>";
                while ($row = $result->fetchArray()) {
            ?>
                    <a href="<?php echo "https://n.ethz.ch/~" . htmlspecialchars($nethz) . "/coursereview/" . htmlspecialchars($row[0]) . "/"; ?>"><?php echo htmlspecialchars("$row[0] $row[1]"); ?></a><br>
            <?php
                }
                $db->close();
            }
            ?>
            <form method="post" action="#">
                <input list="courses" id="course" name="course" placeholder="Search for Reviews" size="40">
                <datalist id="courses">
                    <?php
                    $db = new SQLite3('CourseReviews.db');
                    $stmt = $db->prepare("SELECT * FROM COURSES;");
                    $result = $stmt->execute();

                    while ($row = $result->fetchArray()) {
                    ?>
                        <option value="<?php echo htmlspecialchars("$row[0] $row[1]"); ?>">
                        <?php
                    }
                    $db->close();
                        ?>
                </datalist>
                <input type="submit" value="Submit">
            </form>

            <h2>Welcome <?php echo htmlspecialchars("$name $surname"); ?>!</h2>
            <p>Here you can add and read reviews of courses from ETHZ!</p>
            <a href="https://n.ethz.ch/~<?php echo htmlspecialchars($nethz);?>/coursereview/add.php">Add a review!</a> <br>
            <a href="https://n.ethz.ch/~<?php echo htmlspecialchars($nethz);?>/coursereview/edit.php">Edit your existent reviews!</a> <br>
            </p>
            <!-- add latest 10 coursereviews here, sort by time of review entry in db -->
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>