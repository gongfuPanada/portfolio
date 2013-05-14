<?php
/**
 * The document root, as an absolute filesystem path.
 */
define('DOCUMENT_ROOT', rtrim(preg_replace('/[\\/\\\\]+/', '/', $_SERVER['DOCUMENT_ROOT']), '/'));

/**
 * The site root, as an absolute filesystem path.
 */
define('SITE_ROOT_DIR', preg_replace('/[\\/\\\\]+/', '/', dirname(dirname(dirname(__FILE__)))));

/**
 * Check whether the site root, relative to the document root, can be computed,
 * which is only possible when SITE_ROOT_DIR is under DOCUMENT_ROOT.
 */
assert(strncmp(DOCUMENT_ROOT, SITE_ROOT_DIR, strlen(DOCUMENT_ROOT)) === 0);

/**
 * The site root, relative to the document root.
 */
define('SITE_ROOT_FROM_DOCUMENT_ROOT', substr(SITE_ROOT_DIR, strlen(DOCUMENT_ROOT) + 1));

/**
 * The site root, as a local URL (that is, an absolute-path reference).  It will
 * be relative to the document root, and it will include a leading and trailing
 * path separator.
 */
define('SITE_ROOT_URL', '/' . SITE_ROOT_FROM_DOCUMENT_ROOT . '/');

/**
 * The directory where cached files are stored, relative to the site root.
 */
define('CACHE_FROM_SITE_ROOT', 'include/cache');

/**
 * The directory where projects are stored, relative to the site root.
 */
define('PROJECTS_FROM_SITE_ROOT', 'include/content/projects');

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
 * The minimum width of the project-preview boxes.
 */
define('MIN_BOX_SIZE', 350);

/**
 * The default width of the project-preview boxes, when Javascript is disabled.
 */
define('DEFAULT_BOX_SIZE', 450);

////////////////////////////////////////////////////////////////////////////////

include_once 'php.php'; // must be included before "path.php"
include_once 'path.php'; // path_{relative_to,without_extension}

/**
 * Generates a unique ID for the current page, based on its path.
 */
function _get_page_id()
{
	$path = $_SERVER['PHP_SELF'];
	$path = path_relative_to($path, SITE_ROOT_URL);
	$path = path_without_extension($path);

	$file = basename($path);
	if ($file === '' || $file === 'index') $path = dirname($path);
	return $path === '.' ? 'root' : strtr($path, '/', '_');
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
$cache_dir = SITE_ROOT_DIR . '/' . CACHE_FROM_SITE_ROOT;
if (!is_dir($cache_dir))
	mkdir($cache_dir);

////////////////////////////////////////////////////////////////////////////////

session_start();
?>
