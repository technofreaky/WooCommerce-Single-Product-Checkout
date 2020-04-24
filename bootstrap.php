<?php

defined( 'ABSPATH' ) || exit;

use VSP\Framework;

if ( ! class_exists( 'Single_Product_Checkout_For_WC' ) ) {
	/**
	 * Class Single_Product_Checkout_For_WC
	 *
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 */
	final class Single_Product_Checkout_For_WC extends Framework {
		/**
		 * Single_Product_Checkout_For_WC constructor.
		 *
		 * @throws \Exception
		 */
		public function __construct() {
			$this->name      = SPCF_WC_NAME;
			$this->file      = SPCF_WC_FILE;
			$this->version   = SPCF_WC_VERSION;
			$this->slug      = 'single-product-checkout-for-woocommerce';
			$this->hook_slug = 'spcf_wc';
			$this->db_slug   = 'spcf_wc';

			$options                  = array(
				'addons'       => false,
				'logging'      => false,
				'system_tools' => false,
				'localizer'    => false,
				'autoloader'   => array(
					'namespace' => 'SPCF_WC',
					'base_path' => $this->plugin_path( 'includes/' ),
					'options'   => array(
						'classmap' => $this->plugin_path( 'classmaps.php' ),
					),
				),
			);
			$options['settings_page'] = array(
				'option_name'    => '_spcf_wc',
				'framework_desc' => __( 'Allows Shop Owners To Set Product To Be Sold Separately' ),
				'theme'          => 'wp',
				'ajax'           => true,
				'search'         => false,
				'menu'           => array(
					'page_title' => $this->plugin_name(),
					'menu_title' => __( 'Single Product Checkout' ),
					'submenu'    => 'woocommerce',
					'menu_slug'  => 'spcf-wc',
				),
			);
			parent::__construct( $options );
		}

		/**
		 * Settings Before Init.
		 *
		 * @uses \SPCF_WC\Admin\Settings\Settings
		 */
		public function settings_init_before() {
			$this->_instance( '\SPCF_WC\Admin\Settings\Settings' );
		}

		/**
		 * Inits All Class Instance.
		 */
		public function init_class() {
			$this->_instance( '\SPCF_WC\Handler' );
		}

		/**
		 * Inits All Required Classes On The Admin Side.
		 */
		public function admin_init() {
			$this->_instance( '\SPCF_WC\Admin\Settings\Product' );
		}
	}
}