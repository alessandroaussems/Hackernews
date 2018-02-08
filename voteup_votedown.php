<?php 
require("partials/noaccess.php");
require("partials/databaseconnection.php");
$query_id=$db->prepare('SELECT user_id FROM users WHERE naam=:naam');
$query_id->bindValue(":naam",$_COOKIE["login"]);
$query_id->execute();
$result_id=$query_id->fetch( PDO:: FETCH_ASSOC);
if (isset($_POST["up"]) || isset($_POST["down"])) 
{
	if (isset($_POST["up"])) 
	{
		$bericht=$_POST["up"];
	}
	if (isset($_POST["down"])) 
	{
		$bericht=$_POST["down"];
	}
	$query_points=$db->prepare('SELECT points FROM berichten WHERE bericht_id=:id');
	$query_points->bindValue(":id",$bericht);
	$query_points->execute();
	$result_points=$query_points->fetch( PDO:: FETCH_ASSOC);
	$points=intval($result_points["points"]);
	if (isset($_POST["up"]))
	{
		$points++;
	}
	if (isset($_POST["down"]))
	{
		$points--;
	}
	$query_action_points=$db->prepare('UPDATE berichten SET points=:points  WHERE bericht_id=:id');
	$query_action_points->bindValue(":id",$bericht);
	$query_action_points->bindValue(":points",$points);
	$succes=$query_action_points->execute();
	$query_vote=$db->prepare('INSERT INTO votes (user_id,bericht_id) VALUES (:user,:bericht)');
	$query_vote->bindValue(":user",$result_id["user_id"]);
	$query_vote->bindValue(":bericht",$bericht);
	$succes_add_vote=$query_vote->execute();
	if ($succes && $succes_add_vote) 
	{
		header("Location: index.php");
	}
}
 ?>
