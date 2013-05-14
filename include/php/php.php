<?php
/**
 * Core PHP enhancements.
 *
 * @note This file will be included in "config.php" before any other PHP files.
 */

function debug(&$var)
{
	ob_end_clean();
	var_dump($var);
	die();
}

function include_if_exists($file)
{
	if (file_exists($file))
		include $file;
}

function isset_or(&$var, $default=NULL)
{
	return isset($var) ? $var : $default;
}

function not_empty_or($var, $default=NULL)
{
	return !empty($var) ? $var : $default;
}

function not_false_or($var, $default=NULL)
{
	return $var !== FALSE ? $var : $default;
}
?>
