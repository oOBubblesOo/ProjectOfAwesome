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
					<ul>
						<?php	
							//This checks if there is anything clicked
							if(!empty($_POST))
							{
								//This is where the new subjects users have put in is sent to the database
								if(isset($_POST['subject_name']))
								{
									if($_POST['subject_name'] !== "")
									{
										$_POST = null;
										$subject_name = filter_input(INPUT_POST, 'subject_name');
										$statement = $pdo->prepare("INSERT INTO subjects (name) VALUES (:subject_name)");
										$statement->bindParam(":subject_name", $subject_name);
										if(!$statement->execute()) //if it doesn't work the errorInfo tells you why
											print_r($statement->errorInfo());
									}
								}
								//This is where the new summarys are sent in the database
								else if(isset($_POST['author_name']) && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['subject_id'])) 
								{
									if($_POST['author_name'] !== "" && $_POST['title'] !== "" && $_POST['content'] !== "" && $_POST['subject_id'] !== "")
									{
										$author_name = filter_input(INPUT_POST, 'author_name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
										$title       = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
										$content     = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
										$subject_id  = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
										$statement = $pdo->prepare("INSERT INTO summaries (subject_id, author_name, date, title, content) VALUES (:subject_id, :author_name, NOW(), :title, :content)");
										$statement->bindParam(":subject_id", $subject_id);
										$statement->bindParam(":author_name", $author_name);
										$statement->bindParam(":title", $title);
										$statement->bindParam(":content", $content);
										if($statement->execute())
											print_r($statement->errorInfo());
									}	
								}
							}
							
							if(!empty($_GET) && isset($_GET['subject_id']))//this makes it so summaries with a non existent subject_id are not shown
							{
								if($_GET['subject_id'] !== "")
								{
									$subject_id = filter_input(INPUT_GET, "subject_id", FILTER_VALIDATE_INT);

									$sub_statement = $pdo->prepare("SELECT * FROM summaries WHERE subject_id=:subject_id");
									$sub_statement->bindParam(":subject_id", $subject_id);
									if($sub_statement->execute())
									{
										if($row = $sub_statement->fetch())
										{
											echo "<ul>";
											foreach($pdo->query("SELECT * FROM summaries ORDER BY date DESC") as $row) //Here the subjects are getting shown
											{
												echo "<li><a href=\"?summary_id={$row['id']}\">{$row['title']}, av {$row['author_name']} ({$row['date']})</a></li>";
											}
											echo "</ul>";
										}
									}
									else
										print_r($sub_statement->errorInfo());
								}
							}
							/*This code makes it so that when you press a subject
							all the summaries with the subject_id that is equall 
							to the pressed subjects id are shown*/
							elseif(!empty($_GET) && isset($_GET['summary_id']))
							{
								if($_GET['summary_id'] !== "")
								{
									$summary_id = filter_input(INPUT_GET, "summary_id", FILTER_VALIDATE_INT);

									$sum_statement = $pdo->prepare("SELECT summaries.*, subjects.name AS 'subject_name' FROM summaries JOIN subjects ON summaries.subject_id=subjects.id WHERE summaries.id=:summary_id");
									$sum_statement->bindParam(":summary_id", $summary_id);
									if($sum_statement->execute())
									{
										if($row = $sum_statement->fetch())
										{
											
											echo "<div id=\"title_date\"> <h1 id=\"summary_title\" >{$row['title']}</h1>
													<p id=\"summary_date\">{$row['date']}</p></div>
													<div id=\"name_subject\"><p id=\"summary_author\">av {$row['author_name']}</p>
													<p id=\"summar_subject\">Ämne: {$row['subject_name']}</p></div>
													<p id=\"summary_content\">{$row['content']}</p>";
											?>
												<section id="comment_box">
											<?php
											echo "<form action=\"courses.php?summary_id={$row['id']}\" method=\"POST\">";
											echo "<input type=\"hidden\" name=\"summary_id\" value=\"{$row['id']}\" />";
											?>
												<!--Here is a form where you write comments on the summary you are watching-->
													<p>
														<label for="author_name">Ditt namn:</label>
														<input type="text" name="author_name" />
													</p>
													<p>
														<label for="content">Kommentar: </label>
														<input type="text" name="content" />
													</p>
													<input type="submit" />
												</form>
													<div id="comment_output">
														<?php
														//This sends the comments to the database
														if(isset($_POST['summary_id']) && isset($_POST['author_name']) && isset($_POST['content'])) 
														{
															if($_POST['summary_id'] !== "" && $_POST['author_name'] !== "" && $_POST['content'] !== "")
															{
																$summary_id  = filter_input(INPUT_POST, 'summary_id', FILTER_VALIDATE_INT);
																$author_name = filter_input(INPUT_POST, 'author_name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
																$content     = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
																$statement = $pdo->prepare("INSERT INTO comments (summary_id, author_name, date, content) VALUES (:summary_id, :author_name, NOW(), :content)");
																$statement->bindParam(":summary_id", $summary_id);
																$statement->bindParam(":author_name", $author_name);
																$statement->bindParam(":content", $content);
																if($statement->execute())
																	print_r($statement->errorInfo());
															}
														}
														//This shows all comments on the summary you are visiting
														$com_statement = $pdo->prepare("SELECT * FROM comments WHERE summary_id=:summary_id ORDER BY date DESC");
														$com_statement->bindParam(":summary_id", $summary_id);
														if($com_statement->execute())
														{
															while($comment=$com_statement->fetch())
															{
																echo "<h1>{$comment['author_name']} ({$comment['date']})</h1>
																		<p>{$comment['content']}</p>";
															}
														}
														else
															print_r($com_statement->errorInfo());
														?>
													</div>
												</section>
											<?php
											
										}
									}
									else
										print_r($sum_statement->errorInfo());
								}
							}
							else
							{
								//This shows all the subjects if you are not visiting any
								echo "<h1 id=\"kurser\">Kurser</h1>";
								echo "<li><a id=\"add_subject\" href=\"add_subject.php\">Lägg till kurs</a></li>";
								foreach($pdo->query(" SELECT * FROM subjects ORDER BY name") as $row)
								{
									echo "<li><a href=\"courses.php?subject_id={$row['id']}\">{$row['name']}</a></li>";
								}
							}
						?>
					</ul>
				</div>
			</section>
		</nav>
	</body>
</html>