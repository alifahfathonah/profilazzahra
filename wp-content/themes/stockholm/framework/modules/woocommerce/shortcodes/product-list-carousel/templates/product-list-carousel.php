<div class="qode-plc-holder <?php echo esc_attr($holder_classes) ?>">
	<div class="qode-plc-outer qode-owl-slider" <?php echo stockholm_qode_get_inline_attrs($holder_data); ?>>
		<?php if($query_result->have_posts()): while ($query_result->have_posts()) : $query_result->the_post(); ?>
			<div class="qode-plc-item">
                <div class="qode-plc-image">
					<?php stockholm_qode_get_module_template_part('templates/parts/image', 'woocommerce', '', $params); ?>
                </div>
                <div class="qode-plc-text">
                    <div class="qode-plc-text-outer">
                        <div class="qode-plc-text-inner <?php stockholm_qode_if_quicklook_or_wishlist_are_installed_class(); ?>">
                        	<?php
	                        	if ($display_category === "yes") {                        		                        	
									stockholm_qode_get_module_template_part('templates/parts/category', 'woocommerce', '', $params);
								}
							?>
							<?php stockholm_qode_get_module_template_part('templates/parts/title', 'woocommerce', '', $params); ?>

							<?php stockholm_qode_get_module_template_part('templates/parts/excerpt', 'woocommerce', '', $params); ?>

							<?php stockholm_qode_get_module_template_part('templates/parts/rating', 'woocommerce', '', $params); ?>

							<?php stockholm_qode_get_module_template_part('templates/parts/price', 'woocommerce', '', $params); ?>

                            <a class="qode-plc-link" itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"></a>
                            <div class="qode-plc-additional-info">
								<?php do_action('stockholm_qode_action_woocommerce_additional_info'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<?php endwhile;	else:
			stockholm_qode_get_module_template_part('templates/parts/no-posts', 'woocommerce', '', $params);
		endif;
			wp_reset_postdata();
		?>
	</div>
</div>