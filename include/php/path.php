<?php
/**
 * Builds a path by joining the arguments as sub-paths.
 *
 * @note Only the leading directory-separator of the first component is
 *       permitted to remain; all other extraneous separators are removed,
 *       including the trailing separator of the last component.
 */
function join_path()
{
	$paths = func_get_args();

	// trim extraneous separators
	$separators = '/' . DIRECTORY_SEPARATOR;
	$paths[0] = rtrim($paths[0], $separators);
	foreach ($paths as &$path)
		$path = $path == $paths[0] ?
			rtrim($path, $separators) :
			 trim($path, $separators);

	return implode('/', array_filter($paths));
}

/**
 * @return The filename extension of the specified path.
 */
function path_extension($path)
{
	return strtolower(substr($path, strrpos($path, '.') + 1));
}

function path_without_extension($path)
{
	$i = max(
		strrpos($path, '/'),
		strrpos($path, DIRECTORY_SEPARATOR));
	$i = strpos($path, '.', $i);
	if ($i !== FALSE)
		$path = substr($path, 0, $i);
	return $path;
}

function normalize_path($path)
{
	if (!$path) return '';
	$path = strtr($path, DIRECTORY_SEPARATOR, '/');
	$abs = $path[0] == '/';
	$path = explode('/', $path);
	$path = array_filter($path, function($x) { return $x && $x != '.'; });

	// factor out parent-directory components
	for ($i = 1; $i < count($path); ++$i)
		if ($path[$i] == '..' && $path[$i - 1] != '..')
		{
			array_splice($path, $i - 1, 2);
			$i = max($i - 2, 0);
		}

	$path = implode('/', $path);
	if ($abs) $path = '/' . $path;
	return $path;
}

function path_relative_to($path, $base)
{
	$path = normalize_path($path);
	$base = normalize_path($base);

	if (!$path || !$base) return $path;

	// find first mismatching character
	// http://stackoverflow.com/a/7475502
	$i = strspn($path ^ $base, "\0");

	if ($i == strlen($base))
	{
		if ($i == strlen($path)) return '';
		if ($path[$i] == '/') return substr($path, $i + 1);

		$i = strrpos($path, '/', $i - strlen($path));
		return '../' . substr($path, $i ? $i + 1 : 0);
	}

	$i = strrpos($path, '/', $i - strlen($path));
	return str_repeat(substr_count($base, '/', $i) + 1, '../') . substr($path, $i ? $i + 1 : 0);
}
?>
