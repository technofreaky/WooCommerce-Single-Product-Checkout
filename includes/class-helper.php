<?php

namespace SPCF_WC;

defined( 'ABSPATH' ) || exit;

use ReflectionException;
use ReflectionProperty;
use SPCF_WC\Validators\Single_Product_Quantity;
use SPCF_WC\Validators\Single_Product_Status;
use VSP\WC_Compatibility;
use WPOnion\Exception\Cache_Not_Found;

if ( ! class_exists( '\SPCF_WC\Helper' ) ) {
	/**
	 * Class Helper
	 *
	 * @package SPCF_WC
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 */
	class Helper {
		/**
		 * Fetches Option From DB.
		 *
		 * @param string $option_key
		 * @param bool   $defaults
		 *
		 * @static
		 * @return array|bool|\WPOnion\DB\Option
		 */
		public static function option( $option_key, $defaults = false ) {
			return wpo_settings( '_spcf_wc', $option_key, $defaults );
		}

		/**
		 * Returns All Template Tags.
		 *
		 * @static
		 * @return array
		 */
		public static function template_tags() {
			return array(
				'{sku}'      => __( 'Product SKU Code', 'spcfwc' ),
				'{name}'     => __( 'Product Name', 'spcfwc' ),
				'{id}'       => __( 'Product ID', 'spcfwc' ),
				'{cart_qty}' => __( 'Shows Quantity Submitted By User', 'spcfwc' ),
				'{min_qty}'  => __( 'Minimum Required Quantity', 'spcfwc' ),
				'{max_qty}'  => __( 'Maximum Allowed Quantity', 'spcfwc' ),
			);
		}

		/**
		 * Generates Template Description HTML.
		 *
		 * @static
		 * @return array|mixed
		 */
		public static function template_desc_tags() {
			try {
				return Cache::get( 'template_tags_desc' );
			} catch ( Cache_Not_Found $exception ) {
				$tags   = self::template_tags();
				$thead  = '<tr><th>' . __( 'Tag', 'spcfwc' ) . '</th><th>' . __( 'Description', 'spcfwc' ) . '</th></tr>';
				$return = '<table class="wpo-table wpo-text-left wpo-table-sm wpo-table-hover wpo-table-bordered" style="width:35%;">
<thead class="wpo-thead-dark">' . $thead . '</thead><tbody>';
				foreach ( $tags as $tag_id => $desc ) {
					$return .= '<tr><td>`' . $tag_id . '`</td><td>' . $desc . '</td></tr>';
				}
				$return .= '</tbody></table>';
				$exception->set( $return );
				return $return;
			}
		}

		/**
		 * Returns A Valid Instance.
		 *
		 * @param int|string|\WC_Product $product
		 *
		 * @static
		 * @return \SPCF_WC\Product
		 */
		public static function product_config( $product ) {
			$product_id = WC_Compatibility::get_product_id( $product );
			$ins        = new Product();
			try {
				return Cache::get( 'product/' . $product_id );
			} catch ( Cache_Not_Found $cache_exception ) {
				try {
					$data = wpo_post_meta( $product_id, '_spcf_wc' );
					if ( ! empty( $data ) ) {
						$ops = new ReflectionProperty( $ins, 'options' );
						$ops->setAccessible( true );
						$ops->setValue( $ins, $data );
						$cache_exception->set( $ins );
					}
				} catch ( ReflectionException $exception ) {
					return $ins;
				}
			}
			return $ins;
		}

		/**
		 * Renders Error Message.
		 *
		 * @param int        $error_code
		 * @param int|string $product_id
		 * @param int        $cart_qty
		 *
		 * @static
		 * @return string
		 */
		public static function render_error_message( $error_code, $product_id, $cart_qty ) {
			$product_id = WC_Compatibility::get_product_id( $product_id );
			$product    = wc_get_product( $product_id );
			$msg        = false;
			switch ( $error_code ) {
				case 100:
					$msg = Global_Settings::error_100();
					break;
				case 101:
					$msg = Global_Settings::error_101();
					break;
				case 102:
					$msg = Global_Settings::error_102();
					break;
				case 103:
					$msg = Global_Settings::error_103();
					break;
			}

			if ( ! empty( $msg ) ) {
				$replace_values = array(
					$product->get_sku(),
					$product->get_title(),
					$product_id,
					$cart_qty,
					Single_Product_Quantity::min( $product_id ),
					Single_Product_Quantity::max( $product_id ),
				);
				return str_replace( array_keys( self::template_tags() ), $replace_values, $msg );
			}
			return __( 'Unknown Error Occured ! ERROR : 104', 'spcfwc' );
		}

		/**
		 * Checks if cart has any single checkout product.
		 * if it does then it returns true.
		 *
		 * @static
		 * @return bool
		 */
		public static function wc_has_single_checkout_in_cart() {
			$products_in_cart = \VSP\Helper::wc_get_product_ids_in_cart();
			if ( ! empty( $products_in_cart ) && is_array( $products_in_cart ) ) {
				foreach ( array_keys( $products_in_cart ) as $id ) {
					if ( true === Single_Product_Status::is_enabled( $id ) ) {
						return true;
					}
				}
			}
			return false;
		}
	}
}
