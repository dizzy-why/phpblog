<!DOCTYPE html>
<!-- Web Programming III practice -->
<html>
<head>
<meta charset="utf-8"/>
<link rel='stylesheet' href='./assets/css/style.css'>
</head>
<body>
<div class="login-wrapper">
<div class="form-wrapper">
<p style="color:red;text-align:center">
<?php
session_start();
echo($_SESSION['loginError'] ?? '');
unset($_SESSION['loginError']);
?>
</p>
<p class="jumbotron">Login Failed. <a href="login.php">Try again.</a></p>
</div>
</div>
</body>
</html>