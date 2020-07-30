<!DOCTYPE html>
<!-- Web Programming III practice -->
<html>
<head>
<meta charset="utf-8"/>
<link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body>
<?php
// show logged-in user's name.
session_start();
$loginName = $_SESSION['loginName'] ?? '';
if(!$loginName):
	header('Location:/login.php');?> 
<!-- <button onclick=location.href="login.php">LogIn</button> -->
<?php
endif; ?>
<?php
try{
	// receive the user's id attached in the URL.
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
		exit();
	elseif($loginId != $id):
		throw new Exception("<p>id #{$id} is NOT yours.</p>");
	endif;
	// execute DELETE
	include("../connect.php");
	$sql = "DELETE FROM tbl_user WHERE Id = :id";
	$result = $conn->prepare($sql);
	if($result->execute([':id'=>$id])):
		$flag = ($result->rowCount() == 1);
	endif;
?>
<p>
<a href='read.php?id=<?php echo($id); ?>'>User id #<?php echo($id); ?></a> 
is <?php echo((isset($flag) && $flag) ? "" : "not "); ?>deleted.
</p>
<?php
header('Location:/logout.php');
// show error message.
}catch(Exception $e){
	echo($e->getMessage());
}
?>
</body>
</html>