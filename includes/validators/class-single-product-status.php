<?php

namespace SPCF_WC\Validators;

defined( 'ABSPATH' ) || exit;

use SPCF_WC\Global_Settings;
use SPCF_WC\Helper;
use VSP\Error;
use VSP\Helper as VSP_Helper;

/**
 * Class Status_Check
 *
 * @package SPCF_WC\Validators
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
final class Single_Product_Status {
	/**
	 * Validates if Its Enabled.
	 *
	 * @param $product_id
	 *
	 * @return bool
	 * @since 2.0
	 */
	public static function is_enabled( $product_id ) {
		$instance = Helper::product_config( $product_id );
		return ( $instance->enabled() );
	}

	/**
	 * Fetches Quantity Details And Returns It.
	 *
	 * @param string|int $product_id
	 * @param string     $type
	 *
	 * @return bool|int
	 */
	protected static function qty( $product_id, $type = 'min' ) {
		$instance    = Helper::product_config( $product_id );
		$product_qty = ( 'min' === $type ) ? $instance->min_qty() : $instance->max_qty();
		$global_qty  = ( 'min' === $type ) ? Global_Settings::min_qty() : Global_Settings::max_qty();

		if ( false !== $product_qty && '0' === $product_qty ) {
			return false;
		}

		if ( ( false === $product_qty || 0 === intval( $product_qty ) ) && ! empty( $global_qty ) ) {
			return intval( $global_qty );
		}
		return intval( $product_qty );
	}

	/**
	 * Validates.
	 *
	 * @param string|int $product_id User Selected Product
	 * @param string|int $qty User Required Qty
	 *
	 * @return bool|\VSP\Error
	 */
	public static function validate( $product_id, $qty ) {
		if ( self::is_enabled( $product_id ) ) {
			if ( ! VSP_Helper::wc_has_product_in_cart( $product_id ) && ! empty( wc()->cart->get_cart() ) ) {
				return new Error( 100, Helper::render_error_message( 100, $product_id, $qty ) );
			}
		}

		if ( Helper::wc_has_single_checkout_in_cart() && ! empty( wc()->cart->get_cart() ) ) {
			return new Error( 103, Helper::render_error_message( 103, $product_id, $qty ) );
		}
		return false;
	}
}
