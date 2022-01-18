<?php 

namespace WPCryptoCurrencyConverter;

use WPCryptoCurrencyConverter\ApiRequest;

/**
 * Class Ajax
 * 
 * @since 1.0.0
 */
class Ajax {

	/**
	 * Constructor of class
	 *
	 * @return void
	*/
	public function __construct() {

		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_ccc_get_data_currencies', array( $this, 'ccc_get_data_currencies' ) );
			add_action( 'wp_ajax_nopriv_ccc_get_data_currencies', array( $this, 'ccc_get_data_currencies' ) );

            add_action( 'wp_ajax_ccc_get_data_conversion', array( $this, 'ccc_get_data_conversion' ) );
			add_action( 'wp_ajax_nopriv_ccc_get_data_conversion', array( $this, 'ccc_get_data_conversion' ) );

			add_action( 'wp_ajax_ccc_refresh_data', array( $this, 'ccc_refresh_data' ) );
			add_action( 'wp_ajax_nopriv_ccc_refresh_data', array( $this, 'ccc_refresh_data' ) );
		}
	}

    /**
     * Send request to API endpoint for getting currencies
     *
     * @access public
     * @return response 
     */
    public function ccc_get_data_currencies() {

        $ccc_nonce = ( isset( $_GET['ccc_nonce'] ) ) ? $_GET['ccc_nonce'] : '';

        if ( wp_verify_nonce( $ccc_nonce, 'ccc_ajax-nonce' ) ) {
            $data_from_api = WPCryptoCurrencyConverter_run()->ccc_get_api_request()->ccc_get_data_currencies();

            if ( $data_from_api ) {
                $status_code     = 200;
                $response_return = array(
                    'result'  => $data_from_api,
                    'message' => 'success'
                );
            } else {
                $status_code     = 400;
                $response_return = array(
                    'message' => 'error'
                );
            }
        }

        return wp_send_json( $response_return, $status_code );
    }

    /**
     * Send request to API endpoint for getting data conversion
     *
     * @access public
     * @return response 
     */
    public function ccc_get_data_conversion() {

        $ccc_nonce = ( isset( $_GET['ccc_nonce'] ) ) ? $_GET['ccc_nonce'] : '';
        $amount = ( isset( $_GET['amount'] ) ) ? $_GET['amount'] : '';
        $symbol = ( isset( $_GET['symbol'] ) ) ? $_GET['symbol'] : '';
        $convert = ( isset( $_GET['convert'] ) ) ? $_GET['convert'] : '';

        if ( wp_verify_nonce( $ccc_nonce, 'ccc_ajax-nonce' ) ) {
            $data_from_api = WPCryptoCurrencyConverter_run()->ccc_get_api_request()->ccc_get_data_conversion( $amount, $symbol, $convert );

            if ( $data_from_api ) {
                $status_code     = 200;
                $response_return = array(
                    'result'  => $data_from_api,
                    'message' => 'success'
                );
            } else {
                $status_code     = 400;
                $response_return = array(
                    'message' => 'error'
                );
            }
        }

        return wp_send_json( $response_return, $status_code );
    }

    /**
     * Refresh data
     *
     * @access public
     * @return response 
     */
    public function ccc_refresh_data() {

        $ccc_nonce = ( isset( $_GET['ccc_nonce'] ) ) ? $_GET['ccc_nonce'] : '';

        if ( wp_verify_nonce( $ccc_nonce, 'ccc_ajax-nonce' ) ) {
            if ( ApiRequest::ccc_reset_request_cache() ) {

                $status_code     = 200;
                $response_return = array(
                    'message' => 'success'
                );
            } else {
                $status_code     = 400;
                $response_return = array(
                    'message' => 'error'
                );
            }
        }

        return wp_send_json( $response_return, $status_code);
    }
}
