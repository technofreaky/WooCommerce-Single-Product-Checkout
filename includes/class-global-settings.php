<?php

namespace SPCF_WC;

defined( 'ABSPATH' ) || exit;

/**
 * Class Global_Setting
 *
 * @package SPCF_WC
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
final class Global_Settings {
	/**
	 * Returns Minimum Quantity Configured In Settings.
	 *
	 * @return string|int
	 */
	public static function min_qty() {
		return Helper::option( 'min_qty', false );
	}

	/**
	 * Returns Maximum Quantity Configured In Settings.
	 *
	 * @return string|int
	 */
	public static function max_qty() {
		return Helper::option( 'max_qty', false );
	}

	/**
	 * Returns Redirect To Info Configured In Settings.
	 *
	 * @return string
	 */
	public static function redirect_to() {
		return Helper::option( 'redirect_to', false );
	}

	/**
	 * Adding Other Product Along With Single Checkout Product
	 *
	 * Error Message To Shown When A Product Configured With Single Product Checkout Added With Another Product already in cart.
	 *
	 * @return string
	 */
	public static function error_100() {
		return Helper::option( 'error_100' );
	}

	/**
	 * Validation Message For Minimum Quantity.
	 *
	 * Error Message To Shown When A Product Does Not Met With Minimum Required Quantity.
	 *
	 * @return string
	 */
	public static function error_101() {
		return Helper::option( 'error_101' );
	}

	/**
	 * Validation Message For Maximum Quantity.
	 *
	 * Error Message To Shown When A Product Exceeds Maximum Allowed Quantity.
	 *
	 * @return string
	 */
	public static function error_102() {
		return Helper::option( 'error_102' );
	}

	/**
	 * Added With Single Product Checkout Configured Product
	 *
	 * Error Message To Shown When A Non Single Product Checkout Product Added To Cart when single checkout product already in cart.
	 *
	 * @return string
	 */
	public static function error_103() {
		return Helper::option( 'error_103' );
	}
}
