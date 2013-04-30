<?php
/**
 * @return An associative array of recognized tags, where the key is a single
 * character representing the tag, and the value is the name of the tag.
 */
function get_all_tags()
{
	return array(
	'p' => 'Programming',
	'g' => 'Game development',
	'c' => 'Content creation',
	'a' => 'Art');
}

/**
 * @return An array of the currently requested tags.
 *
 * @note The tags are represented by their keys, as defined in
 *       get_recognized_tags().
 */
function get_requested_tags()
{
	$tags = $_GET['tags'];
	return $tags ? $tags : "";
}

/**
 * Toggles one tag in an array of tags.
 *
 * @param[in] tag The tag to search for.
 * @param[in] tags The array of tags to search through, defaulting to the
 *            currently requested tags.
 *
 * @return A new array containing (or not containing) tag.
 *
 * @note The tags are represented by their keys, as defined in
 *       get_recognized_tags().
 */
function toggle_tag($tag, $tags=NULL)
{
	if ($tags === NULL)
		$tags = get_requested_tags();
	
	$tags = str_split($tags);
	$i = array_search($tag, $tags, TRUE);
	if ($i !== FALSE) unset($tags[$i]);
	else $tags[] = $tag;
	sort($tags);
	$tags = array_unique($tags, SORT_STRING);
	return implode($tags);
}

/**
 * Checks whether a tag is contained in an array.
 *
 * @param[in] tag The tag to search for.
 * @param[in] tags The array of tags to search through, defaulting to the
 *            currently requested tags.
 *
 * @return TRUE if tag is in tags.
 *
 * @note The tags are represented by their keys, as defined in
 *       get_recognized_tags().
 */
function tag_in_array($tag, $tags=NULL)
{
	if ($tags === NULL)
		$tags = get_requested_tags();
	
	$tags = str_split($tags);
	return in_array($tag, $tags, TRUE);
}

/**
 * Retrieves the name of a tag using get_recognized_tags().
 *
 * @return The name of the specified tag.
 */
function get_tag_name($tag)
{
	return get_all_tags()[$tag];
}

/**
 * @return TRUE if any of the needles is in the haystack.
 */
function any_tags($needles, $haystack=NULL)
{
	if ($haystack === NULL)
		$haystack = get_requested_tags();
	
	$s = strtr($haystack, $needles, str_pad('', strlen($needles), '$'));
	return strpos($s, '$') !== FALSE;
}
?>