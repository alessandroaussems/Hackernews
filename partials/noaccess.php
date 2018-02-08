<?php 
if (isset($_COOKIE["login"])) 
{
	//do nothing just continue
}
else
{
	header("Location: index.php");
	exit();
}
 ?>
