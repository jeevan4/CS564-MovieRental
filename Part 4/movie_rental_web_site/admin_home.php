<!--This file is the administrator home page, we could see this file when we first login as an administrator-->
<html>
<link rel="stylesheet" type="text/css" href="styles.css">
<body>
<?php
session_start();
if (isset($_SESSION["firstname"])) {
    $first = $_SESSION["firstname"];
    $last = $_SESSION["lastname"];
    $custid = $_SESSION["custid"];
    $gend = $_SESSION["gend"];
} else {
    header("Location: error.html");
}
if ($gend == "M") {
    $salut = "Mr";
} else {
    $salut = "Ms";
}
?>
<div class="myheading">
    Hello <strong style="color: brown"> <?php echo "$salut. $first $last" ?></strong>
    <br>
    <strong>Please Select any Operation</strong>
</div>
<div class="mynavigation">
    <ul>
        <li><a class="mylink" href="#home">Admin Home</a></li>
        <li><a class="mylink" href="update_admin.php">Update Profile</a></li>
        <li><a class="mylink" href="add_movie.php">Add Movies</a></li>
        <li><a class="mylink" href="logout.php">Logout</a></li>
    </ul>
</div>
</body>