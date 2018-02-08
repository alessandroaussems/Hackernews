<?php 
$error="";
$areyousure=false;
require("partials/databaseconnection.php");
if (isset($_GET["id"])) 
{
	$comments=array();
	$query_artikel=$db->prepare('SELECT * FROM berichten WHERE bericht_id=:id');
	$query_artikel->bindValue(":id",$_GET["id"]);
	$query_artikel->execute();
	$result_artikel=$query_artikel->fetch( PDO:: FETCH_ASSOC);
	$query_comments=$db->prepare('SELECT c.comment_id,c.comment,c.time,u.naam FROM comments c INNER JOIN users u ON(u.user_id=c.FK_user_id) WHERE FK_bericht_id=:id && c.deleted IS NULL ORDER BY c.time');
	$query_comments->bindValue(":id",$_GET["id"]);
	$query_comments->execute();
	while ($row=$query_comments->fetch( PDO:: FETCH_ASSOC)) 
	{
		array_push($comments,$row);
	}
}
if (isset($_POST["deletecomment"])) 
{
	$areyousure=true;
	$comments=array();
	$query_artikel=$db->prepare('SELECT * FROM berichten WHERE bericht_id=:id');
	$query_artikel->bindValue(":id",$_POST["id"]);
	$query_artikel->execute();
	$result_artikel=$query_artikel->fetch( PDO:: FETCH_ASSOC);
	$query_comments=$db->prepare('SELECT c.comment_id,c.comment,c.time,u.naam FROM comments c INNER JOIN users u ON(u.user_id=c.FK_user_id) WHERE FK_bericht_id=:id && c.deleted IS NULL');
	$query_comments->bindValue(":id",$_POST["id"]);
	$query_comments->execute();
	while ($row=$query_comments->fetch( PDO:: FETCH_ASSOC)) 
	{
		array_push($comments,$row);
	}
}	
$title=$result_artikel["bericht"];
$message="";
require("partials/header.php")
 ?>


 <main>
	 <?php if ($areyousure): ?>
	 	<p>Are you sure you want to delete comment <?=$_POST["deletecomment"]?></p>
	 	<form action="deletecomment.php" method="post">
	 	<input type="hidden" name="berichtid" value="<?=$_POST["id"]?>">
	 	<input type="hidden" name="id" value="<?=$_POST["deletecomment"]?>">
	 	<button type="submit" value="yes" name="deletecommentnow">Yes!</button>
	 	<button type="submit" value="no" name="deletecommentnow">No!</button>
	 	</form>	
	 <?php endif ?>
 	
 <div class="box">	


	<div class="formlabel">
		<a href="<?=$result_artikel["berichtlink"]?>"><h3><?=$result_artikel["bericht"]?></h3></a>
	</div>
	<div class="formcontent">
		<?php if (empty($comments)): ?>
		<p>No comments yet...will you be the first one?</p>
			<?php endif ?>
		<ul class="comments">
		 	<?php foreach ($comments as $comment): ?>
		 		<li class="comment clearfix">

					<!-- USER -->
		 			<div class="userComment">
		 				<h2 class="comment_user"><?=$comment["naam"]?></h2>

		 			<!-- COMMENT -->
		 				<p class="comment_text"><?=$comment["comment"]?></p><br>
		 				<p><?=$comment["time"]?></p>
		 			</div>
					<?php if (isset($_COOKIE["login"])): ?>
		 			<?php if ($comment["naam"]==$_COOKIE["login"]): ?>

		 				<!-- EDIT AND DELETE BUTTON -->
			 			<div class="settings">
			 				<form action="bericht.php" method="post" class="edit">
				 				<input type="hidden" name="id" value="<?php if(isset($_GET["id"])) { echo $_GET["id"]; }?>">
				 				<button type="submit" name="deletecomment" value="<?=$comment["comment_id"]?>" class="delete">
				 					Delete
				 				</button>
				 			</form>
			 				<form action="editcomment.php" method="post">
			 					<button type="submit" name="editcomment" value="<?=$comment["comment_id"]?>">
			 						Edit
			 					</button>
			 				</form>
			 			</div>

		 			<?php endif ?>
					<?php endif ?>
		 		</li>
		 	<?php endforeach ?>

		 </ul>
	</div>
	<?php if (!isset($_COOKIE["login"])): ?>
		 	<p>You need to login to post a comment</p>
		 <?php endif ?>
	 <?php if (isset($_COOKIE["login"])): ?>

	 	<!-- ADD COMMENT -->
	 	<form action="addcomment.php" method="post">
	 		<a class="comments">
	 			<button type="submit" name="comment" value="<?=$result_artikel["bericht_id"]?>" class="comment-btn">
	 	 			<p>Comment</p>
	 	 		</button>
	 		</a>
	 	 	
	 	</form>

	 <?php endif ?>
</div>
</main>
</body>
</html>