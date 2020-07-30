<?php
	session_start();
	require_once('../connect.php');
?>


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
<div class="login-wrapper">
<?php
switch($_SERVER['REQUEST_METHOD']):
case 'GET': ?>
	<!-- a form to add a new user -->
<div class="form-wrapper">
	<h2>Add new user</h2>
	
	<table class="content-table-wrapper">
	
	<tr><form action="create.php" method="POST"><th>userID</th>
	<td><input type="text" name="userID" placeholder="userID"/></td></tr>
	<tr><th>password</th>
	<td><input type="password" name="password" placeholder="password"/></td></tr>
	<tr><th>name</th>
	<td><input type="text" name="name" placeholder="name"/></td></tr>
	<tr><th></th><td><button class="button-wrapper">Create</button></form>  |  <button class="button-wrapper" onclick=location.href="/user/search.php">back</button></td></tr>
	</table>
	
</div>
<?php
break;
case 'POST':
try{
	// receive userID, password, name sent from the form.
	$userID = $_POST['userID'];
	$password = $_POST['password'];
	$name = $_POST['name'];
	if(empty($userID) || empty($password) || empty($name)):
		throw new Exception("<p>userID, password, name should all be non-empty string.</p>");
	endif;

	include("../connect.php");
	// prevent adding already existing userID.
	$sql = "SELECT * FROM tbl_user WHERE userID = :userID";
	$result = $conn->prepare($sql);
	if($result->execute([":userID"=>$userID])):
		$r = $result->fetch();
		if($r):
			throw new Exception(
				"<p>Cannot add : UserID '{$userID}' already exists.</p>");
		endif;
	else:
		throw new Exception(
			"<p>Cannot add : Failed to confirm '{$userID}' is still free.</p>");
	endif;
	// execute INSERT
	$sql = "INSERT INTO tbl_user VALUES (NULL, :userID, :name, :password,now(),'0000-00-00 00:00:00')";
	$result = $conn->prepare($sql);
	if($result->execute([
		':userID'=>$userID, ':password'=>$password, 'name'=>$name])):
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
User id #<?php echo($newId); ?></a> is added.<button onclick=location.href="/user/search.php">back</button>
</p>
<?php
// show error message.
}catch(Exception $e){
	echo($e->getMessage());
}
break;
endswitch; ?>
</body>
</html>