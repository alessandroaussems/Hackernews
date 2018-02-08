<?php 
session_start();
$error="";
require("partials/noaccess.php");
$title="Comment";
$message="";
require("partials/databaseconnection.php");
require("partials/header.php");
if (isset($_POST["insertcomment"])) 
{
		$query_id=$db->prepare('SELECT user_id FROM users WHERE naam=:naam');
		$query_id->bindValue(":naam",$_COOKIE["login"]);
		$query_id->execute();
		$result_id=$query_id->fetch( PDO:: FETCH_ASSOC);
		$_SESSION["commenting"]=array($_POST["id"],$_POST["commenttext"]);
		if (empty($_POST["commenttext"])) 
		{
			$error="Comment cannot be empty";
		}
		else
		{
			$query=$db->prepare('INSERT INTO comments (comment,FK_user_id,FK_bericht_id) VALUES(:comment,:FK_user_id,:FK_bericht)');
			$query->bindValue(":comment", $_POST["commenttext"]);
			$query->bindValue(":FK_bericht", $_POST["id"]);
			$query->bindValue(":FK_user_id", $result_id["user_id"]);
			$succes=$query->execute();
			if ($succes) 
			{
			session_destroy();
			header("Location: bericht.php?id=".$_POST["id"]);
			exit();
			}
			else
			{
				$error="Something went wrong";
			}
		}
}
 ?>
 <main class="box">
  <p class="error"><?=$error?></p>
  	<div class="formlabel">
  		<h3>Comment toevoegen</h3>
  	</div>
  	<div class="formcontent">
  		<form action="addcomment.php" method="post">
			<input type="hidden" name="id" value="<?php if(isset($_SESSION["commenting"])){ echo $_SESSION["commenting"][0];} else { echo $_POST["comment"];}?>">
			<label for="commenttext">Comment:</label><br>
			<textarea placeholder="Write your comment here!" rows="10" cols="40" value="<?php if(isset($_SESSION["commenting"])){ echo $_SESSION["commenting"][1];}?>" name="commenttext"></textarea><br>
			<button type="submit" name="insertcomment" class="comment-btn">Comment!</button>
		</form>
  	</div>
 </main>
 </body>
