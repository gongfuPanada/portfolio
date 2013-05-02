<?php
function join_path()
{
	$paths = func_get_args();

	/**
	 * Determine the best path-component separator by looking for an existing
	 * separator in the arguments.
	 */
	$separator = '/';
	foreach ($paths as $path)
	{
		$i = strcspn($path, '/' . DIRECTORY_SEPARATOR);
		if ($i < strlen($path))
			$separator = $path[$i];
	}

	// trim extraneous separators and whitespace
	$separators = '/' . DIRECTORY_SEPARATOR;
	foreach ($paths as &$path)
	{
		$path = rtrim(trim($path), $separators);
		if ($path !== $paths[0])
			$path = ltrim($path, $separators);
	}

	// combine the specified paths using the chosen separator
	return implode($separator, array_filter($paths));
}
?>
