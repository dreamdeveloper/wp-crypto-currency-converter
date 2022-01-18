<?php 

namespace WPCryptoCurrencyConverter;

/**
 * Logs class
 * 
 * @since 1.0.0
 */
class Logs {

    /**
     * Add log data to table 
     *
     * @param response $convert_data
     * @param array $params
     * @static
     * @since 1.0.0
     * @access public
     * @return void
     */
    public static function add( $convert_data, $params ){
        global $wpdb;

        $wpdb->insert( 
            $wpdb->prefix . CCC_TABLE_NAME, 
            array( 
                'time'        => current_time( 'mysql' ), 
                'from_symbol' => $params['symbol'], 
                'to_symbol'   => $params['convert'], 
                'amount'      => $params['amount'], 
                'convert'     => $convert_data->price, 
            ) 
        );
    }

    /**
     * Select list of logs data
     *
     * @static
     * @since 1.0.0
     * @access public
     * @return Array|Object|null
     */
    public static function list( $limit = 0, $paged = 0, $order = '' ) {
        global $wpdb;

        if ( $limit != 0 ) { 
            $limit_sql = " LIMIT " . $limit;

            if ($paged != 0) {
                $limit_sql = " LIMIT " . ($paged-1) * $limit . ", " . $limit;
            }
        }

        if ( $order != '' ) {
            $order_sql = " ORDER BY id " . $order;
        }

        return $wpdb->get_results( 
            "SELECT * FROM " . $wpdb->prefix . CCC_TABLE_NAME . $order_sql . $limit_sql
        );
    }
}