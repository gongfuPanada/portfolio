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
				<nav>
					<?php include_if_exists(join_path(
						SITE_ROOT_DIR, 'include/html/page', PAGE_ID, 'nav.php'))?>
				</nav>
			</td>
		</tr>
	</table>
</header>
