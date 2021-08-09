<?php
/**
 * General functions that don't have a better home.
 *
 * @package MESHResearch\CommonsConnect
 * @author Mike Thicke
 *
 * @since 0.3.0
 */

namespace MESHResearch\CommonsConnect;

/*
 * Actions
 */
add_action( 'admin_notices', __NAMESPACE__ . '\display_user_guide_notice' );
add_action( 'admin_init', __NAMESPACE__ . '\dismiss_user_guide_notice' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_styles' );

/**
 * Creates a notice pointing to user documentation. Not displayed if user has
 * already dismissed it.
 */
function display_user_guide_notice() {
	global $current_user;
	$userid = $current_user->ID;

	if ( ! get_user_meta( $userid, CC_PREFIX . 'dismiss_user_guide_notice' ) ) {
		?>
		<div class='notice notice-info mcc-admin-notice'>
			Thank you for installing Commons Connect! Please read the <a href='<?= USER_GUIDE_URL ?>' target='_blank'>user guide</a> to help you get started.
			<a href='?dismiss_user_guide_notice=1'>Dismiss</a>
		</div>
		<?php
	}
}

/**
 * Handles dismissal of user guide notice.
 */
function dismiss_user_guide_notice() {
	global $current_user;
	$userid = $current_user->ID;
	if ( isset( $_GET['dismiss_user_guide_notice'] ) && '1' == $_GET['dismiss_user_guide_notice'] ) {
		add_user_meta( $userid, CC_PREFIX . 'dismiss_user_guide_notice', '1', true );
	}
}

/**
 * Enqueue admin styles
 */
function enqueue_admin_styles() {
	wp_enqueue_style(
		CC_PREFIX . 'admin-styles',
		plugins_url( 'style.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
	);
}