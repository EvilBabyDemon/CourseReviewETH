<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CourseReview</title>
    <link rel="icon" href="../icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../main.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include '../includes/menu.php' ?>
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
                            $db = new SQLite3('../secret/CourseReviews.db');
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
                        <label>Review:</label><br>
                            <textarea name="review" id="text" rows="4" placeholder="For some very hard, while others who already have knowledge about the content it is very easy."></textarea>
                    </p>
                    <script>
                        if (localStorage.text) {
                            document.getElementById('text').innerHTML = localStorage.text;
                        }
                        const input = document.querySelector('textarea');
                        input.addEventListener('input', updateValue);

                        function updateValue(e) {
                            localStorage.text = e.target.value;
                        }
                    </script>
                    <?php
                    require_once("../rating.php");
                    includeRating(null, 0);
                    ?>
                    <p>
                        <button type="submit">Submit</button>
                    </p>
                </fieldset>
            </form>

        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>