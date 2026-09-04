<?php
session_start();
session_unset();   // remove all session variables
session_destroy(); // end the session
header("Location: index.php"); // redirect to homepage
exit();
?>
