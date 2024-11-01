<?php 

/*
Plugin Name: Motif Woocommerce Quick Product Inquiry
Plugin URI: http://Motifdev.com
Description: To quick inquiry of your woocommerce products.
Author: Motif Solutions
Version: 1.0.1
textdomain: product_inquiry
Author URI: http://motif-solution.com/
Support: http://support@motifsolution.com
Developer: Motif Solutions

*/


//If not user for security purpose
if ( ! defined( 'ABSPATH' ) ) exit; 

//Exit if woocommerce not installed
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function motif_check_if_woocommerce_activ() {
		// Deactivate the plugin
		deactivate_plugins(__FILE__);
		$error_message = __('<div class="error notice"><p>This plugin requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> plugin to be installed and active!</p></div>', 'fme_faq_woocommerce');
		die($error_message);
	}

	add_action( 'motif_check_if_woocommerce_activ', 'my_admin_notice' );
}


// Main class motif product inquiry
class motif_product_inquiry_main_class {

	//constructor main class
	public function __construct() {
		
		$this->module_constant();

		add_action( 'wp_loaded', array( $this, 'motif_scripts_main' ) );

		if( is_admin() ) {

			require_once( motif_product_inquiry_plguin_dir.'motif-admin-pinquiry.php');
		
		} else {
		
			require_once( motif_product_inquiry_plguin_dir.'motif-front-pinquiry.php');
		
		}

    	add_action( 'wp_ajax_woo_inquiry_action_motif', array($this,'inquiry_motif_callback' ));
		
		add_action( 'wp_ajax_nopriv_woo_inquiry_action_motif', array($this,'inquiry_motif_callback' ));

		add_action( 'wp_loaded', array( $this,'motif_inquiry_settings'));
		
	}

	// motif inquiry settings
	public function motif_inquiry_settings() {

	 	//single page btn text
	 	$Singlebtntext = get_option('_motif_inq_btn_text');	
	 	if($Singlebtntext !='') {
	 		$Singlebtntext = get_option('_motif_inq_btn_text');	
	 	}else {
	 		$Singlebtntext = "Quick Product Inquiry"; 
	 	}

	 	//single page btn color
	 	$singlebtncolor = get_option('_motif_inq_btn_color');	
	 	if($singlebtncolor !='') {
	 		$singlebtncolor = get_option('_motif_inq_btn_color');	
	 	}else {
	 		$singlebtncolor = "#fff"; 
	 	}

	 	//popuptitle
	 	$popuptitle = get_option('_motif_inq_popup_title');	
	 	if($popuptitle !='') {
	 		$popuptitle = get_option('_motif_inq_popup_title');	
	 	}else {
	 		$popuptitle = "Product Inquiry"; 
	 	}

	 	//btnsubmitcolor
	 	$submitbtncolor = get_option('_motif_inq_btnsc_color');	
	 	if($submitbtncolor !='') {
	 		$submitbtncolor = get_option('_motif_inq_btnsc_color');	
	 	}else {
	 		$submitbtncolor = "#000"; 
	 	}

	   	//background submit color
	 	$btnsumbitbgcolor = get_option('_motif_inq_btnbsc_color');	
	 	if($btnsumbitbgcolor !='') {
	 		$btnsumbitbgcolor = get_option('_motif_inq_btnbsc_color');	
	 	}else {
	 		$btnsumbitbgcolor = "#34d960"; 
	 	}


		//send me notification copy/customer
	 	$notifyuser = get_option('_motif_sent_notify');	
	 	if($notifyuser !='') {
	 		$notifyuser = get_option('_motif_sent_notify');	
	 	}else {
	 		$notifyuser = "0"; 
	 	}


	 	$mofit_support_settings = array();
 		
 		$mofit_support_settings['Singlebtntext'] = $Singlebtntext; 
 		$mofit_support_settings['singlebtncolor'] = $singlebtncolor; 
 		$mofit_support_settings['popuptitle'] = $popuptitle; 
 		$mofit_support_settings['submitbtncolor'] = $submitbtncolor; 
 		$mofit_support_settings['btnsumbitbgcolor'] = $btnsumbitbgcolor; 

 		$mofit_support_settings['notifyuser'] = $notifyuser; 
 		        
 		return $mofit_support_settings;
	}

	// send inquiry email function
	public function inquiry_motif_callback () {
		
		if(isset($_POST['condition']) && $_POST['condition'] == "motif_inquiry_wocoomerce") {
			
			$motif_username_i = sanitize_text_field($_POST['motif_username_i']);
			$motif_email_i = sanitize_email($_POST['motif_email_i']) ;
			$motif_subject_i = sanitize_text_field($_POST['motif_subject_i']);
			$motif_question_i = sanitize_text_field($_POST['motif_question_i']);
			$motif_pinquiry = sanitize_text_field($_POST['motif_pinquiry']);
		
			$admin_emailsup = get_option('admin_email');
			$to = $admin_emailsup;
			$subject = $motif_subject_i;

			$message = "
			<html>
			<head>
			<title>Motif Inquiry Email (Product Inquiry)</title>
			</head>
			<body>
			<p>This email contains HTML Tags!</p>
			<table>
			<tr>
			<th>User Information</th>
			<th>User Message</th>
			</tr>
			<tr>
			<td>Dear Site Admin a user $motif_username_i With email with ($motif_email_i)</td>
			</tr>
			<tr>
			<td>Have some query regarding your shop product called <strong>$motif_pinquiry</strong> , Message is $motif_question_i</td>
			</tr>
			</table>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";

			// More headers
			$headers .= 'From: <'.$motif_email_i.'>' . "\r\n";

			mail($to,$subject,$message,$headers);
			
		}

		die();
	}

	//module constant 
	public function module_constant() {

		if ( !defined( 'motif_product_inquiry_url' ) )
	    define( 'motif_product_inquiry_url', plugin_dir_url( __FILE__ ) );

	    if ( !defined( 'motif_product_inquiry_basename' ) )
	    define( 'motif_product_inquiry_basename', plugin_basename( __FILE__ ) );

	    if ( ! defined( 'motif_product_inquiry_plguin_dir' ) )
	    define( 'motif_product_inquiry_plguin_dir', plugin_dir_path( __FILE__ ) );
	}

	//enqueue the scripts and style
	public function motif_scripts_main() { 

		wp_enqueue_script('jquery');
		
		wp_enqueue_style( 'motif-back', plugins_url( '/Asset/back-style.css', __FILE__ ));
		
		if ( function_exists( 'load_plugin_textdomain' ) )
		load_plugin_textdomain( 'motif_product_inquiry', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
	} 	

}
new motif_product_inquiry_main_class();