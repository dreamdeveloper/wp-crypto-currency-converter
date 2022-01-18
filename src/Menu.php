<?php 

namespace WPCryptoCurrencyConverter;

use WPCryptoCurrencyConverter\Logs;

/**
 * Menu class
 * 
 * @since 1.0.0
 */
class Menu { 

    /**
     * Menu Construct
     *
     * @return void
     */
    public function __construct() {

        add_action( 'admin_menu', array( __CLASS__, 'ccc_add_menu_page' ) );        
    }

    /**
     * Add menu page description.
     * 
     * @since 1.0.0
     * @static
     * @return void
     */
    public static function ccc_add_menu_page() {
        
        $capability = 'manage_options';

        add_menu_page( 
            esc_html__( 'WP Crypto Currency Converter', 'wp-crypto-currency-converter' ),
            esc_html__( 'WP Crypto Currency Converter', 'wp-crypto-currency-converter' ),
            $capability,
            'wp-crypto-currency-converter',
            array( __CLASS__, 'ccc_main' ),
            'dashicons-money-alt',
            7
        );
    }

    /**
     * Display Main Admin page template.
     * 
     * @since 1.0.0
     * @static
     * @return void
     */
    public static function ccc_main() {

        $paged         = $_GET['paged'] ? $_GET['paged'] : 1;
        $post_per_page = 20;
        $list          = Logs::list( $post_per_page, $paged );
        $count_total   = count( Logs::list() );

        $args = array(
            'data'     => $list,
            'paginate' => self::paginate( $list, ceil($count_total/$post_per_page) )
        );
        
        WPCryptoCurrencyConverter_run()->ccc_get_api_request()->ccc_get_data_currencies();

        load_template( CCC_PATH . '/templates/wp-crypto-currency-converter.php', true, $args );
    }

    /**
     * Pagination func. for table in Admin page
     *
     * @param Array $list
     * @param Integer $total
     * @static
     * @return void
     */
    public static function paginate( $list, $total ) {
        $big   = 999999;
        $paged = $_GET['paged'] ? $_GET['paged'] : 1;

        $paginate = paginate_links(array(
            'base'         => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'       => '?paged=%#%',
            'current'      => max( 1, $paged ),
            'total'        => $total,
            'prev_text'    => __('<'),
            'next_text'    => __('>'),
            'type'         => 'plain',
        ));

        return $paginate;
    }
}
