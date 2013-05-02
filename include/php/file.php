<?php
include_once 'path.php'; // join_path

/**
 * Expands the curly-brace sub-patterns in an extended shell-wildcard-pattern
 * into multiple fnmatch-compatible patterns.
 */
function expand_brace_pattern($pattern)
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
 * Returns TRUE if fnmatch() matches one of an array of patterns.
 */
function fnmatch_any($patterns, $string, $flags=0)
{
	foreach ($patterns as $pattern)
		if (fnmatch($pattern, $string, $flags))
			return TRUE;
	return FALSE;
}

////////////////////////////////////////////////////////////////////////////////

/**
 * An enumeration of the path types that can be returned by list_tree().
 */
class ListTreePathType
{
	/**
	 * The path is relative to the search directory.  This is the default.
	 */
	const RELATIVE_TO_SEARCH_ROOT = 0;

	/**
	 * The path is absolute.
	 *
	 * Implies ListTreeFlags::DIRECTORY_SEPARATOR.
	 */
	const ABSOLUTE = 1;

	/**
	 * The path is relative to the document root.
	 */
	const RELATIVE_TO_DOCUMENT_ROOT = 2;

	/**
	 * The path is relative to the site root.
	 */
	const RELATIVE_TO_SITE_ROOT = 3;

	/**
	 * The path is an absolute-path-reference URL.
	 *
	 * Disables ListTreeFlags::DIRECTORY_SEPARATOR.
	 */
	const URL = 4;
};

/**
 * Flags affecting the behaviour of list_tree().
 */
class ListTreeFlags
{
	/**
	 * Uses the directory separator of the host's filesystem to separate path
	 * components.  Otherwise, the POSIX separator ('/') is used.
	 */
	const DIRECTORY_SEPARATOR = 0x01;

	/**
	 * Causes files/directories that start with a dot to be ignored.
	 */
	const SKIP_DOT = 0x02;
};

/**
 * Finds all the files in a directory tree on the server.
 */
function list_tree($root, $pattern='', $pathType=0, $flags=0)
{
	// implied behaviour
	switch ($pathType)
	{
		case ListTreePathType::ABSOLUTE:
		$flags |= ListTreeFlags::DIRECTORY_SEPARATOR;
		break;

		case ListTreePathType::URL:
		$flags &= ~ListTreeFlags::DIRECTORY_SEPARATOR;
		break;
	}

	// initialize path-component separator
	$separator = ($flags & ListTreeFlags::DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '/';

	// initialize path prefix for results
	switch ($pathType)
	{
		case ListTreePathType::RELATIVE_TO_SEARCH_ROOT:   $prefix = ''; break;
		case ListTreePathType::ABSOLUTE:                  $prefix = join_path(SITE_ROOT_DIR, $root) . $separator; break;
		case ListTreePathType::RELATIVE_TO_DOCUMENT_ROOT: $prefix = join_path(SITE_ROOT_DIR_FROM_DOCUMENT_ROOT, $root) . $separator; break;
		case ListTreePathType::RELATIVE_TO_SITE_ROOT:     $prefix = $root . $separator; break;
		case ListTreePathType::URL:                       $prefix = SITE_ROOT_URL . $root . '/'; break;
		default: trigger_error('invalid path type');
	}

	// expand brace pattern into multiple patterns
	$patterns = expand_brace_pattern($pattern);

	// walk through filesystem tree, starting at root
	$results = array();
	$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(join_path(SITE_ROOT_DIR, $root)));
	foreach ($iter as $file)
	{
		// convert filename to URL when requested
		if (!($flags & ListTreeFlags::DIRECTORY_SEPARATOR))
			$file = strtr($file, DIRECTORY_SEPARATOR, '/');

		// add prefix to filename
		$file = $prefix . $iter->getInnerIterator()->getSubPathname();

		// check filename against patterns
		if (!fnmatch_any($patterns, $file))
			continue;

		// optionally exclude paths having a dot prefix
		if (($flags & ListTreeFlags::SKIP_DOT) &&
			preg_match('/[\/\\\]\\./', $file))
				continue;

		$results[] = $file;
	}

	sort($results);
	return $results;
}
?>
