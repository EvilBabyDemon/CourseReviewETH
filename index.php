<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
$api = trim(file_get_contents("secret/api.txt"));
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; object-src 'none'">
    <title>CourseReview</title>
    <link rel="icon" href="icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="main.css" rel="stylesheet" type="text/css" />
    <?php
    if (isset($_POST["course"])) {
        $course = $_POST["course"] . " ";
        $course = substr($course, 0, strpos($course, " "));
        $db = new SQLite3('secret/CourseReviews.db');
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
                $db = new SQLite3('secret/CourseReviews.db');
                $stmt = $db->prepare("SELECT * FROM COURSES WHERE NAME Like '%' || REPLACE(:input, ' ', '%') || '%' limit 10;");
                $stmt->bindParam(':input', $_POST["course"], SQLITE3_TEXT);
                $result = $stmt->execute();
            ?>
                Your search didn't find an exact result, so here are the closest ones: <br>
                <ol>
                    <?php
                    while ($row = $result->fetchArray()) {
                    ?>
                        <li><a href="<?php echo "https://n.ethz.ch/~lteufelbe/coursereview/" . htmlspecialchars($row[0]) . "/"; ?>"><?php echo htmlspecialchars($row[0]) . " <b>" . htmlspecialchars($row[1]) . "</b>"; ?></a></li>
                    <?php
                    }
                    $db->close();
                    ?>
                </ol>
            <?php
            }
            ?>
            <form method="post" action="#">
                <input id="search" list="courses" name="course" placeholder="Search for Reviews">
                <datalist id="courses">
                    <?php
                    $db = new SQLite3('secret/CourseReviews.db');
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
                <input id="searchbutton" type="submit" value="Search">
            </form>

            <h3>Welcome <?php echo htmlspecialchars("$name $surname"); ?>!</h3>
            <p>Here you can add and read reviews of courses from ETHZ!</p>
            <a href="https://n.ethz.ch/~lteufelbe/coursereview/add.php">Add a review!</a> <br>
            <a href="https://n.ethz.ch/~lteufelbe/coursereview/edit.php">Edit your existent reviews!</a> <br>
            <a href="https://n.ethz.ch/~lteufelbe/coursereview/all.php">All courses with reviews!</a> <br>

            <?php
            function getStats(String $token, String $api) {
                $ducky = $api . "stats/";
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
                $js = json_decode(json_decode($result, true), true);
                print "<b>" .  htmlspecialchars($js[0]['total']) . "</b> reviews for <b>" . htmlspecialchars($js[0]['percourse']) . "</b> courses have been published so far.";
                return false;
            }
            if (getStats($token, $api)) {
                //get new token
                require_once('newToken.php');
                $token = newToken();
                getStats($token, $api);
            }



            function getLatest(String $token, String $api)
            {
                $ducky = $api . "latest";
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
                    foreach ($js as $value) {
                        $coursename = "";
                        $db = new SQLite3('secret/CourseReviews.db');
                        $stmt = $db->prepare("SELECT NAME FROM COURSES WHERE COURSE=:course");
                        $stmt->bindParam(':course', $value->CourseNumber, SQLITE3_TEXT);
                        $qresult = $stmt->execute();

                        if ($row = $qresult->fetchArray()) {
                            $coursename = $row[0];
                        }
                        $db->close();

                        echo '<li><a href="https://n.ethz.ch/~lteufelbe/coursereview/' . htmlspecialchars($value->CourseNumber) . '/">' .
                            htmlspecialchars($value->CourseNumber) . ' <b>' . htmlspecialchars($coursename) . '</b></a></li>';
                    }
                    ?>
                </ul>
            <?php
                return false;
            }

            if (getLatest($token, $api)) {
                //get new token
                require_once('newToken.php');
                $token = newToken();
                getLatest($token, $api);
            }

            ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>