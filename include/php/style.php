<?php
include_once 'file.php'; // list_tree
include_once 'path.php'; // join_path

/**
 * Produces <link> tags for stylesheets in the specified directory tree.
 */
function load_styles($dir)
{
	// compile LESS files and include the generated CSS files
	$less = new lessc;
	foreach (list_tree($dir, '*.less', ListTreePathType::RELATIVE_TO_SITE_ROOT) as $less_file)
	{
		// determine filename for generated CSS file
		$css_file = substr($less_file, 0, strlen($less_file) - 4) . 'css';
		$css_file = strtr($css_file, '/' . DIRECTORY_SEPARATOR, '__');

		// compile LESS file into CSS file
		$less->checkedCompile(
			join_path(SITE_ROOT_DIR, $less_file),
			join_path(SITE_ROOT_DIR, 'include/cache', $css_file));

		// include generated CSS file
		echo '<link rel="stylesheet" href="', join_path(SITE_ROOT_URL, 'include/cache', $css_file), '"/>', "\n";
	}

	// include static CSS files
	foreach (list_tree($dir, '*.css', ListTreePathType::URL) as $file)
		echo '<link rel="stylesheet" href="', $file, '"/>', "\n";
}
?>
