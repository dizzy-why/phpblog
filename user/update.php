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
switch($_SERVER['REQUEST_METHOD']):
case 'GET':
	try{
		// receive the user's id attached in the URL
		$id = $_GET['id'] ?? '';
		if(empty($id)):
			throw new Exception("<p>Specify user's id to be updated.</p>");
		endif;
		// check whether the user going to be updated is the logged-in user or not.
		session_start();
		$loginId = $_SESSION['loginId'] ?? '';
		if(empty($loginId)):
			// force the user to log in.
			header("Location:login.php");
		elseif($loginId != $id):
			throw new Exception("<p>id #{$id} is NOT yours.</p>");
		endif;
		// find a user who has the given id.
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
<h2>User for id #<?php echo($id); ?>:</h2>
<!-- a form to update current user -->
<form action="update.php" method="POST">
	<input type="hidden" name="id" value="<?php echo($id); ?>"/>
	<table class="content-table-wrapper">
		<tr><th>id</th><td><?php echo($id); ?></td></tr>
		<tr><th>userID</th>
		<td><input type="text" name="userID" placeholder="userID"
		value="<?php echo($r['userID']); ?>"/></td></tr>
		<tr><th>name</th>
		<td><input type="test" name="name" placeholder="name"
		value="<?php echo($r['name']); ?>"/></td></tr>
		<tr><th>password</th>
		<td><input type="text" name="password" placeholder="password"
		value="<?php echo($r['password']); ?>"/></td></tr>
	</table>
	<button class="button-wrapper">UPDATE</button>
</form>
<?php
	// show error message.
	}catch(Exception $e){
		echo($e->getMessage());
	}
break;
case 'POST':
	try{
		// receive id, userID, password, name sent from the form.		
		$id = $_POST['id'];
		$userID = $_POST['userID'];
		$password = $_POST['password'];
		$name = $_POST['name'];
		if(empty($userID) || empty($password) || empty($name)): 
			throw new Exception(
				"<p>userID, password, name should all be specified with non-empty word.</p>");
		endif;

		// prevent updating the userID to any existing one.
		include("../connect.php");
		$sql = "SELECT * FROM tbl_user WHERE userID = :userID";
		$result = $conn->prepare($sql);
		if($result->execute([":userID"=>$userID])):
			$r = $result->fetch();
			if($r && $r['id'] != $id):
				throw new Exception(
					"<p>Cannot update : UserID '{$userID}' already exists.</p>");
			endif;
		else:
			throw new Exception(
				"<p>Cannot update : Failed to confirm '{$userID}' is still free.</p>");
		endif;
		// execute UPDATE
		$sql = "UPDATE tbl_user SET " 
			."userID=:userID, password=:password, name=:name "
			."WHERE id=:id";
		$result = $conn->prepare($sql);
		if($result->execute([
			':userID'=>$userID, ':password'=>$password, ':name'=>$name,
			':id'=>$id ])):
			$flag = ($result->rowCount() == 1);
		endif;
?>
<p>
<a href='read.php?id=<?php echo($id); ?>'>User id #<?php echo($id); ?></a> 
is <?php echo((isset($flag) && $flag) ? "updated." : "not updated."); ?>
<button class="button-wrapper" onclick=location.href="/article/search.php">back</button></p>
<?php
	// show error message.
	}catch(Exception $e){
		echo($e->getMessage());
	}		
break;
endswitch;
?>
</body>
</div>
</div>
</html>