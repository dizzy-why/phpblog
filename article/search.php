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
// receive a search key sent from the form.
$key = $_POST['key'] ?? '';
?>
<h2>Search Articles</h2>
<form action="search.php" method="POST">
<input type="text" name="key" value="<?php echo($key); ?>"/> : 
<button class="button-wrapper">Search</button>
</form>
<button class="button-wrapper" onclick=location.href="create.php">Add new Article</button></p>
<hr/>
<div>
<table class="content-table-wrapper">
<tr><th>id</th><th>subject</th><th>body</th><th>author</th><th>modified</th></tr>
<?php
try{
include("../connect.php");
if($key):
	// find users that has a $key value in their name.
	
	$sql = "SELECT * FROM tbl_article INNER JOIN `tbl_user` ON `tbl_user`.`Id` = `tbl_article`.`author` WHERE `subject` LIKE :key";
	$result = $conn->prepare($sql);
	$result->execute([":key"=>"%{$key}%"]);
	// show search results
	foreach($result as $r):
		echo$r; ?>
	
<tr><td><a href='read.php?id=<?php echo($r['id']); ?>'>
<?php echo($r['id']); ?></a></td>
<td><?php echo($r['subject']); ?></td>
<td><?php echo($r['body']); ?></td>
<td><?php echo($r['userID']); ?></td>
<td><?php echo($r['modified']); ?></td>
</tr>
<?php
	endforeach;
else:
	$sql = "SELECT * FROM tbl_article INNER JOIN `tbl_user` ON `tbl_user`.`Id` = `tbl_article`.`author` ";
	$result = $conn->prepare($sql);
	$result->execute();
	// show search resultss
	foreach($result as $r): ?>
<tr><td><a href='read.php?id=<?php echo($r['id']); ?>'>
<?php echo($r['id']); ?></a></td>
<td><?php echo($r['subject']); ?></td>
<td><?php echo($r['body']); ?></td>
<td><?php echo($r['userID']); ?></td>
<td><?php echo($r['modified']); ?></td>
</tr>
<?php
	endforeach;
?>
</table>
</div>
<?php
endif;
// show error message.
}catch(Exceptino $e){
	echo($e->getMessage());
}
?>
	</div>
	</div>
</body>
</html>