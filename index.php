<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; object-src 'none'">
    <title>CourseReview</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../default.css" rel="stylesheet" type="text/css" />
    <?php
    if (isset($_POST["course"])) {
        $course = $_POST["course"] . " ";
        $course = substr($course, 0, strpos($course, " "));
        $db = new SQLite3('CourseReviews.db');
        $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
        $stmt->bindParam(':course', $course, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result->fetchArray()) {
            echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://n.ethz.ch/~lteufelbe/coursereview/$course/'\" />)";
            $db->close();
            exit();
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
                <input list="courses" id="course" name="course" placeholder="Search for Reviews" size="40">
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

            <h2>Welcome <?php echo htmlspecialchars("$name $surname"); ?>!</h2>
            <p>Here you can add and read reviews of courses from ETHZ!</p>
            <a href="https://n.ethz.ch/~lteufelbe/coursereview/add.php">Add a review!</a> <br>
            <a href="https://n.ethz.ch/~lteufelbe/coursereview/edit.php">Edit your existent reviews!</a> <br>

            <?php
            function getLatest(String $token)
            {
                $ducky = "https://rubberducky.vsos.ethz.ch:1855/latest";
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
                $js = json_decode($result);
                $js = json_decode($js);
                ?>
                <br> Courses with the newest Reviews:
                <ul>
                    <?php
                    foreach($js as $value){
                        $fullname = $value->CourseNumber;
                        $db = new SQLite3('CourseReviews.db');
                        $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                        $stmt->bindParam(':course', $value->CourseNumber, SQLITE3_TEXT);
                        $qresult = $stmt->execute();
                        
                        if ($row = $qresult->fetchArray()) {
                            $fullname = $value->CourseNumber  . " ". $row[0];
                        }
                        $db->close();

                        echo '<li><a href="https://n.ethz.ch/~lteufelbe/coursereview/' . htmlspecialchars($value->CourseNumber) . '/">' . htmlspecialchars("$fullname") . '</a></li>';
                    }
                    ?>
                </ul>
                <?php
                return false;
            }
            
            if (getLatest($token)) {
                //get new token
                require_once('newToken.php');
                $token = newToken();
                getLatest($token);
            }
            
            ?>
        </div>
        <div id="footer">
            <p>If you think something is wrong or have any suggestion please contact me: <a href="mailto:lteufelbe@ethz.ch">lteufelbe@ethz.ch</a><br>
            The code is also open source: <a href="https://github.com/EvilBabyDemon/CourseReviewETH">https://github.com/EvilBabyDemon/CourseReviewETH</a></p>
        </div>
    </div>
</body>

</html>