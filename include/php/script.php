<?php
include_once 'file.php'; // list_tree

/**
 * Produces <script> tags for scripts found in the specified directory tree.
 */
function load_scripts($dir)
{
	foreach (list_tree($dir, '*.js', ListTreePathType::URL) as $file)
		echo '<script src="', $file, '"></script>', "\n";
}
?>
