<?php
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
?>
