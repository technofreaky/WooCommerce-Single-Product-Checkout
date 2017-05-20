<?php
/**
 * WooCommerce Uninstall
 *
 * Uninstalling WooCommerce deletes user roles, pages, tables, and options.
 *
 * @author      WooThemes
 * @category    Core
 * @package     WooCommerce/Uninstaller
 * @version     2.3.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$options = array('wc_spc_redirect_to',
'wc_spc_other_product_error',
'wc_spc_product_with_qty_error',
'wc_spc_single_product_other_error');

foreach($options as $option){
    delete_option($option);
}