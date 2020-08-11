<?php
	$featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); //original size

	if(get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true) != ""){
		$large_image = get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true);
	} else {
		$large_image = $featured_image_array[0];
	}

	$caption = get_post(get_post_thumbnail_id())->post_excerpt;

	if($caption != ''){
		$title = $caption;
	}
?>
<div class="icons_holder">
	<div class="icons_holder_inner">
		<?php if ($lightbox == "yes") { ?>
			<a class='portfolio_lightbox' title='<?php echo esc_attr($title); ?>' href='<?php echo esc_url($large_image); ?>' data-rel='prettyPhoto[<?php echo esc_attr($slug_list_); ?>]'></a>
		<?php } ?>
		<?php if ( $show_like == "yes") {
			echo stockholm_qode_get_like_template( true, get_the_ID() );
		} ?>
	</div>
</div>