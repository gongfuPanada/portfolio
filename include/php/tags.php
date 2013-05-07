<?php
include_once 'array.php'; // array_transform

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
 * Toggles one set of tag keys in another set of tag keys.
 */
function toggle_tag($toggle_tags, $subject_tags=NULL)
{
	if ($subject_tags === NULL)
		$subject_tags = get_selected_tags();

	$zeros = str_repeat("\0", max(strlen($toggle_tags), strlen($subject_tags)));
	$new_tags = str_replace(' ', '', strtr($toggle_tags, $subject_tags, $zeros));
	$subject_tags = str_replace(' ', '', strtr($subject_tags, $toggle_tags, $zeros));
	$subject_tags = str_split($subject_tags .= $new_tags);
	sort($subject_tags);
	return implode($subject_tags);
}

/**
 * @return TRUE if there is any intersection between two sets of tag keys.
 */
function any_tags($tags1, $tags2=NULL)
{
	if ($tags2 === NULL)
		$tags2 = get_selected_tags();

	return strcspn($tags1, $tags2) < strlen($tags1);
}
?>
