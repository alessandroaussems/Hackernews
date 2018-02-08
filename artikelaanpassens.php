<?php 
$showeditform=false;
session_start();
$message="";
$error="";
$title="Change";
require("partials/noaccess.php");
require("partials/databaseconnection.php");
$query_id=$db->prepare('SELECT user_id FROM users WHERE naam=:naam');
$query_id->bindValue(":naam",$_COOKIE["login"]);
$query_id->execute();
$result_id=$query_id->fetch( PDO:: FETCH_ASSOC);
if(isset($_POST["edit"]))
{
$query_artikel=$db->prepare('SELECT * FROM berichten WHERE bericht_id=:id');
$query_artikel->bindValue(":id",$_POST["edit"]);
$query_artikel->execute();
$result_artikel=$query_artikel->fetch( PDO:: FETCH_ASSOC);
if ($result_artikel["FK_user_id"]!=$result_id["user_id"]) 
{
	header("Location: index.php");
	exit();
}
}
require("partials/header.php");
if (isset($_POST["editarticle"])) 
{
	$title=$_POST["title"];
	$url=$_POST["url"];
	$id=$_POST["id"];
	$_SESSION["edit"]=array($id,$title,$url);
	if (strlen($title)>255) 
	{
		$error="The title was too long";
	}
	if (empty($title)) 
	{
		$error="The title is required";
	}
	if (strlen($url)>255) 
	{
		$error="The url was too long";
	}
	if (empty($url)) 
	{
		$error="The url is required";
	}
	if ($error=="") 
	{
		require("partials/databaseconnection.php");
		$query=$db->prepare('UPDATE berichten SET bericht=:bericht,berichtlink=:link WHERE bericht_id=:id');
		$query->bindValue(":bericht", $_POST["title"]);
		$query->bindValue(":link", $_POST["url"]);
		$query->bindValue(":id", $_POST["id"]);
		$succes=$query->execute();
		if ($succes) 
		{
			session_destroy();
			header("Location: index.php");
			exit();
		}
		else
		{
			$error="Something went wrong!";
		}
	}
}
 ?>
 
 <main class="box">
 <p class="error"><?=$error?></p>
 	<div class="formlabel">
 		<h3>Artikel aanpassen</h3>
 	</div>
 	<div class="formcontent">
 		<form action="artikelaanpassens.php" method="post">
	 	<input type="hidden" name="id" value="<?php  if(isset($_SESSION["edit"])) {echo $_SESSION["edit"][0];} else {echo $result_artikel["bericht_id"];}?>">
	 	<label for="title">Title:</label><br>
	 	<input type="text" name="title" value="<?php  if(isset($_SESSION["edit"])) {echo $_SESSION["edit"][1];} else {echo $result_artikel["bericht"];}?>"><br>
	 	<label for="url">Url:</label><br>
	 	<input type="text" name="url" value="<?php  if(isset($_SESSION["edit"])) {echo $_SESSION["edit"][2];} else {echo $result_artikel["berichtlink"];}?>"><br>
	 	<button type="submit" name="editarticle" class="comment-btn">Edit!</button>
	 </form>
 	</div>
	
 </main>
 </body>
 </html>