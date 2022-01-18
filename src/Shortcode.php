<?php 

namespace WPCryptoCurrencyConverter;

use WPCryptoCurrencyConverter\Logs;

/**
 * Shortcode class
 * 
 * @since 1.0.0
 */
class Shortcode { 

    /**
     * Constructor of class
     * 
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function __construct() {

        add_action( 'init', array( $this, 'ccc_scripts' ) );
        add_shortcode( 'ccc-conversion', array( $this, 'ccc_conversion_data' ) );
        add_shortcode( 'ccc-list-data', array( $this, 'ccc_list_data' ) );
    }

    /**
	 * Enqueue for shortcode.
     * 
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function ccc_scripts() {

		wp_register_style( 'ccc-shortcode-style', plugins_url( '../assets/css/ccc-shortcode.css', __FILE__ ), array(), CCC_VERSION );
		wp_register_script( 'ccc-conversion-script', plugins_url( '../assets/js/ccc-conversion.js', __FILE__ ), array( 'jquery' ), CCC_VERSION, true );

		wp_localize_script( 'ccc-conversion-script', 'ccc_ajax',
			array(
				'nonce'        => wp_create_nonce( 'ccc_ajax-nonce' ),
				'url_endpoint' => admin_url( 'admin-ajax.php' )
			)
		);
	}

    /**
     * Declare template conversion shortcode
     *
     * @since 1.0.0
     * @access public
     * @return ob_get_clean()
     */
    public function ccc_conversion_data( $atts, $content ) {

        wp_enqueue_style( 'ccc-shortcode-style' );
        wp_enqueue_script( 'ccc-conversion-script' );

        ob_start();

        load_template( CCC_PATH . '/templates/ccc-conversion.php', true);

        return ob_get_clean();
    }

    /**
     * Declare template list data shortcode
     *
     * @since 1.0.0
     * @access public
     * @return ob_get_clean()
     */
    public function ccc_list_data( $atts, $content ) {

        wp_enqueue_style( 'ccc-shortcode-style' );

        $list = Logs::list( 10, 0, 'DESC' );

        $args = array(
            'data' => $list,
        );

        ob_start();

        load_template( CCC_PATH . '/templates/ccc-list-data.php', true, $args );

        return ob_get_clean();
    }
}