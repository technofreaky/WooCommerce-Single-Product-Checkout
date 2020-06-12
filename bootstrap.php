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
			$options                  = array(
				'name'         => SPCF_WC_NAME,
				'file'         => SPCF_WC_FILE,
				'version'      => SPCF_WC_VERSION,
				'slug'         => 'single-product-checkout-for-woocommerce',
				'hook_slug'    => 'spcf_wc',
				'db_slug'      => 'spcf_wc',
				'addons'       => false,
				'logging'      => false,
				'system_tools' => false,
				'localizer'    => false,
				'autoloader'   => array(
					'namespace' => 'SPCF_WC',
					'base_path' => $this->plugin_path( 'includes/', SPCF_WC_FILE ),
					'options'   => array(
						'classmap' => $this->plugin_path( 'classmaps.php', SPCF_WC_FILE ),
					),
				),
			);
			$options['settings_page'] = array(
				'option_name'    => '_spcf_wc',
				'framework_desc' => __( 'Allows Shop Owners To Set Product To Be Sold Separately', 'spcfwc' ),
				'theme'          => 'wp',
				'ajax'           => true,
				'search'         => false,
				'menu'           => array(
					'page_title' => SPCF_WC_NAME,
					'menu_title' => __( 'Single Product Checkout', 'spcfwc' ),
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

			wponion_plugin_links( $this->file() )
				->action_link_before( 'settings', __( '⚙️ Settings', 'spcfwc' ), admin_url( 'admin.php?page=spcf-wc' ) )
				->action_link_after( 'sysinfo', __( 'ℹ️ System Info', 'spcfwc' ), admin_url( 'admin.php?page=spcf-wc&container-id=sysinfo' ) )
				->row_link( __( '📚 F.A.Q', 'spcfwc' ), 'https://wordpress.org/plugins/woocommerce-single-product-checkout/faq' )
				->row_link( __( '📦 View On Github', 'spcfwc' ), 'https://github.com/varunsridharan/single-product-checkout-for-woocommerce' )
				->row_link( __( '📝 Report An Issue', 'spcfwc' ), 'https://github.com/varunsridharan/single-product-checkout-for-woocommerce/issues' )
				->row_link( __( '💁🏻 Donate', 'wc-product-subtitle', 'spcfwc' ), 'https://paypal.me/varunsridharan' );
		}
	}
}
