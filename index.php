<?php require 'include/php/config.php'?>

<?php include SITE_ROOT_DIR . '/include/html/basic-prefix.php'?>

<div id="project-list">
	<?php
	// load all projects
	$projects = array();
	foreach (list_projects() as $project_name)
		$projects[] = load_project($project_name);

	if (get_requested_tags())
	{
		// hide projects that don't match the selected tags
		$matching_projects = array();
		foreach ($projects as $project)
			if (any_tags($project['tags']))
				$matching_projects[] = $project;
		$projects = $matching_projects;

		?>
		<p>
			Here are all of my projects that involve
			<?php
			$tags = array();
			foreach (str_split(get_requested_tags()) as $tag)
				$tags[] = strtolower(get_tag_name($tag));
			echo format_english_list($tags, 'or');
			?>.
		</p>
		<?php
	}
	else
	{
		?>
		<p>
			Here are all of my projects.  To fine tune this list, select one or
			more tags from the menu above.  Only those projects that match at
			least one of the selected tags will be displayed.
		</p>
		<?php
	}

	// render a preview box for each project
	foreach ($projects as $project)
	{
		?><article>
			<header>
				<hgroup>
					<a href="<?php echo SITE_ROOT_URL . 'project/?project=' . $project['name']?>">
						<h1>
							<?php echo $project['title']?>
						</h1>
					</a>
					<h2>
						<table>
							<tr>
								<td>
									By <?php echo $project['author']?>
								</td>
								<td>
									Tags: <?php echo $project['tags']?>
								</td>
							</tr>
						</table>
					</h2>
				</hgroup>
			</header>
			<div class="content">
				<?php echo $project['preview']?>
			</div>
		</article><?php
	}
	?>
</div>

<script>
	/**
	 * Size project-preview boxes to produce the best layout for the width of
	 * the window.
	 */
	function optimize_article_size()
	{
		minSize = 350;
		margin = $('article').position().left * 2;
		bodySize = $('body').width() - margin;
		n = Math.floor(bodySize / minSize);
		$('article').width(bodySize / n - (margin + n + 3));
	}
	$(window).bind('resize', optimize_article_size);
	optimize_article_size();
</script>

<?php include SITE_ROOT_DIR . '/include/html/basic-suffix.php'?>
