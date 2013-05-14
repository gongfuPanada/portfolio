<!-- make constants available to scripts -->
<script>var MIN_BOX_SIZE = <?php echo MIN_BOX_SIZE?>;</script>

<?php
load_scripts('include/script/thirdparty', ListDirFlags::RECURSIVE);
load_scripts('include/script');
load_script_if_exists('include/script/page/' . PAGE_ID . '.js');
?>
