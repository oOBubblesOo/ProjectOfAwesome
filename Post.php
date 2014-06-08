<!DOCTYPE html>
<?php
	//This is where the database is linked to and the pdo starts
	$host = "localhost";
	$dbname = "poa";
	$username = "poa";
	$password = "kolok123";
	$dsn = "mysql:host=$host;dbname=$dbname";
	$attr = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
	$pdo = new PDO($dsn, $username, $password, $attr);
?>
<html>
	<head>
		<!--Here the link to the stylesheet exists -->
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
	</head>


	<body>
		<!--this is where the header gets created-->
		<nav id="header">

			<section id="link">
				<div id="title_div">
					<h1 id="title">Skolhjälpen</h1>
				</div>
				<div class="link_div">
					<!--all the links-->
					<a class="link_text" href="home.php">Hem</a>
					<a class="link_text" href="courses.php">Kurser</a>
					<a class="link_text" href="post.php">Post</a>
				</div>
			</section>
		</nav>
		<!--here the content of the pages gets created-->
		<nav class="body">
			<section class="content">
				<div class="inside_content">
					<form action="courses.php" method="POST"><!--This code makes you able to create new summaries-->
						<p class="post_text">
							<label for="subject_id">Ämne: </label>
							<select name="subject_id">

								<?php
									foreach($pdo->query("SELECT * FROM subjects ORDER BY name") as $row)
									{
										echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
									}
								?>
							</select>
						</p>	
						<p class="post_text">
							<label for="author_name">Ditt namn: </label>
							<input type="text" name="author_name">
						</p>
						<p class="post_text">
							<label for="title">Rubrik: </label>
							<input type="text" name="title">
						</p>
						<p>
							<textarea name="content" rows="10" cols="70" placeholder="Skriv din sammanfattning här..."></textarea>
						</p>
						<input type="submit" value="Lägg till sammanfattning">
					</form>
				</div>
			</section>
		</nav>	
	</body>
</html>