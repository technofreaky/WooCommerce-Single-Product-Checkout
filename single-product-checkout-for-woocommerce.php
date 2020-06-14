<?php
/**
 * Plugin Name:       Single Product Checkout For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-single-product-checkout/
 * Description:       Allows Shop Owners To Set Product To Be Sold Separately
 * Version:           1.2
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Varun Sridharan
 * Author URI:        https://varunsridharan.in
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       spcfwc
 * Domain Path:       /i18n
 * WC requires at least: 3.5
 * WC tested up to: 4.2
 */

defined( 'ABSPATH' ) || exit;

defined( 'SPCF_WC_FILE' ) || define( 'SPCF_WC_FILE', __FILE__ );
defined( 'SPCF_WC_VERSION' ) || define( 'SPCF_WC_VERSION', '1.2' );
defined( 'SPCF_WC_NAME' ) || define( 'SPCF_WC_NAME', __( 'Single Product Checkout For WooCommerce', 'spcfwc' ) );

use Varunsridharan\WordPress\Plugin_Version_Management;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( function_exists( 'vsp_maybe_load' ) ) {
	vsp_maybe_load( 'spcf_wc_init', __DIR__ . '/vendor/varunsridharan' );
}

if ( function_exists( 'wponion_load' ) ) {
	wponion_load( __DIR__ . '/vendor/wponion/wponion' );
}

register_activation_hook( SPCF_WC_FILE, 'spcf_wc_activate' );

if ( ! function_exists( 'spcf_wc_activate' ) ) {
	/**
	 * This function clears all old data.
	 */
	function spcf_wc_activate() {
		require_once __DIR__ . '/installer/index.php';

		$instance = new Plugin_Version_Management( array(
			'slug'    => 'spcf_wc',
			'version' => SPCF_WC_VERSION,
			'logs'    => true,
		), array(
			'1.0' => array( '\SPCF_WC\Installer\Installer', 'run_v1' ),
		) );
		$instance->run();
	}
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
