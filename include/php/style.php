<?php
include_once 'file.php'; // list_dir
include_once 'path.php'; // join_path, path_extension

// initialize LESS compiler
$less = new lessc;
$less->setFormatter('compressed');
$less->setVariables(array('root' => '\'' . SITE_ROOT_URL . '\''));

/**
 * Produces a <link> tag for the specified stylesheet file.
 */
function load_style($file)
{
	global $less;

	if (substr($file, -5) == '.less')
	{
		// determine filenames for generated files
		$less_file = join_path(SITE_ROOT_DIR, $file);
		$cache_file = strtr($file, '/' . DIRECTORY_SEPARATOR, '__');
		$cache_file = join_path('include/cache', $cache_file);
		$css_file = substr($cache_file, 0, -4) . 'css';
		$css_url = join_path(SITE_ROOT_URL, $css_file);
		$cache_file = join_path(SITE_ROOT_DIR, $cache_file);
		$css_file = join_path(SITE_ROOT_DIR, $css_file);

		// compile LESS file into CSS file when different from cached copy
		if (file_exists($cache_file))
		{
			$cache = unserialize(file_get_contents($cache_file));
			$new_cache = $less->cachedCompile($cache);
			if ($new_cache['updated'] > $cache['updated'])
			{
				file_put_contents($cache_file, serialize($cache));
				file_put_contents($css_file, $new_cache['compiled']);
			}
		}
		else
		{
			$cache = $less->cachedCompile($less_file);
			file_put_contents($cache_file, serialize($cache));
			file_put_contents($css_file, $cache['compiled']);
		}
	}
	else $css_url = join_path(SITE_ROOT_URL, $file);

	// produce <link> tag for CSS file
	echo "<link rel=\"stylesheet\" href=\"$css_url\"/>\n";
}

/**
 * Produces <link> tags for all stylesheets in the specified directory.
 */
function load_styles($dir, $flags=0)
{
	foreach (list_dir($dir, '*.{css,less}', ListDirPathType::RELATIVE_TO_SITE_ROOT, $flags) as $file)
		load_style($file);
}
?>
