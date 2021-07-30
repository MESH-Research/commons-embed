<?php
/**
 * Commons Connect
 *
 * @package MESHResearch\CommonsConnect
 * @author Mike Thicke
 *
 * @wordpress-plugin
 * Plugin Name: Commons Connect
 * Description: A suite of plugins that allows WordPress sites to embed content from Humanities Commons.
 * Version: 0.3.0
 * Author: Mike Thicke
 * Author URI: http://www.mikethicke.com
 * Text Domain: commons-connect
 */

namespace MESHResearch\CommonsConnect;

const CC_PREFIX = 'mcc_';                           // prefix for options, etc.

// Is this a development build? Determines directory structure and whether
// some debug settings are set. This is set to false by the release.sh script.
const DEV_BUILD = true;

// For dev builds, fedora-embbed.php is one directory up, so that the build
// will be detected by WordPress as a plugin.
if ( DEV_BUILD ) {
	$require_prefix = 'src/';
} else {
	$require_prefix = '';
}

if ( DEV_BUILD ) {
	define( 'CC_BASE_URL', plugin_dir_url( __FILE__ ) . 'src/' );
} else {
	define( 'CC_BASE_URL', plugin_dir_url( __FILE__ ) );
}

if ( DEV_BUILD ) {
	global $wpdb;
	$wpdb->show_errors = true;

	// Stop auto refresh of pages when debugging.
	add_action(
		'init',
		function () {
			wp_deregister_script( 'heartbeat' );
		},
		1
	);

	// Flush rewrite rules when debugging.
	add_action(
		'init',
		function() {
			flush_rewrite_rules();
		}
	);
}

// Composer autoloader.
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

// General.
require_once $require_prefix . 'settings-page.php';

// Blocks.
require_once $require_prefix . 'blocks/blocks.php';

// CoreConnect.
require_once $require_prefix . 'core-connect/core-connect.php';

// Adds setting options.
add_options();
