<meta http-equiv="Content-Script-Type" content="text/javascript"/>

<!-- LESS configuration -->
<script>
	less =
	{
		env: 'development', // 'production'
		rootpath: <?php echo SITE_ROOT_URL?>
	};
</script>

<!-- auto-load scripts -->
<?php load_scripts()?>
<?php //load_scripts('_thirdparty/.ajax')?>

<!-- SEO by Amortech -->
<script>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-29436860-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();
</script>
