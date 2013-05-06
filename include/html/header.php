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
			<?php
			// only display the tag menu if the current page the root page
			if ($_SERVER['PHP_SELF'] === SITE_ROOT_URL . 'index.php')
			{
				?>
				<td width="100%">
					<nav id="menu">
						<ul>
							<?php
							foreach (get_all_tags() as $tag_key => $tag_name)
							{
								echo
									'<li><a onclick="add_tag(\'',
									$tag_key,
									'\')" href="',
									SITE_ROOT_URL,
									'?tags=',
									toggle_tag($tag_key),
									'"';
								if (any_tags($tag_key))
									echo ' class="selected"';
								echo
									'>',
									ucfirst($tag_name),
									'</a></li>',
									"\n";
							}
							?>
						</ul>
					</nav>
				</td>
				<?php
			}
			?>
		</tr>
	</table>
</header>
