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
.hidetext { -webkit-text-security: disc; }
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
	// receive a search key sent from the form.
	$key = $_POST['key'] ?? '';
	?>
	<h2>Search Users</h2>
	<form action="search.php" method="POST">
		<input type="text" name="key" value="<?php echo($key); ?>"/> : 
		<button class="button-wrapper">Search</button>
	</form>
	<button class="button-wrapper" onclick=location.href="create.php">Add new User</button></p>
	<hr/>
	<div>
	<table class="content-table-wrapper">
	<tr><th>id</th><th>name</th><th>userId</th><th>createdAt</th><th>updatedAt</th></tr>
	<?php
	try{
	include("../connect.php");	
	if($key):
		// find users that has a $key value in their name.
		
		$sql = "SELECT * FROM tbl_user WHERE name LIKE :key";
		$result = $conn->prepare($sql);
		$result->execute([":key"=>"%{$key}%"]);
		// show search results

		foreach($result as $r): ?>
	<tr><td><a href='read.php?id=<?php echo($r['id']); ?>'>
	<?php echo($r['Id']); ?></a></td>
	<td><?php echo($r['name']); ?></td>
	<td><?php echo($r['userID']); ?></td>
	<td><?php echo($r['createdAt']); ?></td>
	<td><?php echo($r['updateAt']); ?></td></tr>
	<?php
		endforeach;
	else:
		$sql = "SELECT * FROM tbl_user";
		$result = $conn->prepare($sql);
		$result->execute();
		// show search results

		foreach($result as $r): ?>
	<tr><td><a href='read.php?id=<?php echo($r['Id']); ?>'>
	<?php echo($r['Id']); ?></a></td>
	<td><?php echo($r['name']); ?></td>
	<td><?php echo($r['userID']); ?></td>
	<td><?php echo($r['createdAt']); ?></td>
	<td><?php echo($r['updateAt']); ?></td></tr>
	<?php
		endforeach;
	endif;
	?>
	</table>
	</div>
	<?php
		// show error message.
		}catch(Exceptino $e){
			echo($e->getMessage());
		}
	?>
	</div>
	</div>
</body>
</html>