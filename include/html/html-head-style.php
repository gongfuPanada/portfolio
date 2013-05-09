<?php
// auto-load styles
load_styles('include/style/thirdparty', ListDirFlags::RECURSIVE);
load_styles('include/style');

// auto-load the page-specific style, if it exists
$file = join_path('include/style/page', PAGE_ID . '.less');
load_style($file);
?>
