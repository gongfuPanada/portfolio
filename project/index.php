<?php require '../include/php/config.php'?>

<?php include SITE_ROOT_DIR . '/include/html/basic-prefix.php'?>

<?php
if (isset($_GET['project']) &&
	($project = load_project($_GET['project'])))
{
	?><article>
		<header>
			<hgroup>
				<h1><?php echo $project['title']?></h1>
				<h2>By <?php echo $project['author']?></h2>
			</hgroup>
		</header>
		<div class="content">
			<?php echo $project['content']?>
		</div>
	</article><?php
}
else
{
	?>Invalid project.<?php
}
?>

<?php include SITE_ROOT_DIR . '/include/html/basic-suffix.php'?>
