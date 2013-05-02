<header>
	<table>
		<tr>
			<td>
				<a href="<?php echo SITE_ROOT_URL?>">
					<h1>
						<?php echo SITE_TITLE?>
					</h1>
				</a>
			</td>
			<td width="100%">
				<nav id="menu">
					<ul>
						<?php
							foreach (get_all_tags() as $tag_key => $tag_name)
							{
								echo '<li><a onclick="add_tag(\'', $tag_key, '\')" href="?tags=', toggle_tag($tag_key), '"';
								if (tag_in_array($tag_key))
									echo ' class="selected"';
								echo '>', $tag_name, '</a></li>', "\n";
							}
						?>
					</ul>
				</nav>
			</td>
		</tr>
	</table>
</header>
