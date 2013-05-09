<?php
include_once 'file.php'; // list_dir

/**
 * Produces <script> tags for scripts found in the specified directory tree.
 */
function load_scripts($dir, $flags=0)
{
	foreach (list_dir($dir, '*.js', ListDirPathType::URL, $flags) as $file)
		echo "<script src=\"$file\"></script>\n";
}
?>
