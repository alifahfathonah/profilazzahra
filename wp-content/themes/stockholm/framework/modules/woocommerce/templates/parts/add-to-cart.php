<?php
$product = stockholm_qode_return_woocommerce_global_variable();

if ( ! $product->is_in_stock() ) {
	$button_classes = 'ajax_add_to_cart qode-button qode-read-more-button';
} else if ( $product->get_type() === 'variable' ) {
	$button_classes = 'product_type_variable add_to_cart_button qode-variable qode-button';
} else if ( $product->get_type() === 'external' ) {
	$button_classes = 'product_type_external qode-external qode-button';
} else if ( $product->get_type() === 'grouped' ) {
	$button_classes = 'add_to_cart_button ajax_add_to_cart qode-grouped qode-button';
} else {
	$button_classes = 'add_to_cart_button ajax_add_to_cart qode-add-to-cart qode-button';
}
?>
<div class="qode-<?php echo esc_attr( $class_name ); ?>-add-to-cart">
	<?php echo apply_filters( 'stockholm_qode_filter_product_list_add_to_cart_link',
		sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( $button_classes ),
			esc_html( $product->add_to_cart_text() )
		),
		$product ); ?>
</div>