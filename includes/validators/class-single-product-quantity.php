<?php

namespace SPCF_WC\Validators;

defined( 'ABSPATH' ) || exit;

use SPCF_WC\Global_Settings;
use SPCF_WC\Helper;
use VSP\Error;

if ( ! class_exists( '\SPCF_WC\Validators\Single_Product_Quantity' ) ) {
	/**
	 * Class Status_Check
	 *
	 * @package SPCF_WC\Validators
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 */
	final class Single_Product_Quantity {
		/**
		 * Fetches Quantity Details And Returns It.
		 *
		 * @param string|int $product_id
		 * @param string     $type
		 *
		 * @static
		 * @return bool|int
		 */
		protected static function qty( $product_id, $type = 'min' ) {
			$instance    = Helper::product_config( $product_id );
			$product_qty = ( 'min' === $type ) ? $instance->min_qty() : $instance->max_qty();
			$global_qty  = ( 'min' === $type ) ? Global_Settings::min_qty() : Global_Settings::max_qty();

			if ( false !== $product_qty && '0' === $product_qty ) {
				return false;
			}

			if ( false === $product_qty || 0 === intval( $product_qty ) ) {
				return ( ! empty( intval( $global_qty ) ) ) ? intval( $global_qty ) : false;
			}
			return intval( $product_qty );
		}

		/**
		 * Returns Minimum QTY Count.
		 *
		 * @param int|string $product_id
		 *
		 * @static
		 * @return bool|int
		 */
		public static function min( $product_id ) {
			return self::qty( $product_id, 'min' );
		}

		/**
		 * Returns Maximum QTY Count.
		 *
		 * @param int|string $product_id
		 *
		 * @static
		 * @return bool|int
		 */
		public static function max( $product_id ) {
			return self::qty( $product_id, 'max' );
		}

		/**
		 * Validates.
		 *
		 * @param string|int $product_id User Selected Product
		 * @param string|int $qty User Required QTy
		 *
		 * @static
		 * @return bool|\VSP\Error
		 */
		public static function validate( $product_id, $qty ) {
			if ( Single_Product_Status::is_enabled( $product_id ) ) {
				$min = self::min( $product_id );
				if ( false !== $min && $qty < self::min( $product_id ) ) {
					return new Error( 101, Helper::render_error_message( 101, $product_id, $qty ) );
				}

				$max = self::max( $product_id );
				if ( false !== $max && $qty > self::max( $product_id ) ) {
					return new Error( 102, Helper::render_error_message( 102, $product_id, $qty ) );
				}
			}
			return false;
		}
	}
}
