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
        self::func()->add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ),99);
        self::func()->add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
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
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() { 
        if(in_array($this->current_screen() , $this->get_screen_ids())) {
            wp_enqueue_style(WC_SPC_SLUG.'_core_style',plugins_url('css/style.css',__FILE__) , array(), $this->version, 'all' );  
        }
	}
	
    
    /**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
        if(in_array($this->current_screen() , $this->get_screen_ids())) {
            wp_enqueue_script(WC_SPC_SLUG.'_core_script', plugins_url('js/script.js',__FILE__), array('jquery'), $this->version, false ); 
        }
 
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
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', $this->__('Settings') );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', $this->__('F.A.Q') );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', 'View On Github' );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', $this->__('Report Issue') );
            $plugin_meta[] = sprintf('&hearts; <a href="%s">%s</a>', '#', $this->__('Donate') );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'http://varunsridharan.in/plugin-support/', $this->__('Contact Author') );
		}
		return $plugin_meta;
	}	    
}

?>