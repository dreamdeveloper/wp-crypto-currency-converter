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
<div id="wp-crypto-currency-converter">

    <h4><?php esc_html_e( 'Last Converions', $plugin_textdomain ); ?></h4>

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