<?php
include_once 'array.php'; // array_transform
include_once 'path.php'; // join_path
include_once 'string.php'; // format_english_list
include_once 'tags.php'; // get_{selected_tags,tag_name}

/**
 * Generates the HTML for the description of a project list.
 *
 * @param string $tags A string of the tag keys associated with the listed
 *        projects.  Defaults to the currently-selected tags.
 */
function generate_project_list_description($tags=NULL)
{
	if (!isset($tags))
		$tags = get_selected_tags();

	if ($tags)
	{
		// format the tags for user output
		$tags_in_english = format_english_list(
			array_transform(str_split($tags), 'get_tag_name'),
			'or');

		// write HTML description
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
		// write HTML description
		?>
		<p>
			Here are all of my projects.  To fine tune this list, select one or
			more tags from the menu above.  Only those projects that match at
			least one of the selected tags will be displayed.
		</p>
		<?php
	}
}

/**
 * Generates the HTML for the preview box of a project.
 *
 * @param array|string $project The name of a project, or the associative array
 *        resulting from loading the project.
 */
function generate_project_preview($project)
{
	if (is_string($project))
		$project = load_project($project);

	?><article id="project-preview-<?php echo strtr($project['name'], '/', '-')?>">
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
?>
