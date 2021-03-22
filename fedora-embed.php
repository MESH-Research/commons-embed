<?php
/**
 * Fedora Embed
 *
 * @package MikeThicke\FedoraEmbed
 * @author Mike Thicke
 *
 * @wordpress-plugin
 * Plugin Name: Fedora Embed
 * Description: Embeds items stored in Fedora repositories in WordPress.
 * Version: 0.1.1
 * Author: Mike Thicke
 * Author URI: http://www.mikethicke.com
 * Text Domain: fedora-embed
 */

namespace MikeThicke\FedoraEmbed;

const FEM_PREFIX = 'fem_'; // prefix for options, etc.

const DEV_BUILD = true;

// For dev builds, fedora-embbed.php is one directory up, so that the build
// will be detected by WordPress as a plugin.
if ( DEV_BUILD ) {
	$require_prefix = 'src/';
} else {
	$require_prefix = '';
}

if ( DEV_BUILD ) {
	define( 'FEM_BASE_URL', plugin_dir_url( __FILE__ ) . 'src/' );
} else {
	define( 'FEM_BASE_URL', plugin_dir_url( __FILE__ ) );
}

if ( DEV_BUILD ) {
	global $wpdb;
	$wpdb->show_errors = true;
}

require_once $require_prefix . 'settings-page.php';
require_once $require_prefix . 'blocks/blocks.php';
require_once $require_prefix . 'rest.php';

// Adds setting options.
add_options();
