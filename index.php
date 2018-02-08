<?php
	$login=0;
	$showbuttons=true;
	$areyousure=false;
	$title="Overzicht";
	$artikels=array();
	$votes=array();
	$message="";
	require("partials/databaseconnection.php");
	if (isset($_COOKIE["login"]))
	{
		$login=$_COOKIE["login"];
	}
	$query_id=$db->prepare('SELECT user_id FROM users WHERE naam=:naam');
	$query_id->bindValue(":naam",$login);
	$query_id->execute();
	$result_id=$query_id->fetch( PDO:: FETCH_ASSOC);
	$artikels_query=$db->query('SELECT b.bericht_id,b.berichtlink,b.bericht,b.points,b.FK_user_id,u.naam FROM berichten b INNER JOIN users u ON (b.FK_user_id=u.user_id)  WHERE b.deleted IS NULL ORDER BY b.points DESC');
	while ($row=$artikels_query->fetch( PDO:: FETCH_ASSOC))
	{
		array_push($artikels,$row);
	}
	require("partials/header.php");
	if (isset($_POST["delete"]))
	{
		$areyousure=true;
	}
?>

<main class="box">
<div class="formlabel">
	<h3>article overview</h3>
</div>
<div class="formcontent">
	<?php if ($areyousure): ?>
	<p>Are you sure you want to delete article <?=$_POST["delete"]?></p>
	<form action="deletearticle.php" method="post">
		<button type="submit" name="yes" value="<?=$_POST["delete"]?>">Yes!</button>
		<button type="submit" name="no">No!</button>
	</form>
	<?php endif ?>
	<?php foreach ($artikels as $artikel) :?>
	<article>
			<?php 
			$query_votes=$db->prepare('SELECT * FROM votes WHERE bericht_id=:berichtid AND user_id=:id');
			$query_votes->bindValue(":berichtid",$artikel['bericht_id']);
			$query_votes->bindValue(":id",$result_id["user_id"]);
			$query_votes->execute();
			$votes=$query_votes->fetch(PDO:: FETCH_ASSOC);
			if ($votes["user_id"]==$result_id["user_id"]) 
			{
				$showbuttons=false;
			}
			else
			{
				$showbuttons=true;
			}
		 ?>
	<div class="articles clearfix">
		<div class="vote">
			<!-- VOTE BUTTONS -->
			<?php if (isset($_COOKIE["login"]) && $showbuttons): ?>
				<div class="vote-btns">
					<form action="voteup_votedown.php" method="post">
						<button type="submit" name="up" value="<?=$artikel['bericht_id']?>">
							<img src="img/vote-arrow-up.png">
						</button>
					</form>


					<form action="voteup_votedown.php" method="post">
						<button type="submit" name="down" value="<?=$artikel['bericht_id']?>">
							<img src="img/vote-arrow-down.png">
						</button>
					</form>
				</div>
			<?php endif ?>
		</div>

		<div class="article">
			<!-- TITLE -->
			<a href="<?=$artikel['berichtlink']?>">
				<h1><?=$artikel['bericht']?></h1>
			</a>

			<!-- POSTED BY -->
			<p class="author"><i>posted by <?=$artikel['naam']?></i></p>
			<!-- POINTS -->
			<div class="points">
				<p><?=$artikel['points']?> points</p>
			</div>
			<!-- COMMENTS -->
			<a href="bericht.php?id=<?=$artikel['bericht_id']?>" class="comments">
				<button class="comment-btn">comments</button>
			</a>
			
		</div>

		<div class="settings">
			<!-- EDIT/DELETE ARTICLE -->
			<?php if ($artikel['FK_user_id']==$result_id["user_id"]): ?>
			<form action="artikelaanpassens.php" method="post">
				<button type="submit" name="edit" value="<?=$artikel['bericht_id']?>">Edit</button>
			</form>
			<form action="index.php" method="post">
				<button id="delete" type="submit" name="delete" value="<?=$artikel['bericht_id']?>" class="delete">Delete</button>
			</form>
			<?php endif ?>
		</div>
	</div>
	</article>
	<?php endforeach ?>
</div>

</main>
</body>
</html>