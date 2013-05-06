<?php
/**
 * @return An associative array of known tags, where the key is a single
 * character representing the tag, and the value is the name of the tag.
 */
function get_all_tags()
{
	// FIXME: this should not be hard-coded
	return array(
		'p' => 'programming',
		'g' => 'game development',
		'c' => 'content creation',
		'a' => 'art',
		'b' => 'testing',
		'e' => 'another tag');
}

/**
 * @return TRUE if there is a tag with the specified key.
 */
function is_tag($key)
{
	$tags = get_all_tags();
	return isset($tags[$key]);
}

/**
 * @return The name of the tag with the specified key.
 */
function get_tag_name($key)
{
	$tags = get_all_tags();
	return $tags[$key];
}

////////////////////////////////////////////////////////////////////////////////

/**
 * @return A string containing the keys of the currently selected tags.
 */
function get_selected_tags()
{
	static $selected_tags;
	if (!isset($selected_tags))
		$selected_tags = isset($_GET['tags']) ?
			implode(array_filter(str_split($_GET['tags']), 'is_tag')) :
			'';
	return $selected_tags;
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Toggles one tag in an array of tags.
 *
 * @param[in] tag The tag to search for.
 * @param[in] tags A string containing the original tag set, defaulting to
 *            the currently selected tags.
 *
 * @return A new array containing (or not containing) tag.
 *
 * @note The tags are represented by their keys, as defined in
 *       get_recognized_tags().
 */
function toggle_tag($tag, $tags=NULL)
{
	if ($tags === NULL)
		$tags = get_selected_tags();

	$tags = array_toggle(str_split($tags), $tag);
	sort($tags); // FIXME: array_unique should implicitly sort the array
	$tags = array_unique($tags, SORT_STRING);
	return implode($tags);
}

/**
 * @return TRUE if any of the needles is in the haystack.
 */
function any_tags($needles, $haystack=NULL)
{
	if ($haystack === NULL)
		$haystack = get_selected_tags();

	return strcspn($haystack, $needles) < strlen($haystack);
}
?>
