<?php
/**
 * Registers a settings page for the plugin.
 *
 * @link https://developer.wordpress.org/plugins/settings/custom-settings-page/
 *
 * @package MESHResearch\CommonsConnect
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
	$cc_options             = [];
	$cc_options['base_url'] = '';
	add_option( CC_PREFIX . 'options', $cc_options );
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
		__( 'Options', 'commons-connect' ),
		__NAMESPACE__ . '\options_section_callback',
		CC_PREFIX . 'options'
	);

	add_settings_field(
		CC_PREFIX . 'field-base-url',
		__( 'Repository base URL', 'commons-connect' ),
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
 * Adds the Commons Connect settings page to the settings admin menu.
 */
function settings_page() {
	add_submenu_page(
		'options-general.php',
		'Commons Connect',
		'Commons Connect',
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
 */
function options_section_callback() {
	?>
	<div class= '<?= CC_PREFIX ?>options_section'>
		For explanation of Commons Connect settings, please consult the <a href='<?= USER_GUIDE_URL ?>' target='_blank'>user guide</a>.
	</div>
	<?php
}

/**
 * Displays and processes form field for base_url option.
 */
function field_base_url_callback() {
	$cc_options = get_option( CC_PREFIX . 'options' );
	?>
	<input
		type="text"
		name="<?= esc_attr( CC_PREFIX . 'options' ) ?>[base_url]"
		id="<?= esc_attr( CC_PREFIX . 'options' ) ?>[base_url]"
		value="<?= isset( $cc_options ) ? esc_attr( $cc_options['base_url'] ) : ''; ?>"
		style="width:25em;"
	>
	<?php
}
