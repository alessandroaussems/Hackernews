<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta property="og:image" content="http://hackernews.alessandro.aussems.mtantwerp.eu/img/icon.png"/>
	<meta name="author" content="Alessandro Aussems">
	<title>Hackernews | <?=$title?></title>
	<link rel="stylesheet" type="text/css" href="css/styling.css">
	<link rel="icon" type="image/png" href="img/icon.png">
</head>
<body>
	<?=$message ?>
	<header class="clearfix">
		<nav>
			<ul class="clearfix">
				<li class="left">
					<h1 id="logo">Hackernews!</h1>
				</li>
				<li class="left">
					<a class="link" href="index.php">Home</a>
				</li>
				<li>
					<?php if (isset($_COOKIE["login"])): ?>
					<a href="artikeltoevoegen.php">Add Article</a>
				</li>
				<li>
					<div id="actions" class="log clearfix">
						<li>
							<a class="link" href="uitloggen.php?logout">
								Uitloggen
							</a>
						</li>
						<li>
							<p class="log">
								Ingelogd als <strong><?=$_COOKIE["login"]?></strong>
							</p>
						</li>
					</div>
				</li>
				<li>
					<?php else: ?>
					<div id="actions" class="clearfix">
						<li>
							<a href="inloggen.php">Inloggen</a>
						</li>
						<li>
							<a href="registreren.php">Registreren</a>
						</li>
					<?php endif	 ?>
					</div>
				</li>
			</ul>
		</nav>
	</header>