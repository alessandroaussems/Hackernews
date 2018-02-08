<?php 
	require("databaseconnection.php");
	$reset_comments=$db->query('DELETE FROM comments WHERE FK_user_id!=1');
	$reset_berichten=$db->query('DELETE FROM berichten WHERE FK_user_id!=1');
	$reset_votes=$db->query('DELETE FROM votes');
 ?>