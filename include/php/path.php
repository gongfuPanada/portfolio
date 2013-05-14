<?php
/**
 * Builds a path by joining the arguments as sub-paths.
 *
 * @note The leading path-separator of the first component and the trailing
 *       path-separator of the last component, if any, are retained.
 */
function join_path()
{
	$paths = func_get_args();
	if (empty($paths)) return '';
	if (count($paths) === 1) return $paths[0];

	// trim extraneous path-separators
	$separators = '/' . DIRECTORY_SEPARATOR;
	$paths[0] = rtrim($paths[0], $separators);
	$paths[count($paths) - 1] = ltrim($paths[count($paths) - 1], $separators);
	for ($i = 1; $i < count($paths) - 1; ++$i)
		$paths[$i] = trim($paths[$i], $separators);

	return implode('/', array_filter($paths));
}

/**
 * Returns the full extension of the file at the specified path.
 */
function path_extension($path)
{
	return strtolower(substr($path, strrpos($path, '.') + 1));
}

/**
 * Returns a path with the last component stripped of its full extension.
 */
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

/**
 * Factors out the extraneous components of a path, including empty components
 * and current- and parent-directory references.  Leading and trailing path-
 * separators are retained.
 *
 * @fixme This is very ugly, and it might not handle all the special cases
 *        correctly.
 */
function reduce_path($path)
{
	$i = 0 + ($path[0] === '/');
	$n = strlen($path);
	$at_end = FALSE;
	$remove_trailing_separator = FALSE;
	for (;;)
	{
		$j = strpos($path, '/', $i);
		if ($j === FALSE)
		{
			$at_end = TRUE;
			$remove_trailing_separator = TRUE;
			$j = $n;
		}
		$component = substr($path, $i, $j - $i);
		$at_end = $j >= $n - 1;
		switch ($component)
		{
			case '':
			$m = strspn($path, '/', $i + 1) + 1;
			$path = substr_replace($path, '', $i, $m);
			$n -= $m;
			break;

			case '.':
			$m = $j - $i + !$at_end;
			$path = substr_replace($path, '', $i - $remove_trailing_separator, $m + $remove_trailing_separator);
			$n -= $m;
			break;

			case '..':
			if ($i >= 2)
			{
				$last_i = strrpos($path, '/', -($n - $i + 2));
				if ($last_i === FALSE) $last_i = 0;
				else ++$last_i;
				$last_component = substr($path, $last_i, $i - 1 - $last_i);
				if ($last_component !== '..' && $last_component !== '.')
				{
					$m = $j - $last_i + !$at_end;
					$path = substr_replace($path, '', $last_i - $remove_trailing_separator, $m + $remove_trailing_separator);
					$n -= $m;
					$i = $last_i;
					break;
				}
			}

			default:
			$i = $j + 1;
		}
		if ($at_end) break;
	}
	return $path;
}

/**
 * Normalizes a path by converting the path separators to the POSIX style,
 * combining adjacent path-separators, and factoring out any extraneous path
 * components.
 */
function normalize_path($path)
{
	if (!$path) return '';
	// FIXME: both the regex and reduce_path are handling adjacent separators
	return reduce_path(preg_replace('/[\\/\\\\]+/', '/', $path));
}

/**
 * Converts an absolute path to a relative path, using another path as its base.
 * If the path is not entirely under its base, up-directory components will be
 * added as necessary.
 *
 * @note This function assumes that both the path and the base are absolute,
 *       whether they have a leading path-separator or not.  Leading path-
 *       separators are implicit and ignored, and the resulting path will not
 *       have a leading path-separator.
 *
 * @note Trailing path-separators are retained.
 */
function path_relative_to($path, $base)
{
	// normalize the paths
	$path = normalize_path($path);
	$base = normalize_path($base);

	// remove leading path-separators
	if (!empty($path) && $path[0] === '/')
		$path = not_false_or(substr($path, 1), '');
	if (!empty($base))
	{
		if ($base[0] === '/')
			$base = not_false_or(substr($base, 1), '');

		// remove trailing path-separator from base
		if (!empty($base))
		{
			if (substr($base, -1) === '/')
				$base = substr($base, 0, strlen($base) - 1);

			// optimization
			goto base_not_empty;
		}
		// optimization
		else goto base_empty;
	}

	// if the base is empty, the path can be returned as is
	if (empty($base))
	{
		base_empty:
		return $path;
	}
	base_not_empty:

	// if the path is empty, the result is a move up from the base to the root
	if (empty($path))
		return '..' . str_repeat('/..', substr_count($base, '/'));

	/**
	 * Find first mismatching character.
	 * Based on the algorithm from <http://stackoverflow.com/a/7475502>.
	 */
	$i = strspn($path ^ $base, "\0");

	/**
	 * If the path does not match the base at all, the result is a move up from
	 * the base to the root, followed by the path.
	 */
	if (!$i)
		return str_repeat('../', substr_count($base, '/') + 1) . $path;

	/**
	 * If the path is under the base, we don't have to do any backtracking and
	 * the base can be factored out completely.  Check if the path is an initial
	 * substring of the base, which would indicate that the path is probably
	 * under the base.
	 */
	if ($i === strlen($base))
	{
		/**
		 * If the path is the same as the base, except for a possible trailing
		 * path-separator, the result is an empty path.
		 */
		if ($i === strlen($path) ||
			$i === strlen($path) - 1 && substr($path, -1) === '/')
				return '';

		/**
		 * If the path is entirely under the base, the result is the remainder
		 * of the path minus the base.
		 */
		if ($path[$i] === '/')
			return substr($path, $i + 1);

		/**
		 * The final component of the base is only a substring of the respective
		 * component of the path, so the result is the remainder of the path
		 * after backtracking one level and subtracting the base at that point.
		 */
		return '../' . substr($path,
			not_false_or(strrpos($path, '/', $i - 1 - strlen($path)), 0));
	}

	/**
	 * The path is more than one level away from being under the base, so the
	 * result is the remainder of the path after backtracking through the
	 * mismatching levels and subtracting the base at that point.
	 */
	$i = not_false_or(strrpos($base, '/', $i - 1 - strlen($base)), -1) + 1;
	return '..' . str_repeat('/..', substr_count($base, '/', $i)) . '/' . substr($path, $i);
}

////////////////////////////////////////////////////////////////////////////////
// tests

assert(reduce_path('//../aaa/bbb/../../ccc/./../ddd///.///eee//') === '/../ddd/eee/');
assert(reduce_path('a/b/../../c/./../d///.///.') === 'd');

assert(path_relative_to('/', '/') === '');
assert(path_relative_to('/', '') === '');
assert(path_relative_to('', '/') === '');
assert(path_relative_to('//', '//') === '');
assert(path_relative_to('a/b', '') === 'a/b');
assert(path_relative_to('', 'a/b') === '../..');
assert(path_relative_to('a/b', 'a/b') === '');
assert(path_relative_to('a/b/', 'a/b') === '');
assert(path_relative_to('a/b', 'a') === 'b');
assert(path_relative_to('a/b', '/a') === 'b');
assert(path_relative_to('a/b', 'a/') === 'b');
assert(path_relative_to('/a/b', 'a') === 'b');
assert(path_relative_to('a/b/', 'a') === 'b/');
assert(path_relative_to('a/b/c', 'a/d') === '../b/c');
assert(path_relative_to('a/b/c', 'd/e') === '../../a/b/c');
?>
