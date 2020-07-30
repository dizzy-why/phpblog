<!DOCTYPE html>
<!-- Web Programming III practice -->
<html>
<head>
<meta charset="utf-8"/>
<link rel='stylesheet' href='../assets/css/style.css'>
<style>
table, th, td {
	border : 1px solid black;
}
</style>
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
<button class="button-wrapper" onclick=location.href="/user/search.php">user</button>
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
		throw new Exception("<p>Specify user's id to read.</p>");
	endif;

	// find the user who has the given id.
	include("../connect.php");
	$sql = "SELECT * FROM tbl_user WHERE id=:id";
	$result = $conn->prepare($sql);
	if($result->execute([':id'=>$id])):
		$r = $result->fetch();
	endif;
	if(!isset($r) || empty($r)):
		throw new Exception("<p>No registered user for id #{$id}.</p>");
	endif;
?>
<h2>User id #<?php echo($id); ?></h2>
<table class="content-table-wrapper">
	<tr><th>id</th>
	<td><?php echo($r['Id']); ?></td></tr>
	<tr><th>userID</th>
	<td><?php echo($r['userID']); ?></td></tr>
	<tr><th>name</th>
	<td><?php echo($r['name']); ?></td></tr>
</table>
<a class="button-wrapper" onclick=location.href="/user/search.php">back</a>
<?php 
// retrieve logged-in user's id stored in the session.
session_start();
$loginId = $_SESSION['loginId'] ?? '';
// allow editting / deleting only when the logged-in user is shown.
if($loginId == $id):
?>
 | <a class="button-wrapper" href="delete.php?id=<?php echo($id); ?>">Delete</a> | 
<a class="button-wrapper" href="update.php?id=<?php echo($id); ?>">Update</a>
<?php 
endif;
?>
<?php
// show error message.
}catch(Exception $e){
	echo($e->getMessage());
}
?>
</body>
</html>