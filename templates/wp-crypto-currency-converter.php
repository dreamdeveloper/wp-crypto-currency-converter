<?php 
    extract($args); 
    $plugin_textdomain = 'wp-crypto-currency-converter';
    $headers = array(
        'id' => __( 'ID', $plugin_textdomain ),
        'time' => __( 'Time', $plugin_textdomain ),
        'from_symbol' => __( 'Symbol From Convert', $plugin_textdomain ),
        'to_symbol' => __( 'Symbol To Convert', $plugin_textdomain ),
        'amount' => __( 'Amount', $plugin_textdomain ),
        'convert' => __( 'Result', $plugin_textdomain ),
    );
?>
<div class="wrap" id="wp-crypto-currency-converter">    
    <h2><?php esc_html_e('WP Crypto Currency Converter', $plugin_textdomain); ?></h2>
    <br/>
    <div class="menu-head">
        <a href="<?php echo admin_url( "admin.php?page=" . $_GET["page"] ) ?>" class="tab active">
            <?php esc_html_e( 'General', $plugin_textdomain ); ?>
        </a>
        <p class="ccc-submit">
            <button type="submit" class="ccc-button ccc-md ccc-orange btn-refresh"><?php _e( 'Refresh', $plugin_textdomain)?></button>
        </p>
    </div>

    <h2><?php esc_html_e( 'List conversions Data', $plugin_textdomain ); ?></h2>
    <p class="desc"><?php _e("You can display the last conversion data on the page with shortocde <strong>\"[ccc-list-data]\"</strong>, and you can use the Shortcode for display the Conversion Widget <strong>\"[ccc-conversion]\"</strong> or in the php file of your theme you can use it like this: <strong>\"echo do_shortocde(\"[ccc-conversion]\");\"</strong> and <strong>\"echo do_shortocde(\"[ccc-list-data]\");\"</strong>. <br>
    The data for conversions is refreshed every 5 minutes, in order to refresh the data forcibly, click the <strong>Refresh</strong> button in the upper right corner.<br><br>
    Also, to force a data refresh, you can do it through the command line using the command: <strong>wp ccc-refresh-data</strong>", $plugin_textdomain); ?></p>

    <div class="pagination">
        <?php echo $paginate; ?>
    </div>

    <table>
        <thead>
            <tr class="headers">
                <?php foreach( $headers as $column ) { ?>
                    <th> <?php print_r($column); ?> </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $data as $row )  { ?>
                <tr class="item">
                    <td> <?php echo $row->id; ?> </td>
                    <td> <?php echo $row->time; ?> </td>
                    <td> <?php echo $row->from_symbol; ?> </td>
                    <td> <?php echo $row->to_symbol; ?> </td>
                    <td> <?php echo $row->amount; ?> </td>
                    <td> <?php echo number_format( $row->convert, 6 ); ?> </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php echo $paginate; ?>
    </div>
</div>
