<?php
// auto-load scripts
load_scripts('include/script/thirdparty', ListDirFlags::RECURSIVE);
load_scripts('include/script');

// auto-load the page-specific script, if it exists
$file = join_path(SITE_ROOT_URL, 'include/script/page', PAGE_ID . '.js');
echo "<script src=\"$file\"></script>\n";
?>
