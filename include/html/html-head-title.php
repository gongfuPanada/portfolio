<title>
<?php
	function get_title()
	{
		$replacements = array(
			'crew'       => 'Meet the Crew',
			'equipment'  => 'Our Equipment',
			'contact'    => 'Get in Touch',
			'work-order' => 'Work Order');

		$parts = explode('/', substr($_SERVER['REQUEST_URI'], 1), -1);
		foreach ($parts as &$part)
			$part = array_key_exists($part, $replacements) ? $replacements[$part] : ucfirst($part);
		array_unshift($parts, SITE_TITLE);
		return implode(': ', $parts);
	}
	echo get_title();
?>
</title>
