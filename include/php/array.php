<?php
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
