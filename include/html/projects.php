<?php
	foreach (list_tree('include/content/projects', '*.{html,php}', RelativeTo::ABSOLUTE) as $file)
	{
		ob_start();
		require $file;
		$content = ob_get_clean();
		if (substr_compare($content, '<!--', 0, 4) === 0)
		{
			// separate header from content
			$header_length = strpos($content, '-->', 4);
			$header = trim(substr($content, 4, $header_length - 4));
			$content = trim(substr($content, $header_length + 3));
			
			// convert header to associative array
			$header_dict = array(
				'tags'   => '',
				'title'  => '',
				'author' => '');
			foreach (explode("\n", $header) as $line)
			{
				$pair = explode(':', $line, 2);
				if (isset($pair[1]))
					$header_dict[$pair[0]] = trim($pair[1]);
			}
			
			// continue only if one of the tags is selected
			if (any_tags($header_dict['tags']))
			{
				?>
				<article class="project">
					<div class="project-header">
						<h1 class="project-title">
							<?php echo $header_dict['title']?>
						</h1>
						<div class="project-info">
							<table>
								<tr>
									<td>
										By <?php echo $header_dict['author']?>
									</td>
									<td>
										Tags: <?php echo $header_dict['tags']?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="project-content">
						<?php echo $content?>
					</div>
				</article>
				<?php
			}
		}
	}
?>

<script>
function resize_project_boxes()
{
	minSize = 350;
	margin = $('article.project').position().left * 2;
	bodySize = $('body').width() - margin;
	n = Math.floor(bodySize / minSize);
	$('article.project').width(bodySize / n - (margin + n + 3));
}
$(window).resize(resize_project_boxes);
resize_project_boxes();
</script>