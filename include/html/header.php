<header>
	<table>
		<tr>
			<td>
				<h1>
					<?php echo SITE_TITLE?>
				</h1>
			</td>
			<td width="100%">
				<nav id="menu">
					<ul>
						<?php
							foreach (get_all_tags() as $tag_key => $tag_name)
								echo
									'<li><a onclick="add_tag(\'',
									$tag_key,
									'\')" href="?tags=',
									toggle_tag($tag_key),
									'" class="',
									(tag_in_array($tag_key) ? "enabled" : "disabled"),
									'" rel="prefetch">',
									$tag_name,
									'</a></li>',"\n"
						?>
					</ul>
				</nav>
			</td>
		</tr>
	</table>
</header>
