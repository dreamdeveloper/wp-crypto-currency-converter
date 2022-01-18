<?php 

namespace WPCryptoCurrencyConverter;

use WPCryptoCurrencyConverter\ApiRequest;

/**
 * WpCLI class
 * 
 * @since 1.0.0
 */
class WpCLI {

    /**
     * Constructor of class
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'cli_init', array( $this, 'register_command' ) );
    }

    /**
     * Register command
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function register_command() {
        \WP_CLI::add_command( 'ccc-refresh-data', array( $this, 'ccc_add_command' ) );
    }

    /**
     * Add command 
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function ccc_add_command( $args, $assoc_args ) {
        
        if ( ! ApiRequest::ccc_reset_request_cache() ) {
            \WP_CLI::error( "Could not delete cache. Does it exist?" );
        } else {
            \WP_CLI::success( "Сache time has been successfully reset." );
        }
    }
}