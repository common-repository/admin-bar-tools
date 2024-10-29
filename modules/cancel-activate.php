<?php
/**
 * Cancel activate.
 *
 * @author Ken-chan
 * @package WordPress
 * @subpackage Admin Bar Tools
 * @since 1.0.3
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'You do not have access rights.' );
}

/**
 * Return error message.
 */
function cancel_activate() {
	?>
<div class="error">
	<p><?php esc_html_e( '[Plugin error] Admin Bar Tools has been stopped because the PHP version is old.', 'admin-bar-tools' ); ?></p>
	<p>
		<?php esc_html_e( 'Admin Bar Tools requires at least PHP 7.3.0 or later.', 'admin-bar-tools' ); ?>
		<?php esc_html_e( 'Please upgrade PHP.', 'admin-bar-tools' ); ?>
	</p>
	<p>
		<?php esc_html_e( 'Current PHP version:', 'admin-bar-tools' ); ?>
		<?php echo PHP_VERSION; ?>
	</p>
</div>
	<?php
}
