<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/woocommerce-role-based-price/
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Single_Product_Checkout_Admin extends WooCommerce_Single_Product_Checkout {

    /**
	 * Initialize the class and set its properties.
	 * @since      0.1
	 */
	public function __construct() {
        self::func()->add_filter( 'plugin_row_meta', array($this, 'plugin_row_links' ), 10, 2 );
        self::func()->add_action( 'admin_init', array( $this, 'admin_init' ));
        self::func()->add_action( 'plugins_loaded', array( $this, 'init' ) );
        self::func()->add_filter( 'woocommerce_get_settings_pages',  array($this,'settings_page') ); 

	}

    /**
     * Inits Admin Sttings
     */
    public function admin_init(){
       new WooCommerce_Single_Product_Checkout_Product_Settings;
    }
 
    
	/**
	 * Add a new integration to WooCommerce.
	 */
	public function settings_page( $integrations ) {
        foreach(glob(WC_SPC_PATH.'admin/woocommerce-settings*.php' ) as $file){
            $integrations[] = require_once($file);
        }
		return $integrations;
	}
    
    
    /**
     * Gets Current Screen ID from wordpress
     * @return string [Current Screen ID]
     */
    public function current_screen(){
       $screen =  get_current_screen();
       return $screen->id;
    }
    
    /**
     * Returns Predefined Screen IDS
     * @return [Array] 
     */
    public function get_screen_ids(){
        $screen_ids = array();
        $screen_ids[] = 'edit-product';
        $screen_ids[] = 'product';
        return $screen_ids;
    }
    
    
    /**
	 * Adds Some Plugin Options
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 * @since 0.11
	 * @return array
	 */
	public function plugin_row_links( $plugin_meta, $plugin_file ) {
		if ( WC_SPC_FILE == $plugin_file ) {
            $url = admin_url('admin.php?page=wc-settings&tab=wc_scp');
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', $url, $this->__('Settings') );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'https://github.com/technofreaky/WooCommerce-Single-Product-Checkout', $this->__('Report Issue') );
            $plugin_meta[] = sprintf('&hearts; <a href="%s">%s</a>', 'http://paypal.me/varunsridharan23', $this->__('Donate') );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'http://varunsridharan.in/plugin-support/', $this->__('Contact Author') );
		}
		return $plugin_meta;
	}	    
}