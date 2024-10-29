<?php
/**
 * Functions related to connecting to the database
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
 * Handle database connection.
 */
class Abt_Connect_Database {

	/**
	 * Table name.
	 *
	 * @var string
	 */
	private $table_name;

	/**
	 * Gave prefix.
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . Abt_Return_Data::TABLE_NAME;
		add_action( 'admin_init', [ $this, 'abt_database_check' ] );
	}

	/**
	 * Insert wp_option table.
	 *
	 * @param array $data abt_status data or null.
	 */
	public function add_abt_option( ?array $data ) {
		if ( null === $data ) {
			$abt_status = Abt_Return_Data::options();
			update_option( 'abt_status', $abt_status );
		} else {
			update_option( 'abt_status', $data );
		}
	}

	/**
	 * Create abt column in wp_option when activating.
	 */
	public static function create_abt_options() {
		add_option( 'abt_locale', get_locale() );
		add_option( 'abt_db_version', Abt_Return_Data::DB_VERSION );
		add_option( 'abt_status', Abt_Return_Data::options() );
		add_option( 'abt_sc', 0 );
	}

	/**
	 * Check database version.
	 */
	public function abt_db_check(): bool {
		$current_db_version = get_option( 'abt_db_version' );
		if ( Abt_Return_Data::DB_VERSION === $current_db_version ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check database version.
	 */
	public function abt_database_check() {
		if ( current_user_can( 'manage_options' ) ) {
			if ( ! $this->abt_db_check() ) {
				global $wpdb;
				$get_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $this->table_name ) ); // db call ok; no-cache ok.

				if ( $get_table ) {
					$data = $this->wp_abt_to_abt_status();
					$this->add_abt_option( $data );

					if ( is_array( get_option( 'abt_status' ) ) ) {
						$this->abt_delete_db();
					}
				} else {
					$this->add_abt_option( null );
				}
			};
		}
	}

	/**
	 * Delete table.
	 */
	public static function abt_delete_db() {
		global $wpdb;
		$delete_table_name = $wpdb->prefix . Abt_Return_Data::TABLE_NAME;

		$sql = 'DROP TABLE IF EXISTS ' . $delete_table_name;
		$wpdb->query( "${sql}" ); // db call ok; no-cache ok.
	}

	/**
	 * Delete wp_option Admin Bar Tools column.
	 */
	public function delete_wp_options() {
		delete_option( 'abt_locale' );
		delete_option( 'abt_db_version' );
		delete_option( 'abt_status' );
		delete_option( 'abt_sc' );
	}

	/**
	 * Return select table data.
	 *
	 * @param string $str SQL.
	 * @return array $result Result.
	 */
	public function return_table_data( string $str ): array {
		global $wpdb;
		$table_name = $wpdb->prefix . $str;
		$result     = $wpdb->get_results( "SELECT * FROM ${table_name}" ); // db call ok; no-cache ok.

		return $result;
	}

	/**
	 * If wp_abt table exists, reflect the value of status column to abt_status of wp_options.
	 */
	public function wp_abt_to_abt_status() {
		$result     = $this->return_table_data( 'abt' );
		$abt_status = get_option( 'abt_status' ) ? get_option( 'abt_status' ) : Abt_Return_Data::options();

		foreach ( $result as $value ) {
			if ( '0' === $value->status ) {
				$abt_status[ $value->shortname ]['status'] = false;
			} elseif ( '1' === $value->status ) {
				$abt_status[ $value->shortname ]['status'] = true;
			}
		}
		return $abt_status;
	}
}
