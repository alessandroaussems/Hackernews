<?php 
if (isset($_COOKIE["login"])) 
{
	header("Location: index.php");
	exit();
}
 ?>