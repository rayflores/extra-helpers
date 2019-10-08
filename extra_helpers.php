<?php
	/*
	 * Plugin Name: Extra Helpers
	 */
	function enqueue_extras_js(){
		wp_enqueue_script( 'extrasjs', plugins_url('/js/extras.js', __FILE__), array( 'jquery', 'woocommerce-one-page-checkout', 'wc-add-to-cart-variation' ) );
	}
	add_action('wp_enqueue_scripts', 'enqueue_extras_js');
	function remove_tns_product_image() {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
	}
	add_action('after_setup_theme', 'remove_tns_product_image' );
	
	function remove_grouped_from_price( $price, $product ) {
		$target_product_types = array(
			'grouped'
		);
		if ( in_array ( $product->product_type, $target_product_types ) ) {
			// if variable product return and empty string
			return '';
		}
		// return normal price
		return $price;
	}
	add_filter('woocommerce_get_price_html', 'remove_grouped_from_price', 10, 2);
	
	function extras_plugin_path() {
		
		// gets the absolute path to this plugin directory
		
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	add_filter( 'woocommerce_locate_template', 'extras_woocommerce_locate_template', 10, 3 );
	
	
	
	function extras_woocommerce_locate_template( $template, $template_name, $template_path ) {
		global $woocommerce;
		
		$_template = $template;
		
		if ( ! $template_path ) $template_path = $woocommerce->template_url;
		
		$plugin_path  = extras_plugin_path() . '/woocommerce/';
		
		// Look within passed path within the theme - this is priority
		$template = locate_template(
			
			array(
				$template_path . $template_name,
				$template_name
			)
		);
		
		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) )
			$template = $plugin_path . $template_name;
		
		// Use default template
		if ( ! $template )
			$template = $_template;
		
		// Return what we found
		return $template;
	}
	// hide coupon field on the TNS cart page
	function hide_coupon_field_on_cart( $enabled ) {
		if ( is_page(24770) ) {
			$enabled = false;
		}
		return $enabled;
	}
	add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_cart' );
// hide coupon field on checkout page
	function hide_coupon_field_on_checkout( $enabled ) {
		if ( is_page(24770) ) {
			$enabled = false;
		}
		return $enabled;
	}
	add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout' );
	// remove messages
	// move selection below fields 
	function remove_move_product_selection() {
		remove_action( 'wcopc_product_selection_fields_before', 'PP_One_Page_Checkout::opc_messages', 10, 2 );
		remove_action( 'woocommerce_checkout_before_customer_details', 'PP_One_Page_Checkout::add_product_selection_fields', 11 );
		add_action( 'woocommerce_checkout_after_customer_details', 'PP_One_Page_Checkout::add_product_selection_fields', 11);
	}
	add_action('init', 'remove_move_product_selection');
	// remove wc standard add to cart messaging
	function remove_added_to_cart_notice()
	{
		if ( is_page(24770) ) {
			$notices = WC()->session->get( 'wc_notices', array() );
			
			foreach ( $notices['success'] as $key => &$notice ) {
				if ( strpos( $notice, 'added to your' ) !== false ) {
					$added_to_cart_key = $key;
					break;
				}
			}
			unset( $notices['success'][ $added_to_cart_key ] );
			
			WC()->session->set( 'wc_notices', $notices );
		}
	}
	add_action('woocommerce_before_single_product','remove_added_to_cart_notice',1);
	add_action('woocommerce_shortcode_before_product_cat_loop','remove_added_to_cart_notice',1);
	add_action('woocommerce_before_shop_loop','remove_added_to_cart_notice',1);
	
	// Removes Order Notes Title - Additional Information & Notes Field
    function maybe_remove_order_notes(){
        return false;
    }
	add_filter( 'woocommerce_enable_order_notes_field', 'maybe_remove_order_notes', 9999 );



