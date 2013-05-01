<?php
/**
 * Returns a list of projects by searching the projects directory.
 */
function list_projects()
{
	$names = array();
	$files = list_tree(PROJECTS_DIR_FROM_SITE_ROOT, '*.{html,php}', RelativeTo::SEARCH_ROOT);
	foreach ($files as $file)
		$names[] = substr($file, 0, strpos($file, '.'));
	return $names;
}

/**
 * Loads a project into an associative array.
 */
function load_project($name)
{
	// search for the project file
	$files = glob(PROJECTS_DIR . DIRECTORY_SEPARATOR . $name . '.*');
	$patterns = expand_pattern('*.{html,php}');
	foreach ($files as $file)
		if (fnmatch_any($patterns, $file))
			break;
	if (!isset($file))
		return FALSE;
	
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