<?php
/**
 * Returns data class
 *
 * @author Ken-chan
 * @package WordPress
 * @subpackage Admin Bar Tools
 * @since 1.0.6
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'You do not have access rights.' );
}

/**
 * Add Admin Bar Tools to admin bar.
 */
class Abt_Add_Admin_Bar extends Abt_Base {
	/**
	 * WordPress hook.
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', [ $this, 'add_admin_bar' ], 999 );
		add_action( 'admin_bar_menu', [ $this, 'add_theme_support_link' ], 999 );
	}
	/**
	 * Insert Admin bar
	 *
	 * @param object $wp_admin_bar Admin bar.
	 */
	public function add_admin_bar( object $wp_admin_bar ): void {
		$url             = rawurlencode( get_pagenum_link( get_query_var( 'paged' ) ) );
		$add_url_lists   = [ 'psi', 'lh', 'gc', 'gi', 'bi', 'twitter', 'facebook' ];
		$sanitize_domain = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$sanitize_uri    = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( is_user_logged_in() ) {
			$wp_admin_bar->add_node(
				[
					'id'    => self::PREFIX,
					'title' => __( 'Admin Bar Tools', 'admin-bar-tools' ),
					'meta'  => [
						'target' => self::PREFIX,
					],
				]
			);

			$abt_options = $this->get_abt_options();
			$link_url    = '';

			foreach ( $abt_options['items'] as $item ) {
				if ( $item['status'] ) {
					if ( is_admin() ) {
						$link_url = $item['adminurl'];
					} else {
						$link_url = match ( $item['shortname'] ) {
							'hatena' => $item['url'] . $sanitize_domain . $sanitize_uri,
							'gsc'    => $this->searchconsole_url( $item['url'], $abt_options['sc'], $url ),
							default  => in_array( $item['shortname'], $add_url_lists, true ) ? $item['url'] . $url : $item['url'],
						};
					}
					$wp_admin_bar->add_node(
						[
							'id'     => $item['shortname'],
							'title'  => $item['name'],
							'parent' => self::PREFIX,
							'href'   => $link_url,
							'meta'   => [
								'target' => '_blank',
							],
						],
					);
				}
			}
		}
	}

	/**
	 * Create Google SearchConsole URL.
	 *
	 * @param string $url           SearchConsole URL.
	 * @param int    $status        Use SearchConsole type (Don't use, Domain or URL Prefix).
	 * @param string $encode_url    Use rawurlencode page url.
	 *
	 * @return string SearchConsole URL.
	 */
	private function searchconsole_url( string $url, int $status, string $encode_url ): string {
		$domain    = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
		$gsc_url   = $url;
		$parameter = [
			'?resource_id=sc-domain:',
			'/performance/search-analytics?resource_id=sc-domain:',
		];

		$gsc_url .= match ( $status ) {
			1       => is_front_page() ? $parameter[0] . $domain : $parameter[1] . $domain . '&page=!' . $encode_url,
			2       => is_front_page() ? $parameter[0] . $encode_url : $parameter[1] . rawurlencode( $domain . '/' ) . '&page=!' . $encode_url,
			default => null,
		};

		return $gsc_url;
	}

	/**
	 * Identify the theme and display a link to the support page.
	 *
	 * @param object $wp_admin_bar Admin bar.
	 */
	public function add_theme_support_link( object $wp_admin_bar ): void {
		$theme_url_list = [
			'cocoon-master' => [
				'name'     => 'Cocoon',
				'official' => 'https://wp-cocoon.com/',
				'manual'   => 'https://wp-cocoon.com/manual/',
				'forum'    => 'https://wp-cocoon.com/community/',
			],
			'jin'           => [
				'name'     => 'JIN',
				'official' => 'https://jin-theme.com/',
				'manual'   => 'https://jin-theme.com/manual/',
				'forum'    => 'https://jin-forum.jp/',
			],
			'jinr2'         => [
				'name'     => 'JIN:R',
				'official' => 'https://jinr.jp/',
				'manual'   => 'https://jinr.jp/manual/',
				'forum'    => 'https://jinr-forum.jp/',
			],
			'sango-theme'   => [
				'name'     => 'SANGO',
				'official' => 'https://saruwakakun.design/',
				'manual'   => 'https://saruwakakun.com/sango/',
				'forum'    => 'https://www.sangoland.app/issues',
			],
			'thesonic'      => [
				'name'     => 'THE SONIC',
				'official' => 'https://the-sonic.jp/',
				'manual'   => 'https://the-sonic.jp/category/manual/',
				'forum'    => null,
			],
		];
		$current_theme  = wp_get_theme()->Template;

		if ( $this->get_abt_options()['theme_support'] && array_key_exists( $current_theme, $theme_url_list ) ) {
			$theme_data = $theme_url_list[ $current_theme ];
			$title_list = [
				'official' => __( 'Official Site', 'admin-bar-tools' ),
				'manual'   => __( 'Manual', 'admin-bar-tools' ),
				'forum'    => __( 'Forum', 'admin-bar-tools' ),
			];

			$wp_admin_bar->add_node(
				[
					'id'     => self::PREFIX . '_theme_support',
					'title'  => $theme_data['name'],
					'parent' => self::PREFIX,
					'meta'   => [
						'target' => self::PREFIX,
					],
				]
			);

			foreach ( array_keys( $title_list ) as $type ) {
				if ( ! is_null( $theme_data[ $type ] ) ) {
					$wp_admin_bar->add_node(
						[
							'id'     => $type,
							'title'  => $title_list[ $type ],
							'parent' => self::PREFIX . '_theme_support',
							'href'   => $theme_data[ $type ],
							'meta'   => [
								'target' => '_blank',
							],
						],
					);
				}
			}
		}
	}
}
