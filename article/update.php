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
		
		// find a user who has the given id.
		include("../connect.php");
		$sql = "SELECT * FROM tbl_article WHERE id=:id";
		$result = $conn->prepare($sql);
		if($result->execute([':id'=>$id])):
			$r = $result->fetch();
		endif;
		if(empty($loginId)):
			// force the user to log in.
			header("Location:login.php");
		elseif($loginId != $r['author']):
			throw new Exception("<p>id #{$id} is NOT yours.</p>");
		endif;
		if(!isset($r) || empty($r)):
			throw new Exception("<p>No article found for id #{$id}.</p>");
		endif;
?>
<h2>Article for id #<?php echo($id); ?>:</h2>
<!-- a form to update current user -->
<form action="update.php" method="POST">
	<input type="hidden" name="id" value="<?php echo($id); ?>"/>
	<table class="content-table-wrapper" >
		<tr><th>id</th><td><?php echo($id); ?></td></tr>
		<tr><th>Subject</th>
		<td><input type="text" name="subject" placeholder="Subject"
		value="<?php echo($r['subject']); ?>"/></td></tr>
		<tr><th>Body</th>
		<td><input type="test" name="body" placeholder="Body"
		value="<?php echo($r['body']); ?>"/></td></tr>
		<tr><th>Author</th>
		<td><?php echo($r['author']); ?></td></tr>
		<tr><th>Modified</th>
		<td><?php echo($r['modified']); ?></td></tr>
	</table>
	<button  class="button-wrapper">UPDATE</button>
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
		$subject = $_POST['subject'];
		$body = $_POST['body'];
		$id = $_POST['id'];
		$loginId = $_SESSION['loginId'] ?? '';
		if(empty($id) || empty($subject) || empty($body)): 
			throw new Exception(
				"<p>id, subject, title should all be specified with non-empty word.</p>");
		endif;

		// prevent updating the article id belonging to other user.
		include("../connect.php");
		$sql = "SELECT * FROM tbl_article WHERE id = :id";
		$result = $conn->prepare($sql);
		if($result->execute([":id"=>$id])):
			$r = $result->fetch();
			if($r && $r['author'] != $loginId):
				throw new Exception(
					"<p>Cannot update : id '{$id}' belongs to other user.</p>");
			else:
		// execute UPDATE
				$sql = "UPDATE tbl_article SET `subject`=:subject, `body`=:body WHERE `id`=:id";
				$result = $conn->prepare($sql);

				if($result->execute([':subject'=>$subject, ':body'=>$body,':id'=>$id ])):
					$flag = ($result->rowCount() == 1);
				endif;
			endif;
		endif;
?>
<p>
<a href='read.php?id=<?php echo($id); ?>'>Article id #<?php echo($id); ?></a> 
is <?php echo((isset($flag) && $flag) ? "updated." : "not updated."); ?><button class="button-wrapper" onclick=location.href="/article/search.php">back</button>
</p>
<?php
	// show error message.
	}catch(Exception $e){
		echo($e->getMessage());
	}		
break;
endswitch;
?>
</div>
</div>
</body>
</html>