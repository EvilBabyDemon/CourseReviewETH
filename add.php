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
                            <textarea name="review" rows="4" placeholder="For some very hard, while others who already have knowledge about the content it is very easy." required></textarea>
                        </label>
                    </p>
                    Would <b>recommend</b> it <div class="tooltip">&#x1F6C8; <span class="tooltiptext">1 no, 5 yes</span></div> 
                    <div class="rating">
                        <input type="radio" name="recommend" value="5" id="recommend5"><label for="recommend5">☆</label>
                        <input type="radio" name="recommend" value="4" id="recommend4"><label for="recommend4">☆</label>
                        <input type="radio" name="recommend" value="3" id="recommend3"><label for="recommend3">☆</label>
                        <input type="radio" name="recommend" value="2" id="recommend2"><label for="recommend2">☆</label>
                        <input type="radio" name="recommend" value="1" id="recommend1"><label for="recommend1">☆</label>
                    </div>
                    <b>Interesting</b> content <div class="tooltip">&#x1F6C8; <span class="tooltiptext">1 boring, 5 very interesting</span></div> 
                    <div class="rating">
                        <input type="radio" name="interesting" value="5" id="interesting5"><label for="interesting5">☆</label>
                        <input type="radio" name="interesting" value="4" id="interesting4"><label for="interesting4">☆</label>
                        <input type="radio" name="interesting" value="3" id="interesting3"><label for="interesting3">☆</label>
                        <input type="radio" name="interesting" value="2" id="interesting2"><label for="interesting2">☆</label>
                        <input type="radio" name="interesting" value="1" id="interesting1"><label for="interesting1">☆</label>
                    </div>
                    Approriate <b>difficulty</b> <div class="tooltip">&#x1F6C8; <span class="tooltiptext">1 very hard, 5 very easy</span></div> 
                    <div class="rating">
                        <input type="radio" name="difficulty" value="5" id="difficulty5"><label for="difficulty5">☆</label>
                        <input type="radio" name="difficulty" value="4" id="difficulty4"><label for="difficulty4">☆</label>
                        <input type="radio" name="difficulty" value="3" id="difficulty3"><label for="difficulty3">☆</label>
                        <input type="radio" name="difficulty" value="2" id="difficulty2"><label for="difficulty2">☆</label>
                        <input type="radio" name="difficulty" value="1" id="difficulty1"><label for="difficulty1">☆</label>
                    </div>
                    Approriate amount of <b>effort</b> <div class="tooltip">&#x1F6C8; <span class="tooltiptext">1 worst, 5 best</span></div>
                    <div class="rating">
                        <input type="radio" name="effort" value="5" id="effort5"><label for="effort5">☆</label>
                        <input type="radio" name="effort" value="4" id="effort4"><label for="effort4">☆</label>
                        <input type="radio" name="effort" value="3" id="effort3"><label for="effort3">☆</label>
                        <input type="radio" name="effort" value="2" id="effort2"><label for="effort2">☆</label>
                        <input type="radio" name="effort" value="1" id="effort1"><label for="effort1">☆</label>
                    </div>
                    Amount and quality of <b>resources</b> <div class="tooltip">&#x1F6C8; <span class="tooltiptext">1 worst, 5 best</span></div> 
                    <div class="rating">
                        <input type="radio" name="resources" value="5" id="resources5"><label for="resources5">☆</label>
                        <input type="radio" name="resources" value="4" id="resources4"><label for="resources4">☆</label>
                        <input type="radio" name="resources" value="3" id="resources3"><label for="resources3">☆</label>
                        <input type="radio" name="resources" value="2" id="resources2"><label for="resources2">☆</label>
                        <input type="radio" name="resources" value="1" id="resources1"><label for="resources1">☆</label>
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