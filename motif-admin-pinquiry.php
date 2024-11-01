<?php  if ( ! defined( 'ABSPATH' ) ) exit;  

//Admin class mofit product inquiry
class motif_product_inquiry_admin_class extends motif_product_inquiry_main_class {
	
	// constructor admin class
	public function __construct() {

		add_action('admin_menu', array($this, 'motif_woo_qty_support_page'));

		add_action('wp_loaded', array($this, 'support_motif_scripts'));

		add_action( 'wp_ajax_support_motif_contact', array($this,'support_motif_callback' ));
		add_action( 'wp_ajax_nopriv_support_motif_contact', array($this,'support_motif_callback' ));

		add_action('admin_init', array($this,'display_event_panel_fields'));

	}

	// adding menu and submenu pages
	public function motif_woo_qty_support_page() {

		add_menu_page(
            'Mofit Settings','Product Enquiry','manage_options','motif-support',
	            array($this,'motif_setting_callback'),
            		motif_product_inquiry_url.'img/motif-menu-cion.png', 10
        );
        
        add_submenu_page('motif-support', 'Motif Support', 'Motif Support', 'manage_options', 'motif-settings', array($this,'motif_woo_qty_support_callback' ));
	}

	// motif settings api
	public function motif_setting_callback() { ?>
	
			<div class="wrap main-setting">
		    <h2>Woocommerce Product Inquiry Setting Options</h2>

		    <form method="post" action="options.php">
			        <?php
			            settings_fields("section");
			            do_settings_sections("woocommerce_product_inq_setting_options");    
			            submit_button(); 
			        ?>          
			</form>
		   
			</div>
	
	<?php }


    //SIngle page button text
    public function button_text_motif_function() {
		?>
	    	<input  type="text" name="_motif_inq_btn_text" value="<?php echo get_option('_motif_inq_btn_text'); ?>" />
	    	<p class="description">If you want to change the button text of single page kindly enter and save.</p>
	    <?php
	}

	//SIngle page button color
    public function button_color_motif_function() {
		?>
	    	<input  type="color" name="_motif_inq_btn_color" value="<?php echo get_option('_motif_inq_btn_color'); ?>" />
	    	<p class="description">If you want to change the button color of single page kindly choose</p>
	    <?php
	}

	 //Inquiry popup title
    public function inquiry_popup_motif_function() {
		?>
	    	<input  type="text" name="_motif_inq_popup_title" value="<?php echo get_option('_motif_inq_popup_title'); ?>" />
	    	<p class="description">Inquiry Popup top Title</p>
	    <?php
	}
	//popup submit button color
    public function color_motif_submit_function() {
		?>
	    	<input  type="color" name="_motif_inq_btnsc_color" value="<?php echo get_option('_motif_inq_btnsc_color'); ?>" />
	    	<p class="description">Send Inquiry Button Color</p>
	    <?php
	}
	//popup submit button background color
    public function color_motif_submitbg_function() {
		?>
	    	<input  type="color" name="_motif_inq_btnbsc_color" value="<?php echo get_option('_motif_inq_btnbsc_color'); ?>" />
	    	<p class="description">Send Inquiry Button Background Color</p>
	    <?php
	}

	//Send Me Notification Cliend
    public function _motif_sendmecopynotifcation_function() {
		?>
	    	<input <?php checked( '1', get_option( '_motif_sent_notify' ) ); ?> type="checkbox" value="1" name="_motif_sent_notify" value="<?php echo get_option('_motif_sent_notify'); ?>" />
	    	<p class="description">Unable Inquiry Notificaiton to user</p>
	    <?php
	}

	//function for adding fields in setting panal also incluede all fields function in this
    public function display_event_panel_fields() {

		add_settings_section('section', 'All General Settings', null, 'woocommerce_product_inq_setting_options');

		add_settings_field('_motif_inq_btn_text', 'Button Text', array($this,'button_text_motif_function'), 'woocommerce_product_inq_setting_options', 'section');
		add_settings_field('_motif_inq_btn_color', 'Button Color', array($this,'button_color_motif_function'), 'woocommerce_product_inq_setting_options', 'section');
		add_settings_field('_motif_inq_popup_title', 'Inquiry Popup Title', array($this,'inquiry_popup_motif_function'), 'woocommerce_product_inq_setting_options', 'section');
		add_settings_field('_motif_inq_btnsc_color', 'Inquiry Popup Button Color', array($this,'color_motif_submit_function'), 'woocommerce_product_inq_setting_options', 'section');
		add_settings_field('_motif_inq_btnbsc_color', 'Inquiry Popup Button Backgournd Color', array($this,'color_motif_submitbg_function'), 'woocommerce_product_inq_setting_options', 'section');
		add_settings_field('_motif_sent_notify', 'Inquiry Notification To User', array($this,'_motif_sendmecopynotifcation_function'), 'woocommerce_product_inq_setting_options', 'section');
		
		register_setting('section', '_motif_inq_btn_text');
		register_setting('section', '_motif_inq_btn_color');
	   	register_setting('section', '_motif_inq_popup_title');
	   	register_setting('section', '_motif_inq_btnsc_color');
	   	register_setting('section', '_motif_inq_btnbsc_color');
	   	register_setting('section', '_motif_sent_notify');
	   	
	}


