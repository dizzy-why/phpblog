<?php
//create.php
	session_start();
	require_once('../connect.php');
?>
<!DOCTYPE html>
<!-- Web Programming III practice -->
<html>
<head>
<link rel='stylesheet' href='../assets/css/style.css'>
<meta charset="utf-8"/>
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
$loginName = $_SESSION['loginName'];
if($loginName): ?>
<p class="jumbotron">Current user : <?php echo($loginName); ?> |
<button class="button-wrapper" onclick=location.href="/article/search.php">Article</button>
<button class="button-wrapper" onclick=location.href="/user/search.php">User</button> 
<button class="button-wrapper" onclick=location.href="/logout.php">LogOut</button></p>
<?php 
else: 
	header('Location: login.php');?>
<!-- <button onclick=location.href="login.php">LogIn</button> -->
<?php
endif; ?>
<hr/>
<?php
switch($_SERVER['REQUEST_METHOD']):
case 'GET': ?>
	<!-- a form to add a new user -->
	<div class="form-wrapper">
	<h2>Add new Article</h2>
	<table  class="content-table-wrapper">
	<tr><form action="create.php" method="POST"><th>Subject</th>
	<td><input type="text" name="subject" placeholder="Subject"/></td></tr>
	<tr><th>Body</th>
	<td><input type="text" name="body" placeholder="Content"/></td></tr>
	<tr><th></th><td><button class="button-wrapper">Create Article</button></form> | <button class="button-wrapper" onclick=location.href="/article/search.php">back</button></td></tr>
	</table>
	</form>
	</div>
<?php
break;
case 'POST':
try{
	// receive userID, password, name sent from the form.
	$subject = $_POST['subject'];
	$content = $_POST['body'];
	$loginId = $_SESSION['loginId'] ?? '';
	if(empty($subject) || empty($content)):
		throw new Exception("<p> items should all be non-empty string.</p>");
	endif;
	// execute INSERT
	$sql = "INSERT INTO tbl_article VALUES (NULL, :subject, :content, :userID, now(),'0000-00-00 00:00:00')";
	$result = $conn->prepare($sql);
	if($result->execute([
		':subject'=>$subject, ':content'=>$content, 'userID'=>$loginId])):
		if($result->rowCount() == 1):
			// retrieve newly created id.
			$newId = $conn->lastInsertId();
		endif;
	endif;
	if(!isset($newId)):
		throw new Exception("<p>Failed to add new user '{$userID}'.</p>");
	endif;
?>
<p>
<a href="read.php?id=<?php echo($newId); ?>">
Article id #<?php echo($newId); ?></a> is added.<button class="button-wrapper"  onclick=location.href="/article/search.php">back</button></p>
<?php
// show error message.
}catch(Exception $e){

	echo($e->getMessage());
}
break;
endswitch; ?>
</body>
</html>