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
 * The directory where projects are stored, relative to the site's root
 * directory.
 */
define('PROJECTS_DIR_FROM_SITE_ROOT', strtr('include/content/projects', '/', DIRECTORY_SEPARATOR));

/**
 * The directory where projects are stored.
 */
define('PROJECTS_DIR', realpath(SITE_ROOT_DIR . DIRECTORY_SEPARATOR . PROJECTS_DIR_FROM_SITE_ROOT));

////////////////////////////////////////////////////////////////////////////////

define('SITE_AUTHOR',      'David Osborn');
define('SITE_AUTHOR_LINK', 'mailto:davidcosborn@gmail.com');
define('SITE_HOST',        'Registered Hosting');
define('SITE_HOST_LINK',   'http://registeredhosting.ca/');
define('SITE_TITLE',       'David Osborn');

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

include_once SITE_ROOT_DIR . '/include/php/file.php';
include_once SITE_ROOT_DIR . '/include/php/include.php';
include_once SITE_ROOT_DIR . '/include/php/project.php';
include_once SITE_ROOT_DIR . '/include/php/tags.php';

////////////////////////////////////////////////////////////////////////////////

session_start();
?>
