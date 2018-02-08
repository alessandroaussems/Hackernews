<?php 
session_start();
$message="";
$error="";
$title="Comment Edit";
$message="";
require("partials/noaccess.php");
require("partials/databaseconnection.php");
require("partials/header.php");
if (isset($_POST["commentedit"])) 
{
		$_SESSION["commentingedit"]=array($_POST["id"],$_POST["commenttext"]);
		if (empty($_POST["commenttext"]))
		{
			$error="Comment cannot be empty";
		}
		else
		{
			$query=$db->prepare('UPDATE comments SET comment=:comment WHERE comment_id=:id');
			$query->bindValue(":comment", $_POST["commenttext"]);
			$query->bindValue(":id", $_POST["id"]);
			$succes=$query->execute();
			if ($succes) 
			{
			session_destroy();
			header("Location: bericht.php?id=".$_POST["commentedit"]);
			exit();
			}
			else
			{
				$error="Something went wrong";
			}
		}
}
		$query=$db->prepare('SELECT * FROM comments  WHERE comment_id=:id');
		$query->bindValue(":id", $_POST["editcomment"]);
		$succes=$query->execute();
		$result_comment=$query->fetch( PDO:: FETCH_ASSOC);
		$query_id=$db->prepare('SELECT user_id FROM users WHERE naam=:naam');
		$query_id->bindValue(":naam",$_COOKIE["login"]);
		$query_id->execute();
		$result_id=$query_id->fetch( PDO:: FETCH_ASSOC);
		if ($result_comment["FK_user_id"]!=$result_id["user_id"]) 
		{ 
			header("Location: bericht.php?id=".$result_comment["FK_bericht_id"]);
			exit();
		}
 ?>
  <main class="box">
  <p class="error"><?=$error?></p>
  	<div class="formlabel">
  		<h3>Edit Comment</h3>
  	</div>
  	<div class="formcontent">
	  	<form action="editcomment.php" method="post">
			<input type="hidden" name="id" value="<?php if(isset($_SESSION["commentingedit"])){ echo $_SESSION["commentingedit"][0];} else{ echo $_POST["editcomment"];}?>">
			<label for="commenttext">Comment:</label><br>
				<textarea  rows="10" cols="40" name="commenttext"><?php if(isset($_SESSION["commentingedit"])){ echo $_SESSION["commentingedit"][1]; }else{  echo $result_comment["comment"];} ?></textarea>
			<br>
			<button type="submit" name="commentedit" value="<?=$result_comment["FK_bericht_id"]?>" class="comment-btn">Change Comment!</button>
		</form>
  	</div>
	
 </main>
 </body>
