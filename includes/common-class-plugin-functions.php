<?php
/**
 * functionality of the plugin.
 *
 * @link       @TODO
 * @since      1.0
 *
 * @package    @TODO
 * @subpackage @TODO
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Single_Product_Checkout_Functions {

    
    public function add_action($key,$value,$priority = 10,$variable = 1){
        add_action($key,$value,$priority, $variable) ;
    }
    
    public function add_filter($key,$value,$priority = 10,$variable = 1){
        add_filter($key,$value,$priority,$variable);          
    }
    
        
    public function get_error_message($id){
        $message = $this->get_options($id); 
        return $message;
    }
    
    public function get_options($option_name){
        return get_option($option_name);
    }
}
?>