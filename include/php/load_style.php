<?php
include_once 'file.php'; // list_dir
include_once 'path.php'; // join_path

/**
 * @return A single, shared instance of the LESS compiler.
 */
function _lessc()
{
	static $lessc = NULL;
	if (!isset($lessc))
	{
		$lessc = new lessc();
		$lessc->setFormatter('compressed');
		$lessc->setVariables(array('root' => '\'' . SITE_ROOT_URL . '\''));
	}
	return $lessc;
}

/**
 * Produces a <link> tag for an external stylesheet.
 */
function load_style($file)
{
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
			$new_cache = _lessc()->cachedCompile($cache);
			if ($new_cache['updated'] > $cache['updated'])
			{
				file_put_contents($cache_file, serialize($cache));
				file_put_contents($css_file, $new_cache['compiled']);
			}
		}
		else
		{
			$cache = _lessc()->cachedCompile($less_file);
			file_put_contents($cache_file, serialize($cache));
			file_put_contents($css_file, $cache['compiled']);
		}
	}
	else $css_url = join_path(SITE_ROOT_URL, $file);

	// produce <link> tag for CSS file
	echo "<link rel=\"stylesheet\" href=\"$css_url\"/>\n";
}

/**
 * Produces a <link> tag for an external stylesheet, if it exists.
 */
function load_style_if_exists($file)
{
	if (is_file(join_path(SITE_ROOT_DIR, $file)))
		load_style($file);
}

/**
 * Produces <link> tags for external stylesheets found by searching a path.
 *
 * @param string $path A path pointing to a directory or a wildcard pattern,
 *        relative to the site root, which is used to search for stylesheets.
 * @param int $flags A combination of ListDirFlags that is passed to list_dir.
 */
function load_styles($path, $flags=0)
{
	$wildcard_i = strcspn($path, '*?');
	if ($wildcard_i < strlen($path))
	{
		if (strpos($path, '/', $wildcard_i + 1) !== FALSE)
			die('Can\'t handle wildcards in parent components.');

		$pattern = basename($path);
		$path = dirname($path);
	}
	else $pattern = '*.{css,less}';

	foreach (list_dir($path, $pattern, ListDirPathType::RELATIVE_TO_SITE_ROOT, $flags) as $file)
		load_style($file);
}
?>
