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
	$less->setFormatter('compressed');
	$less->setVariables(array('root' => '"' . SITE_ROOT_URL . '"'));
	foreach (list_tree($dir, '*.less', ListTreePathType::RELATIVE_TO_SITE_ROOT) as $less_file)
	{
		// determine filename for generated files
		$cache_file = strtr($less_file, '/' . DIRECTORY_SEPARATOR, '__');
		$cache_file = join_path('include/cache', $cache_file);
		$css_file = substr($cache_file, 0, strlen($cache_file) - 4) . 'css';
		$css_url = join_path(SITE_ROOT_URL, $css_file);
		$cache_file = join_path(SITE_ROOT_DIR, $cache_file);
		$css_file = join_path(SITE_ROOT_DIR, $css_file);

		// compile LESS file into CSS file when different from cached copy
		if (file_exists($cache_file))
		{
			$cache = unserialize(file_get_contents($cache_file));
			$new_cache = $less->cachedCompile($cache);
			if ($new_cache['updated'] > $cache['updated'])
				file_put_contents($cache_file, serialize($cache));
		}
		else
		{
			$cache = $less->cachedCompile($less_file);
			file_put_contents($cache_file, serialize($cache));
		}

		// include generated CSS file
		echo "<link rel=\"stylesheet\" href=\"$css_url\"/>\n";
	}

	// include static CSS files
	foreach (list_tree($dir, '*.css', ListTreePathType::URL) as $file)
		echo '<link rel="stylesheet" href="', $file, '"/>', "\n";
}
?>
