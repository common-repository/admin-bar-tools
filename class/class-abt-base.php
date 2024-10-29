<?php
/**
 * Admin Bar Tools base class.
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
 * Send Chat Tools base class.
 */
class Abt_Base {
	protected const PREFIX              = 'abt';
	protected const PLUGIN_SLUG         = 'admin-bar-tools';
	protected const PLUGIN_NAME         = 'Admin Bar Tools';
	protected const PLUGIN_FILE         = self::PLUGIN_SLUG . '.php';
	protected const API_NAME            = self::PLUGIN_SLUG;
	protected const API_VERSION         = 'v1';
	protected const VERSION             = '1.6.2';
	protected const OPTIONS_COLUMN_NAME = 'options';

	public const OPTIONS_COLUMN = [
		'options',
	];

	protected const OPTIONS_KEY = [
		'items',
		'locale',
		'sc',
		'theme_support',
		'version',
	];

	protected const PSI_LOCALES = [
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
	 * Return add prefix.
	 *
	 * @param string $value After prefix value.
	 */
	public static function add_prefix( string $value ): string {
		return self::PREFIX . '_' . $value;
	}

	/**
	 * Return plugin url.
	 * e.g. https://expamle.com/wp-content/plugins/admin-bar-tools
	 *
	 * @param string $plugin_name Plugin name.
	 */
	protected function get_plugin_url( string $plugin_name = self::PLUGIN_SLUG ): string {
		return WP_PLUGIN_URL . '/' . $plugin_name;
	}

	/**
	 * Return plugin name.
	 * e.g. Admin Bar Tools
	 */
	public static function get_plugin_name(): string {
		return self::PLUGIN_NAME;
	}

	/**
	 * Return plugin directory.
	 * e.g. /DocumentRoot/wp-content/plugins/admin-bar-tools
	 *
	 * @param string $plugin_slug Plugin slug.
	 */
	protected function get_plugin_dir( string $plugin_slug = self::PLUGIN_SLUG ): string {
		return WP_PLUGIN_DIR . '/' . $plugin_slug;
	}

	/**
	 * Return plugin file path.
	 * e.g. /DocumentRoot/wp-content/plugins/send-chat-tools/admin-bar-tools.php
	 *
	 * @param string $plugin_slug Plugin slug. e.g. send-chat-tools .
	 * @param string $plugin_file Plugin file. e.g. send-chat-tools.php .
	 */
	protected function get_plugin_path( string $plugin_slug = self::PLUGIN_SLUG, string $plugin_file = self::PLUGIN_FILE ): string {
		return $this->get_plugin_dir( $plugin_slug ) . '/' . $plugin_file;
	}

	/**
	 * Return WP-API parameter.
	 * e.g. admin-bar-tools/v1
	 *
	 * @param string $api_name    Plugin unique name.
	 * @param string $api_version Plugin API version.
	 */
	protected function get_api_namespace( string $api_name = self::API_NAME, string $api_version = self::API_VERSION ): string {
		return "{$api_name}/{$api_version}";
	}

	/**
	 * Return option group.
	 * Use register_setting.
	 * e.g. admin-bar-tools-settings
	 */
	protected function get_option_group(): string {
		return self::PLUGIN_SLUG . '-settings';
	}

	/**
	 * Get abt_options.
	 */
	protected function get_abt_options(): array|bool {
		return get_option( $this->add_prefix( self::OPTIONS_COLUMN_NAME ) );
	}

	/**
	 * Set abt_options.
	 *
	 * @param array $abt_options abt_options column data.
	 */
	protected function set_abt_options( array $abt_options ): void {
		update_option( $this->add_prefix( self::OPTIONS_COLUMN_NAME ), $abt_options );
	}

	/**
	 * Output browser console.
	 * WARNING: Use debag only!
	 *
	 * @param mixed $value Output data.
	 */
	protected function console( mixed $value ): void {
		echo '<script>console.log(' . wp_json_encode( $value ) . ');</script>';
	}
}
