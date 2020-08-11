<?php

class StockholmQodeWoocommerceDropdownCart extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'woocommerce-dropdown-cart', // Base ID
			esc_html__( 'Stockholm WooCommerce Dropdown Cart', 'stockholm' ), // Name
			array( 'description' => esc_html__( 'Display a shop cart icon with a dropdown that shows products that are in the cart', 'stockholm' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		echo wp_kses_post( $before_widget );
		
		$cart_icon = stockholm_qode_options()->getOptionValue( 'woo_dropdown_cart_icon' );
		switch ( $cart_icon ) {
			case 'font-elegant':
				$cart_icon_class = "icon_bag_alt";
				break;
			case 'font-linear':
				$cart_icon_class = "lnr lnr-cart";
				break;
			default:
				$cart_icon_class = "fa fa-shopping-cart";
				break;
		}
		?>
		<div class="shopping_cart_outer">
			<div class="shopping_cart_inner">
				<div class="shopping_cart_header">
					<a class="header_cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><i class="<?php echo esc_html( $cart_icon_class ); ?>"></i></a>
					<div class="shopping_cart_dropdown">
						<div class="shopping_cart_dropdown_inner">
							<ul class="woocommerce-mini-cart cart_list product_list_widget">
								<?php if ( ! WC()->cart->is_empty() ) :
									foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
										$_product = $cart_item['data'];
										
										// Only display if allowed
										if ( ! $_product->exists() || $cart_item['quantity'] == 0 ) {
											continue;
										}
										
										// Get price
										$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? wc_get_price_excluding_tax( $_product ) : wc_get_price_including_tax( $_product );
										$product_price = apply_filters( 'woocommerce_cart_item_price_html', wc_price( $product_price ), $cart_item, $cart_item_key );
										?>
										<li>
											<?php
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'stockholm' ),
												esc_attr( $cart_item['product_id'] ),
												esc_attr( $cart_item_key ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
											?>
											<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
												<?php echo stockholm_qode_get_module_part($_product->get_image()); ?>
												<?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?>
											</a>
											<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
											
											<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
										</li>
									<?php endforeach; ?>
								<?php else : ?>
									<li><?php esc_html_e( 'No products in the cart.', 'stockholm' ); ?></li>
								<?php endif; ?>
							</ul>
						</div>
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="qbutton dark small view-cart"><?php esc_html_e( 'Cart', 'stockholm' ); ?></a>
						<span class="total"><?php esc_html_e( 'Total:', 'stockholm' ); ?><span><?php wc_cart_totals_subtotal_html(); ?></span></span>
					</div>
				</div>
			</div>
		</div>
	<?php
		echo wp_kses_post( $after_widget );
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		return $instance;
	}
}

if ( ! function_exists( 'stockholm_qode_woocommerc_dropdown_cart_widget_load' ) ) {
	function stockholm_qode_woocommerc_dropdown_cart_widget_load( $widgets ) {
		$widgets[] = 'StockholmQodeWoocommerceDropdownCart';
		
		return $widgets;
	}
	
	add_filter( 'stockholm_qode_filter_register_widgets', 'stockholm_qode_woocommerc_dropdown_cart_widget_load' );
}

add_filter( 'woocommerce_add_to_cart_fragments', 'stockholm_qode_woocommerce_add_to_cart_fragment' );

function stockholm_qode_woocommerce_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<span class="header_cart_span"><?php echo WC()->cart->cart_contents_count; ?></span>
	<?php
		$fragments['span.header_cart_span'] = ob_get_clean();
		return $fragments;
	}
?>