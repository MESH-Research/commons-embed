<?php
/**
 * Register the Fedora Embed block and enqueue scripts.
 *
 * @package MikeThicke\WordPress
 */

namespace MESHResearch\CommonsConnect\Blocks;

use const MESHResearch\CommonsConnect\CC_PREFIX;
use const MESHResearch\CommonsConnect\DEV_BUILD;

/**
 * Actions
 */
add_action( 'plugins_loaded', __NAMESPACE__ . '\register_embed_block' );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_block_scripts' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_block_frontend_scripts' );

/**
 * Registers the Fedora Embed block.
 */
function register_embed_block() {
	register_block_type(
		'commons-connect/core-connect',
		[
			'render_callback' => __NAMESPACE__ . '\render_core_connect_block',
			'attributes'      => [
				'searchValues' => [
					'default' => '',
					'type'    => 'string', // JSON encoded string.
				],
			],
		]
	);
}

/**
 * Renders Fedora Embed block on front end.
 *
 * @param Array $attributes The block attributes.
 */
function render_core_connect_block( $attributes ) {
	if ( is_admin() ) {
		return null;
	}

	$encoded_attributes = wp_wp_json_encode( $attributes );

	return (
		"<div 
			class='fem-embed-block-frontend' 
			data-attributes='$encoded_attributes'
		>
		</div>"
	);
}

/**
 * Callback to load block scripts.
 */
function enqueue_block_scripts() {
	if ( DEV_BUILD ) {
		$block_path = '/build/';
	} else {
		$block_path = '';
	}

	wp_enqueue_script(
		CC_PREFIX . 'blocks',
		plugins_url( $block_path . 'index.js', __FILE__ ),
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-api-fetch' ],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'index.js' ),
		true
	);
	wp_enqueue_style(
		CC_PREFIX . 'block-style-front',
		plugins_url( $block_path . 'style-frontend.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'style-frontend.css' )
	);
	wp_enqueue_style(
		CC_PREFIX . 'block-style-editor',
		plugins_url( $block_path . 'index.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'index.css' )
	);

	/**
	 * Icons for mime types
	 *
	 * @link https://github.com/dmhendricks/file-icon-vectors/
	 */
	//phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_style( 'file-icon-vectors', 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vivid.min.css', null, null );
}

/**
 * Callback to load block scripts for frontend.
 */
function enqueue_block_frontend_scripts() {
	if ( DEV_BUILD ) {
		$block_path = '/build/';
	} else {
		$block_path = '';
	}

	wp_enqueue_script(
		CC_PREFIX . 'blocks',
		plugins_url( $block_path . 'frontend.js', __FILE__ ),
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api-fetch' ],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'frontend.js' ),
		true
	);
	wp_enqueue_style(
		CC_PREFIX . 'block-style-front',
		plugins_url( $block_path . 'style-frontend.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'style-frontend.css' )
	);

	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_style( 'file-icon-vectors', 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vivid.min.css', null, null );
}