	// motif support
	public function motif_woo_qty_support_callback() { ?>
         
        <div class="wrap motif-support">
			
			<div class="motif_pageTitle  ">
        		<h1 class="pageTitle-heading">Welcome to Motif Support</h1><p class="pageTitle-intro">We’re here to help.</p>
        	</div>

			<div class="about-text">Get additional customization coverage and support from Motif, we are the people who know your Motif products best. Find out if your product is still in issue or learn more about purchasing Motif products. 
			</div>
			
			
			<div class="motif-support-form">
				
				<h5 id="motif_sup_success">Your message has been successfully sent. We will contact you very soon!</h5>

				<form id="motif-support-form" class="cf">
				  <div class="half left cf">
				    <input type="text" id="input-name" placeholder="Name">
				    <input data-parsley-required type="email" id="input-email" placeholder="Email address">
				    <input type="text" id="input-subject" placeholder="Subject">
				  </div>
				  <div class="half right cf">
				    <textarea data-parsley-required name="message" type="text" id="input-message" placeholder="Message"></textarea>
				  </div>  
				  <input type="button" id="input-submit" value="Submit" onclick="motifupport()">
				</form>

				<div class="motif-socials">
					<ul class="motif-social-left">
						<li>
							<a target="_blank" href="">
								<img src="<?php echo motif_woo_qty_field_url.'img/motif-fb.png'; ?>">
							</a>
						</li>
						<li>
							<a target="_blank" href="">
								<img src="<?php echo motif_woo_qty_field_url.'img/motif-google.png'; ?>">
							</a>
						</li>
						<li>
							<a target="_blank" href="">
								<img src="<?php echo motif_woo_qty_field_url.'img/motif-logo.png'; ?>">
							</a>
						</li>
						<li>
							<a target="_blank" href="">
								<img src="<?php echo motif_woo_qty_field_url.'img/motif-twi.png'; ?>">
							</a>
						</li>
						<li>
							<a target="_blank" href="">
								<img src="<?php echo motif_woo_qty_field_url.'img/motif-link.png'; ?>">
							</a>
						</li>
					</ul>
				</div>

			</div>
			
		</div>
    	
    	<script type="text/javascript">
			
			function motifupport() { 
				
				jQuery('#motif-support-form').parsley().validate();	
				
				var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";

				var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
				
				var condition = 'motif_support_contact';
				var motif_username = jQuery('#input-name').val();
				var motif_useremail = jQuery('#input-email').val();
				var motif_message = jQuery('#input-message').val();
				var motif_subject = jQuery('#input-subject').val();
			
				if(motif_username == '' && motif_useremail == '' && motif_message == '' && motif_subject == '') {
					return false;
				}else if (motif_message == '') { 
					return false;
				}else if (!pattern.test(motif_useremail)) {
					return false;
				}else {

					jQuery.ajax({
						url : ajaxurl,
						type : 'post',
						data : {
							action : 'support_motif_contact',
							condition : condition,
							motif_username : motif_username,
							motif_useremail : motif_useremail,
							motif_message : motif_message,
							motif_subject : motif_subject,		

						},
						success : function(response) {
							jQuery('#motif_sup_success').show().delay(3000).fadeOut();
							jQuery('#motif-support-form').each(function() {
								this.reset(); 
							});
						}
					});
				}
			}

		</script>


    <?php }

    // motif support callback email function
    public function support_motif_callback () {
		
		if(isset($_POST['condition']) && $_POST['condition'] == "motif_support_contact") {

			$suppextemail = $_POST['motif_useremail'];
			$support_subject = $_POST['motif_subject'];
			$support_message = $_POST['motif_message'];
			$suppextfname = $_POST['motif_username'];
		
			$to = "Motifdev@gmail.com";
			$subject = $support_subject;
			
			$admin_emailsup = get_option('admin_email');

			$message = "
			<html>
			<head>
			<title>Motif Support Email (Product Inquiry)</title>
			</head>
			<body>
			<p>This email contains HTML Tags!</p>
			<table>
			<tr>
			<th>User Information</th>
			<th>Message</th>
			</tr>
			<tr>
			<td>Dear Motif Admin a user name with $suppextfname and email with ($suppextemail)</td>
			<td>Have some query regarding Porduct inquiry Woo extension, Message is $support_message</td>
			</tr>
			</table>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";

			// More headers
			$headers .= 'From: <'.$admin_emailsup.'>' . "\r\n";

			mail($to,$subject,$message,$headers);

		}

		die();
	}


	//admin init function
	public function support_motif_scripts() { 
		
		wp_enqueue_script('jquery');

		wp_enqueue_script('parsley-js', plugins_url( 'Asset/parsley.min.js', __FILE__ ), false );

		wp_enqueue_style('parsley-css', plugins_url( 'Asset/parsley.css', __FILE__ ), false );

	} 


}
new motif_product_inquiry_admin_class();





