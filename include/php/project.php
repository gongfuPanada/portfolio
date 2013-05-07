<?php
include_once 'file.php'; // list_tree
include_once 'path.php'; // join_path

/**
 * Returns a list of projects by searching the projects directory.
 */
function list_projects()
{
	$names = array();
	$files = list_tree(PROJECTS_DIR_FROM_SITE_ROOT, '*.{html,php}');
	foreach ($files as $file)
	{
		$name = substr($file, 0, strrpos($file, '.'));
		if (basename($name) == 'index')
			$name = dirname($name);
		$names[] = $name;
	}
	sort($names);
	return $names;
}

/**
 * Loads a project into an associative array.
 */
function load_project($name)
{
	// search for the project file
	$suffixes = expand_brace_pattern('{.{html,php},/index.{html,php}}');
	foreach ($suffixes as $suffix)
	{
		$file = join_path(SITE_ROOT_DIR, PROJECTS_DIR_FROM_SITE_ROOT, $name . $suffix);
		if (file_exists($file))
			goto found_project_file;
	}
	return FALSE;
	found_project_file:

	// search for the project file
	/*$files = glob(join_path(SITE_ROOT_DIR, PROJECTS_DIR_FROM_SITE_ROOT, $name . '.*'));
	$patterns = expand_brace_pattern('*{.{html,php},/index.{html,php}}');
	foreach ($files as $file)
		if (fnmatch_any($patterns, $file))
			break;
	if (!isset($file))
		return FALSE;*/

	// set global variables (conceptually constants) for the project
	$GLOBALS['PROJECT_DIR'] = join_path(SITE_ROOT_DIR, PROJECTS_DIR_FROM_SITE_ROOT, dirname($name));
	$GLOBALS['PROJECT_URL'] = join_path(SITE_ROOT_URL, PROJECTS_DIR_FROM_SITE_ROOT, dirname($name)) . '/';

	// read file (and process with PHP)
	ob_start();
	require $file;
	$content = ob_get_clean();

	// only continue if file contains header
	if (substr_compare($content, '<!--', 0, 4) !== 0)
		return FALSE;

	// separate header from content
	$header_length = strpos($content, '-->', 4);
	$header = trim(substr($content, 4, $header_length - 4));
	$content = trim(substr($content, $header_length + 3));

	// convert header to associative array
	$dict = array(
		'tags'   => '',
		'title'  => '',
		'author' => '');
	foreach (explode("\n", $header) as $line)
	{
		$pair = explode(':', $line, 2);
		if (isset($pair[1]))
			$dict[$pair[0]] = trim($pair[1]);
	}

	// separate preview from content
	$preview_length = strpos($content, CONTENT_SEPARATOR);
	if ($preview_length)
	{
		$preview = substr($content, 0, $preview_length);
		$content = substr($content, $preview_length + strlen(CONTENT_SEPARATOR));
	}
	else $preview = $content;

	// add more to the associative array
	$dict['name'] = $name;
	$dict['file'] = $file;
	$dict['header'] = $header;
	$dict['preview'] = $preview;
	$dict['content'] = $content;

	return $dict;
}
?>
