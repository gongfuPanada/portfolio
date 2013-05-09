<?php include 'xhtml-header.php'?>
	<?php include 'html-head.php'?>
	<body id="<?php echo PAGE_ID?>">
		<?php include 'page-header.php'?>
		<div id="content">
			<?php echo isset_or($PAGE_CONTENT)?>
		</div>
		<?php include 'page-footer.php'?>

		<!-- best practice to include scripts at end of body -->
		<?php include 'html-head-script.php'?>
	</body>
</html>
