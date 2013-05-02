<?php require '../include/php/config.php'?>

<?php include SITE_ROOT_DIR . '/include/html/basic-prefix.php'?>

<?php
// display full content of currently-selected project
if (isset($_GET['project']) &&
	($project = load_project($_GET['project'])))
{
	?><article>
		<header>
			<hgroup>
				<h1><?php echo $project['title']?></h1>
				<!--<h2>By <?php echo $project['author']?></h2>-->
			</hgroup>
		</header>
		<div class="content">
			<?php echo $project['content']?>
		</div>
	</article><?php
}
else
{
	// error: no or invalid project specified
	?>
	<p>
		<?php
		if (!isset($_GET['project']))
		{
			?>
				No project specified.
			<?php
		}
		else
		{
			?>
				The name "<?php echo $_GET['project']?>" does not identify a
				valid project.
			<?php
		}
		?>
		Please choose one of the following projects.
	</p>
	<ul>
		<?php
		foreach (list_projects() as $project_name)
		{
		?>
		<li>
			<a href="?project=<?php echo $project_name?>">
				<?php echo $project_name?>
			</a>
		</li>
		<?php
		}
		?>
	</ul>
	<?php
}
?>

<?php include SITE_ROOT_DIR . '/include/html/basic-suffix.php'?>
