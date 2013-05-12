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
define('SITE_CODE_HOST',   'GitHub');
define('SITE_CODE_LINK',   'https://github.com/davidcosborn/portfolio');

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

/**
 * The initializer of PAGE_ID.
 */
function _get_page_id()
{
	include_once 'path.php'; // path_{relative_to,without_extension}

	$path = $_SERVER['PHP_SELF'];
	$path = path_relative_to($path, SITE_ROOT_URL);
	$path = path_without_extension($path);
	if ($path == 'index') return 'root';
	if (basename($path) == 'index') $path = dirname($path);
	$path = strtr($path, '/' . DIRECTORY_SEPARATOR, '__');
	return $path;
}

/**
 * The ID of the current page.
 */
define('PAGE_ID', _get_page_id());

////////////////////////////////////////////////////////////////////////////////

// set PHP include path
set_include_path('.' . PATH_SEPARATOR . SITE_ROOT_DIR . '/include/php');

// auto-load PHP modules
include_once 'load_php.php';
load_php_files('include/php/thirdparty', ListDirFlags::RECURSIVE);
load_php_files('include/php');
load_php_file_if_exists('include/php/page/' . PAGE_ID . '.php');

// initialize cache directory
$cache_dir = SITE_ROOT_DIR . DIRECTORY_SEPARATOR . CACHE_DIR_FROM_SITE_ROOT;
if (!is_dir($cache_dir))
	mkdir($cache_dir);

////////////////////////////////////////////////////////////////////////////////

session_start();
?>
