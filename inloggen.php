<?php 
require("partials/redirectonlogin.php");
session_start();
$error="";
$title="Inloggen";
$message="";
require("partials/header.php");
if (isset($_POST["login"])) 
{
	$email=$_POST["email"];
	$wachtwoord=$_POST["password"];
	$_SESSION["login"]=array($email,$wachtwoord);
	require("partials/databaseconnection.php");
	$queryuser='SELECT naam,email,wachtwoord,uniquekey FROM users WHERE email=:currentemail';
	$query=$db->prepare($queryuser);
	$query->bindValue(":currentemail",$email);
	$query->execute();
	$result=$query->fetch( PDO:: FETCH_ASSOC);
	$password_rehash=hash( 'sha512', $result["uniquekey"] . $wachtwoord);
	if ($password_rehash==$result["wachtwoord"]) 
	{
		setcookie("login", $result["naam"], time() + 2592000);
		session_destroy();
		header("Location: index.php"); /* Redirect browser */
		exit();
	}
	else
	{
		$error="Your email or password is not correct";
	}
}

 ?>
  	<main>
  		<p class="error"><?=$error?></p>
		<form action="inloggen.php" method="post" class="box">
			<div class="formlabel">
	   			<h3>Inloggen</h3>
	   		</div>
	   		<div class="formcontent">
	   			<label for="email">E-mail:</label><br>
				<input type="text" name="email" value="<?php if(isset($_SESSION["login"])){ echo $_SESSION["login"][0];}?>"><br>
				<label for="email">Password</label><br>
				<input type="password" name="password" value="<?php if(isset($_SESSION["login"])){ echo $_SESSION["login"][1];}?>"><br>
				<button type="submit" name="login" class="comment-btn">Inloggen</button>
	   		</div>
		</form>
		<p>No account? Register <a href="registreren.php">here </a>!</p>
 	</main>
 </body>
 </html>
