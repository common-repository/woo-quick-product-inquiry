<?php  if ( ! defined( 'ABSPATH' ) ) exit;  

//Front class mofit product inquiry
class motif_product_inquiry_front_class extends motif_product_inquiry_main_class {
	
    //front class constructor
    public function __construct() {

		add_action( 'wp_loaded', array( $this, 'motif_scripts_front' ) );

    	add_action( 'woocommerce_before_add_to_cart_form', array($this,'add_content_after_addtocart_button_func' ));

    	add_action( 'wp_ajax_woo_inquiry_action_motif', array($this,'inquiry_motif_callback' ));
		add_action( 'wp_ajax_nopriv_woo_inquiry_action_motif', array($this,'inquiry_motif_callback' ));
		
	}

	//frontend btn with popup action
	public function add_content_after_addtocart_button_func() {

		global $product, $current_user;
	    
		$setings = $this->motif_inquiry_settings();

	    if( is_user_logged_in() ) {
	    	
	    	$current_user = wp_get_current_user();

	    }

	   echo '<div class="motif-inquiry-btn">
	    		<a style="color:'.$setings['singlebtncolor'].'" id="inquiry-btn-a" href="#open-modal">'.$setings['Singlebtntext'].'</a>
	    	</div>'; ?>

			<div aria-hidden="true" role="dialog" class="modal-dialog dialog" id="open-modal">

				<div class="modal">

					<a href="#close" title="Close" class="close" id="close-modal">×</a>

					<div class="inner-dialog">
						<h5 id="motif-inquiry-success">Your Inquiry/Query for this product was send successfully, Admin can contact with you soon..!</h5>
						<h3><?php echo $product->get_name(); ?> <?php echo $setings['popuptitle'] ?></h3>
						
						<form id="motif-inq-form" class="motif-inq-form">
						
							<div class="form-field-motif">
								<input <?php if($current_user->ID > 0) echo "disabled"; ?> type="text" id="motif_username_i" placeholder="Name" value="<?php echo $current_user->user_login; ?>">
							</div>
							<div class="form-field-motif">
								<input <?php if($current_user->ID > 0) echo "disabled"; ?> type="email" id="motif_email_i" placeholder="Email" value="<?php echo $current_user->user_email; ?>" data-parsley-required>
							</div>
							<div class="form-field-motif">
								<input type="text" id="motif_subject_i" placeholder="Subject">
							</div>

							<div class="form-field-motif">
								<textarea rows="6" cols="6" id="motif_question_i" placeholder="Question/Query" data-parsley-required></textarea>
							</div>
							
							<div class="form-field-motif">
								<input style="color:<?php echo $setings['submitbtncolor']; ?> ; background:<?php echo $setings['btnsumbitbgcolor']; ?> !important"  type="button" onclick="motif_inquiry_ajax()" value="Send Query">
								<input type="hidden" id="motif_for_inquiry" value="<?php echo get_the_title($product->get_id()); ?>">
							</div>

						</form>

					</div> <!-- /inner-dialog -->

					<div class="footer-modal no__select">
						<a href="#close" class="help">Close</a>
					</div>

				</div> <!-- /modal -->
			 
			</div>

			<!-- ajax request for inquery form -->
		<script type="text/javascript">
				
			function motif_inquiry_ajax() {

				jQuery('#motif-inq-form').parsley().validate();	
				
				var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";

				var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
				
				var condition = 'motif_inquiry_wocoomerce';
				var motif_username_i = jQuery('#motif_username_i').val();
				var motif_email_i = jQuery('#motif_email_i').val();
				var motif_subject_i = jQuery('#motif_subject_i').val();
				var motif_question_i = jQuery('#motif_question_i').val();
				var motif_pinquiry = jQuery('#motif_for_inquiry').val();

				if(motif_username_i == '' && motif_email_i == '' && motif_subject_i == '' && motif_question_i == '') {
					return false;
				}else if (motif_question_i == '') { 
					return false;
				}else if (!pattern.test(motif_email_i)) {
					return false;
				}else {

					jQuery.ajax({
						url : ajaxurl,
						type : 'post',
						data : {
							action : 'woo_inquiry_action_motif',
							condition : condition,
							motif_username_i : motif_username_i,
							motif_email_i : motif_email_i,
							motif_subject_i : motif_subject_i,
							motif_question_i : motif_question_i,
							motif_pinquiry : motif_pinquiry,		

						},
						success : function(response) {
							jQuery('#motif-inquiry-success').show().delay(3000).fadeOut();
							jQuery('#motif-inq-form').each(function() {
								this.reset(); 
							});
						}
					});
				}
			}

		</script>

	 <?php }


	//enqueue the scripts and style
	public function motif_scripts_front() { 
		
		wp_enqueue_style( 'motif-front', plugins_url( '/Asset/front.style.css', __FILE__ ));
		
		wp_enqueue_script('parsley-js', plugins_url( 'Asset/parsley.min.js', __FILE__ ), false );

		wp_enqueue_style('parsley-css', plugins_url( 'Asset/parsley.css', __FILE__ ), false );

		if ( function_exists( 'load_plugin_textdomain' ) )
		load_plugin_textdomain( 'motif_product_inquiry', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
	} 	
}
new motif_product_inquiry_front_class();