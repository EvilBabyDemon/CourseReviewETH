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
            <h2>Welcome <?php system("echo '$name $surname';"); ?>!</h2>
            <p><b>CourseReview</b><br>
                Everyone logged in should see this! </p>
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