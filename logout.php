<?php
session_start();
$_SESSION = array();
session_destroy();

header("Location:login.php");
// header("Location:../articles/search.php");
?>