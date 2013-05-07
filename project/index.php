<?php require '../include/php/config.php'?>

<?php include SITE_ROOT_DIR . '/include/html/basic-prefix.php'?>

<?php
$selected_project = isset_or($_GET['project']);
if ($selected_project &&
	($project = load_project($selected_project)))
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
	// error: failed to load specified project or project not specified
	?>
	<p>
		<?php
		if (!$selected_project)
		{
			echo 'No project specified.';
		}
		else
		{
			echo "The project \"$selected_project\" does not exist.";
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
