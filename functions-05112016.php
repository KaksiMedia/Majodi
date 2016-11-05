<?php
/* ADD custom theme functions here  */

/**
 * Hide the "In stock" message on product page.
 *
 * @param string $html
 * @param string $text
 * @param WC_Product $product
 * @return string
 */
function my_wc_hide_in_stock_message( $html, $text, $product ) {
	$availability = $product->get_availability();
	if ( isset( $availability['class'] ) && 'in-stock' === $availability['class'] ) {
		return '';
	}
	return $html;
}
add_filter( 'woocommerce_stock_html', 'my_wc_hide_in_stock_message', 10, 3 );



add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
 
function woo_custom_cart_button_text() {
 
        return __( 'ADICIONAR AO CARRINHO', 'woocommerce' );
 
}

add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability( $availability, $_product ) {
    
    // Change Out of Stock Text
    if ( ! $_product->is_in_stock() ) {
        $availability['availability'] = __('ESGOTADO', 'woocommerce');
    }
    return $availability;
}

/**
* @snippet Notice with € remaining to Free Shipping @ WooCommerce Cart
*/
 
function bbloomer_free_shipping_cart_notice_zones() {
 
// Get Shipping Methods for Current Zone
 
global $woocommerce;
$shipping_methods = $woocommerce->shipping->get_shipping_methods();
 
// Loop through the array to find min_amount value/s
 
foreach($shipping_methods as $key => $value) {
    if ( $shipping_methods[$key]->min_amount > 0 ) {
      $min_amounts[$key] = $shipping_methods[$key]->min_amount;
    }
}
 
if ( is_array($min_amounts) ) {
 
// Find lowest min_amount
 
$min_amount = min($min_amounts);
 
// Get Cart Subtotal inc. Tax excl. Shipping
 
$current = WC()->cart->subtotal;
 
// If Subtotal < Min Amount Echo Notice
// and add "Continue Shopping" button
 
if ( $current < $min_amount ) {
echo '<div class="woocommerce-message-shipping"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '" class="button wc-forward">Continue a comprar</a><img src="/wp-content/uploads/2016/10/portes-gratis.png">Faltam ' . wc_price( $min_amount - $current ) . ' para o envio ser grátis!</div>';
}
 
}
 
}
 
add_action( 'woocommerce_before_cart', 'bbloomer_free_shipping_cart_notice_zones' );

/**
* @snippet Notice with € remaining to Free Shipping @ WooCommerce Mini Cart
*/
 
function bbloomer_mini_cart_notice() {
 
// Get Min Amount from Woo Settings
$free_shipping_settings = get_option( 'woocommerce_free_shipping_settings' );
$min_amount = $free_shipping_settings['min_amount']; 
 
// Get Cart Subtotal inc. Tax
$current = WC()->cart->subtotal;
 
// If Subtotal < Min Amount Echo Notice
if ( $current < $min_amount ) {
echo '<div class="woocommerce-message-shipping"><img src="/wp-content/uploads/2016/10/portes-gratis.png">Faltam ' . wc_price( $min_amount - $current ) . ' para o envio ser grátis!</div>';
}
}
 
add_action( 'woocommerce_before_mini_cart', 'bbloomer_mini_cart_notice' );