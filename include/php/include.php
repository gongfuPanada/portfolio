<?php
/**
 * Produces <script> tags for scripts found in the specified directory.
 */
function load_scripts($dir='')
{
	if ($dir) $dir = '/' . $dir;
	foreach (list_tree('include/script' . $dir, '*.js', RelativeTo::URL) as $file)
		echo '<script src="', $file, '"></script>', "\n";
}

/**
 * Produces <link> tags for stylesheets found in the specified directory.
 */
function load_styles($dir='')
{
	if ($dir) $dir = '/' . $dir;
	foreach (list_tree('include/style' . $dir, '*.{css,less}', RelativeTo::URL) as $file)
		echo '<link rel="stylesheet/less" href="', $file, '"/>', "\n";
}
?>
