<?php
/**
 * Register the Fedora Embed block and enqueue scripts.
 *
 * @package MikeThicke\WordPress
 */

namespace MikeThicke\FedoraEmbed;

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
	$fem_options = get_option( FEM_PREFIX . 'options' );
	register_block_type(
		'fedora-embed',
		[
			'render_callback' => __NAMESPACE__ . '\render_fedora_embed_block',
			'attributes'      => [
				'baseURL'      => [
					'default' => $fem_options['base_url'],
					'type'    => 'string',
				],
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
function render_fedora_embed_block( $attributes ) {
	if ( is_admin() ) {
		return null;
	}

	$encoded_attributes = wp_json_encode( $attributes );

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
		WPM_PREFIX . 'blocks',
		plugins_url( $block_path . 'index.js', __FILE__ ),
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor' ],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'index.js' ),
		true
	);
	wp_enqueue_style(
		WPM_PREFIX . 'block-style-front',
		plugins_url( $block_path . 'style-frontend.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'style-frontend.css' )
	);
	wp_enqueue_style(
		WPM_PREFIX . 'block-style-editor',
		plugins_url( $block_path . 'index.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'index.css' )
	);
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
		WPM_PREFIX . 'blocks',
		plugins_url( $block_path . 'frontend.js', __FILE__ ),
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components' ],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'frontend.js' ),
		true
	);
	wp_enqueue_style(
		WPM_PREFIX . 'block-style-front',
		plugins_url( $block_path . 'style-frontend.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'style-frontend.css' )
	);
	wp_enqueue_style(
		'wordpress-components-styles',
		includes_url( '/css/dist/components/style.min.css' ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . $block_path . 'frontend.js' )
	);
}