// Remove Order Notes Field
	add_filter( 'woocommerce_checkout_fields' , 'remove_order_notes');
	
	function remove_order_notes( $fields ) {
	    if ( is_page(24770) ) {
		    unset( $fields['order']['order_comments'] );
	    } else {
		    unset( $fields['order']['order_comments'] );
		    unset( $fields['billing']['student_heading'] );
		    unset( $fields['billing']['student_first_name'] );
		    unset( $fields['billing']['student_last_name'] );
		    unset( $fields['billing']['student_email'] );
		    unset( $fields['billing']['student_address_1'] );
		    unset( $fields['billing']['student_address_2'] );
		    unset( $fields['billing']['student_city'] );
		    unset( $fields['billing']['student_state'] );
		    unset( $fields['billing']['student_zip'] );
		    unset( $fields['billing']['student_country'] );
		    unset( $fields['billing']['student_phone'] );
        }
		return $fields;
	}

	add_action('wp_head', 'add_col_css');
	function add_col_css(){
		if ( is_page(24770) ) {
			?>
            <style >
            .woocommerce .col2-set .col-1, .woocommerce-page .col2-set .col-1 {
                float:none;
				width: 100%!important;
			}
			.woocommerce .woocommerce-checkout .col2-set .col-1 {
				margin-bottom: 15px;
			}
            .woocommerce #customer_details {
                margin-bottom:0;
            }
            .optional {
                display: none;
            }
                .select2-container {
                    padding-bottom: 1em;
                }
			</style >
		  <?php 
		} else { ?>
		    <style>
                .select2-container {
                    padding-bottom: 1em;
                }
            </style>
       <?php  }
	}
	function wc_billing_field_strings( $translated_text, $text, $domain ) {
		switch ( $translated_text ) {
			case 'Billing details' :
				$translated_text = __( '', 'woocommerce' );
				break;
		}
		return $translated_text;
	}
	add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );
	
    add_action( 'woocommerce_review_order_before_submit', 'add_checkout_checkboxes', 9 );
	function add_checkout_checkboxes() {
		$product_id_1 = 19494;
		$product_cart_id_1 = WC()->cart->generate_cart_id( $product_id_1 );
		$in_cart_1 = WC()->cart->find_product_in_cart( $product_cart_id_1 );
		$product_id_2 = 19489;
		$product_cart_id_2 = WC()->cart->generate_cart_id( $product_id_2 );
		$in_cart_2 = WC()->cart->find_product_in_cart( $product_cart_id_2 );
		if ( $in_cart_1 || $in_cart_2 ) {
			echo '<div id="checkboxes_custom_heading"><b><small>' . __( 'By enrolling in TNS, I agree to' ) . '</small></b></div>';
			
			woocommerce_form_field( 'enroll_live', array(
				'type'        => 'checkbox',
				'class'       => array( 'form-row privacy' ),
				'label_class' => array( 'woocommerce-form__label woocommerce-form__label-for-checkbox checkbox' ),
				'input_class' => array( 'woocommerce-form__input woocommerce-form__input-checkbox input-checkbox' ),
				'required'    => true,
				'label'       => 'Live my own most nutritious life',
			) );
			woocommerce_form_field( 'enroll_help', array(
				'type'        => 'checkbox',
				'class'       => array( 'form-row privacy' ),
				'label_class' => array( 'woocommerce-form__label woocommerce-form__label-for-checkbox checkbox' ),
				'input_class' => array( 'woocommerce-form__input woocommerce-form__input-checkbox input-checkbox' ),
				'required'    => true,
				'label'       => 'Help others live their most nutritious lives',
			) );
			woocommerce_form_field( 'enroll_contribute', array(
				'type'        => 'checkbox',
				'class'       => array( 'form-row privacy' ),
				'label_class' => array( 'woocommerce-form__label woocommerce-form__label-for-checkbox checkbox' ),
				'input_class' => array( 'woocommerce-form__input woocommerce-form__input-checkbox input-checkbox' ),
				'required'    => true,
				'label'       => 'Contribute to the TNS community whenever I can',
			) );
		}
	}

// Show notice if customer does not tick
	
	add_action( 'woocommerce_checkout_process', 'not_approved_checkboxes' );
	
	function not_approved_checkboxes() {
		if ( get_the_ID() !== 24809 ) return;
	    
		if ( ! (int) isset( $_POST['enroll_live'] ) ) {
			wc_add_notice( __( 'Please acknowledge that you will Live your most nutritious life.' ), 'error' );
		}
		if ( ! (int) isset( $_POST['enroll_help'] ) ) {
			wc_add_notice( __( 'Please acknowledge that you will Help others live their most nutritious lives.' ), 'error' );
		}
		if ( ! (int) isset( $_POST['enroll_contribute'] ) ) {
			wc_add_notice( __( 'Please acknowledge that you will Contribute to the TNS community whenever I can.' ), 'error' );
		}
		
	}