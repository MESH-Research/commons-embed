<?php
/**
 * Registers a settings page for the plugin.
 *
 * @link https://developer.wordpress.org/plugins/settings/custom-settings-page/
 *
 * @package MikeThicke\FedoraEmbed
 */

namespace MikeThicke\FedoraEmbed;

/**
 * Actions
 */
add_action( 'admin_init', __NAMESPACE__ . '\settings_init' );
add_action( 'admin_menu', __NAMESPACE__ . '\settings_page' );

/**
 * Adds settings options as an object.
 */
function add_options() {
	$fem_options             = [];
	$fem_options['base_url'] = '';
	add_option( FEM_PREFIX . 'options', $fem_options );
}

/**
 * Initialize settings page.
 */
function settings_init() {
	register_setting(
		FEM_PREFIX . 'options',
		FEM_PREFIX . 'options'
	);

	add_settings_section(
		FEM_PREFIX . 'options-section',
		__( 'Options', 'fedora-embed' ),
		__NAMESPACE__ . 'fem_options_section_callback',
		FEM_PREFIX . 'options'
	);

	add_settings_field(
		FEM_PREFIX . 'field-base-url',
		__( 'Repository base URL', 'fedora-embed' ),
		__NAMESPACE__ . '\field_base_url_callback',
		FEM_PREFIX . 'options',
		FEM_PREFIX . 'options-section',
		[
			'class'     => FEM_PREFIX . 'settings-row',
		]
	);
}

/**
 * Callback functions.
 */

/**
 * Adds the Fedora Embed settings page to the settings admin menu.
 */
function settings_page() {
	add_submenu_page(
		'options-general.php',
		'Fedora Embed',
		'Fedora Embed',
		'manage_options',
		FEM_PREFIX . 'settings_page',
		__NAMESPACE__ . '\settings_page_html'
	);
}

/**
 * Displays the settings page.
 */
function settings_page_html() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	?>
	<div class="wrap">
		<h1><?= esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( FEM_PREFIX . 'options' );
			do_settings_sections( FEM_PREFIX . 'options' );
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}

/**
 * Displays options section.
 *
 * @param Array $args Section settings.
 */
function fedora_options_section_callback( $args ) {
	?>
	<p id="<?= esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Options', 'fedora-embed' ); ?></p>
	<?php
}

/**
 * Displays and processes form field for base_url option.
 */
function field_base_url_callback() {
	$fem_options = get_option( FEM_PREFIX . 'options' );
	?>
	<input
		type="text"
		name="<?= esc_attr( FEM_PREFIX . 'options' ) ?>[base_url]"
		id="<?= esc_attr( FEM_PREFIX . 'options' ) ?>[base_url]"
		value="<?= isset( $fem_options ) ? esc_attr( $fem_options['base_url'] ) : ''; ?>"
		style="width:25em;"
	>
	<?php
}
