<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../default.css" rel="stylesheet" type="text/css" />
    <?php
    if (isset($_POST["course"])) {
        $course = substr($_POST["course"], 0, strpos($_POST["course"], " "));
        $db = new SQLite3('CourseReviews.db');
        $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
        $stmt->bindParam(':course', $course, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result->fetchArray()) {
            echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://n.ethz.ch/~lteufelbe/coursereview/$course/'\" />)";
        }
        $db->close();
    }
    ?>

</head>

<body>
    <div id="header">
        <h1>CourseReview</h1>
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
            <?php
            if (isset($_POST["course"])) {
                $db = new SQLite3('CourseReviews.db');
                $stmt = $db->prepare("SELECT * FROM SEARCH WHERE SEARCH MATCH ':input' ORDER BY rank limit 10;");
                $stmt->bindParam(':input', $_POST["course"], SQLITE3_TEXT);
                $result = $stmt->execute();
                echo "Your search didn't find an exact result, so here are the closest: <br>";
                while ($row = $result->fetchArray()) {
            ?>
                    <a href="<?php echo "https://n.ethz.ch/~lteufelbe/coursereview/$row[0]/"; ?>"><?php echo "$row[0] $row[1]"; ?></a><br>
            <?php
                }
                $db->close();
            }
            ?>
            <form method="post" action="#">
                <input list="courses" id="course" name="course" placeholder="Search for Reviews" pattern="[A-Z0-9]{3}-[A-Z0-9]{4}-[A-Z0-9]{3}( .*)?" size="40">
                <datalist id="courses">
                    <?php
                    $db = new SQLite3('CourseReviews.db');
                    $stmt = $db->prepare("SELECT * FROM COURSES;");
                    $result = $stmt->execute();

                    while ($row = $result->fetchArray()) {
                    ?>
                        <option value="<?php echo "$row[0] $row[1]"; ?>">
                        <?php
                    }
                    $db->close();
                        ?>
                </datalist>
                <input type="submit" value="Submit">
            </form>

            <h2>Welcome <?php echo "$name $surname"; ?>!</h2>
            <p><b>CourseReview</b><br>
                Here you can add and read reviews of courses from ETHZ! </p>
            <a href="https://n.ethz.ch/~lteufelbe/coursereview/add.php">Add a review!</a> <br>
            <a href="https://n.ethz.ch/~lteufelbe/coursereview/edit.php">Edit your existent reviews!</a> <br>
            </p>

        </div>
        <div id="footer">
            <p>If you think something is wrong or have any suggestion please contact me: lteufelbe@ethz.ch</p>
        </div>
    </div>
</body>

</html>