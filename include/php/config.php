<?php
/**
 * A relative path specifying the root of the site on the server's filesystem,
 * relative to the document root.
 */
define('SITE_ROOT_RELATIVE_TO_DOCUMENT_ROOT', 'portfolio');

/**
 * A relative URL (that is, an absolute-path reference) specifying the root of
 * the site.  It is relative to the document root of the webserver.  Because it
 * is an absolute-path reference, it must start with a single '/' character.  It
 * must also end with a '/' character.
 */
define('SITE_ROOT_URL', preg_replace('/\/\//', '/', '/' . strtr(SITE_ROOT_RELATIVE_TO_DOCUMENT_ROOT, DIRECTORY_SEPARATOR, '/') . '/'));

/**
 * An absolute path specifying the root of the site on the server's filesystem.
 */
define('SITE_ROOT_DIR', realpath($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . SITE_ROOT_RELATIVE_TO_DOCUMENT_ROOT));

define('SITE_AUTHOR',      'David Osborn');
define('SITE_AUTHOR_LINK', 'mailto:davidcosborn@gmail.com');
define('SITE_HOST',        'Registered Hosting');
define('SITE_HOST_LINK',   'http://registeredhosting.ca/');
define('SITE_TITLE',       'David Osborn');

include_once SITE_ROOT_DIR . '/include/php/file.php';
include_once SITE_ROOT_DIR . '/include/php/include.php';
include_once SITE_ROOT_DIR . '/include/php/tags.php';

session_start();
?>
