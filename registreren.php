<?php 
require("partials/redirectonlogin.php");
session_start();
$message="";
$error="";
$title="Registreren";
if (isset($_POST["register"])) 
{
	require("partials/databaseconnection.php");
	$naam=$_POST["name"];
	$email=$_POST["email"];
	$password=$_POST["password"];
	$password2=$_POST["password2"];
	$_SESSION["register"]=array($naam,$email,$password,$password2);
	$query=$db->prepare('SELECT email FROM users WHERE email=:currentemail');
	$query->bindValue(":currentemail", $email);
	$query->execute();
	$result=$query->fetch( PDO:: FETCH_ASSOC);
	$query_name=$db->prepare('SELECT naam FROM users WHERE naam=:currentnaam');
	$query_name->bindValue(":currentnaam", $naam);
	$query_name->execute();
	$result_name=$query_name->fetch( PDO:: FETCH_ASSOC);
	if ($result!=false)
	{
		$error="That email is already in use!";
	}
	if($result_name!=false)
	{
		$error="That username is already in use!";
	}
	if (preg_match('/\s/',$naam)) 
	{
		$error="Username cannot be space!";
	}
	if (preg_match('/\s/',$password)) 
	{
		$error="Password cannot be space!";
	}
	if (empty($naam)) 
	{
		$error="Your name cannot be empty!";
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$error="Your email is not correct";
	}
	if (empty($password)) 
	{
		$error="Your password can not be empty!";
	}
	if($password!=$password2)
	{
		$error="The passwords do not match!";
	}
	if ($error=="") 
	{
		$uniqueid=uniqid(mt_rand (0 ,10000), true);
		$hased_password=hash( 'sha512', $uniqueid . $password);
		$queryinsert=$db->prepare('INSERT INTO users (naam,email,wachtwoord,uniquekey) VALUES (:naam,:email,:wachtwoord,:uniquekey)');
		$queryinsert->bindValue(":naam", $naam);
		$queryinsert->bindValue(":email", $email);
		$queryinsert->bindValue(":wachtwoord", $hased_password);
		$queryinsert->bindValue(":uniquekey", $uniqueid);
		$isinserted=$queryinsert->execute();
		if ($isinserted) 
		{
			setcookie("login", $naam , time() + 2592000);
			session_destroy();
			header("Location: index.php");
		}
		else
		{
			$error="Something went wrong!";
			header("Location: registreren.php");
		}
	}

}

require("partials/header.php");
 ?>
 <p class="error"><?=$error?></p>
   	<main>
		<form action="registreren.php" method="post" class="box">
			<div class="formlabel">
	   			<h3>Registreren</h3>
	   		</div>
	   		<div class="formcontent">
		   		<label for="name">Name:</label><br>
				<input type="text" name="name" value="<?php if(isset($_SESSION["register"])){ echo $_SESSION["register"][0];}?>"><br>
				<label for="email">E-mail:</label><br>
				<input type="text" name="email" value="<?php if(isset($_SESSION["register"])){ echo $_SESSION["register"][1];}?>"><br>
				<label for="email">Password</label><br>
				<input type="password" name="password" value="<?php if(isset($_SESSION["register"])){ echo $_SESSION["register"][2];}?>"><br>
				<label for="password2">Repeat Password</label><br>
				<input type="password" name="password2" value="<?php if(isset($_SESSION["register"])){ echo $_SESSION["register"][3];}?>"><br>
				<button type="submit" name="register" class="comment-btn">Register</button>	
	   		</div>
		</form>
 	</main>
 </body>
 </html>