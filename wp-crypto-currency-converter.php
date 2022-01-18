<?php
/**
 * Plugin Name:     WP Crypto Currency Converter
 * Plugin URI:      #
 * Description:     Working with the API of a third-party resource - coinmarketcap, displaying data using a shortcode.
 * Author:          Alexey Kiriushyn
 * Author URI:      #
 * Text Domain:     wp-crypto-currency-converter
 * Domain Path:     /languages
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package WP_Crypto_Currency_Converter
 * @author 	Alexey Kiriushyn <leadergreed@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( file_exists( __DIR__ . '/vendor/autoload.php') ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

define( 'CCC_VERSION', '1.0.0' );
define( 'CCC_URL', plugin_dir_url( __DIR__ ) );
define( 'CCC_PATH', __DIR__ );
define( 'CCC_NAME', plugin_basename( __FILE__ ) );
define( 'CCC_PLUGIN_FILE', __FILE__ );
define( 'CCC_TABLE_NAME', 'ссс_logs' );

// Get Init instance
function WPCryptoCurrencyConverter_run() {
	return WPCryptoCurrencyConverter\Init::instance();
}

// Run plugin
WPCryptoCurrencyConverter_run();
// WPCryptoCurrencyConverter_run()->ccc_get_api_request()->ccc_reset_request_cache();


// exit();