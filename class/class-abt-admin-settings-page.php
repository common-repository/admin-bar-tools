<?php
/**
 * Admin settings page.
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

/**
 * Return admin settings page.
 */
class Abt_Admin_Settings_Page {
	/**
	 * CONSTRUCT!!
	 * WordPress Hook
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'abt_add_menu' ] );
	}

	/**
	 * Add Admin Bar Tools to admin bar
	 */
	public function abt_add_menu() {
		add_options_page(
			__( 'Admin Bar Tools', 'admin-bar-tools' ),
			__( 'Admin Bar Tools', 'admin-bar-tools' ),
			'administrator',
			'admin-bar-tools-settings',
			[ $this, 'abt_settings_page' ]
		);
	}

	/**
	 * Add configuration link to plugin page
	 *
	 * @param array|string $links plugin page setting links.
	 */
	public static function add_settings_links( array $links ): array {
		$add_link = '<a href="options-general.php?page=admin-bar-tools-settings">' . __( 'Settings', 'admin-bar-tools' ) . '</a>';
		array_unshift( $links, $add_link );
		return $links;
	}

	/**
	 * Settings Page
	 */
	public function abt_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You have no sufficient permissions to access this page.', 'admin-bar-tools' ) );
		};

		global $wpdb;
		$table_name  = $wpdb->prefix . Abt_Return_Data::TABLE_NAME;
		$db_call     = new Abt_Connect_Database();
		$result      = $db_call->return_table_data( Abt_Return_Data::TABLE_NAME );
		$result_name = array_column( $result, 'name' );

		$hidden_field_name = 'hiddenStatus';

		if ( isset( $_POST[ $hidden_field_name ] ) && 'Y' === $_POST[ $hidden_field_name ] ) {
			if ( check_admin_referer( 'abt_settings_nonce', 'abt_settings_nonce' ) ) {
				foreach ( $result_name as $value ) {
					if ( isset( $_POST['checkStatus'] ) && in_array( $value, $_POST['checkStatus'], true ) ) {
						$wpdb->update(
							$table_name,
							[ 'status' => 1 ],
							[ 'name' => $value ],
							[ '%d' ],
							[ '%s' ],
						); // db call ok; no-cache ok.
					} else {
						$wpdb->update(
							$table_name,
							[ 'status' => 0 ],
							[ 'name' => $value ],
							[ '%d' ],
							[ '%s' ],
						); // db call ok; no-cache ok.
					};
				};
				$result = $db_call->return_table_data( Abt_Return_Data::TABLE_NAME );

				$locale = get_option( 'abt_locale' );

				if ( isset( $_POST['select-locale'] ) && $_POST['select-locale'] !== $locale ) {
					$post_locale       = sanitize_text_field( wp_unslash( $_POST['select-locale'] ) );
					$new_location_urls = Abt_Return_Data::change_locale( $post_locale );
					foreach ( $new_location_urls as $key => $value ) {
						$wpdb->update(
							$table_name,
							[
								'url'      => $new_location_urls[ $key ]['url'],
								'adminurl' => $new_location_urls[ $key ]['adminurl'],
							],
							[ 'shortname' => $new_location_urls[ $key ]['shortname'] ],
							[ '%s' ],
							[ '%s' ],
						); // db call ok; no-cache ok.
					};
					update_option( 'abt_locale', $post_locale );
				}

				if ( isset( $_POST['searchconsole_radio'] ) && $_POST['searchconsole_radio'] !== $locale ) {
					$post_sc_radio = sanitize_text_field( wp_unslash( $_POST['searchconsole_radio'] ) );
					update_option( 'abt_sc', $post_sc_radio );
				}
			};

		};

		$sc_radio_value = get_option( 'abt_sc' );

		?>
<div class="wrap">
		<?php if ( isset( $_POST[ $hidden_field_name ] ) && 'Y' === $_POST[ $hidden_field_name ] ) : ?>
			<?php if ( check_admin_referer( 'abt_settings_nonce', 'abt_settings_nonce' ) ) : ?>
	<div class="updated">
		<p><?php esc_html_e( 'Your update has been successfully completed!!', 'admin-bar-tools' ); ?></p>
		<p><?php esc_html_e( 'Please reload to reflect the settings. (F5 key for Windows, ⌘ key + R key for Mac).', 'admin-bar-tools' ); ?></p>
	</div>
			<?php else : ?>
	<div class="error">
		<p><?php esc_html_e( 'An error has occurred. Please try again.', 'admin-bar-tools' ); ?></p>
	</div>
			<?php endif ?>
		<?php endif ?>
	<h1><?php esc_html_e( 'Admin Bar Tools Settings', 'admin-bar-tools' ); ?></h1>
	<form name="abt_settings_form" method="post">
		<input type="hidden" name="<?php echo esc_attr( $hidden_field_name ); ?>" value="Y">
		<?php wp_nonce_field( 'abt_settings_nonce', 'abt_settings_nonce' ); ?>
		<h2><?php esc_html_e( 'Please select the items you want to display.', 'admin-bar-tools' ); ?></h2>
		<?php foreach ( $result as $key => $value ) : ?>
		<p>
			<label>
				<input type="checkbox" name="checkStatus[]" value="<?php echo esc_attr( $value->name ); ?>" <?php echo '0' === $value->status ? '' : 'checked'; ?>>
				<?php echo esc_html( $value->name ); ?>
			</label>
		</p>
		<?php endforeach ?>
		<h2><?php esc_html_e( 'Choose how you want to register with Google SearchConsole.', 'admin-bar-tools' ); ?></h2>
		<p>			
			<input type="radio" id="dont_use" name="searchconsole_radio" value="0" <?php echo '0' === $sc_radio_value ? 'checked' : ''; ?> />
			<label for="dont_use"><?php esc_html_e( 'I don\'t use it.', 'admin-bar-tools' ); ?></label>
		</p>
		<p>
			<input type="radio" id="sc_domain" name="searchconsole_radio" value="1" <?php echo '1' === $sc_radio_value ? 'checked' : ''; ?> />
			<label for="sc_domain"><?php esc_html_e( 'Domain', 'admin-bar-tools' ); ?></label>
		</p>
		<p>
			<input type="radio" id="sc_url" name="searchconsole_radio" value="2" <?php echo '2' === $sc_radio_value ? 'checked' : ''; ?> />
			<label for="sc_url"><?php esc_html_e( 'URL Prefix', 'admin-bar-tools' ); ?></label>
		</p>
		<p><?php esc_html_e( 'Language (Country)', 'admin-bar-tools' ); ?>:
			<select name="select-locale">
			<?php foreach ( Abt_Return_Data::PSI_LOCALES as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php echo get_option( 'abt_locale' ) === $key ? 'selected' : ''; ?>><?php echo esc_html( $value['name'] ); ?></option>
			<?php endforeach ?>
			</select>
		</p>
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
		</p>
	</form>
</div>
		<?php
	}
}

if ( is_admin() && ! get_option( 'abt_status' ) ) {
	$settings_page = new Abt_Admin_Settings_Page();
}
