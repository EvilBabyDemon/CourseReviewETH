<!DOCTYPE html>
<html lang="en">

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
            <p><b>Add a CourseReview here.</b><br>

            <form method="post" action="submit.php">
                <fieldset>
                    <legend>Review</legend>
                    <p>
                        <label for="course">Course number:</label><br>
                        <input list="courses" id="course" name="course" placeholder="252-0027-00L" pattern="[A-Z0-9]{3}-[A-Z0-9]{4}-[A-Z0-9]{3}( .*)?" <?php if (isset($_GET["course"])) {
                                                                                                                                                            echo "value=\"" . $_GET["course"] . "\"";
                                                                                                                                                        } ?> required>
                        <datalist id="courses">
                            <?php
                            $db = new SQLite3('secret/CourseReviews.db');
                            $stmt = $db->prepare("SELECT * FROM COURSES");
                            $result = $stmt->execute();

                            while ($row = $result->fetchArray()) {
                            ?>
                                <option value="<?php echo "$row[0] $row[1]"; ?>">
                                <?php
                            }
                            $db->close();
                                ?>
                        </datalist>
                    </p>
                    <p>
                        <label>
                            Review:
                            <br>
                            <textarea name="review" rows="4" placeholder="For some very hard, while others who already have knowledege about the content it is very easy." required></textarea>
                        </label>
                    </p>
                    First question?
                    <div class="rating"> 
                        <input type="radio" name="lecture" value="5" id="5"><label for="5">☆</label>
                        <input type="radio" name="lecture" value="4" id="4"><label for="4">☆</label>
                        <input type="radio" name="lecture" value="3" id="3"><label for="3">☆</label>
                        <input type="radio" name="lecture" value="2" id="2"><label for="2">☆</label>
                        <input type="radio" name="lecture" value="1" id="1"><label for="1">☆</label>
                    </div>
                    2nd question?
                    <div class="rating"> 
                        <input type="radio" name="exam" value="5" id="e5"><label for="e5">☆</label>
                        <input type="radio" name="exam" value="4" id="e4"><label for="e4">☆</label>
                        <input type="radio" name="exam" value="3" id="e3"><label for="e3">☆</label>
                        <input type="radio" name="exam" value="2" id="e2"><label for="e2">☆</label>
                        <input type="radio" name="exam" value="1" id="e1"><label for="e1">☆</label>
                    </div>
                    3rd question?
                    <div class="rating"> 
                        <input type="radio" name="grading" value="5" id="g5"><label for="g5">☆</label>
                        <input type="radio" name="grading" value="4" id="g4"><label for="g4">☆</label>
                        <input type="radio" name="grading" value="3" id="g3"><label for="g3">☆</label>
                        <input type="radio" name="grading" value="2" id="g2"><label for="g2">☆</label>
                        <input type="radio" name="grading" value="1" id="g1"><label for="g1">☆</label>
                    </div>
                    <p>
                        <button type="submit">Submit</button>
                    </p>
                </fieldset>
            </form>

        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>