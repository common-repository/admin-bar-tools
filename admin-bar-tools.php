<?php
/**
 * Plugin Name: Admin Bar Tools
 * Plugin URI:  https://www.braveryk7.com/
 * Description: A plugin that allows you to add useful links to the admin bar.
 * Version:     1.6.2
 * Author:      Ken-chan
 * Author URI:  https://twitter.com/braveryk7
 * Text Domain: admin-bar-tools
 * Domain Path: /languages
 * License:     GPL2
 *
 * @author Ken-chan
 * @package WordPress
 * @subpackage Admin Bar Tools
 * @since 0.0.1
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'You do not have access rights.' );
}

load_plugin_textdomain( 'admin-bar-tools', false, basename( dirname( __FILE__ ) ) . '/languages' );

require_once dirname( __FILE__ ) . '/class/class-abt-phpver-judge.php';

$abt_phpver_judge    = new Abt_Phpver_Judge();
$require_php_version = '8.0.0';

if ( ! $abt_phpver_judge->judgment( $require_php_version ) ) {
	$abt_phpver_judge->deactivate(
		__FILE__,
		'Admin Bar Tools',
		$require_php_version,
	);
} else {
	require_once dirname( __FILE__ ) . '/class/class-abt-base.php';
	require_once dirname( __FILE__ ) . '/class/class-abt-activate.php';
	require_once dirname( __FILE__ ) . '/class/class-abt-admin-page.php';
	require_once dirname( __FILE__ ) . '/class/class-abt-add-admin-bar.php';

	/**
	 * Start admin page.
	 */
	new Abt_Admin_Page();

	/**
	 * Plugin activate.
	 */
	new Abt_Activate();

	/**
	 * Add admin bar menu.
	 */
	new Abt_Add_Admin_Bar();

	/**
	 * Plugin uninstall hook.
	 * Delete wp_options column.
	 */
	register_uninstall_hook( __FILE__, 'Abt_Activate::uninstall_options' );
}
