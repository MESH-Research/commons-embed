<?php
/**
 * Commons Embed
 *
 * @package MESHResearch\CommonsEmbed
 * @author Mike Thicke
 *
 * @wordpress-plugin
 * Plugin Name: Commons Embed
 * Description: Embeds items stored in Commons repositories in WordPress.
 * Version: 0.2.0
 * Author: Mike Thicke
 * Author URI: http://www.mikethicke.com
 * Text Domain: commons-embed
 */

namespace MESHResearch\CommonsEmbed;

const CEM_PREFIX = 'cem_'; // prefix for options, etc.

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
