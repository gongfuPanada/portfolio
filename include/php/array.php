<?php
/**
 * Toggles one element in an array.
 */
function array_toggle($array, $value)
{
	$i = array_search($value, $array, TRUE);
	if ($i !== FALSE) unset($array[$i]);
	else $array[] = $value;
	return $array;
}

/**
 * Transforms each element of an array with a function.
 */
function array_transform($array, $function)
{
  $result = array();
  foreach ($array as $element)
    $result[] = $function($element);
  return $result;
}
?>
