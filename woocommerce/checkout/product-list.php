<?php
/**
 * Template to display product selection fields in a list
 *
 * @package WooCommerce-One-Page-Checkout/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<label for="checkout-products">Payment Options</label>
<select id="checkout-products">
    <option disabled selected>Please Choose Payment Option</option>
	<?php foreach( $products as $product ) : ?>
	<option class="product-item <?php if ( wcopc_get_products_prop( $product, 'in_cart' ) ) echo 'selected'; ?>" type="option" id="product_<?php echo $product->get_id(); ?>"  value="<?php echo $product->get_id(); ?>" data-add_to_cart="<?php echo 
    $product->get_id(); ?>" <?php selected( wcopc_get_products_prop( $product, 'in_cart' ) ); ?> >
		<?php echo $product->get_title(); ?>
		<span class="dash">&nbsp;&mdash;&nbsp;</span>
		<?php if ( $product->is_in_stock() ) { ?>
		<span itemprop="price" class="price"><?php echo $product->get_price_html(); ?></span>
		<?php } else {
		wc_get_template( 'checkout/add-to-cart/availability.php', array( 'product' => $product ), '', PP_One_Page_Checkout::$template_path );
		} ?>
	</option>
	<?php endforeach; // end of the loop. ?>
</select>
<div class="full-payment"><p><small>Your tuition includes your $300 scholarship - no code needed! <br/>(Regular price tuition: Pay in Full - $1549 ; Payment Plan - 6 monthly payments of $308.16)</small></p></div>
