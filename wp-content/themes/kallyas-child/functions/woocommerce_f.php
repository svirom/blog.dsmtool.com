<?php

/*Redirect straight to checkout page (skip cart)*/
add_filter('woocommerce_add_to_cart_redirect', 'themeprefix_add_to_cart_redirect');
function themeprefix_add_to_cart_redirect() {
 global $woocommerce;
 $checkout_url = wc_get_checkout_url();
 return $checkout_url;
}

/*Add New Pay Button Text (instead of Add to Cart)*/
add_filter( 'woocommerce_product_single_add_to_cart_text', 'themeprefix_cart_button_text' ); 
 
function themeprefix_cart_button_text() {
 return __( 'Buy Now', 'woocommerce' );
}

/*Remove/change chekout fields*/
// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     unset($fields['billing']['billing_company']);

     return $fields;
}

/* Hide "add to cart" and "more info" buttons on main shop page */
add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );
    function remove_add_to_cart_buttons() {
      if( is_product_category() || is_shop()) { 
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
      }
    }
