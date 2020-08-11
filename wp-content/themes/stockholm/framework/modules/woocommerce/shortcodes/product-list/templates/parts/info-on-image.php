<div class="qode-pli qode-<?php echo esc_html($image_size); ?>-size">
	<div class="qode-pli-inner">
		<div class="qode-pli-image">
			<?php stockholm_qode_get_module_template_part('templates/parts/image', 'woocommerce', '', $params); ?>
		</div>
		<div class="qode-pli-text">
			<div class="qode-pli-text-outer">
				<div class="qode-pli-text-inner <?php stockholm_qode_if_quicklook_or_wishlist_are_installed_class(); ?>">

					<?php stockholm_qode_get_module_template_part('templates/parts/category', 'woocommerce', '', $params); ?>

					<?php stockholm_qode_get_module_template_part('templates/parts/title', 'woocommerce', '', $params); ?>
					
					<?php stockholm_qode_get_module_template_part('templates/parts/excerpt', 'woocommerce', '', $params); ?>
					
					<?php stockholm_qode_get_module_template_part('templates/parts/rating', 'woocommerce', '', $params); ?>
					
					<?php stockholm_qode_get_module_template_part('templates/parts/price', 'woocommerce', '', $params); ?>

                    <a class="qode-pli-link" itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"></a>
                    <div class="qode-pli-additional-info">
						<?php do_action('stockholm_qode_action_woocommerce_additional_info'); ?>
                    </div>
				</div>
			</div>
		</div>

	</div>
</div>