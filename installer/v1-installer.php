<?php

namespace SPCF_WC\Installer;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\SPCF_WC\Installer\Version_1' ) ) {
	/**
	 * WC Product Subtitle V4.0 Installer.
	 *
	 * @package WC_Product_Subtitle\Installer
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 */
	class Version_1 {
		/**
		 * Runs When V4.0 Upgrade is required.
		 *
		 * @static
		 * @return bool
		 */
		public static function run() {
			global $wpdb;

			$wpdb->query( "DELETE FROM `{$wpdb->postmeta}` WHERE meta_key LIKE '%wc_spc_product_%'" );

			$options = array(
				'wc_spc_redirect_to',
				'wc_spc_other_product_error',
				'wc_spc_product_with_qty_error',
				'wc_spc_single_product_other_error',
				'wc_spc_gmin_req_qty',
				'wc_spc_gmax_req_qty',
				'wc_spc_product_with_min_qty_error',
			);

			foreach ( $options as $option ) {
				delete_option( $option );
			}
			return true;
		}
	}
}
