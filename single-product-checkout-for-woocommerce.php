<?php
/**
 * Plugin Name:       Single Product Checkout For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-single-product-checkout/
 * Description:       Allows Shop Owners To Set Product To Be Sold Separately
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Varun Sridharan
 * Author URI:        https://varunsridharan.in
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       spcfwc
 * Domain Path:       /i18n
 */

defined( 'ABSPATH' ) || exit;

defined( 'SPCF_WC_FILE' ) || define( 'SPCF_WC_FILE', __FILE__ );
defined( 'SPCF_WC_VERSION' ) || define( 'SPCF_WC_VERSION', '1.0' );
defined( 'SPCF_WC_NAME' ) || define( 'SPCF_WC_NAME', __( 'Single Product Checkout For WooCommerce' ) );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( function_exists( 'vsp_maybe_load' ) ) {
	vsp_maybe_load( 'spcf_wc_init', __DIR__ . '/vendor/varunsridharan' );
}

if ( function_exists( 'wponion_load' ) ) {
	wponion_load( __DIR__ . '/vendor/wponion/wponion' );
}

if ( ! function_exists( 'spcf_wc_init' ) ) {
	/**
	 * Triggers An Plugin Instance When VSP Framework Loads.
	 *
	 * @return bool|\Single_Product_Checkout_For_WC
	 */
	function spcf_wc_init() {
		if ( ! vsp_add_wc_required_notice( SPCF_WC_NAME ) ) {
			if ( ! vsp_is_ajax() || ! vsp_is_cron() ) {
				require_once __DIR__ . '/bootstrap.php';
				return spcf_wc();
			}
		}
		return false;
	}
}

if ( ! function_exists( 'spcf_wc' ) ) {
	/**
	 * Returns Plugin's Instance.
	 *
	 * @return \Single_Product_Checkout_For_WC
	 */
	function spcf_wc() {
		return Single_Product_Checkout_For_WC::instance();
	}
}
