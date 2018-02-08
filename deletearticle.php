<?php 
require("partials/noaccess.php");
if (isset($_POST["yes"])) 
{
		require("partials/databaseconnection.php");
		$query=$db->prepare('UPDATE berichten SET deleted=1 WHERE bericht_id=:id');
		$query->bindValue(":id", $_POST["yes"]);
		$succes=$query->execute();
		if ($succes) 
		{
			header("Location: index.php");
			exit();
		}
		else
		{
			echo "Something went wrong!";
		}
}
else
{
	header("Location: index.php");
	exit();
}
 ?>
