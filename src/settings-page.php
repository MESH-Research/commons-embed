<?php
/**
 * Registers a settings page for the plugin.
 *
 * @link https://developer.wordpress.org/plugins/settings/custom-settings-page/
 *
 * @package MESHResearch\CommonsEmbed
 */

namespace MESHResearch\CommonsConnect;

/**
 * Actions
 */
add_action( 'admin_init', __NAMESPACE__ . '\settings_init' );
add_action( 'admin_menu', __NAMESPACE__ . '\settings_page' );

/**
 * Adds settings options as an object.
 */
function add_options() {
	$CC_options             = [];
	$CC_options['base_url'] = '';
	add_option( CC_PREFIX . 'options', $CC_options );
}

/**
 * Initialize settings page.
 */
function settings_init() {
	register_setting(
		CC_PREFIX . 'options',
		CC_PREFIX . 'options'
	);

	add_settings_section(
		CC_PREFIX . 'options-section',
		__( 'Options', 'fedora-embed' ),
		__NAMESPACE__ . 'CC_options_section_callback',
		CC_PREFIX . 'options'
	);

	add_settings_field(
		CC_PREFIX . 'field-base-url',
		__( 'Repository base URL', 'fedora-embed' ),
		__NAMESPACE__ . '\field_base_url_callback',
		CC_PREFIX . 'options',
		CC_PREFIX . 'options-section',
		[
			'class' => CC_PREFIX . 'settings-row',
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
		CC_PREFIX . 'settings_page',
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
			settings_fields( CC_PREFIX . 'options' );
			do_settings_sections( CC_PREFIX . 'options' );
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
	$CC_options = get_option( CC_PREFIX . 'options' );
	?>
	<input
		type="text"
		name="<?= esc_attr( CC_PREFIX . 'options' ) ?>[base_url]"
		id="<?= esc_attr( CC_PREFIX . 'options' ) ?>[base_url]"
		value="<?= isset( $CC_options ) ? esc_attr( $CC_options['base_url'] ) : ''; ?>"
		style="width:25em;"
	>
	<?php
}
