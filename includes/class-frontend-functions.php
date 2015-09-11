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

class WooCommerce_Single_Product_Checkout_Frontend extends WooCommerce_Single_Product_Checkout {

    
    
    /**
	 * Initialize the class and set its properties.
	 * @since      0.1
	 */
	public function __construct() {
        $this->is_in_cart_single_checkout = false;
        self::func()->add_filter('woocommerce_add_to_cart_validation', array($this,'check_cart'),99,3);
        self::func()->add_action('woocommerce_after_cart_item_quantity_update',array($this,'cart_qty_update'),99,3);
        self::func()->add_filter('woocommerce_add_to_cart_redirect',array($this,'change_redirect_url'),99);
    }
    //WC()->cart->set_quantity( $cart_item_key, $quantity, false );
    
    private function get_product_status($id){
        $status = get_post_meta( $id, WC_SPC_DBKEY.'product_status',  true );
        return $status;
    }
    
    private function get_product_max_qty($id){
        $max_qty = get_post_meta( $id, WC_SPC_DBKEY.'product_allowed_qty',  true );
        return $max_qty;
    }
    
    
    public function cart_qty_update($cart_item_key, $quantity, $old_quantity ){
        $exiting_product = WC()->cart->get_cart_item( $cart_item_key );
        //quantity
        $status = $this->get_product_status($exiting_product['product_id']);
        $max_qty = $this->get_product_max_qty($exiting_product['product_id']); 
        if(!empty($status)){ 
            
            if($quantity <= $max_qty ){
                return true;
            } else {
                WC()->cart->set_quantity( $cart_item_key, $old_quantity, false );
                $allowed = $max_qty - $old_quantity;
                $message = self::func()->get_error_message(WC_SPC_DBKEY.'product_with_qty_error');
                $message = str_replace(array('[cart_qty]','[allowed]'),array($old_quantity,$allowed),$message);
                wc_add_notice($message,'error');
                return false;
            }
        }
        return false;        
    }
    
    
    public function check_cart($status,$product_id,$qty){
        $status = $this->get_product_status($product_id);
        $max_qty = intval($this->get_product_max_qty($product_id));
        
        if(!empty($status)){
            // Check For Other Products
            if($this->check_cart_contents($product_id)){
                // Checks For Qty
                if($this->check_max_product_Qty($product_id,$qty,$max_qty)){
                    $this->is_in_cart_single_checkout = true;   
                    return true;
                }  else {
                    return false;
                }
            }  else {
                return false;
            }
        }  else {
            $productids = $this->get_product_ids_from_cart();
            if(! empty($productids)){
                foreach($productids as $id){
                    $status = $this->get_product_status($id); 
                    if(!empty($status)){
                        wc_add_notice(self::func()->get_error_message(WC_SPC_DBKEY.'single_product_other_error'),'error');
                        return false;
                    } else {
                        continue;
                    }
                } 
            } 
        }
        
        return true;
    }
     
    
    
    public function check_cart_contents($product_id){
        $productids = $this->get_product_ids_from_cart();
        if(! empty($productids)){
            if(in_array($product_id ,$productids)){
                return true;
            } else {
                
                wc_add_notice(self::func()->get_error_message(WC_SPC_DBKEY.'other_product_error'),'error');
                return false;
            }
        } 
        return true;
    }
    
    protected function check_max_product_Qty($product_id,$qty,$max_qty){
        $cart_qty = $this->get_product_qty_from_cart($product_id);
        $final_qty = $cart_qty + $qty; 
        
        if($final_qty <= $max_qty ){
            return true;
        } else {
            $allowed = $max_qty - $cart_qty;
            $message = self::func()->get_error_message(WC_SPC_DBKEY.'product_with_qty_error');
            $message = str_replace(array('[cart_qty]','[allowed]'),array($cart_qty,$allowed),$message);
            wc_add_notice($message,'error');
            return false;
        } 
    }
    
    
    protected function get_product_qty_from_cart($product_ID){
        global $woocommerce; 
        if(is_object($woocommerce->cart) && sizeof($woocommerce->cart->get_cart()) > 0){
             foreach($woocommerce->cart->get_cart() as $cart_item_key => $values){
                 $_product = $values['data'];
                 if($_product->id == $product_ID){
                    return $values['quantity'];
                 }
            }
        } 
        return false;
    }
    
    /**
     * Gets All Product ID's From Cart Array And Returns As Array
     */
    protected function get_product_ids_from_cart(){
        global $woocommerce;
        $product_ids = array();
        if(is_object($woocommerce->cart) && sizeof($woocommerce->cart->get_cart()) > 0){
             foreach($woocommerce->cart->get_cart() as $cart_item_key => $values){
                 $_product = $values['data'];
                 $product_ids[] = $_product->id;
            }
        }
        return $product_ids;
    }
    
    
    public function change_redirect_url($url){
        $redirect_to = self::func()->get_options(WC_SPC_DBKEY.'redirect_to');
        if($this->is_in_cart_single_checkout){
            if($redirect_to == 'checkout'){
                $new_url = WC()->cart->get_checkout_url();
            } else if($redirect_to == 'cart'){
                $new_url = WC()->cart->get_cart_url();
            }
            return $new_url;
        }
        return $url;
    }
}
