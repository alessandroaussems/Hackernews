<?php 
require("partials/noaccess.php");
session_start();
$message="";
$error="";
$title="Artikel Toevoegen";
require("partials/header.php");
if (isset($_POST["add"])) 
{
	$title=$_POST["title"];
	$url=$_POST["url"];
	$_SESSION["article"]=array($title,$url);
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
		$query_id=$db->prepare('SELECT user_id FROM users WHERE naam=:naam');
		$query_id->bindValue(":naam",$_COOKIE["login"]);
		$query_id->execute();
		$result_id=$query_id->fetch( PDO:: FETCH_ASSOC);
		$query=$db->prepare('INSERT INTO berichten (bericht,berichtlink,points,FK_user_id) VALUES(:bericht,:berichtlink,:points,:FK_user_id)');
		$query->bindValue(":bericht", $title);
		$query->bindValue(":berichtlink", $url);
		$query->bindValue(":points", "0");
		$query->bindValue(":FK_user_id", $result_id["user_id"]);
		$succes=$query->execute();
		if ($succes) 
		{
			header("Location: index.php");
			exit();
			session_destroy();
		}
		else
		{
			$error="Something went wrong!";
		}
	}
}
 ?>
 <main>
 <p class="error"><?=$error?></p>
 <form action="artikeltoevoegen.php" method="post" class="box">
	 <div class="formlabel">
	 	<h3>Artikel Toevoegen</h3>
	 </div>
	 <div class="formcontent">
	 	<label for="title">Title:</label><br>
	 	<input type="text" name="title" value="<?php if(isset($_SESSION["article"])) { echo $_SESSION["article"][0]; } ?>"><br>
	 	<label for="url">Url:</label><br>
	 	<input type="text" name="url" value="<?php if(isset($_SESSION["article"])) { echo $_SESSION["article"][1]; } ?>"><br>
	 	<button type="submit" name="add" class="comment-btn">Add</button>
	 </div>
 </form>
 </main>
 </body>
 </html>