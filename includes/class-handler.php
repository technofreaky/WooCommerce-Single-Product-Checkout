<?php

namespace SPCF_WC;

defined( 'ABSPATH' ) || exit;

use SPCF_WC\Validators\Single_Product_Quantity;
use SPCF_WC\Validators\Single_Product_Status;
use VSP\Base;

/**
 * Class Handler
 *
 * @package SPCF_WC
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
class Handler extends Base {
	/**
	 * Handler constructor.
	 *
	 * @uses validate_cart
	 * @uses validate_qty_update
	 * @uses change_redirect_url
	 */
	public function __construct() {
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_cart' ), 99, 3 );
		add_filter( 'woocommerce_update_cart_validation', array( $this, 'validate_qty_update' ), 99, 4 );
		add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'change_redirect_url' ), 99 );
	}

	/**
	 * Validates On AddToCart.
	 *
	 * @param bool $cart_status addtocart status. return true if needs to be added to cart.
	 * @param int  $product_id Product ID.
	 * @param int  $qty Product Quantity.
	 *
	 * @return bool
	 */
	public function validate_cart( $cart_status, $product_id, $qty ) {
		$product_status = Single_Product_Status::validate( $product_id, $qty );
		if ( vsp_is_error( $product_status ) ) {
			\VSP\Helper::vsp_error_to_wc_notice( $product_status );
			return false;
		}

		$qty_status = Single_Product_Quantity::validate( $product_id, $qty );
		if ( vsp_is_error( $qty_status ) ) {
			\VSP\Helper::vsp_error_to_wc_notice( $qty_status );
			return false;
		}
		return $cart_status;
	}

	/**
	 * Handles Cart Quantity Validation.
	 *
	 * @param bool   $status
	 * @param string $cart_item_key
	 * @param array  $values
	 * @param int    $quantity
	 *
	 * @return bool
	 */
	public function validate_qty_update( $status, $cart_item_key, $values, $quantity ) {
		$product_id = ( isset( $values['product_id'] ) ) ? $values['product_id'] : false;
		if ( false !== $product_id ) {
			$qty_status = Single_Product_Quantity::validate( $product_id, $quantity );
			if ( vsp_is_error( $qty_status ) ) {
				\VSP\Helper::vsp_error_to_wc_notice( $qty_status );
				return false;
			}
		}
		return $status;
	}


	/**
	 * Modify's Checkout URL. if single checkout has product.
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public function change_redirect_url( $url ) {
		if ( Helper::wc_has_single_checkout_in_cart() ) {
			if ( 'checkout' === Global_Settings::redirect_to() ) {
				return wc_get_checkout_url();
			}
		}
		return $url;
	}
}
