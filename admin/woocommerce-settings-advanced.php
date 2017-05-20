<?php
/**
 * WooCommerce General Settings
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce_Single_Product_Checkout_Settings' ) ) :

/**
 * WC_Admin_Settings_General
 */
class WooCommerce_Single_Product_Checkout_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'wc_scp';
		$this->label = __( 'WC-SCP', WC_SPC_LANG);

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
        add_filter( 'woocommerce_sections_'.$this->id,      array( $this, 'output_sections' ));
        add_filter( 'woocommerce_settings_'.$this->id,      array( $this, 'output_settings' )); 
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {
        $sections = array(
            ''                   => __( 'General', WC_SPC_LANG ),
            'message'     => __( 'Messages',WC_SPC_LANG ),
            'newsletter'     => __( 'Newsletter',WC_SPC_LANG ),
        );
        return $sections;
    }
    
    
    public function output_settings(){
        global $current_section;
        $settings = $this->get_settings( $current_section ); 
        if($current_section == 'newsletter'){
            $GLOBALS['hide_save_button'] = true;
        } else {
            WC_Admin_Settings::output_fields( $settings );
        }
    }    
    
    
	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings($section = null) {  
        if($section == null){
            $settings = array(
                array( 
                    'title' => '', 
                    'type' => 'title', 
                    'desc' => '', 
                    'id' => 'wc_scp_general_start' 
                ),

                array(
                    'title'    => __( 'Redirect User To', WC_SPC_LANG ),
                    'desc'     => __( 'Option To Redirect Use Checkout / Cart Page', WC_SPC_LANG ),
                    'id'       => WC_SPC_DBKEY.'redirect_to',
                    'css'      => 'min-width:350px;',
                    'class'    => 'wc-enhanced-select',
                    'default'  => 'cart',
                    'type'     => 'select',
                    'desc_tip' =>  true,
                    'options'  => array('checkout' => 'Checkout Page' , 'cart' => 'Cart Page')
                ),
                
                
                array(
                    'title'    => __( 'Global Min required Qty', WC_SPC_LANG ),
                    'desc'     => __( 'Set Global Minimum required Qty if not set in product', WC_SPC_LANG ),
                    'id'       => WC_SPC_DBKEY.'gmin_req_qty',
                    'css'      => 'min-width:350px;',
                    'class'    => '',
                    'default'  => 'cart',
                    'type'     => 'number',
                    'desc_tip' =>  true,

                ),
                
                array(
                    'title'    => __( 'Global Max Allowed Qty', WC_SPC_LANG ),
                    'desc'     => __( 'Set Global Max required Qty if not set in product', WC_SPC_LANG ),
                    'id'       => WC_SPC_DBKEY.'gmax_req_qty',
                    'css'      => 'min-width:350px;',
                    'class'    => '',
                    'default'  => 'cart',
                    'type'     => 'number',
                    'desc_tip' =>  true,

                ),
                array( 'type' => 'sectionend', 'id' => 'wc_scp_general_end'),
            );
        } else if($section == 'message'){
            $settings = array(
                array( 
                    'title' => '',
                    'type' => 'title', 
                    'desc' => '', 
                    'id' => 'wc_scp_message_start' 
                ),
                
                array(
                    'title'    => __( 'Added With Another Product', WC_SPC_LANG),
                    'id'       => WC_SPC_DBKEY.'other_product_error',
                    'default'  => __( 'This Product Is Sold Seperatly. Kindly Complete Your Existing Order.', WC_SPC_LANG),
                    'type'     => 'textarea',
                    'css'     => 'width:550px; height: 65px;',
                    'desc'    => 'Error Message When Single Checkout Product Added With Other Product',
                    'desc_tip'  => true,
                    'autoload' => false
                ),
                
                
                array(
                    'title'    => __( 'Product Without Min Required Qty Limit', WC_SPC_LANG),
                    'id'       => WC_SPC_DBKEY.'product_with_min_qty_error',
                    'default'  => __( 'Min Required Qty Not Reached [min_req]. You Entered Have [entered] Qty', WC_SPC_LANG),
                    'type'     => 'textarea',
                    'css'     => 'width:550px; height: 65px;',
                    'desc'    => 'Error Message When Product Order Qty Limit Reached / Exceeded <br/> 
                                  Use <code> [min_req] </code> to get min required qty for the product <br/>
                                  Use <code> [entered] </code> to get the quantity entered by the user',
                    'desc_tip'  => true,
                    'autoload' => false
                ),
                
                
                array(
                    'title'    => __( 'Product With Exceeded Qty Limit', WC_SPC_LANG),
                    'id'       => WC_SPC_DBKEY.'product_with_qty_error',
                    'default'  => __( 'Max Qty Reached. You Already Have [cart_qty] Qty in Cart You Can Add [allowed] More', WC_SPC_LANG),
                    'type'     => 'textarea',
                    'css'     => 'width:550px; height: 65px;',
                    'desc'    => 'Error Message When Product Order Qty Limit Reached / Exceeded <br/> 
                                  Use <code> [cart_qty]</code>  to get quantity from cart if exist <br/>
                                  Use <code> [allowed] </code> to get the total quantity allowed for order',
                    'desc_tip'  => true,
                    'autoload' => false
                ),
                
                array(
                    'title'    => __( 'Other Product With Single Checkout Product', WC_SPC_LANG),
                    'id'       => WC_SPC_DBKEY.'single_product_other_error',
                    'default'  => __( 'The Product In Cart Has To Be Ordered Seperatly So. Finish That Order First', WC_SPC_LANG),
                    'type'     => 'textarea',
                    'css'     => 'width:550px; height: 65px;',
                    'desc'    => 'Error Message When Normal Product Added To Cart When Single Checkout Product Exist In Cart ',
                    'desc_tip'  => true,
                    'autoload' => false
                ),
                
                
                
                array( 'type' => 'sectionend', 'id' => 'wc_scp_general_end'),
            );            
        } else if($section == 'newsletter'){
            $settings = '';
            include_once('settings-newsletter.php');
        }

		return $settings;
	}
 

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;
        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new WooCommerce_Single_Product_Checkout_Settings();