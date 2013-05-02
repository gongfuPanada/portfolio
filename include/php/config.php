<?php
/**
 * A relative path specifying the root of the site on the server's filesystem,
 * relative to the document root.
 *
 * @note You should update this constant when you install the website on a new
 *       server.
 */
define('SITE_ROOT_DIR_FROM_DOCUMENT_ROOT', 'portfolio');

/**
 * An absolute path specifying the root of the site on the server's filesystem.
 */
define('SITE_ROOT_DIR', realpath($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . SITE_ROOT_DIR_FROM_DOCUMENT_ROOT));

/**
 * A relative URL (that is, an absolute-path reference) specifying the root of
 * the site.  It is relative to the document root of the webserver.  Because it
 * is an absolute-path reference, it must start with a single '/' character.  It
 * must also end with a '/' character.
 */
define('SITE_ROOT_URL', '/' . strtr(SITE_ROOT_DIR_FROM_DOCUMENT_ROOT, DIRECTORY_SEPARATOR, '/') . '/');

/**
 * The directory where cached files are stored, relative to the site's root
 * directory.
 */
define('CACHE_DIR_FROM_SITE_ROOT', 'include/cache');

/**
 * The directory where projects are stored, relative to the site's root
 * directory.
 */
define('PROJECTS_DIR_FROM_SITE_ROOT', 'include/content/projects');

////////////////////////////////////////////////////////////////////////////////

define('SITE_TITLE',       'David Osborn');
define('SITE_AUTHOR',      'David Osborn');
define('SITE_AUTHOR_LINK', 'mailto:davidcosborn@gmail.com');
define('SITE_HOST',        'DreamHost.com');
define('SITE_HOST_LINK',   'http://dreamhost.com/');

////////////////////////////////////////////////////////////////////////////////

/**
 * The special string that separates the preview from the content in a project
 * file.
 */
define('CONTENT_SEPARATOR', '<!-- end of preview -->');

/**
 * The minimum width of project-summary boxes.
 */
define('MIN_BOX_SIZE', 350);

////////////////////////////////////////////////////////////////////////////////

// auto-load PHP modules
include_once 'file.php'; // list_tree
foreach (list_tree('include/php', '*.php', ListTreePathType::ABSOLUTE) as $file)
	include_once $file;

// initialize cache directory
$cache_dir = join_path(SITE_ROOT_DIR, CACHE_DIR_FROM_SITE_ROOT);
if (!is_dir($cache_dir))
	mkdir($cache_dir);

////////////////////////////////////////////////////////////////////////////////

session_start();
?>
