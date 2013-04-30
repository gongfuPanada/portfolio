<?php
function isabspath($path)
{
}

function array_transform($array, $function)
{
	$result = array();
	foreach ($array as $element)
		$result[] = $function($element);
	return $result;
}

function trim_path($path)
{
	return trim($s, DIRECTORY_SEPARATOR);
}

function join_path()
{
	return implode(DIRECTORY_SEPARATOR,
		array_filter(array_transform(func_get_args(), trim_path)));
}

/**
 * Expands the curly-brace sub-patterns in an extended shell-wildcard-pattern
 * into multiple fnmatch-compatible patterns.
 */
function expand_pattern($pattern)
{
	$expanded_patterns = array();
	$new_patterns = array($pattern);
	while ($new_patterns)
	{
		$old_patterns = $new_patterns;
		$new_patterns = array();
		foreach ($old_patterns as $pattern)
			if (($start = strrpos($pattern, '{')) !== FALSE &&
				($end = strpos($pattern, '}', $start + 1)) !== FALSE)
			{
				$subs = explode(',', substr($pattern, $start + 1, $end - $start - 1));
				foreach ($subs as $sub)
					$new_patterns[] = substr($pattern, 0, $start) . $sub . substr($pattern, $end + 1);
			}
			else $expanded_patterns[] = $pattern;
	}
	return $expanded_patterns;
}

/**
 * Specifies the type of a path, such as an absolute or relative filesystem
 * path, or a URL.
 */
class RelativeTo
{
	const ABSOLUTE      = 0;
	const DOCUMENT_ROOT = 1;
	const SITE_ROOT     = 2;
	const SEARCH_ROOT   = 3;
	const URL           = 4;
};

/**
 * Finds all the files in a directory tree on the server.
 *
 * @param string $root The root directory to search, relative to the site root.
 * @param string $pattern A shell wildcard pattern to match against.  Defaults
 *        to an empty string.  If the pattern is empty, all files will match.
 * @param RelativeTo $relativeto The absolute/relative context for the results.
 *
 * @remark Skips directories that start with a dot (ie: ".svn").
 */
function list_tree($root, $pattern='', $relativeto=RelativeTo::SEARCH_ROOT)
{
	// determine prefix for results
	switch ($relativeto)
	{
		case RelativeTo::ABSOLUTE:      $prefix = SITE_ROOT_DIR . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR; break;
		case RelativeTo::DOCUMENT_ROOT: $prefix = SITE_ROOT_RELATIVE_TO_DOCUMENT_ROOT . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR; break;
		case RelativeTo::SITE_ROOT:     $prefix = $root . DIRECTORY_SEPARATOR; break;
		case RelativeTo::SEARCH_ROOT:   $prefix = ''; break;
		case RelativeTo::URL:           $prefix = SITE_ROOT_URL . $root . '/'; break;
		
		default:
		// error
	}
	
	// expand extended pattern into multiple patterns
	$patterns = expand_pattern($pattern);
	
	// walk through filesystem tree, starting at root
	$results = array();
	$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(SITE_ROOT_DIR . DIRECTORY_SEPARATOR . $root));
	foreach ($iter as $file)
	{
		// convert filename to URL when requested
		if ($relativeto === RelativeTo::URL)
			$file = strtr($file, DIRECTORY_SEPARATOR, '/');
		
		// add prefix to filename
		$file = $prefix . $iter->getInnerIterator()->getSubPathname();
		
		// check filename against patterns
		$match = FALSE;
		foreach ($patterns as $pattern)
			if ($match = fnmatch($pattern, $file))
				break;
		if (!$match)
			continue;
		
		// exclude hidden directories (whose names start with a dot)
		if (preg_match('/[\/\\\]\\./', $file))
			continue;
			
		$results[] = $file;
	}
	
	sort($results);
	return $results;
}

/**
 * Converts a filesystem path to a URL.
 *
 * @param string $path The filesystem path to convert.
 *
 * If $path is absolute and a descendent of $_SERVER['DOCUMENT_ROOT'], 
 */
function path2url($path)
{
	$path = realpath($path);
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	$n = strlen($root);
	if (!strncmp($path, $root, $n))
		$path = substr($path, $n);
	return strtr($path, DIRECTORY_SEPARATOR, '/');
}
?>
