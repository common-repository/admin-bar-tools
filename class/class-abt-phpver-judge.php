<?php
/**
 * Judgment PHP Version.
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
 * Return true or false.
 */
class Abt_Phpver_Judge {
	/**
	 * Judgment PHP version.
	 *
	 * @param string $version_received php version.
	 * @return bool
	 */
	public function judgment( string $version_received ): bool {
		return version_compare( PHP_VERSION, $version_received, '>=' ) ? true : false;
	}

	/**
	 * Deactivate plugin & show deactivate massege.
	 *
	 * @param string $path Plugin path.
	 * @param string $project Project name.
	 * @param string $version PHP version.
	 */
	public function deactivate( string $path, string $project, string $version ): void {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( plugin_basename( $path ) ) ) {
			if ( is_admin() ) {
				$messages = $this->deactivate_message( $project, $version );

				?>
				<div class="error">
					<p><?php echo esc_html( $messages['header'] ); ?></p>
					<p>
						<?php echo esc_html( $messages['require'] ); ?>
						<?php echo esc_html( $messages['upgrade'] ); ?>
					</p>
					<p>
						<?php echo esc_html( $messages['current'] ); ?>
						<?php echo PHP_VERSION; ?>
					</p>
				</div>
				<?php

			}
			deactivate_plugins( plugin_basename( $path ) );
		} else {
			$messages = $this->deactivate_message( $project, $version );

			?>
			<p>
				<?php echo esc_html( $messages['require'] ); ?>
				<?php echo esc_html( $messages['upgrade'] ); ?>
			</p>
			<?php

			exit;
		}
	}

	/**
	 * Show deactivate error message.
	 *
	 * @param string $project Project name.
	 * @param string $version PHP version.
	 */
	public function deactivate_message( string $project, string $version ): array {
		$messages = [
			'header'  => sprintf(
				/* translators: 1: Plugin name */
				__( '[Plugin error] %s has been stopped because the PHP version is old.', 'admin-bar-tools' ),
				$project,
			),
			'require' => sprintf(
				/* translators: 1: Plugin name 2: PHP version */
				__( '%1$s requires at least PHP %2$s or later.', 'admin-bar-tools' ),
				$project,
				$version,
			),
			'upgrade' => __( 'Please upgrade PHP.', 'admin-bar-tools' ),
			'current' => __( 'Current PHP version:', 'admin-bar-tools' ),
		];

		return $messages;
	}
}
