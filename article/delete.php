<!DOCTYPE html>
<!-- Web Programming III practice -->
<html>
<head>
<meta charset="utf-8"/>
<link rel='stylesheet' href='../assets/css/style.css'>

</head>
<body>
<div class="view-wrapper">
<div class="content-wrapper">	
<?php
// show logged-in user's name.
session_start();
$loginName = $_SESSION['loginName'] ?? '';
if($loginName): ?>
<p class="jumbotron">Current user : <?php echo($loginName); ?> |
<button class="button-wrapper" onclick=location.href="/article/search.php">Article</button>
<button class="button-wrapper" onclick=location.href="/user/search.php">User</button> 
<button class="button-wrapper" onclick=location.href="/logout.php">LogOut</button></p>
<?php 
else:
	header('Location:/login.php');?> 
<!-- <button onclick=location.href="login.php">LogIn</button> -->
<?php
endif; ?>
<hr/>
<?php
try{
	// receive the user's id attached in the URL.
	$id = $_GET['id'] ?? '';
	if(empty($id)):
		throw new Exception("<p>Specify article id to be updated.</p>");
	endif;
	// check whether the user going to be updated is the logged-in user or not.
	session_start();
	$loginId = $_SESSION['loginId'] ?? '';
	include("../connect.php");
	$sql = "SELECT * FROM tbl_article WHERE id=:id";
	$result = $conn->prepare($sql);
	if($result->execute([':id'=>$id])):
		$r = $result->fetch();
	endif;
	if(empty($loginId)):
		// force the user to log in.
		header("Location:login.php");
		exit();
	include("../connect.php");
	$sql = "DELETE FROM tbl_article WHERE id = :id";
	$result = $conn->prepare($sql);
	elseif($loginId != $r['author']):
		throw new Exception("<p>id #{$id} is NOT yours.</p>");
	endif;
	// execute DELETE
	include("../connect.php");
	$sql = "DELETE FROM tbl_article WHERE id = :id";
	$result = $conn->prepare($sql);
	if($result->execute([':id'=>$id])):
		$flag = ($result->rowCount() == 1);
	endif;
?>
<p>
<a href='read.php?id=<?php echo($id); ?>'>Article id #<?php echo($id); ?></a> 
is <?php echo((isset($flag) && $flag) ? "" : "not "); ?>deleted.<button class="button-wrapper"  onclick=location.href="/article/search.php">back</button>
</p>
<?php
// show error message.
}catch(Exception $e){
	echo($e->getMessage());
}
?>
</body>
</html>