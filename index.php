<?php
if (!preg_match( '|^www\..*|', $_SERVER [ 'HTTP_HOST' ]) && preg_match( '|^aroshamed\..*|', $_SERVER [ 'HTTP_HOST' ]) && !preg_match( '|^curacen.by\..*|', $_SERVER [ 'HTTP_HOST' ]) && !preg_match( '|^laennec.by\..*|', $_SERVER [ 'HTTP_HOST' ])) {
    header ( 'HTTP/1.0 301 Moved Permanently' );
    $url = trim ($_SERVER [ 'REQUEST_URI' ], '/');
    if(trim($_SERVER [ 'REQUEST_URI' ], '/') != '') $url .= '/';
    header('Location: http://www.aroshamed.by/' . $url);
    die();
}
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
