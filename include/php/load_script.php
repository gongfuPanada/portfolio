<?php
include_once 'file.php'; // list_dir
include_once 'path.php'; // join_path

/**
 * Produces a <script> tag for an external script.
 */
function load_script($file)
{
	$file = join_path(SITE_ROOT_URL, $file);
	echo "<script src=\"$file\"></script>\n";
}

/**
 * Produces a <script> tag for an external script, if it exists.
 */
function load_script_if_exists($file)
{
	if (is_file(join_path(SITE_ROOT_DIR, $file)))
		load_script($file);
}

/**
 * Produces <script> tags for external scripts found by searching a path.
 *
 * @param string $path A path pointing to a directory or a wildcard pattern,
 *        relative to the site root, which is used to search for scripts.
 * @param int $flags A combination of ListDirFlags that is passed to list_dir.
 */
function load_scripts($path, $flags=0)
{
	$wildcard_i = strcspn($path, '*?');
	if ($wildcard_i < strlen($path))
	{
		if (strpos($path, '/', $wildcard_i + 1) !== FALSE)
			die('Can\'t handle wildcards in parent components.');

		$pattern = basename($path);
		$path = dirname($path);
	}
	else $pattern = '*.js';

	foreach (list_dir($path, $pattern, ListDirPathType::RELATIVE_TO_SITE_ROOT, $flags) as $file)
		load_script($file);
}
?>
