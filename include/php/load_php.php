<?php
include_once 'file.php'; // list_dir
include_once 'path.php'; // join_path

/**
 * Includes an external PHP file.
 */
function load_php_file($file)
{
	include_once join_path(SITE_ROOT_DIR, $file);
}

/**
 * Includes an external PHP file, if it exists.
 */
function load_php_file_if_exists($file)
{
	if (is_file(join_path(SITE_ROOT_DIR, $file)))
		load_php_file($file);
}

/**
 * Includes external PHP files found by searching a path.
 *
 * @param string $path A path pointing to a directory or a wildcard pattern,
 *        relative to the site root, which is used to search for PHP files.
 * @param int $flags A combination of ListDirFlags that is passed to list_dir.
 */
function load_php_files($path, $flags=0)
{
	$wildcard_i = strcspn($path, '*?');
	if ($wildcard_i < strlen($path))
	{
		if (strpos($path, '/', $wildcard_i + 1) !== FALSE)
			die('Can\'t handle wildcards in parent components.');

		$pattern = basename($path);
		$path = dirname($path);
	}
	else $pattern = '*.php';

	foreach (list_dir($path, $pattern, ListDirPathType::RELATIVE_TO_SITE_ROOT, $flags) as $file)
		load_php_file($file);
}
?>
