<ul>
	<?php
	foreach (get_all_tags() as $tag_key => $tag_name)
	{
		echo
			'<li><a href="',
			SITE_ROOT_URL,
			'?tags=',
			toggle_tags($tag_key),
			'"';
		if (any_tags($tag_key))
			echo ' class="selected"';
		echo
			'>',
			ucfirst($tag_name),
			'</a></li>';
	}
	?>
</ul>
