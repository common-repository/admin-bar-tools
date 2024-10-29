<?php
/**
 * Plugin activate.
 *
 * @author     Ken-chan
 * @package    WordPress
 * @subpackage Admin Bar Tools
 * @since      1.5.0
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'You do not have access rights.' );
}

/**
 * Activate process.
 */
class Abt_Activate extends Abt_Base {
	/**
	 * Constructor.
	 */
	public function __construct() {
		register_activation_hook( $this->get_plugin_path(), [ $this, 'register_options' ] );
		add_action( 'init', [ $this, 'check_abt_options_column_exists' ], 10 );
	}

	/**
	 * Check abt_options column exists.
	 */
	public function check_abt_options_column_exists() {
		$abt_options = $this->get_abt_options();

		if ( ! $abt_options ) {
			$this->register_options();
		}

		if ( self::VERSION !== $abt_options['version'] ) {
			foreach ( self::OPTIONS_KEY as $key_name ) {
				if ( ! array_key_exists( $key_name, $abt_options ) ) {
					if ( 'theme_support' === $key_name ) {
						$abt_options[ $key_name ] = true;
					}
				}
			}

			$this->set_abt_options( $abt_options );
		}

	}

	/**
	 * Register wp_options column.
	 */
	public function register_options(): void {
		if ( ! $this->get_abt_options() ) {
			$options = [
				'items'         => $this->create_items(),
				'locale'        => get_locale(),
				'sc'            => 0,
				'theme_support' => true,
				'version'       => self::VERSION,
			];

			$this->set_abt_options( $options );
		}
	}

	/**
	 * Uninstall wp_options column.
	 */
	public static function uninstall_options(): void {
		foreach ( self::OPTIONS_COLUMN as $option_name ) {
			delete_option( self::add_prefix( $option_name ) );
		}
	}

	/**
	 * Create status item value.
	 */
	private function create_items(): array {
		$items       = [];
		$locale      = get_locale();
		$abt_options = $this->get_abt_options();
		$psi         = 'https://developers.google.com/speed/pagespeed/insights/?hl=';

		$psi_admin_url = array_key_exists( $locale, self::PSI_LOCALES ) ? $psi . self::PSI_LOCALES[ $locale ]['id'] : $psi . 'us';
		$psi_url       = $psi_admin_url . '&url=';

		$location_url = [
			'psi'      => [
				'url'   => $psi_url,
				'admin' => $psi_admin_url,
				'name'  => __( 'PageSpeed Insights', 'admin-bar-tools' ),
				'order' => 1,
			],
			'lh'       => [
				'url'   => 'https://googlechrome.github.io/lighthouse/viewer/?psiurl=',
				'admin' => 'https://googlechrome.github.io/lighthouse/viewer/',
				'name'  => __( 'Lighthouse', 'admin-bar-tools' ),
				'order' => 2,
			],
			'gsc'      => [
				'url'   => 'https://search.google.com/search-console',
				'admin' => 'https://search.google.com/search-console',
				'name'  => __( 'Google Search Console', 'admin-bar-tools' ),
				'order' => 3,
			],
			'gc'       => [
				'url'   => 'https://webcache.googleusercontent.com/search?q=cache%3A',
				'admin' => 'https://webcache.googleusercontent.com/search?q=cache%3A',
				'name'  => __( 'Google Cache', 'admin-bar-tools' ),
				'order' => 4,
			],
			'gi'       => [
				'url'   => 'https://www.google.com/search?q=site%3A',
				'admin' => 'https://www.google.com/search?q=site%3A',
				'name'  => __( 'Google Index', 'admin-bar-tools' ),
				'order' => 5,
			],
			'bi'       => [
				'url'   => 'https://www.bing.com/search?q=url%3a',
				'admin' => 'https://www.bing.com/search?q=url%3a',
				'name'  => __( 'Bing Index', 'admin-bar-tools' ),
				'order' => 9,
			],
			'twitter'  => [
				'url'   => 'https://twitter.com/search?f=live&q=',
				'admin' => 'https://twitter.com/',
				'name'  => __( 'Twitter Search', 'admin-bar-tools' ),
				'order' => 6,
			],
			'facebook' => [
				'url'   => 'https://www.facebook.com/search/top?q=',
				'admin' => 'https://www.facebook.com/',
				'name'  => __( 'Facebook Search', 'admin-bar-tools' ),
				'order' => 7,
			],
			'hatena'   => [
				'url'   => 'https://b.hatena.ne.jp/entry/s/',
				'admin' => 'https://b.hatena.ne.jp/',
				'name'  => __( 'Hatena Bookmark', 'admin-bar-tools' ),
				'order' => 8,
			],
		];

		foreach ( $location_url as $key => $value ) {
			$items[ $key ] = [
				'name'      => $value['name'],
				'shortname' => $key,
				'status'    => $abt_options ? $abt_options['items'][ $key ]['status'] : true,
				'url'       => $value['url'],
				'adminurl'  => $value['admin'],
				'order'     => $value['order'],
			];
		}

		return $items;
	}
}
