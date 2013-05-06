<?php
/**
 * Joins an array of words into a string using english grammar.
 */
function format_english_list($pieces, $conjunction='and', $serial_comma=true)
{
	if (!$pieces)
		return '';

	if (count($pieces) == 1)
		return $pieces[0];

	$last_piece = array_pop($pieces);
	$csv = implode($pieces, ', ');
	if (count($pieces) >= 2) $csv .= ', ';
	return implode(' ', array($csv, $conjunction, $last_piece));
}
?>
