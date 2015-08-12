<!--
This page allows user to logout from the system by destroying
all the session variables that are created during a session.

-->
<?php
session_start();
if(isset($_SESSION["firstname"]))
{
session_unset();
session_destroy();
echo "loggingout";
header("Location: index.php");
}
?>