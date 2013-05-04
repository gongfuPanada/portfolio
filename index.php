<?php require 'include/php/config.php'?>

<?php include SITE_ROOT_DIR . '/include/html/basic-prefix.php'?>

<div id="project-list">
	<?php
		foreach (list_projects() as $project_name)
		{
			$project = load_project($project_name);
			
			// only continue if one of the tags is currently selected
			if (any_tags($project['tags']))
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
		}
	?>
</div>

<script>
function optimize_article_size()
{
	var margin = $('article').position().left * 2;
	var bodySize = $('body').width() - margin;
	var n = Math.floor(bodySize / MIN_BOX_SIZE);
	$('article').width(bodySize / n - (margin + n + 3));
}
$(window).bind('resize', optimize_article_size);
optimize_article_size();
</script>

<?php include SITE_ROOT_DIR . '/include/html/basic-suffix.php'?>
