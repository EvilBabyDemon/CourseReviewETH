<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Review</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="../default.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>CourseReview coming up</h1>
        <h2>&nbsp;</h2>
    </div>
    <div id="menu">
        <ul>
            <li><a href="../" onFocus="if(this.blur)this.blur()">Home</a></li>
            <li><a href="../download/" onFocus="if(this.blur)this.blur()">Download</a></li>
            <li><a href="#" onFocus="if(this.blur)this.blur()">Private</a></li>
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
            Just change the text in the fields and press on the button.<br>

            <?php
            //check DB if Course exists
            $db = new SQLite3('CourseReviews.db');

            $stmt = $db->prepare("SELECT * FROM REVIEWS WHERE ID=:id");
            $stmt->bindParam(':id', $val, SQLITE3_TEXT);
            $result = $stmt->execute();

            $test = true;
            while ($row = $result->fetchArray()) {
            ?>
                <form method="post" action="edited.php">
                    <fieldset>
                        <legend>Review</legend>
                        <label>
                            <input style="color:red" type="text" name="course" value="<?php echo $row[1]; ?>" readonly>
                            <br>
                            <textarea name="review" cols="50" rows="3"><?php echo $row[2]; ?></textarea>
                        </label>
                        <p>
                            <button type="submit">Edit</button>
                        </p>
                    </fieldset>
                </form>

            <?php
                $test = false;
            }
            if ($test) {
                print "You didn't review anything yet.";
            }

            $db->close()
            ?>

        </div>
    </div>
    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: lteufelbe@ethz.ch</p>
    </div>

</body>

</html>