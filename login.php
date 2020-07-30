<?php
	switch($_SERVER["REQUEST_METHOD"]):
	case "GET":
?>
<!DOCTYPE html>
<!-- Web Programming III practice -->
<html>
	<head>
	<meta charset="utf-8"/>
	<link rel='stylesheet' href='./assets/css/style.css'>
	<style>
	table {
		border:1px solid black;
	}
	td.right {
		text-align:right;
	}
	</style>
	</head>
	<body>
	<!-- a form to send userID and password -->
		<div class="login-wrapper">
			<div class=form-wrapper>
				<h2 class="jumbotron">Please log in:</h2>
				<form method="POST" action="login.php">
					<table class="table-wrapper">
						<tr><td>UserID:</td>
							<td><input name="userID" type="text" placeholder="userID"/></td></tr>
						<tr><td>Password:</td>
							<td><input name="password" type="password" placeholder="password"/></td></tr>
						<tr><td></td>
							<td class="right"><button class="button-wrapper">LogIn</button></td></tr>
					</table>
				</form>
				<button class="button-wrapper" onclick=location.href="/user/create.php">Register</button>
			</div>
		</div>
	</body>
</html>
<?php
break;
case "POST":
	// receive userID and password sent from the form.
	$u = $_POST['userID'];
	$p = $_POST['password'];

	include('./connect.php');
	session_start();
	try{
		// execute SELECT
		$result = $conn->prepare(
			"SELECT * FROM tbl_user WHERE userID = :u");
		if($result->execute([':u' => $u])):
			$r = $result->fetch();
		endif;
	}catch(PDOException $e){
		$_SESSION['loginError'] = $e->gerMessage(); 
		header("Location:failure.php");
	}
	if($r):
		if($r['password'] == $p ):
			// succeeded to log in.
			$_SESSION['loginId'] = $r['Id'];
			$_SESSION['loginName'] = $r['userID'];	
			// header("Location:../articles/search.php");
			header("Location:success.php");
		else:
			// failed to log in (wrong password)
			$_SESSION['loginError'] = "Woops! Wrong password for '{$u}'."; 
			header("Location:failure.php");
		endif;
	else:
		// failed to log in (no such user)
		$_SESSION['loginError'] = "Woops! '{$u}' is not a registered user."; 
		header("Location:failure.php");
	endif;
break;
endswitch;
?>