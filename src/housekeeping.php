<?php
/**
 * Functions that handle activation, deactivation, uninstall, and requirements
 * checking.
 *
 * @package MESHResearch\CommonsEmbed
 * @author Mike Thicke
 *
 * @since 0.3.0
 */

namespace MESHResearch\CommonsConnect;

/**
 * Checks that the minimum requirements for the plugin are satisfied by the
 * environment.
 *
 * @global $wp_version The current WordPress version.
 *
 * @return bool Whether the requirements are satisfied.
 */
function requirements_satisfied() {
	global $wp_version;

	if ( version_compare( PHP_VERSION, MIN_PHP ) < 0 ) {
		return false;
	}

	if ( version_compare( $wp_version, MIN_WP ) < 0 ) {
		return false;
	}

	if ( ! extension_loaded( 'simplexml' ) ) {
		return false;
	}

	return true;
}

/**
 * Creates an admin notice alerting the user that the minimum requirements for
 * the plugin are not satisfied.
 */
function report_unsatisfied_requirements() {
	global $wp_version;

	if ( extension_loaded( 'simplexml' ) ) {
		$simple_xml_status = 'installed';
	} else {
		$simple_xml_status = 'not installed';
	}

	$wordpress_version = sanitize_text_field( $wp_version );

	//phpcs:disable
	?>
	<div class='notice notice-error is-dismissible'>
		<p>Your environment does not satisfy the minimum requirements for the CommonsConnect plugin.</p>
		<ul>
			<li>The minimum required version of PHP is <?= MIN_PHP ?> and your version is <?= PHP_VERSION ?>.</li>
			<li>The minimum required version of WordPress is <?= MIN_WP ?> and your version is <?= $wordpress_version ?>.</li>
			<li>The SimpleXML PHP extension is required and it is <?= $simple_xml_status ?>.
		</ul>
		<p>Please contact your webhost to fix these issues in order to be able to use this plugin.
	</div>
	<?php
	//phpcs:enable
}
