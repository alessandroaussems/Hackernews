<?php 
if (isset($_GET["logout"])) 
{
	setcookie("login","",-3600);
	header("Location: index.php");
	exit();
}
else
{
	header("Location: index.php");
	exit();
}
?>
