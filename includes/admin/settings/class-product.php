<?php

namespace SPCF_WC\Admin\Settings;

defined( 'ABSPATH' ) || exit;

use VSP\Base;

/**
 * Class Product
 *
 * @package SPCF_WC\Admin\Settings
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
class Product extends Base {
	/**
	 * Generates View For Product Metabox.
	 */
	public function __construct() {
		$args = array( 'option_name' => '_spcf_wc' );

		if ( wponion_is_version( '1.5', '>=' ) ) {
			wponion_wc_product( $args, array( $this, 'fields' ) );
		} else {
			wponion_wc_product( $args, $this->fields() );
		}
	}

	/**
	 * Returns Fields.
	 *
	 * @return \WPO\Builder
	 * @since 1.1
	 */
	public function fields() {
		$builder = wponion_builder();

		$c1 = $builder->container( 'spcf_wc', __( 'Single Product Checkout', 'spcfwc' ) );
		$c1->subheading( __( 'Product Level Configuration', 'spcfwc' ) );
		$c1->select( 'is_enabled', __( 'Enable Single Product Checkout ?', 'spcfwc' ) )
			->option( 'no', __( 'No', 'spcfwc' ) )
			->option( 'yes', __( 'Yes', 'spcfwc' ) )
			->desc_field( 'if default selected then product types that are enabled in settings will be used. ' )
			->desc( __( 'if enabled. then users will have to purchase this product seperatly', 'spcfwc' ) );
		$c1->add_field( 'number', 'min_qty', __( 'Minimum Required Quantity ?', 'spcfwc' ), array(
			'min'  => 1,
			'step' => 1,
		) )->style( 'width:25%;' )->field_default( 0 )->desc_field( array(
			__( 'User will be able to purchase the only if they met the requirement.', 'spcfwc' ),
			__( 'If product does not have a defined Minimum quantity then gloabl values will be used. ', 'spcfwc' ),
		) );
		$c1->add_field( 'number', 'max_qty', __( 'Maximum Allowed Quantity ? ', 'spcfwc' ), array(
			'min'  => 1,
			'step' => 1,
		) )->style( 'width:25%;' )->field_default( 0 )->desc_field( array(
			__( 'User will be able to purchase the only if they met the requirement.', 'spcfwc' ),
			__( 'If product does not have a defined Maximum quantity then gloabl values will be used. ', 'spcfwc' ),
		) );
		return $builder;
	}
}
