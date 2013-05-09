<?php
/**
 * Transforms each element of an array with a function.
 */
function array_transform($array, $function)
{
	foreach ($array as &$element)
		$element = $function($element);
	return $array;
}
?>
