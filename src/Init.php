<?php

namespace WPCryptoCurrencyConverter;

use WPCryptoCurrencyConverter\ApiRequest;

/**
 * Initialization class of plugin
 *
 * @since 1.0.0
 */
class Init {

	/**
	 * The single instance of Init.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $_instance = null;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @since 1.0.0
	 * @access  public
	 */
	public $version;

	/**
	 * The plugin name.
	 *
	 * @var     string
	 * @since 1.0.0
	 * @access  public
	 */
	public $plugin_name;

	/**
	 * The plugin url.
	 *
	 * @var     string
	 * @since 1.0.0
	 * @access  public
	 */
	public $plugin_url;

	/**
	 * The plugin path.
	 *
	 * @var     string
	 * @since 1.0.0
	 * @access  public
	 */
	public $plugin_path;

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 * @return  void
	 * @access  public
	 */
	public function __construct() {		

		// Activation and deactivation hook.
		register_activation_hook( CCC_PLUGIN_FILE, array( $this, 'ccc_activate' ) );
		register_deactivation_hook( CCC_PLUGIN_FILE, array( $this, 'ccc_deactivate' ) );
		register_uninstall_hook( CCC_PLUGIN_FILE, array( $this, 'cc_delete_plugin_database_table' ) );

		// Register actions.
		add_action( 'init', array( $this, 'ccc_load_plugin_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'ccc_get_menu' ) );
		add_action( 'plugins_loaded', array( $this, 'ccc_get_ajax' ) );
		add_action( 'plugins_loaded', array( $this, 'ccc_get_api_request' ) );
		add_action( 'plugins_loaded', array( $this, 'ccc_get_shortcode' ) );
		add_action( 'plugins_loaded', array( $this, 'ccc_get_wp_cli' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'ccc_admin_scripts' ) );
        add_action( 'admin_notices', array( __CLASS__, 'ccc_admin_notice_success' ) );
		add_filter( 'plugin_action_links_' . CCC_NAME, array( $this, 'ccc_plugin_settings_link' ) );
	}

	 /**
     * Add Settings link to plugin page.
     * 
     * @since 1.0.0
     * @static
     * @return links
     */
    public function ccc_plugin_settings_link( $links ) {
        $settings_link = '<a href="admin.php?page=' . dirname(CCC_NAME) . '">Settings</a>';
		array_push( $links, $settings_link );

		return $links;
    }

	/**
	 * Enqueue admin scripts
	 *
	 * @since 1.0.0
	 * @return void
	 * @access public
	 */
	public function ccc_admin_scripts() {
		wp_enqueue_style( 'admin-css', plugins_url( '../assets/css/ccc-admin.css', __FILE__ ), array(), CCC_VERSION );
		wp_enqueue_script( 'admin-js', plugins_url( '../assets/js/ccc-admin.js', __FILE__ ), array(), CCC_VERSION );

		wp_localize_script( 'admin-js', 'ccc_ajax',
			array(
				'nonce' 	   => wp_create_nonce( 'ccc_ajax-nonce' ),
				'url_endpoint' => admin_url( 'admin-ajax.php' )
			)
		);
	}

	/**
	 * Notice success after refresh data
	 *
	 * @since 1.0.0
     * @access public
	 * @return void
	 */
	public static function ccc_admin_notice_success() {

		if ( false === get_transient( 'remote_currencies' ) ) {
            $class 	 = 'notice notice-success';
            $message = esc_html__('The data has been updated successfully.', 'wp-crypto-currency-converter');
     
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message) );
        }
	}

	/**
	 * Menu class instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @return Menu instance
	 */
	public function ccc_get_menu() {

		static $menu;

		if ( ! $menu ) {
			$menu = new Menu();
		}

		return $menu;
	}

	/**
	 * Ajax class instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @return Ajax instance
	 */
	public function ccc_get_ajax() {

		static $ajax;

		if ( ! $ajax ) {
			$ajax = new Ajax();
		}

		return $ajax;
	}

	/**
	 * ApiRequest class instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @return ApiRequest instance
	 */
	public function ccc_get_api_request() {

		static $api_request;

		if ( ! $api_request ) {
			$api_request = new ApiRequest();
		}

		return $api_request;
	}

	/**
	 * Shortcode class instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @return Shortcode instance
	 */
	public function ccc_get_shortcode() {

		static $shortcode;

		if ( ! $shortcode ) {
			$shortcode = new Shortcode();
		}

		return $shortcode;
	}

	/**
	 * WpCLI class instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @return WpCLI instance
	 */
	public function ccc_get_wp_cli() {

		static $wp_cli;

		if ( ! $wp_cli ) {
			$wp_cli = new WpCLI();
		}

		return $wp_cli;
	}

	/**
	 * Init Instance
	 *
	 * @since 1.0.0
	 * @static
	 * @access public
	 * @return Init instance
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Load the localisation file.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return  void
	 */
	public function ccc_load_plugin_textdomain() {
		load_plugin_textdomain( 'wp-crypto-currency-converter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Creating table for conversion data and set option for version of plugin
	 *
	 * @since 1.0.0
	 * @access public
	 * @return  void
	 */
	public function ccc_activate() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE `" . $wpdb->prefix . CCC_TABLE_NAME . "` (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			`from_symbol` varchar(10) NULL,
			`to_symbol` varchar(10) NULL,
			`amount` varchar(100) NULL,
			`convert` varchar(100) NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		// Log the version number. 
		add_option( 'ccc-version', CCC_VERSION );

		$success = empty($wpdb->last_error);

		return $success;
	}

	/**
	 * Deactivation func., reset all the cache data
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function ccc_deactivate() {
		ApiRequest::ccc_reset_request_cache();
	}

	/**
	 * Uninstal func., Delete table, remove option for version of plugin
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function ccc_delete_plugin_database_table(){
        global $wpdb;

        if ( ! defined('WP_UNINSTALL_PLUGIN')) {
            exit();
        }

        $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . CCC_TABLE_NAME );
        delete_option("ccc-versionn");
    }
}
