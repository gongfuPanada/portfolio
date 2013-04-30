<?php
	/**
	 * Produces <script> tags for scripts found in the specified directory.
	 */
	function load_scripts($dir)
	{
		foreach (list_tree($dir, '*.js', RelativeTo::URL) as $file)
			echo '<script src="', $file, '"></script>', "\n";
	}
	
	/**
	 * Produces <link> tags for stylesheets found in the specified directory.
	 */
	function load_styles($dir)
	{
		foreach (list_tree($dir, '*.{css,less}', RelativeTo::URL) as $file)
			echo '<link rel="stylesheet/less" href="', $file, '"/>', "\n";
	}
?>
