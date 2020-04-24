<?php

namespace SPCF_WC\Admin\Settings;

use SPCF_WC\Helper;
use VSP\Core\Abstracts\Plugin_Settings;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\SPCF_WC\Admin\Settings\Settings' ) ) {
	/**
	 * Class Settings
	 *
	 * @package SPCF_WC\Admin\Settings
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 */
	class Settings extends Plugin_Settings {
		/**
		 * Generates Settings & Other Menus
		 */
		protected function fields() {
			$this->settings( $this->builder->container( 'settings', __( 'Settings' ), 'wpoic-settings' ) );

			$this->builder->container( 'docs', __( 'Documentation' ), 'wpoic-book' )
				->container_class( 'wpo-text-success' )
				->href( 'https://wordpress.org/plugins/woocommerce-single-product-checkout/' )
				->attribute( 'target', '_blank' );

			$this->builder->container( 'sysinfo', __( 'System Info' ), ' wpoic-info ' )
				->callback( 'wponion_sysinfo' )
				->set_var( 'developer', 'varunsridharan23@gmail.com' );
		}

		/**
		 * Generates Settings Fields.
		 *
		 * @param \WPO\Container $builder
		 */
		protected function settings( $builder ) {
			$this->basic( $builder->container( 'basic', __( 'Basic' ), 'wpoic-gears' ) );
			$this->notices( $builder->container( 'error-notice', __( 'Validation Messages' ), 'wpoic-spell-check' ) );
		}

		/**
		 * Generates Basic Fields.
		 *
		 * @param \WPO\Container $builder
		 */
		protected function basic( $builder ) {
			$builder->select( 'redirect_to', __( 'Redirect User To' ) )
				->option( 'checkout', __( 'Checkout Page' ) )
				->option( 'cart', __( 'Cart Page' ) )
				->desc( __( 'Redirection Works When User Add A Product To Cart / Click AddToCart button.' ) );

			$builder->subheading( __( 'Global Minimum & Maximum Required Quantity Configuration' ) );

			$builder->field( 'number', 'min_qty', __( 'Minimum Required Quantity ?' ), array(
				'min'  => 1,
				'step' => 1,
			) )
				->desc_field( array(
					__( 'User will be able to purchase the only if they met the requirement.' ),
					__( 'If product does not have a defined Minimum quantity then gloabl values will be used. ' ),
				) );

			$builder->field( 'number', 'max_qty', __( 'Maximum Allowed Quantity ? ' ), array(
				'min'  => 1,
				'step' => 1,
			) )
				->desc_field( array(
					__( 'User will be able to purchase the only if they met the requirement.' ),
					__( 'If product does not have a defined Maximum quantity then gloabl values will be used. ' ),
				) );
		}

		/**
		 * Generates WP EDitor.
		 *
		 * @param \WPO\Container $builder
		 * @param string         $id
		 * @param string         $title
		 *
		 * @return \WPO\Fields\WP_Editor
		 */
		protected function wp_editor( $builder, $id, $title ) {
			return $builder->wp_editor( $id, $title )
				->settings( array(
					'wpautop'          => true,
					'media_buttons'    => false,
					'teeny'            => true,
					'textarea_rows'    => 1,
					'tinymce'          => array(
						'toolbar1' => 'bold,italic,underline,alignleft,aligncenter,alignright,strikethrough',
					),
					'quicktags'        => false,
					'drag_drop_upload' => false,
				) )
				->desc_field( Helper::template_desc_tags() );
		}

		/**
		 * Generates Notice Fields.
		 *
		 * @param \WPO\Container $builder
		 */
		protected function notices( $builder ) {
			$this->wp_editor( $builder, 'error_100', __( 'Adding Other Product Along With Single Checkout Product' ) )
				->field_default( '- ({sku}) {name} Product Is Sold Separately. Kindly Complete Your Existing Order.' )
				->desc( __( 'Error Message To Shown When A Product Configured With Single Product Checkout Added With Another Product already in cart.' ) );

			$this->wp_editor( $builder, 'error_101', __( 'Validation Message For Minimum Quantity.' ) )
				->field_default( 'Minimum Required is ({min_qty}) Quantity | You have requested for [cart_qty] Quantity' )
				->desc( __( 'Error Message To Shown When A Product Does Not Met With Minimum Required Quantity.' ) );

			$this->wp_editor( $builder, 'error_102', __( 'Validation Message For Maximum Quantity.' ) )
				->field_default( 'Maximum Allowed Quantity is ({max_qty}) Reached. You Can\'t Add More' )
				->desc( __( 'Error Message To Shown When A Product Exceeds Maximum Allowed Quantity.' ) );

			$this->wp_editor( $builder, 'error_103', __( 'Added With Single Product Checkout Configured Product' ) )
				->field_default( 'The Product In Cart Has To Be Ordered Separately So. Finish That Order First' )
				->desc( __( 'Error Message To Shown When A Non Single Product Checkout Product Added To Cart when single checkout product already in cart.' ) );

		}

	}

}
