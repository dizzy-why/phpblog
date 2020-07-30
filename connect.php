<?php
/* ウェブプログラミングIII 授業中の演習 */
$conn = null;
try{
	$option = array(
		PDO::ATTR_ERRMODE 
			=> PDO::ERRMODE_EXCEPTION);
	$conn = new PDO(
		"mysql:host=localhost;dbname=test;charset=utf8",
		"root", "admin", $option);
}catch(PDOException $e){
	die($e->getMessage());
}
?>