<?php
load_styles('include/style/thirdparty', ListDirFlags::RECURSIVE);
load_styles('include/style');
load_style_if_exists('include/style/page/' . PAGE_ID . '.css');
load_style_if_exists('include/style/page/' . PAGE_ID . '.less');
?>
