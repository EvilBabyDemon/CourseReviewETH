<?php
if(isset($_GET["course"])) {
    require_once("course.php");
} else {
    require_once("main.php");
}
?>