<?php
require 'include/php/config.php';

////////////////////////////////////////////////////////////////////////////////

ob_start();

echo '<ul>';
foreach (get_all_tags() as $tag_key => $tag_name)
{
	echo
		'<li><a href="',
		SITE_ROOT_URL,
		'?tags=',
		toggle_tags($tag_key),
		'"';
	if (any_tags($tag_key))
		echo ' class="selected"';
	echo
		'>',
		ucfirst($tag_name),
		'</a></li>';
}
echo '</ul>';

$PAGE_NAV = ob_get_clean();

////////////////////////////////////////////////////////////////////////////////

ob_start();

// load all projects
$projects = array();
foreach (list_projects() as $project_name)
	$projects[] = load_project($project_name);

if (get_selected_tags())
{
	// hide projects that don't match the selected tags
	$projects = array_filter($projects,
		function($project) { return any_tags($project['tags']); });

	// format the tags for user output
	$tags_in_english = format_english_list(
		array_transform(str_split(get_selected_tags()), 'get_tag_name'),
		'or');

	// render an introductory paragraph for the project list
	?>
	<p>
		<?php
		echo
			$projects ? 'Here are all of my' : 'There are no',
			" projects that involve $tags_in_english."
		?>
	</p>
	<?php
}
else
{
	// render an introductory paragraph for the project list
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
				<a href="<?php echo join_path(SITE_ROOT_URL, 'project/?project=' . $project['name'])?>">
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

$PAGE_CONTENT = ob_get_clean();

////////////////////////////////////////////////////////////////////////////////

include join_path(SITE_ROOT_DIR, '/include/html/page.php')
?>
