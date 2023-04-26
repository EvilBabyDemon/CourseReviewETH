<!DOCTYPE html>
<html lang="en">
<?php
$token = file_get_contents("secret/key.txt");
$api = trim(file_get_contents("secret/api.txt"));
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy">
    <title>CourseReview</title>
    <?php include "meta.php" ?>
    <meta property="og:url" content="https://n.ethz.ch/~lteufelbe/coursereview/all.php">
    <meta property="og:title" content="All courses with Reviews">

    <link rel="icon" href="icon.png" type="image/icon type">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="" />
    <meta name="description" content="All courses from ETHZ with Reviews on the CourseReview website." />
    <link href="main.css" rel="stylesheet" type="text/css" />
</head>

<script>
    //get all
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            if (this.status == 200) {
                var all = document.getElementById("all");
                var resp = JSON.parse(JSON.parse(this.responseText));
                for (row of resp) {
                    var li = document.createElement("li");
                    var link = document.createElement("a");
                    link.textContent = row.CourseName;
                    link.href = "https://n.ethz.ch/~lteufelbe/coursereview/?course=" + row.CourseNumber;
                    li.appendChild(link);
                    all.appendChild(li);
                }
            }
        }
        xmlhttp.open("GET", "https://rubberducky.vsos.ethz.ch:1855/allReviews", true);
        xmlhttp.send();
    }
</script>

<body>
    <?php include 'includes/menu.php' ?>
    <div id="content">
        <div id="columnA">
            <p><b>All courses with Reviews</b></p>
            <ul id="all">
            </ul>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>