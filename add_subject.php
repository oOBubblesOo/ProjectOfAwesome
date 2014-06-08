<!DOCTYPE html>
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
					<form action="courses.php" method="POST">
						<p>
							<!--This creates a way to create new subjects-->
							<label for="name">Subject name:</label>
							<input type="text" name="subject_name" />
						</p>
						<input type="submit" value="Add" />
					</form>
				</div>
			</section>
		</nav>	
		
	</body>
</html>