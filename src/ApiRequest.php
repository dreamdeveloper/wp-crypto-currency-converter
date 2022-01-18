<?php 

namespace WPCryptoCurrencyConverter;

use WPCryptoCurrencyConverter\Init;

/**
 * ApiRequest class
 * 
 * @since 1.0.0
 */
class ApiRequest {

    /**
     * API url constant get list of crypto currencies
     * 
     * @since 1.0.0
     */
    const API_URL_GET_LIST_CURRENCIES = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/map";

    /**
     * API url constant get result conversion
     * 
     * @since 1.0.0
     */
    const API_URL_GET_CONVERSION = "https://pro-api.coinmarketcap.com/v1/tools/price-conversion"; 

    /**
     * API url constant get metadata
     * 
     * @since 1.0.0
     */
    const API_URL_GET_METADATA = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/info"; 

    /**
	 * Get the User Agent string.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
	public function ccc_get_user_agent() {

		return 'WPCryptoCurrencyConverter/' . CCC_VERSION . '; ' . get_bloginfo( 'url' );
	}

    /**
     * Get list of crypto currencies data from API, once in 5 minutes
     *
     * @since 1.0.0
     * @access public
     * @return array|WP_Error The response or WP_Error on failure.
     */
    public function ccc_get_data_currencies() {
        
        $url = self::API_URL_GET_LIST_CURRENCIES;
        
        $params = array(
            'sort'  => 'cmc_rank',
            'limit' => 450
        );

        $url = add_query_arg( urlencode_deep( $params ), $url );

        if ( false === ( $remote_currencies = get_transient( 'remote_currencies' ) ) ) {
            $remote_get = wp_remote_get(
                $url,
                array(
                    'timeout'     => 5,
                    'redirection' => 5,
                    'user-agent'  => $this->ccc_get_user_agent(),
                    'blocking'    => true,
                    'headers'     => array(
                        'Accepts' => 'application/json',
                        'X-CMC_PRO_API_KEY' => 'f85ecc57-5aff-4e75-88fc-ee07413b0f3b'
                    ),
                    'cookies'     => array(),
                )
            );

            if ( 200 !== wp_remote_retrieve_response_code( $remote_get ) ) {
                return;
            }

            $remote_currencies = wp_remote_retrieve_body( $remote_get );
            set_transient( 'remote_currencies', $remote_currencies );
        }

        $data_currencies = json_decode( $remote_currencies );
        $ids             = implode( ',', array_column( $data_currencies->data, 'id' ) );
        $metadata        = json_decode( $this->ccc_get_data_metadata( $ids ) );
        
        $result['metadata']        = $metadata;
        $result['data_currencies'] = $data_currencies;

        return json_encode($result);
    }

    /**
     * Get result conversion from API
     *
     * @since 1.0.0
     * @access public
     * @return array|WP_Error The response or WP_Error on failure.
     */
    public function ccc_get_data_conversion( $amount, $symbol, $convert ) {

        $url = self::API_URL_GET_CONVERSION;

        $params = array(
            'amount'  => $amount,
            'symbol'  => $symbol,
            'convert' => $convert
        );

        $url               = add_query_arg( urlencode_deep( $params ), $url );
        $remote_conversion = get_transient( 'remote_conversion' );
        $remote_params     = get_transient( 'params ');

        if ( false === $remote_conversion || $url !== $remote_params ) {
            $remote_get = wp_remote_get(
                $url,
                array(
                    'timeout'     => 5,
                    'redirection' => 5,
                    'user-agent'  => $this->ccc_get_user_agent(),
                    'blocking'    => true,
                    'headers'     => array(
                        'Accepts' => 'application/json',
                        'X-CMC_PRO_API_KEY' => 'f85ecc57-5aff-4e75-88fc-ee07413b0f3b'
                    ),
                    'cookies'     => array(),
                )
            );

            if ( 200 !== wp_remote_retrieve_response_code( $remote_get ) ) {
                return;
            }

            $remote_conversion = wp_remote_retrieve_body( $remote_get );
            set_transient( 'remote_conversion', $remote_conversion, 5 * MINUTE_IN_SECONDS);
            set_transient( 'params', $url );
        }

        Logs::add( json_decode( $remote_conversion )->data->quote->{$convert}, $params );

        return $remote_conversion;
    }

    /**
     * Get metadata by currencies from API
     *
     * @since 1.0.0
     * @access public
     * @return array|WP_Error The response or WP_Error on failure.
     */
    public function ccc_get_data_metadata( $ids ) {

        $url = self::API_URL_GET_METADATA;

        $params = array(
            'aux' => 'logo',
            'id'  => $ids
        );

        $url = add_query_arg( urlencode_deep( $params ), $url );

        if ( false === ( $remote_metadata = get_transient( 'remote_metadata' ) ) ) {
            $remote_get = wp_remote_get(
                $url,
                array(
                    'timeout'     => 5,
                    'redirection' => 5,
                    'user-agent'  => $this->ccc_get_user_agent(),
                    'blocking'    => true,
                    'headers'     => array(
                        'Accepts' => 'application/json',
                        'X-CMC_PRO_API_KEY' => 'f85ecc57-5aff-4e75-88fc-ee07413b0f3b'
                    ),
                    'cookies'     => array(),
                )
            );

            if ( 200 !== wp_remote_retrieve_response_code( $remote_get ) ) {
                return;
            }

            $remote_metadata = wp_remote_retrieve_body( $remote_get );
            set_transient( 'remote_metadata', $remote_metadata );
        }

        return $remote_metadata;
    }

    /**
     * Reset cache
     *
     * @since 1.0.0
     * @static
     * @access public
     * @return void
     */
    public static function ccc_reset_request_cache() {

        delete_transient( 'remote_currencies' );
        delete_transient( 'remote_conversion' );
        delete_transient( 'remote_metadata' );
        delete_transient( 'params' );
        
        return true;
    }
}