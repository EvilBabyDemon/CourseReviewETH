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
            <p><b>Add a CourseReview here.</b><br>

            <form method="post" action="submit.php">
                <fieldset>
                    <legend>Review</legend>
                    <p>
                        <label for="course">Course number:</label><br>
                        <input type="text" id="course" name="course" pattern="[A-Z0-9]{3}-[A-Z0-9]{4}-[A-Z0-9]{3}" placeholder="252-0027-00L">
                    </p>
                    <p>
                        <label>
                            Review:
                            <br>
                            <textarea name="review" cols="50" rows="3" placeholder="For some very hard, while others who already have knowledege about the content it is very easy."></textarea>
                        </label>
                    </p>
                    <p>
                        <button type="submit">Submit</button>
                    </p>
                </fieldset>
            </form>

        </div>
    </div>
    <div id="footer">
        <p>If you think something is wrong or have any suggestion please contact me: lteufelbe@ethz.ch</p>
    </div>

</body>

</html>