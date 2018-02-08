<?php 
require("partials/noaccess.php");
if (isset($_POST["deletecommentnow"])) 
{
	if($_POST["deletecommentnow"]=="yes")
	{
		require("partials/databaseconnection.php");
		$query=$db->prepare('UPDATE comments SET deleted=1 WHERE comment_id=:id');
		$query->bindValue(":id", $_POST["id"]);
		$succes=$query->execute();
		if ($succes) 
		{
				header("Location: bericht.php?id=".$_POST["berichtid"]);
				exit();
		}
		else
		{
			echo "Whoops Something went wrong!";
		}
	}
	else
	{
		header("Location: bericht.php?id=".$_POST["berichtid"]);
		exit();
	}
}
else
{
	header("Location: index.php");
	exit();
}
 ?>
