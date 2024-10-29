<?php
/**
 * Returns data class
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
 * Returns data for view and database.
 */
class Abt_Return_Data {
	const TABLE_NAME  = 'abt';
	const DB_VERSION  = '1.4';
	const PSI_LOCALES = [
		'ar'    => [
			'id'   => 'ar',
			'name' => 'العربية', // Arabic.
		],
		'bg_BG' => [
			'id'   => 'bg',
			'name' => 'Български', // Bulgarian.
		],
		'ca'    => [
			'id'   => 'ca',
			'name' => 'Català', // Catalan.
		],
		'cs'    => [
			'id'   => 'cs',
			'name' => 'Čeština', // Czech.
		],
		'da_DK' => [
			'id'   => 'da',
			'name' => 'Dansk', // Danish.
		],
		'da_DE' => [
			'id'   => 'de',
			'name' => 'Deutsch', // German.
		],
		'el'    => [
			'id'   => 'el',
			'name' => 'Ελληνικά', // Greek.
		],
		'en_US' => [
			'id'   => 'us',
			'name' => 'English (United States)', // English(US).
		],
		'en_GB' => [
			'id'   => 'en-GB',
			'name' => 'English (UK)', // English(UK).
		],
		'es_ES' => [
			'id'   => 'es',
			'name' => 'Español', // Spanish.
		],
		'fi'    => [
			'id'   => 'fi',
			'name' => 'Suomi', // Finnish.
		],
		'tl'    => [
			'id'   => 'fil',
			'name' => 'Tagalog', // Tagalog.
		],
		'fr_FR' => [
			'id'   => 'fr',
			'name' => 'Français', // French.
		],
		'hi_IN' => [
			'id'   => 'hi',
			'name' => 'हिन्दी', // Hindi.
		],
		'hr'    => [
			'id'   => 'hr',
			'name' => 'Hrvatski', // Croatian.
		],
		'hu_HU' => [
			'id'   => 'hu',
			'name' => 'Magyar', // Hungarian.
		],
		'id_ID' => [
			'id'   => 'id',
			'name' => 'Bahasa Indonesia', // Indonesian.
		],
		'it_IT' => [
			'id'   => 'it',
			'name' => 'Italiano', // Italian.
		],
		'he_IL' => [
			'id'   => 'iw',
			'name' => 'עִבְרִית', // Hebrew.
		],
		'ja'    => [
			'id'   => 'ja',
			'name' => '日本語', // Japanese.
		],
		'ko_KR' => [
			'id'   => 'ko',
			'name' => '한국어', // Korean.
		],
		'lt_LT' => [
			'id'   => 'lt',
			'name' => 'Lietuvių kalba', // Lithuanian.
		],
		'lv'    => [
			'id'   => 'lv',
			'name' => 'Latviešu valoda', // Latvian.
		],
		'nl_NL' => [
			'id'   => 'nl',
			'name' => 'Nederlands', // Dutch.
		],
		'nb_NO' => [
			'id'   => 'no',
			'name' => 'Norsk bokmål', // Norwegian.
		],
		'pl_PL' => [
			'id'   => 'pl',
			'name' => 'Polski', // Polish.
		],
		'pt_BR' => [
			'id'   => 'pt-BR',
			'name' => 'Português do Brasil', // Portuguese(Brazil).
		],
		'pt_PT' => [
			'id'   => 'pt-PT',
			'name' => 'Português', // Portuguese.
		],
		'ro_RO' => [
			'id'   => 'ro',
			'name' => 'Română', // Romanian.
		],
		'ru_RU' => [
			'id'   => 'ru',
			'name' => 'Русский', // Russian.
		],
		'sk_SK' => [
			'id'   => 'sk',
			'name' => 'Slovenčina', // Slovak.
		],
		'sl_SI' => [
			'id'   => 'sl',
			'name' => 'Slovenščina', // Slovenian.
		],
		'sr_RS' => [
			'id'   => 'sr',
			'name' => 'Српски језик', // Serbian.
		],
		'sv_SE' => [
			'id'   => 'sv',
			'name' => 'Svenska', // Swedish.
		],
		'th'    => [
			'id'   => 'th',
			'name' => 'ไทย', // Thai.
		],
		'tr_TR' => [
			'id'   => 'tr',
			'name' => 'Türkçe', // Turkish.
		],
		'uk'    => [
			'id'   => 'uk',
			'name' => 'Українська', // Ukrainian.
		],
		'vi'    => [
			'id'   => 'vi',
			'name' => 'Tiếng Việt', // Vietnamese.
		],
		'zh_CN' => [
			'id'   => 'zh-CN',
			'name' => '简体中文', // Simplified Chinese.
		],
		'zh_TW' => [
			'id'   => 'zh-TW',
			'name' => '繁體中文', // traditional Chinese.
		],
	];

	/**
	 * Options table locale.
	 *
	 * @var string
	 */
	private static $locale;

	/**
	 * _abt table locale.
	 *
	 * @var string
	 */
	private static $abt_locale;

	/**
	 * Pagespeed Insight URL by country.
	 *
	 * @var string
	 */
	private $psi_url;

	/**
	 * Pagespeed Insight admin page URL by country.
	 *
	 * @var string
	 */
	private $psi_admin_url;

	/**
	 * Various service URL.
	 *
	 * @var array
	 */
	public static $location_url = [];

