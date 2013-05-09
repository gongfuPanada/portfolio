/**
 * Size the project-preview boxes to produce the best layout for the width
 * of the window.
 */
function optimize_article_size()
{
	minSize = 350;
	margin = $('article').position().left * 2;
	bodySize = $('body').width() - margin;
	n = Math.floor(bodySize / minSize);
	$('article').width(bodySize / n - (margin + n + 3));
}
$(window).bind('resize', optimize_article_size);
optimize_article_size();

/**
 * Rewrite the links in the tag menu to update the project list using Ajax.
 */
$(document).ready(function()
{
	a = $('nav#menu a');
	a.click(function()
	{
		$('#project-list').load('/portfolio/');
	});
	a.removeAttr('href');
});
