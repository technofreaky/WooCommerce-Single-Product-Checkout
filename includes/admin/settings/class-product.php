<?php

namespace SPCF_WC\Admin\Settings;

defined( 'ABSPATH' ) || exit;

use VSP\Base;

if ( ! class_exists( '\SPCF_WC\Admin\Settings\Product' ) ) {
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
			$builder = wponion_builder();

			$c1 = $builder->container( 'spcf_wc', __( 'Single Product Checkout' ) );
			$c1->subheading( __( 'Product Level Configuration' ) );
			$c1->select( 'is_enabled', __( 'Enable Single Product Checkout ?' ) )
				->option( 'yes', __( 'Yes' ) )
				->option( 'no', __( 'No' ) )
				//->option( 'default', __( 'Default' ) )
				->desc_field( 'if default selected then product types that are enabled in settings will be used. ' )
				->desc( __( 'if enabled. then users will have to purchase this product seperatly' ) );
			$c1->field( 'number', 'min_qty', __( 'Minimum Required Quantity ?' ), array(
				'min'  => 1,
				'step' => 1,
			) )
				->style( 'width:25%;' )
				->field_default( 0 )
				->desc_field( array(
					__( 'User will be able to purchase the only if they met the requirement.' ),
					__( 'If product does not have a defined Minimum quantity then gloabl values will be used. ' ),
				) );
			$c1->field( 'number', 'max_qty', __( 'Maximum Allowed Quantity ? ' ), array(
				'min'  => 1,
				'step' => 1,
			) )
				->style( 'width:25%;' )
				->field_default( 0 )
				->desc_field( array(
					__( 'User will be able to purchase the only if they met the requirement.' ),
					__( 'If product does not have a defined Maximum quantity then gloabl values will be used. ' ),
				) );
			wponion_wc_product( array( 'option_name' => '_spcf_wc' ), $builder );
		}
	}
}
