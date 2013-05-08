<?php
function debug(&$var)
{
	ob_end_clean();
	var_dump($var);
	die();
}

function isset_or(&$var, $default=NULL)
{
	return isset($var) ? $var : $default;
}
?>
