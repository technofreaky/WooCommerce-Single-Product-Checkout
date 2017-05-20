<?php
/**
 * Plugin Name:       WooCommerce Single Product Checkout
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-single-product-checkout/
 * Description:       Allows users to check out selected product to be ordered in a separate order.
 * Version:           0.4
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * Text Domain:       woocommerce-single-product-checkout
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * GitHub Plugin URI: https://github.com/technofreaky/WooCommerce-Single-Product-Checkout
 */

if ( ! defined( 'WPINC' ) ) { die; }
 
class WooCommerce_Single_Product_Checkout {
	/**
	 * @var string
	 */
	public $version = '0.4';

	/**
	 * @var WooCommerce The single instance of the class
	 * @since 2.1
	 */
	protected static $_instance = null;
    
    protected static $functions = null;

    /**
     * Creates or returns an instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Class Constructor
     */
    public function __construct() {
        $this->define_constant();
		
		
        
        $this->load_required_files();
        $this->init_class();
        $this->func()->add_action( 'init', array( $this, 'init' ));
    }
    
    /**
     * Triggers When INIT Action Called
     */
    public function init(){
        $this->func()->add_action('plugins_loaded', array( $this, 'after_plugins_loaded' ));
        $this->func()->add_filter('load_textdomain_mofile',  array( $this, 'load_plugin_mo_files' ), 10, 2);
    }
    
    /**
     * Loads Required Plugins For Plugin
     */
    private function load_required_files(){
       $this->load_files(WC_SPC_PATH.'includes/common-class-*.php');
        
       if($this->is_request('admin')){
           $this->load_files(WC_SPC_PATH.'admin/class-*.php');
       } 
        
       if($this->is_request('frontend')){
           $this->load_files(WC_SPC_PATH.'includes/class-*.php');
       } 

    }
    
    /**
     * Inits loaded Class
     */
    private function init_class(){
        self::$functions = new WooCommerce_Single_Product_Checkout_Functions;
        
        if($this->is_request('admin')){
            $this->admin = new WooCommerce_Single_Product_Checkout_Admin;
        }
        
        if($this->is_request('frontend')){
            $this->frontend = new WooCommerce_Single_Product_Checkout_Frontend;
        }
    }
    
    
    protected function func(){
        return self::$functions;
    }
    

    protected function load_files($path,$type = 'require'){
        foreach( glob( $path ) as $files ){

            if($type == 'require'){
                require_once( $files );
            } else if($type == 'include'){
                include_once( $files );
            }
            
        } 
    }
    
    /**
     * Set Plugin Text Domain
     */
    public function after_plugins_loaded(){
        load_plugin_textdomain(WC_SPC_LANG, false, WC_SPC_LANGUAGE_PATH );
    }
    
    /**
     * load translated mo file based on wp settings
     */
    public function load_plugin_mo_files($mofile, $domain) {
        if (WC_SPC_LANG === $domain)
            return WC_SPC_LANGUAGE_PATH.'/'.get_locale().'.mo';

        return $mofile;
    }
    
    /**
     * Define Required Constant
     */
    private function define_constant(){
        $this->define('WC_SPC','WooCommerce Single Product Checkout'); # Plugin Name
        $this->define('WC_SPC_SLUG','wc-spc'); # Plugin Slug
        $this->define('WC_SPC_DBKEY','wc_spc_'); # Plugin Slug
        $this->define('WC_SPC_PATH',plugin_dir_path( __FILE__ )); # Plugin DIR 
        $this->define('WC_SPC_LANGUAGE_PATH',WC_SPC_PATH.'languages');
        $this->define('WC_SPC_LANG','woocommerce-single-product-checkout'); #plugin lang Domain
        $this->define('WC_SPC_URL',plugins_url('', __FILE__ )); 
        $this->define('WC_SPC_FILE',plugin_basename( __FILE__ ));
    }
    
    /**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
    protected function define($key,$value){
        if(!defined($key)){
            define($key,$value);
        }
    }
    
        
    protected function __($string){
        return __($string,WC_SPC_LANG);
    }
    /**
     * Adds Filter / Action
     */
    protected function add_filter_action($key,$value,$type = 'action' , $priority = 10, $variable = 1){
        if($type == 'action'){
            add_action($key,$value,$priority,$variable);        
        } else if($type == 'filter'){
            add_filter($key,$value,$priority,$variable);        
        } else {
            return false;
        }
    }

    
	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
    
    
    
}



if(! function_exists('is_plugin_active')){ require_once( ABSPATH . '/wp-admin/includes/plugin.php' ); }
    
    if (is_plugin_active( 'woocommerce/woocommerce.php' )) {
        
        if(! function_exists( 'WC_SPC' )){
            function WC_SPC(){ return WooCommerce_Single_Product_Checkout::get_instance(); }
        }

        $GLOBALS['woocommerce'] = WC_SPC(); 

    } else {
        add_action( 'admin_notices', 'wc_spc_activate_failed_notice' );
    } 
  

function wc_spc_activate_failed_notice() {
	echo '<div class="error"><p> '.__('<strong> <i> WooCommerce Single Product Checkout </i> </strong> Requires',WC_SPC_LANG).'<a href="'.admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce').'"> <strong>'.__(' <u>Woocommerce</u>',WC_SPC_LANG).'</strong>  </a> '.__(' To Be Installed And Activated',WC_SPC_LANG).' </p></div>';
}