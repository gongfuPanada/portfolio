<?php
require '../include/php/config.php';

////////////////////////////////////////////////////////////////////////////////

ob_start();

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
		echo $selected_project ?
			"The project \"$selected_project\" does not exist." :
			'No project specified.';
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

$PAGE_CONTENT = ob_get_clean();

////////////////////////////////////////////////////////////////////////////////

include join_path(SITE_ROOT_DIR, 'include/html/page.php')
?>