	/**
	 * Array for wp_option insertion.
	 *
	 * @var array
	 */
	public static $abt_status = [];

	/**
	 * Perform data initialization for view and database insertion.
	 */
	private function __construct() {
		self::$locale     = get_locale();
		self::$abt_locale = get_option( 'abt_locale' );

		if ( true === array_key_exists( self::$locale, self::PSI_LOCALES ) ) {
			$this->psi_admin_url = 'https://developers.google.com/speed/pagespeed/insights/?hl=' . self::PSI_LOCALES[ self::$locale ]['id'];
			$this->psi_url       = $this->psi_admin_url . '&url=';
		} else {
			$this->psi_admin_url = 'https://developers.google.com/speed/pagespeed/insights/?hl=us';
			$this->psi_url       = $this->psi_admin_url . '&url=';
		}

		self::$location_url += [
			'psi'           => $this->psi_url,
			'psiAdmin'      => $this->psi_admin_url,
			'lh'            => 'https://googlechrome.github.io/lighthouse/viewer/?psiurl=',
			'lhAdmin'       => 'https://googlechrome.github.io/lighthouse/viewer/',
			'gsc'           => 'https://search.google.com/search-console',
			'gscAdmin'      => 'https://search.google.com/search-console',
			'gc'            => 'https://webcache.googleusercontent.com/search?q=cache%3A',
			'gcAdmin'       => 'https://webcache.googleusercontent.com/search?q=cache%3A',
			'gi'            => 'https://www.google.com/search?q=site%3A',
			'giAdmin'       => 'https://www.google.com/search?q=site%3A',
			'bi'            => 'https://www.bing.com/search?q=url%3a',
			'biAdmin'       => 'https://www.bing.com/search?q=url%3a',
			'twitter'       => 'https://twitter.com/search?f=live&q=',
			'twitterAdmin'  => 'https://twitter.com/',
			'facebook'      => 'https://www.facebook.com/search/top?q=',
			'facebookAdmin' => 'https://www.facebook.com/',
			'hatena'        => 'https://b.hatena.ne.jp/entry/s/',
			'hatenaAdmin'   => 'https://b.hatena.ne.jp/',
		];

		self::$abt_status += [
			'psi'      => [
				'name'      => __( 'PageSpeed Insights', 'admin-bar-tools' ),
				'shortname' => 'psi',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'psi' ) : true,
				'url'       => self::$location_url['psi'],
				'adminurl'  => self::$location_url['psiAdmin'],
				'order'     => 1,
			],
			'lh'       => [
				'name'      => __( 'Lighthouse', 'admin-bar-tools' ),
				'shortname' => 'lh',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'lh' ) : true,
				'url'       => self::$location_url['lh'],
				'adminurl'  => self::$location_url['lhAdmin'],
				'order'     => 2,
			],
			'gsc'      => [
				'name'      => __( 'Google Search Console', 'admin-bar-tools' ),
				'shortname' => 'gsc',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'gsc' ) : true,
				'url'       => self::$location_url['gsc'],
				'adminurl'  => self::$location_url['gscAdmin'],
				'order'     => 3,
			],
			'gc'       => [
				'name'      => __( 'Google Cache', 'admin-bar-tools' ),
				'shortname' => 'gc',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'gc' ) : true,
				'url'       => self::$location_url['gc'],
				'adminurl'  => self::$location_url['gcAdmin'],
				'order'     => 4,
			],
			'gi'       => [
				'name'      => __( 'Google Index', 'admin-bar-tools' ),
				'shortname' => 'gi',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'gi' ) : true,
				'url'       => self::$location_url['gi'],
				'adminurl'  => self::$location_url['giAdmin'],
				'order'     => 5,
			],
			'twitter'  => [
				'name'      => __( 'Twitter Search', 'admin-bar-tools' ),
				'shortname' => 'twitter',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'twitter' ) : true,
				'url'       => self::$location_url['twitter'],
				'adminurl'  => self::$location_url['twitterAdmin'],
				'order'     => 6,
			],
			'facebook' => [
				'name'      => __( 'Facebook Search', 'admin-bar-tools' ),
				'shortname' => 'facebook',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'facebook' ) : true,
				'url'       => self::$location_url['facebook'],
				'adminurl'  => self::$location_url['facebookAdmin'],
				'order'     => 7,
			],
			'hatena'   => [
				'name'      => __( 'Hatena Bookmark', 'admin-bar-tools' ),
				'shortname' => 'hatena',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'hatena' ) : true,
				'url'       => self::$location_url['hatena'],
				'adminurl'  => self::$location_url['hatenaAdmin'],
				'order'     => 8,
			],
			'bi'       => [
				'name'      => __( 'Bing Index', 'admin-bar-tools' ),
				'shortname' => 'bi',
				'status'    => get_option( 'abt_status' ) ? $this->get_abt_status( 'bi' ) : true,
				'url'       => self::$location_url['bi'],
				'adminurl'  => self::$location_url['biAdmin'],
				'order'     => 9,
			],
		];
	}

	/**
	 * Return options.
	 */
	public static function options() {
		new self();
		return self::$abt_status;
	}

	/**
	 * If abt_status exist, return status.
	 *
	 * @param string $shortname abt_status key.
	 */
	private function get_abt_status( string $shortname ): bool {
		$abt_status = get_option( 'abt_status' );

		return $abt_status[ $shortname ]['status'];
	}
}
