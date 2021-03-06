<?php

if ( ! function_exists( 'stockholm_qode_is_css_folder_writable' ) ) {
	function stockholm_qode_is_css_folder_writable() {
		return is_writable( QODE_CSS_ROOT_DIR );
	}
}

if ( ! function_exists( 'stockholm_qode_is_js_folder_writable' ) ) {
	function stockholm_qode_is_js_folder_writable() {
		return is_writable( QODE_JS_ROOT_DIR );
	}
}

if ( ! function_exists( 'stockholm_qode_assets_folders_writable' ) ) {
	/**
	 * Function that if css and js folders are writable
	 * @return bool
	 *
	 * @version 0.1
	 * @see stockholm_qode_is_css_folder_writable()
	 * @see stockholm_qode_is_js_folder_writable()
	 */
	function stockholm_qode_assets_folders_writable() {
		return stockholm_qode_is_css_folder_writable() && stockholm_qode_is_js_folder_writable();
	}
}

if ( ! function_exists( 'stockholm_qode_writable_assets_folders_notice' ) ) {
	/**
	 * Function that prints notice that css and js folders aren't writable. Hooks to admin_notices action
	 *
	 * @version 0.1
	 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
	 */
	function stockholm_qode_writable_assets_folders_notice() {
		global $pagenow;
		
		$is_theme_options_page = isset( $_GET['page'] ) && strstr( $_GET['page'], 'qode_theme_menu' );
		
		if ( $pagenow === 'admin.php' && $is_theme_options_page ) {
			if ( ! stockholm_qode_assets_folders_writable() ) { ?>
				<div class="error">
					<p><?php echo sprintf( esc_attr__( 'Note that writing permissions aren\'t set for folders containing css and js files on your server. We recommend setting writing permissions in order to optimize your site performance. For further instructions, please refer to our %sdocumentation%s.', 'stockholm' ), '<a target="_blank" href="http://demo.select-themes.com/stockholm-help/#!/getting_started">', '</a>' ); ?></p>
				</div>
			<?php }
		}
	}
	
	add_action( 'admin_notices', 'stockholm_qode_writable_assets_folders_notice' );
}

if ( ! function_exists( 'stockholm_qode_get_multisite_blog_id' ) ) {
	function stockholm_qode_get_multisite_blog_id() {
		if ( is_multisite() ) {
			return get_blog_details()->blog_id;
		}
	}
}

if ( ! function_exists( 'stockholm_qode_generate_dynamic_css_and_js' ) ) {
	/**
	 * Function that gets content of dynamic assets files and puts that in static ones
	 */
	function stockholm_qode_generate_dynamic_css_and_js() {
		global $wp_filesystem;
		WP_Filesystem();
		
		if ( stockholm_qode_is_css_folder_writable() ) {
			
			ob_start();
			include_once QODE_CSS_ROOT_DIR . '/style_dynamic.php';
			$css = ob_get_clean();
			if ( is_multisite() ) {
				$wp_filesystem->put_contents( QODE_CSS_ROOT_DIR . '/style_dynamic_ms_id_' . stockholm_qode_get_multisite_blog_id() . '.css', $css );
			} else {
				$wp_filesystem->put_contents( QODE_CSS_ROOT_DIR . '/style_dynamic.css', $css );
			}
			
			ob_start();
			include_once QODE_CSS_ROOT_DIR . '/style_dynamic_responsive.php';
			$css = ob_get_clean();
			if ( is_multisite() ) {
				$wp_filesystem->put_contents( QODE_CSS_ROOT_DIR . '/style_dynamic_responsive_ms_id_' . stockholm_qode_get_multisite_blog_id() . '.css', $css );
			} else {
				$wp_filesystem->put_contents( QODE_CSS_ROOT_DIR . '/style_dynamic_responsive.css', $css );
			}
		}
		
		if ( stockholm_qode_is_js_folder_writable() ) {
			
			ob_start();
			include_once QODE_JS_ROOT_DIR . '/default_dynamic.php';
			$js = ob_get_clean();
			if ( is_multisite() ) {
				$wp_filesystem->put_contents( QODE_JS_ROOT_DIR . '/default_dynamic_ms_id_' . stockholm_qode_get_multisite_blog_id() . '.js', $js );
			} else {
				$wp_filesystem->put_contents( QODE_JS_ROOT_DIR . '/default_dynamic.js', $js );
			}
		}
	}
	
	add_action( 'stockholm_qode_action_after_theme_option_save', 'stockholm_qode_generate_dynamic_css_and_js' );
}

if ( ! function_exists( 'stockholm_qode_add_style_dynamic' ) ) {
	function stockholm_qode_add_style_dynamic() {
		$stockholm_options = stockholm_qode_return_global_options();
		?>
		
		<?php if (!empty($stockholm_options['selection_color'])) { ?>
			/* Webkit */
			::selection {
			background: <?php echo esc_attr($stockholm_options['selection_color']);  ?>;
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['selection_color'])) { ?>
			/* Gecko/Mozilla */
			::-moz-selection {
			background: <?php echo esc_attr($stockholm_options['selection_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['first_color'])) { ?>
			
			h6, h6 a,h1 a:hover,h2 a:hover,h3 a:hover,h4 a:hover,h5 a:hover,h6 a:hover,a,p a,
			header:not(.with_hover_bg_color) nav.main_menu > ul > li:hover > a,
			nav.main_menu>ul>li.active > a,
			.drop_down .second .inner > ul > li > a:hover,
			.drop_down .second .inner ul li.sub ul li a:hover,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown li a:hover,
			nav.mobile_menu ul li a:hover,
			nav.mobile_menu ul li.active > a,
			.side_menu_button > a:hover,
			.mobile_menu_button span:hover,
			.vertical_menu ul li a:hover,
			.vertical_menu_toggle .second .inner ul li a:hover,
			nav.popup_menu ul li a:hover,
			nav.popup_menu ul li ul li a,
			.blog_holder article.sticky .post_text h3 a,
			.blog_holder.masonry article.sticky .post_text h5 a,
			.blog_holder.masonry_full_width article.sticky .post_text h5 a,
			.blog_holder article .post_info,
			.blog_holder article .post_info a,
			.blog_holder article .post_text a.more-link:hover span,
			.blog_holder article .post_description a:hover,
			.blog_holder article .post_description .post_comments:hover,
			.blog_holder.masonry article.format-quote  .post_text_inner:hover .qoute_mark,
			.blog_holder.masonry article.format-link .post_text .post_text_inner:hover .link_mark,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner:hover .qoute_mark,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner:hover .link_mark,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .post_comments i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .blog_like i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .social_share_holder > a > i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .post_comments i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .blog_like i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .social_share_holder > a > i,
			.comment_holder .comment .text .comment_date,
			.comment_holder .comment .text .replay,
			.comment_holder .comment .text .comment-reply-link,
			div.comment_form form p.logged-in-as a,
			.blog_holder.masonry .post_author:hover,
			.blog_holder.masonry .post_author a:hover,
			.blog_holder.masonry_full_width .post_author:hover,
			.blog_holder.masonry_full_width .post_author a:hover,
			.blog_holder.masonry article .post_info a:hover,
			.blog_holder.masonry_full_width article .post_info a:hover,
			.blog_holder.masonry article h4 a:hover,
			.blog_holder.masonry_full_width article h4 a:hover,
			.latest_post_holder .latest_post_title a:hover,
			.latest_post_holder .post_info_section:before,
			.latest_post_holder .post_info_section span,
			.latest_post_holder .post_info_section a,
			.latest_post_holder .post_author a.post_author_link:hover,
			.projects_holder article .portfolio_title a:hover,
			.filter_holder ul li.current span,
			.filter_holder ul li:not(.filter_title):hover span,
			.q_accordion_holder.accordion .ui-accordion-header .accordion_mark_icon,
			blockquote.with_quote_icon i,
			blockquote h3,
			.q_dropcap,
			.price_in_table .value,
			.price_in_table .price,
			.q_font_elegant_holder.q_icon_shortcode:hover,
			.q_font_awsome_icon_holder.q_icon_shortcode:hover,
			.q_icon_with_title.normal_icon .icon_holder:hover .icon_text_icon,
			.box_holder_icon_inner.normal_icon .icon_holder_inner:hover .icon_text_icon,
			.q_progress_bars_icons_inner.square .bar.active i,
			.q_progress_bars_icons_inner.circle .bar.active i,
			.q_progress_bars_icons_inner.normal .bar.active i,
			.q_progress_bars_icons_inner .bar.active i.fa-circle,
			.q_progress_bars_icons_inner.square .bar.active .q_font_elegant_icon,
			.q_progress_bars_icons_inner.circle .bar.active .q_font_elegant_icon,
			.q_progress_bars_icons_inner.normal .bar.active .q_font_elegant_icon,
			.q_social_icon_holder.normal_social .simple_social,
			.q_social_icon_holder.normal_social.with_link .simple_social,
			.q_list.number ul>li:before,
			.social_share_list_holder ul li i:hover,
			.q_progress_bar .progress_number,
			.qbutton:hover,
			.load_more a:hover,
			.blog_load_more_button a:hover,
			#submit_comment:hover,
			.drop_down .wide .second ul li .qbutton:hover,
			.drop_down .wide .second ul li ul li .qbutton:hover,
			nav.content_menu ul li.active:hover i,
			nav.content_menu ul li:hover i,
			nav.content_menu ul li.active:hover a,
			nav.content_menu ul li:hover a,
			aside.sidebar .widget:not(.woocommerce) li,
			.header-widget.widget_nav_menu ul.menu li a:hover,
			input.wpcf7-form-control.wpcf7-submit:not([disabled]):hover,
			.gform_wrapper input[type=button]:hover,
			.gform_wrapper input[type=submit]:hover,
			.vc_grid-container .vc_grid-filter.vc_grid-filter-color-grey > .vc_grid-filter-item:hover span,
			.vc_grid-container .vc_grid-filter.vc_grid-filter-color-grey > .vc_grid-filter-item.vc_active span,
			.qode_twitter_widget li .tweet_icon_holder .social_twitter,
			.blog_holder.blog_chequered article .qodef-post-title .time,
			.blog_holder.blog_chequered article.qodef-with-bg-color .qodef-post-title a:hover,
			.portfolio_single .fullscreen-slider .qodef-portfolio-slider-content .qodef-control,
			.qode-wishlist-widget-holder a,
			.woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a,
			.woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover,
			.shopping_cart_dropdown .cart_list span.quantity,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a:hover,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover
			{
			color: <?php echo esc_attr($stockholm_options['first_color']); ?>;
			}
			
			.popup_menu:hover .line,
			.popup_menu:hover .line:after,
			.popup_menu:hover .line:before,
			.blog_holder article .post_text a.more-link span,
			.blog_holder article .post_social .post_comments i,
			.blog_holder article .post_social .blog_like i,
			.blog_holder article .post_social .social_share_holder > a > i,
			.blog_holder article.format-link .post_text .post_text_inner:hover,
			.blog_holder article.format-quote .post_text .post_text_inner:hover,
			.blog_holder.masonry article.format-quote  .post_text_inner .qoute_mark,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner .link_mark,
			.blog_holder.masonry article.format-link .post_text .post_text_inner .link_mark,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner .qoute_mark,
			.blog_holder article .post_image a .post_overlay,
			.latest_post_holder .boxes_image a .latest_post_overlay,
			.mejs-controls .mejs-time-rail .mejs-time-current,
			.mejs-controls .mejs-time-rail .mejs-time-handle,
			.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
			.projects_holder article .portfolio_shader,
			.projects_masonry_holder article .portfolio_shader,
			.portfolio_slides .portfolio_shader,
			.q_accordion_holder.accordion.boxed .ui-accordion-header.ui-state-active,
			.q_accordion_holder.accordion.boxed .ui-accordion-header.ui-state-hover,
			.q_accordion_holder.accordion .ui-accordion-header.ui-state-active .accordion_mark,
			.q_accordion_holder.accordion .ui-accordion-header.ui-state-hover .accordion_mark,
			.q_dropcap.circle,
			.q_dropcap.square,
			.gallery_holder ul li .gallery_hover i,
			.highlight,
			.testimonials_holder.light .flex-direction-nav a:hover,
			.q_tabs .tabs-nav li.active a,
			.q_tabs .tabs-nav li a:hover,
			.q_message,
			.price_table_inner ul li.table_title,
			.price_table_inner .price_button,
			.q_icon_with_title.circle .icon_holder .icon_holder_inner,
			.q_icon_with_title.square .icon_holder .icon_holder_inner,
			.box_holder_icon_inner.circle .icon_holder_inner,
			.box_holder_icon_inner.square .icon_holder_inner,
			.q_icon_with_title.circle .q_font_elegant_holder.circle,
			.q_icon_with_title.square .q_font_elegant_holder.square,
			.box_holder_icon_inner .q_font_elegant_holder.circle,
			.box_holder_icon_inner .q_font_elegant_holder.square,
			.box_holder_icon_inner.circle .icon_holder_inner,
			.q_social_icon_holder .fa-stack,
			.footer_top .q_social_icon_holder:hover .fa-stack,
			.q_list.circle ul>li:before,
			.q_list.number.circle_number ul>li:before,
			.q_pie_graf_legend ul li .color_holder,
			.q_line_graf_legend ul li .color_holder,
			.q_team .q_team_social_holder,
			.animated_icon_inner span.animated_icon_back .animated_icon,
			.service_table_inner li.service_table_title_holder,
			.q_progress_bar .progress_content,
			.q_progress_bars_vertical .progress_content_outer .progress_content,
			.qbutton,
			.load_more a,
			.blog_load_more_button a,
			#submit_comment,
			.qbutton.white:hover,
			.qbutton.solid_color,
			.call_to_action .column2.button_wrapper .qbutton:hover,
			#wp-calendar td#today,
			aside.sidebar .widget h4,
			.qode_image_gallery_no_space.light .controls a.prev-slide:hover,
			.qode_image_gallery_no_space.light .controls a.next-slide:hover,
			input.wpcf7-form-control.wpcf7-submit,
			div.wpcf7-response-output.wpcf7-mail-sent-ok,
			.gform_wrapper input[type=button],
			.gform_wrapper input[type=submit],
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a{
			background-color: <?php echo esc_attr($stockholm_options['first_color']); ?>;
			}
			
			<?php $bg_color = stockholm_qode_hex2rgb($stockholm_options['first_color']); ?>
			
			.q_image_with_text_over:hover .shader{
			background-color: rgba(<?php echo esc_attr($bg_color[0]); ?>,<?php echo esc_attr($bg_color[1]); ?>,<?php echo esc_attr($bg_color[2]); ?>,0.9);
			}
			
			.q_circles_holder .q_circle_inner:hover .q_circle_inner2{
			background-color: <?php echo esc_attr($stockholm_options['first_color']); ?> !important;
			}
			
			.drop_down .second,
			.drop_down .narrow .second .inner ul li ul,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown,
			.blog_holder article .post_text a.more-link span,
			.blog_holder article .post_text a.more-link:hover span,
			#respond textarea:focus,
			#respond input[type='text']:focus,
			.contact_form input[type='text']:focus,
			.contact_form  textarea:focus,
			.q_accordion_holder .ui-accordion-header .accordion_mark,
			.testimonials_holder.light .flex-direction-nav a:hover,
			.q_progress_bars_icons_inner.circle .bar .bar_noactive,
			.q_progress_bars_icons_inner.square .bar .bar_noactive,
			.animated_icon_inner span.animated_icon_back .animated_icon,
			.service_table_holder,
			.service_table_inner li,
			.qbutton,
			.load_more a,
			.blog_load_more_button a,
			#submit_comment,
			.qbutton:hover,
			.load_more a:hover,
			.blog_load_more_button a:hover,
			#submit_comment:hover,
			.drop_down .wide .second ul li .qbutton:hover,
			.drop_down .wide .second ul li ul li .qbutton:hover,
			.qbutton.white:hover,
			.qbutton.solid_color,
			.call_to_action .column2.button_wrapper .qbutton:hover,
			.header-widget.widget_nav_menu ul ul,
			input.wpcf7-form-control.wpcf7-text:focus,
			input.wpcf7-form-control.wpcf7-number:focus,
			input.wpcf7-form-control.wpcf7-date:focus,
			textarea.wpcf7-form-control.wpcf7-textarea:focus,
			select.wpcf7-form-control.wpcf7-select:focus,
			input.wpcf7-form-control.wpcf7-quiz:focus,
			input.wpcf7-form-control.wpcf7-submit,
			input.wpcf7-form-control.wpcf7-submit[disabled],
			input.wpcf7-form-control.wpcf7-submit:not([disabled]),
			input.wpcf7-form-control.wpcf7-submit:not([disabled]):hover,
			.gform_wrapper input[type=text]:focus,
			.gform_wrapper input[type=url]:focus,
			.gform_wrapper input[type=email]:focus,
			.gform_wrapper input[type=tel]:focus,
			.gform_wrapper input[type=number]:focus,
			.gform_wrapper input[type=password]:focus,
			.gform_wrapper textarea:focus,
			.gform_wrapper input[type=button],
			.gform_wrapper input[type=submit],
			.gform_wrapper input[type=button]:hover,
			.gform_wrapper input[type=submit]:hover,
			.qbutton.underlined,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a
			{
			border-color: <?php echo esc_attr($stockholm_options['first_color']); ?>;
			}
			
			.q_circles_holder .q_circle_inner:hover .q_circle_inner2,
			.q_circles_holder .q_circle_inner:hover{
			border-color: <?php echo esc_attr($stockholm_options['first_color']); ?> !important;
			}
			
			<?php if(function_exists('is_woocommerce')) { ?>
				.woocommerce .myaccount_user a,
				.woocommerce .button,
				.woocommerce-page .button,
				.woocommerce button.button,
				.woocommerce-page button.button,
				.woocommerce-page input[type="submit"]:not(.qode_search_field),
				.woocommerce input[type="submit"]:not(.qode_search_field),
				.woocommerce ul.products li.product .added_to_cart,
				.woocommerce .select2-container .select2-choice .select2-arrow .select2-arrow:after ,
				.woocommerce-page .select2-container .select2-choice .select2-arrow:after,
				.woocommerce-checkout .form-row #billing_country_chzn.chzn-container-single .chzn-single div b:before,
				.woocommerce-checkout .form-row #shipping_country_chzn.chzn-container-single .chzn-single div b:before,
				.woocommerce-checkout .form-row .chosen-container-single .chosen-single div b:before,
				.woocommerce-account .form-row .chosen-container-single .chosen-single div b:before,
				.woocommerce-checkout .chosen-container .chosen-results li.active-result.highlighted,
				.woocommerce-account .chosen-container .chosen-results li.active-result.highlighted,
				.woocommerce ul.products li.product span.product-title:hover,
				.woocommerce ul.products li.product .price,
				.woocommerce-page ul.products li.product .price,
				.woocommerce div.product .summary p.price span.amount,
				.woocommerce.single-product button.single_add_to_cart_button:hover,
				.woocommerce .quantity input.qty,
				.woocommerce #content .quantity input.qty,
				.woocommerce-page .quantity input.qty,
				.woocommerce-page #content .quantity input.qty,
				.woocommerce div.product div.product_meta > span span,
				.woocommerce div.product div.product_meta > span a,
				.woocommerce .star-rating,
				.woocommerce-page .star-rating,
				.woocommerce div.cart-collaterals div.cart_totals table tr.order-total strong span.amount,
				.woocommerce-page div.cart-collaterals div.cart_totals table tr.order-total strong span.amount,
				.woocommerce div.cart-collaterals div.cart_totals table tr.order-total strong,
				.woocommerce form.checkout table.shop_table tfoot tr.order-total th,
				.woocommerce form.checkout table.shop_table tfoot tr.order-total td span.amount,
				.woocommerce aside ul.product_list_widget li > a:hover,
				aside ul.product-categories li > a:hover,
				.woocommerce aside ul.product_list_widget li span.amount,
				aside ul.product_list_widget li span.amount,
				.woocommerce .widget_shopping_cart_content p.buttons a.button,
				.woocommerce aside .widget ul.product-categories a:hover,
				aside .widget ul.product-categories a:hover,
				.woocommerce-page aside .widget ul.product-categories a:hover,
				.woocommerce aside.sidebar .woocommerce.widget ul.product-categories ul.children li a:hover,
				aside ul.product-categories ul.children li a:hover,
				.woocommerce-page aside.sidebar .woocommerce.widget ul.product-categories ul.children li a:hover,
				.woocommerce aside.sidebar .woocommerce.widget ul.product-categories  a:hover,
				aside ul.product-categories a:hover,
				.woocommerce-page aside.sidebar .woocommerce.widget ul.product-categories li a:hover,
				.woocommerce .widget_shopping_cart_content .total .amount,
				.woocommerce-page .widget_shopping_cart_content .total .amount,
				.woocommerce .select2-results li.select2-highlighted,
				.woocommerce-page .select2-results li.select2-highlighted,
				.woocommerce .product .woocommerce-product-rating .woocommerce-review-link:hover,
				.shopping_cart_header .header_cart:hover i,
				.shopping_cart_dropdown ul li a:hover,
				.shopping_cart_dropdown span.total span,
				.woocommerce .product .woocommerce-product-rating .woocommerce-review-link:hover,
				.select2-container--default.select2-container--open .select2-selection--single,
				.select2-container--default .select2-results__option[aria-selected=true],
				.select2-container--default .select2-results__option--highlighted[aria-selected]{
				color: <?php echo esc_attr($stockholm_options['first_color']); ?>;
				}
				
				.woocommerce .button:hover,
				.woocommerce-page .button:hover,
				.woocommerce button.button:hover,
				.woocommerce-page button.button:hover,
				.woocommerce #submit:hover,
				.woocommerce ul.products li.product a.qbutton:hover,
				.woocommerce-page ul.products li.product a.qbutton:hover,
				.woocommerce ul.products li.product .added_to_cart:hover,
				.woocommerce-page input[type="submit"]:not(.qode_search_field):hover,
				.woocommerce input[type="submit"]:not(.qode_search_field):hover,
				.woocommerce .product .onsale.out-of-stock-button,
				.woocommerce.single-product button.single_add_to_cart_button,
				.woocommerce .widget_price_filter .ui-slider-horizontal .ui-slider-range,
				.woocommerce-page .widget_price_filter .ui-slider-horizontal .ui-slider-range,
				.woocommerce .quantity .minus:hover,
				.woocommerce #content .quantity .minus:hover,
				.woocommerce-page .quantity .minus:hover,
				.woocommerce-page #content .quantity .minus:hover,
				.woocommerce .quantity .plus:hover,
				.woocommerce #content .quantity .plus:hover,
				.woocommerce-page .quantity .plus:hover,
				.woocommerce-page #content .quantity .plus:hover,
				.woocommerce .checkout-opener-text{
				background-color: <?php echo esc_attr($stockholm_options['first_color']); ?>;
				}
				
				.woocommerce .button,
				.woocommerce-page .button,
				.woocommerce button.button,
				.woocommerce-page button.button,
				.woocommerce-page input[type="submit"]:not(.qode_search_field),
				.woocommerce input[type="submit"]:not(.qode_search_field),
				.woocommerce ul.products li.product .added_to_cart,
				.woocommerce.single-product button.single_add_to_cart_button:hover,
				.woocommerce .quantity .minus:hover,
				.woocommerce #content .quantity .minus:hover,
				.woocommerce-page .quantity .minus:hover,
				.woocommerce-page #content .quantity .minus:hover,
				.woocommerce .quantity .plus:hover,
				.woocommerce #content .quantity .plus:hover,
				.woocommerce-page .quantity .plus:hover,
				.woocommerce-page #content .quantity .plus:hover{
				border-color: <?php echo esc_attr($stockholm_options['first_color']); ?>;
				}
			<?php } ?>
		<?php } ?>
		
		<?php if (!empty($stockholm_options['spinner_color'])) { ?>
			.ajax_loader .pulse,
			.ajax_loader .double_pulse .double-bounce1, .ajax_loader .double_pulse .double-bounce2,
			.ajax_loader .cube,
			.ajax_loader .rotating_cubes .cube1, .ajax_loader .rotating_cubes .cube2,
			.ajax_loader .stripes > div,
			.ajax_loader .wave > div,
			.ajax_loader .two_rotating_circles .dot1, .ajax_loader .two_rotating_circles .dot2,
			.ajax_loader .five_rotating_circles .container1 > div, .ajax_loader .five_rotating_circles .container2 > div, .ajax_loader .five_rotating_circles .container3 > div,
			.loading-center-absolute .object,
			.indeterminate-holder .indeterminate{
			background-color: <?php echo esc_attr($stockholm_options['spinner_color']); ?>;
			}
			.ajax_loader .pulsating_circle,
			.ajax_loader .ripples .ripples_circle{
			border-color: <?php echo esc_attr($stockholm_options['spinner_color']); ?>;
			}
			.ajax_loader .spinner:before {
			border-top-color: <?php echo esc_attr($stockholm_options['spinner_color']); ?>;
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['background_color']) || !empty($stockholm_options['text_color']) || !empty($stockholm_options['text_fontsize']) || !empty($stockholm_options['text_fontweight']) || $stockholm_options['google_fonts'] != "-1") { ?>
			body{
			<?php if($stockholm_options['google_fonts'] != "-1"){ ?>
				<?php $font = str_replace('+', ' ', $stockholm_options['google_fonts']); ?>
				font-family: '<?php echo esc_attr($font); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['text_color'])) { ?> color: <?php echo esc_attr($stockholm_options['text_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['text_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['text_fontsize']); ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['text_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['text_fontweight']);  ?>;<?php } ?>
			}
			<?php if (!empty($stockholm_options['background_color'])) { ?>
				body,
				.wrapper,
				.content,
				.full_width,
				.content .container,
				.more_facts_holder{
				background-color:<?php echo esc_attr($stockholm_options['background_color']);  ?>;
				}
			<?php } ?>
		<?php } ?>
		<?php if($stockholm_options['google_fonts'] != "-1"){ ?>
			h3, h6, .pagination ul li.active span, .pagination ul li > a.inactive, .subtitle, .blog_holder article .post_info, .comment_holder .comment .text .comment_date, .blog_holder.masonry .post_author, .blog_holder.masonry .post_author a, .blog_holder.masonry_full_width .post_author, .blog_holder.masonry_full_width .post_author a, .latest_post_holder .post_info_section span, .latest_post_holder .post_info_section a, .latest_post_holder .post_author, .latest_post_holder a.post_author_link, .projects_holder article .project_category, .portfolio_slides .project_category, .projects_masonry_holder .project_category, .testimonials .testimonial_text_inner p:not(.testimonial_author), .price_in_table .mark, body div.pp_default .pp_description, .side_menu .widget li,aside.sidebar .widget:not(.woocommerce) li, aside .widget #lang_sel ul li a, aside .widget #lang_sel_click ul li a, section.side_menu #lang_sel ul li a, section.side_menu #lang_sel_click ul li a, footer #lang_sel ul li a, footer #lang_sel_click ul li a, .header_top #lang_sel ul li a, .header_top #lang_sel_click ul li a, .header_bottom #lang_sel ul li a, .header_bottom #lang_sel_click > ul > li a,.single_links_pages span,.gform_wrapper .gsection .gfield_label,.gform_wrapper h2.gsection_title,.gform_wrapper h3.gform_title{
			<?php $font = str_replace('+', ' ', $stockholm_options['google_fonts']); ?>
			font-family: '<?php echo esc_attr($font); ?>', sans-serif;
			}
			<?php if(function_exists('is_woocommerce')) { ?>
				.woocommerce ul.products li.product span.product-categories a, .woocommerce-page ul.products li.product span.product-categories a, .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price, .woocommerce aside ul.product_list_widget li span.amount, aside ul.product_list_widget li span.amount, .woocommerce .product .onsale.out-of-stock-button, .woocommerce-pagination ul.page-numbers li span.current, .woocommerce-pagination ul.page-numbers li a, .woocommerce div.product .summary p.price span.amount, .woocommerce .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce-page #content .quantity input.qty, .woocommerce div.product div.product_meta > span span, .woocommerce div.product div.product_meta > span a, .woocommerce aside.sidebar .woocommerce.widget ul.product-categories ul.children li a, aside ul.product-categories ul.children li a, .woocommerce-page aside.sidebar .woocommerce.widget ul.product-categories ul.children li a, .shopping_cart_header .header_cart span{
				<?php $font = str_replace('+', ' ', $stockholm_options['google_fonts']); ?>
				font-family: '<?php echo esc_attr($font); ?>', sans-serif;
				}
			<?php } ?>
		<?php } ?>
		<?php if (!empty($stockholm_options['background_color_box'])) { ?>
			.wrapper{
			<?php if (!empty($stockholm_options['background_color_box'])) { ?> background-color:<?php echo esc_attr($stockholm_options['background_color_box']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php
		$boxed = "no";
		if (isset($stockholm_options['boxed']))
			$boxed = $stockholm_options['boxed'];
		?>
		<?php if($boxed == "yes"){ ?>
			body.boxed .wrapper{
			<?php if (!empty($stockholm_options['background_color_box'])) { ?> background-color:<?php echo esc_attr($stockholm_options['background_color_box']);  ?>; <?php } ?>
			
			<?php if($stockholm_options['pattern_background_image'] != ""){  ?>
				background-image: url('<?php echo esc_url($stockholm_options['pattern_background_image']); ?>');
				background-position: 0px 0px;
				background-repeat: repeat;
			<?php } ?>
			
			<?php if($stockholm_options['background_image'] != ""){  ?>
				background-image: url('<?php echo esc_url($stockholm_options['background_image']); ?>');
				background-attachment: fixed;
				background-position: center 0px;
				background-repeat: no-repeat;
			<?php } ?>
			}
			body.boxed .content{
			<?php if (!empty($stockholm_options['background_color'])) { ?> background-color:<?php echo esc_attr($stockholm_options['background_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['highlight_color'])) { ?>
			span.highlight {
			background-color: <?php echo esc_attr($stockholm_options['highlight_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['header_background_color']) || $stockholm_options['header_background_transparency_initial'] != "") {
			if(!empty($stockholm_options['header_background_color'])){
				$bg_color = stockholm_qode_hex2rgb($stockholm_options['header_background_color']);
			}else{
				$bg_color = stockholm_qode_hex2rgb('#ffffff');
			}
			if ($stockholm_options['header_background_transparency_initial'] != "") {
				$bg_color_transparency = $stockholm_options['header_background_transparency_initial'];
			}else{
				$bg_color_transparency = 1;
			}
			?>
			.header_bottom,
			.header_top {
			background-color: rgba(<?php echo esc_attr($bg_color[0]); ?>,<?php echo esc_attr($bg_color[1]); ?>,<?php echo esc_attr($bg_color[2]); ?>,<?php echo esc_attr($bg_color_transparency); ?>);
			}
			
			<?php if(isset($bg_color_transparency) && $bg_color_transparency == 0) { ?>
				
				.header_bottom,
				.header_top {
				border-bottom: 0;
				}
				
				.header_bottom {
				box-shadow: none;
				}
				
				.header_top .right .inner > div:first-child,
				.header_top .right .inner > div,
				.header_top .left .inner > div:last-child,
				.header_top .left .inner > div {
				border: none;
				}
			
			<?php } ?>
		
		<?php } ?>
		
		<?php if (!empty($stockholm_options['header_separator_color'])) { ?>
			.drop_down .wide .second ul li{
			border-color:<?php echo esc_attr($stockholm_options['header_separator_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['content_top_padding']) && $stockholm_options['content_top_padding'] !== '') { ?>
			.content .content_inner > .container > .container_inner,
			.content .content_inner > .full_width > .full_width_inner{
			padding-top: <?php echo intval($stockholm_options['content_top_padding']); ?>px;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['content_top_padding_default_template']) && $stockholm_options['content_top_padding_default_template'] !== '') { ?>
			.content .content_inner > .container > .container_inner.default_template_holder{
			padding-top: <?php echo intval($stockholm_options['content_top_padding_default_template']); ?>px;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['content_top_padding_mobile']) && $stockholm_options['content_top_padding_mobile'] !== '') { ?>
			@media only screen and (max-width: 1000px){
			.content .content_inner > .container > .container_inner,
			.content .content_inner > .full_width > .full_width_inner,
			.content .content_inner > .container > .container_inner.default_template_holder{
			padding-top: <?php echo intval($stockholm_options['content_top_padding_mobile']); ?>px !important;
			}
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['border_bottom_title_area']) && $stockholm_options['border_bottom_title_area'] == "yes") { ?>
			.title{
			border-bottom-width:1px;
			border-bottom-style:solid;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['border_bottom_title_area_color']) ) { ?>
			.title{
			border-bottom-color:<?php echo esc_attr($stockholm_options['border_bottom_title_area_color']); ?>;
			}
		<?php } ?>
		
		<?php
		if (!empty($stockholm_options['header_background_color_scroll']) || $stockholm_options['header_background_transparency_scroll'] != "") {
			
			if(!empty($stockholm_options['header_background_color_scroll'])){
				$bg_color_scroll = stockholm_qode_hex2rgb($stockholm_options['header_background_color_scroll']);
			}else{
				$bg_color_scroll = stockholm_qode_hex2rgb('#ffffff');
			}
			
			if ($stockholm_options['header_background_transparency_scroll'] != "") {
				$bg_color_scroll_transparency = $stockholm_options['header_background_transparency_scroll'];
			}else{
				$bg_color_scroll_transparency = 1;
			}
			?>
			header.fixed.scrolled .header_bottom,
			header.fixed_hiding.scrolled .header_bottom,
			header.fixed.scrolled .header_top {
			background-color: rgba(<?php echo esc_attr($bg_color_scroll[0]); ?>,<?php echo esc_attr($bg_color_scroll[1]); ?>,<?php echo esc_attr($bg_color_scroll[2]); ?>,<?php echo esc_attr($bg_color_scroll_transparency); ?>) !important;
			}
		<?php } ?>
		
		<?php if($stockholm_options['header_background_transparency_scroll'] != "" && $stockholm_options['header_background_transparency_scroll'] == 0) { ?>
			
			header.scrolled .header_bottom,
			header.scrolled .header_top {
			border-bottom: 0;
			}
			
			header.scrolled .header_bottom {
			box-shadow: none;
			}
			
			header.scrolled .header_top .right .inner > div:first-child,
			header.scrolled .header_top .right .inner > div,
			header.scrolled .header_top .left .inner > div:last-child,
			header.scrolled .header_top .left .inner > div {
			border: none;
			}
		<?php } ?>
		
		
		
		
		
		<?php
		if (!empty($stockholm_options['header_background_color_sticky']) || $stockholm_options['header_background_transparency_sticky'] != "") {
			
			if(!empty($stockholm_options['header_background_color_sticky'])){
				$bg_color_sticky = stockholm_qode_hex2rgb($stockholm_options['header_background_color_sticky']);
			}else{
				$bg_color_sticky = stockholm_qode_hex2rgb('#ffffff');
			}
			
			if ($stockholm_options['header_background_transparency_sticky'] != "") {
				$bg_color_sticky_transparency = $stockholm_options['header_background_transparency_sticky'];
			}else{
				$bg_color_sticky_transparency = 1;
			}
			?>
			header.sticky .header_bottom{
			background-color: rgba(<?php echo esc_attr($bg_color_sticky[0]); ?>,<?php echo esc_attr($bg_color_sticky[1]); ?>,<?php echo esc_attr($bg_color_sticky[2]); ?>,<?php echo esc_attr($bg_color_sticky_transparency); ?>) !important;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['header_top_background_color']) || $stockholm_options['header_background_transparency_initial'] != "") {
			if(!empty($stockholm_options['header_top_background_color'])) {
				$bg_color_top = stockholm_qode_hex2rgb($stockholm_options['header_top_background_color']);
			} else {
				$bg_color_top = stockholm_qode_hex2rgb('#fff');
			}
			
			if ($stockholm_options['header_background_transparency_initial'] != "") {
				$bg_color_transparency = $stockholm_options['header_background_transparency_initial'];
			} else{
				$bg_color_transparency = 1;
			}
			?>
			
			.header_top{
			background-color: rgba(<?php echo esc_attr($bg_color_top[0]); ?>,<?php echo esc_attr($bg_color_top[1]); ?>,<?php echo esc_attr($bg_color_top[2]); ?>,<?php echo esc_attr($bg_color_transparency); ?>);
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['header_bottom_border_color']) && (isset($stockholm_options['enable_header_bottom_border']) && $stockholm_options['enable_header_bottom_border'] == "yes")) {
			if (!empty($stockholm_options['header_botom_border_transparency'])) {
				$header_border_transparency = stockholm_qode_hex2rgb($stockholm_options['header_bottom_border_color']);
				
				if(isset($stockholm_options['header_botom_border_in_grid']) && $stockholm_options['header_botom_border_in_grid'] == "yes"){ ?>
					header:not(.sticky):not(.scrolled) .header_bottom .container_inner{
					border-bottom: 1px solid rgba(<?php echo esc_attr($header_border_transparency[0]); ?>,<?php echo esc_attr($header_border_transparency[1]); ?>,<?php echo esc_attr($header_border_transparency[2]); ?>,<?php echo esc_attr($stockholm_options['header_botom_border_transparency']); ?>);
					}
				<?php } else { ?>
					header:not(.sticky):not(.scrolled) .header_bottom{
					border-bottom: 1px solid rgba(<?php echo esc_attr($header_border_transparency[0]); ?>,<?php echo esc_attr($header_border_transparency[1]); ?>,<?php echo esc_attr($header_border_transparency[2]); ?>,<?php echo esc_attr($stockholm_options['header_botom_border_transparency']); ?>);
					}
				<?php }
			} else {
				if(isset($stockholm_options['header_botom_border_in_grid']) && $stockholm_options['header_botom_border_in_grid'] == "yes"){ ?>
					header:not(.sticky):not(.scrolled) .header_bottom .container_inner{
					border-bottom: 1px solid <?php echo esc_attr($stockholm_options['header_bottom_border_color']); ?>;
					}
				<?php } else { ?>
					header:not(.sticky):not(.scrolled) .header_bottom{
					border-bottom: 1px solid <?php echo esc_attr($stockholm_options['header_bottom_border_color']); ?>;
					}
				<?php }
			}
		}?>
		
		<?php
		if (!empty($stockholm_options['header_top_background_color']) || $stockholm_options['header_background_transparency_scroll'] != "") {
			
			if(!empty($stockholm_options['header_top_background_color'])){
				$bg_color_scroll_top = stockholm_qode_hex2rgb($stockholm_options['header_top_background_color']);
			}else{
				$bg_color_scroll_top = stockholm_qode_hex2rgb('#000000');
			}
			
			if ($stockholm_options['header_background_transparency_scroll'] != "") {
				$bg_color_scroll_transparency = $stockholm_options['header_background_transparency_scroll'];
			}else{
				$bg_color_scroll_transparency = 0.7;
			}
			?>
			header.sticky .header_top{
			background-color: rgba(<?php echo esc_attr($bg_color_scroll_top[0]); ?>,<?php echo esc_attr($bg_color_scroll_top[1]); ?>,<?php echo esc_attr($bg_color_scroll_top[2]); ?>,<?php echo esc_attr($bg_color_scroll_transparency); ?>);
			}
		<?php } ?>
		
		<?php
		$header_bottom_appearance = stockholm_qode_get_header_bottom_appearance();
		
		$margin_top_add = stockholm_qode_return_top_header_height();
		
		if (!empty($stockholm_options['header_height']) && $header_bottom_appearance != "fixed_hiding") {
			$header_height = $stockholm_options['header_height'];
		} elseif(!empty($stockholm_options['header_height']) && $header_bottom_appearance == "fixed_hiding"){
			$header_height = $stockholm_options['header_height'] + 50; // 50 is logo height for fixed advanced header type
		} elseif(isset($stockholm_options['center_logo_image']) && $stockholm_options['center_logo_image'] == "yes" && $header_bottom_appearance != "stick" && $header_bottom_appearance != "fixed_hiding") {
			$header_height = 190;
		} elseif(empty($stockholm_options['header_height']) && $header_bottom_appearance == "fixed_hiding"){
			$header_height = 222;
		} else {
			$header_height = 100;
		}
		if (!empty($stockholm_options['header_bottom_border_color'])) {
			$header_height = $header_height + 1;
		}
		if($header_bottom_appearance == "stick menu_bottom") {
			$menu_bottom = 60; // border 1px
			if ($stockholm_options['center_logo_image'] == "yes") {
				if(is_active_sidebar('header_fixed_right')){
					$menu_bottom = $menu_bottom + 26; // 26 is for right widget in header bottom (line height of text)
				}
			}
		} else {
			$menu_bottom = 0;
		}
		$header_height = $header_height + $menu_bottom;
		?>
		
		<?php if ($header_bottom_appearance != "fixed" && $header_bottom_appearance != "fixed_hiding") {?>
			<?php if ($stockholm_options['center_logo_image'] != "yes") { ?>
				.content{
				margin-top: <?php echo '-'.intval($margin_top_add + $header_height); ?>px;
				}
			<?php } else {
				$height = 0;
				?>
				<?php if ( isset( $stockholm_options['logo_image'] ) && ! empty( $stockholm_options['logo_image'] ) ) {
					$image_dimension = stockholm_qode_get_image_dimensions( $stockholm_options['logo_image'] );
					
					if ( ! empty( $image_dimension ) ) {
						$width = intval( $image_dimension['width'] );
						$height = intval( $image_dimension['height'] );
					}
				} ?>
				<?php if($header_bottom_appearance == "stick menu_bottom") { ?>
					.content{
					margin-top: <?php echo '-'.intval(20 + $height + $menu_bottom + $margin_top_add); // 20 is top margin of centered logo ?>px;
					}
				<?php }  else { ?>
					.content{
					margin-top: <?php echo '-'.intval(20 + $height + $header_height + $margin_top_add); // 20 is top margin of centered logo ?>px;
					}
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
			.content{
			margin-top: 0;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['header_height'])) { ?>
			.logo_wrapper,
			.side_menu_button,
			.shopping_cart_inner{
			height: <?php echo intval($stockholm_options['header_height']);  ?>px;
			}
			
			<?php if($header_bottom_appearance == "fixed_hiding") { ?>
				.content.content_top_margin{
				margin-top: <?php echo intval($stockholm_options['header_height'] + $margin_top_add + 50 + 40); // 50 is logo height for fixed advanced header type, 40 is top and bottom margin of logo ?>px !important;
				}
			<?php } elseif($header_bottom_appearance == "fixed" && $stockholm_options['center_logo_image'] == "yes"){ ?>
				<?php
				$logo_height = 65; // 65 is half height of stockholm logo because of retina
				
				if ( isset( $stockholm_options['logo_image'] ) && ! empty( $stockholm_options['logo_image'] ) ) {
					$image_dimension = stockholm_qode_get_image_dimensions( $stockholm_options['logo_image'] );
					
					if ( ! empty( $image_dimension ) ) {
						$logo_width = intval( $image_dimension['width'] );
						$logo_height = intval( $image_dimension['height'] );
					}
				} ?>
				.content.content_top_margin{
				margin-top: <?php echo intval($stockholm_options['header_height'] + $margin_top_add + $logo_height + 20); // 20 is top margin of logo ?>px !important;
				}
			<?php } else { ?>
				.content.content_top_margin{
				margin-top: <?php echo intval($stockholm_options['header_height'] + $margin_top_add);  ?>px !important;
				}
			<?php } ?>
			
			header:not(.centered_logo) .header_fixed_right_area {
			line-height: <?php echo intval($stockholm_options['header_height']);  ?>px;
			}
		<?php } else { ?>
			.content.content_top_margin{
			margin-top: <?php echo intval($header_height + $margin_top_add); ?>px !important;
			}
		<?php } ?>
		
		<?php
		$search_text_style = array();
		
		if(isset($stockholm_options['search_text_google_fonts']) && $stockholm_options['search_text_google_fonts'] !== '-1') {
			$search_text_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['search_text_google_fonts']).'", sans-serif';
		}
		
		if(isset($stockholm_options['search_text_font_size']) && $stockholm_options['search_text_font_size'] !== '') {
			$search_text_style[] = 'font-size: '.intval($stockholm_options['search_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['search_text_line_height']) && $stockholm_options['search_text_line_height'] !== '') {
			$search_text_style[] = 'line-height: '.intval($stockholm_options['search_text_line_height']).'px'; ?>
			.qode_search_form i {
			line-height: <?php echo intval($stockholm_options['search_text_line_height'] + 15 + 15); ?>px; /* 15 is margin top and margin bottom */
			}
			.qode_search_form input, .qode_search_form input:focus {
			height: <?php echo intval($stockholm_options['search_text_line_height']); ?>px;
			}
			<?php
		}
		
		if(isset($stockholm_options['search_text_letter_spacing']) && $stockholm_options['search_text_letter_spacing'] !== '') {
			$search_text_style[] = 'letter-spacing: '.intval($stockholm_options['search_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['search_text_font_weight']) && $stockholm_options['search_text_font_weight'] !== '') {
			$search_text_style[] = 'font-weight: '.esc_attr($stockholm_options['search_text_font_weight']);
		}
		
		if(isset($stockholm_options['search_text_font_style']) && $stockholm_options['search_text_font_style'] !== '') {
			$search_text_style[] = 'font-style: '.esc_attr($stockholm_options['search_text_font_style']);
		}
		
		if(isset($stockholm_options['search_text_text_transform']) && $stockholm_options['search_text_text_transform'] !== '') {
			$search_text_style[] = 'text-transform: '.esc_attr($stockholm_options['search_text_text_transform']);
		}
		
		if(isset($stockholm_options['search_text_color']) && $stockholm_options['search_text_color'] !== '') {
			$search_text_style[] = 'color: '.esc_attr($stockholm_options['search_text_color']);
		}
		
		if(is_array($search_text_style) && count($search_text_style)) { ?>
			.qode_search_form,
			.qode_search_form input,
			.qode_search_form input:focus,
			.fullscreen_search_holder .qode_search_field{
			<?php echo implode(';', $search_text_style); ?>
			}
		<?php }  ?>
		
		<?php if(!empty($stockholm_options['search_background_color'])){ ?>
			.qode_search_form,
			.qode_search_form input,
			.qode_search_form input:focus{
			<?php if(!empty($stockholm_options['search_background_color'])){ ?>background-color: <?php echo esc_attr($stockholm_options['search_background_color']); ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['search_background_color'])){ ?>
			.fullscreen_search_holder .fullscreen_search_table{
			<?php if(!empty($stockholm_options['search_background_color'])){ ?>background-color: <?php echo esc_attr($stockholm_options['search_background_color']); ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['search_text_color'])){ ?>
			.fullscreen_search_holder  .qode_search_field::-webkit-input-placeholder,
			.qode_search_form input::-webkit-input-placeholder {
			<?php if(!empty($stockholm_options['search_text_color'])){ ?>color: <?php echo esc_attr($stockholm_options['search_text_color']); ?>; <?php } ?>
			}
		<?php } ?>
		<?php if(!empty($stockholm_options['search_text_color'])){ ?>
			.fullscreen_search_holder  .qode_search_field::-moz-placeholder,
			.qode_search_form input::-moz-placeholder{
			<?php if(!empty($stockholm_options['search_text_color'])){ ?>color: <?php echo esc_attr($stockholm_options['search_text_color']); ?>; <?php } ?>
			}
		<?php } ?>
		<?php if(!empty($stockholm_options['search_text_color'])){ ?>
			.fullscreen_search_holder  .qode_search_field:-ms-input-placeholder,
			.qode_search_form input:-ms-input-placeholder {
			<?php if(!empty($stockholm_options['search_text_color'])){ ?>color: <?php echo esc_attr($stockholm_options['search_text_color']); ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php
		$search_icon_style = array();
		
		if(isset($stockholm_options['search_icon_color']) && $stockholm_options['search_icon_color'] !== '') {
			$search_icon_style[] = 'color: '.esc_attr($stockholm_options['search_icon_color']);
		}
		if(isset($stockholm_options['search_icon_font_size']) && $stockholm_options['search_icon_font_size'] !== '') {
			$search_icon_style[] = 'font-size: '.intval($stockholm_options['search_icon_font_size']).'px';
		}
		if(isset($stockholm_options['search_icon_line_height']) && $stockholm_options['search_icon_line_height'] !== '') {
			$search_icon_style[] = 'line-height: '.intval($stockholm_options['search_icon_line_height']).'px';
		}
		
		if(is_array($search_icon_style) && count($search_icon_style)) { ?>
			.fullscreen_search_holder .search_submit{
			<?php echo implode(';', $search_icon_style); ?>
			}
		<?php }  ?>
		
		<?php
		if(isset($stockholm_options['search_icon_hover_color']) && $stockholm_options['search_icon_hover_color'] !== '') { ?>
			.fullscreen_search_holder .search_submit:hover{
			color: <?php echo esc_attr($stockholm_options['search_icon_hover_color']);  ?>;
			}
		<?php } ?>
		
		
		<?php  if(isset($stockholm_options['search_colose_icon_color']) && $stockholm_options['search_colose_icon_color'] !== '') { ?>
			.fullscreen_search_holder .fullscreen_search_close .line:before,
			.fullscreen_search_holder .fullscreen_search_close .line:after{
			background-color: <?php echo esc_attr($stockholm_options['search_colose_icon_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['header_height_scroll'])) { ?>
			header.scrolled .logo_wrapper,
			header.scrolled .side_menu_button{
			height: <?php echo intval($stockholm_options['header_height_scroll']);  ?>px;
			}
			
			header.scrolled nav.main_menu ul li a {
			line-height: <?php echo intval($stockholm_options['header_height_scroll']);  ?>px;
			}
			
			header.scrolled .drop_down .second{
			top: <?php echo intval($stockholm_options['header_height_scroll']);  ?>px;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['header_height_sticky'])) { ?>
			header.sticky .logo_wrapper,
			header.sticky.centered_logo .logo_wrapper,
			header.sticky .side_menu_button,
			header.sticky .shopping_cart_inner{
			height: <?php echo intval($stockholm_options['header_height_sticky']);  ?>px !important;
			}
			
			header.sticky nav.main_menu > ul > li > a,
			.light.sticky nav.main_menu > ul > li > a,
			.light.sticky nav.main_menu > ul > li > a:hover,
			.light.sticky nav.main_menu > ul > li.active > a,
			.dark.sticky nav.main_menu > ul > li > a,
			.dark.sticky nav.main_menu > ul > li > a:hover,
			.dark.sticky nav.main_menu > ul > li.active > a,
			header.sticky .header_bottom .qode-login-register-widget.qode-user-not-logged-in .qode-login-opener,
			header.sticky .header_bottom .qode-login-register-widget.qode-user-logged-in .qode-logged-in-user .qode-logged-in-user-inner > span{
			line-height: <?php echo intval($stockholm_options['header_height_sticky']);  ?>px;
			}
			
			header.sticky .drop_down .second{
			top: <?php echo intval($stockholm_options['header_height_sticky'] + $header_bottom_border_width);  ?>px;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['disable_text_shadow_for_sticky']) && $stockholm_options['disable_text_shadow_for_sticky'] == "yes") { ?>
			header.sticky .header_bottom,
			header.fixed.scrolled .header_bottom,
			header.fixed_hiding.scrolled .header_bottom{
			box-shadow: none;
			-webkit-box-shadow: none;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['header_height_scroll_hidden']) && $stockholm_options['header_height_scroll_hidden'] !== "") { ?>
			@media only screen and (min-width: 1000px){
			header.fixed_hiding.centered_logo.fixed_hiding .header_inner_left,
			header.fixed_hiding .q_logo_hidden a{
			height: <?php echo intval($stockholm_options['header_height_scroll_hidden']);  ?>px;
			}
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['header_height_mobile']) && $stockholm_options['header_height_mobile'] !== "") { ?>
			@media only screen and (max-width: 1000px){
			.mobile_menu_button, .logo_wrapper, .side_menu_button, .shopping_cart_inner{
			height: <?php echo intval($stockholm_options['header_height_mobile']); ?>px !important;
			}
			}
		<?php } ?>
		<?php if ( isset( $stockholm_options['logo_image'] ) && ! empty( $stockholm_options['logo_image'] ) ) {
			$image_dimension = stockholm_qode_get_image_dimensions( $stockholm_options['logo_image'] );
			
			if ( ! empty( $image_dimension ) ) {
				$logo_width = intval( $image_dimension['width'] );
				$logo_height = intval( $image_dimension['height'] );
			}
			?>
			@media only screen and (min-width: 1000px){
			header.fixed_hiding .q_logo a,
			header.fixed_hiding .q_logo{
			max-height: <?php echo intval( $logo_height / 2 ); ?>px;
			}
			}
			<?php
		} ?>
		
		<?php if((isset($stockholm_options['color_border_top_bottom_menu']) && $stockholm_options['color_border_top_bottom_menu'] != "")){ ?>
			header.centered_logo .main_menu_and_widget_holder{
			border-color: <?php echo intval($stockholm_options['color_border_top_bottom_menu']); ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['logo_mobile_header_height']) && !empty($stockholm_options['logo_mobile_header_height'])){ ?>
			@media only screen and (max-width: 1000px){
			.q_logo a{
			height: <?php echo intval($stockholm_options['logo_mobile_header_height']); ?>px !important;
			}
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['logo_mobile_height']) && !empty($stockholm_options['logo_mobile_height'])){ ?>
			@media only screen and (max-width: 480px){
			.q_logo a{
			height: <?php echo intval($stockholm_options['logo_mobile_height']); ?>px !important;
			}
			}
		<?php } ?>
		
		<?php
		$parallax_onoff = "on";
		if (isset($stockholm_options['parallax_onoff']))
			$parallax_onoff = $stockholm_options['parallax_onoff'];
		if ($parallax_onoff == "off"){
			?>
			.touch section.parallax_section_holder{
			height: auto !important;
			min-height: 300px;
			background-position: center top !important;
			background-attachment: scroll;
			background-size: cover;
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['header_height'])) { ?>
			nav.main_menu > ul > li > a{
			line-height: <?php echo intval($stockholm_options['header_height']);  ?>px;
			}
		<?php } ?>
		
		<?php
		if((isset($stockholm_options['dropdown_background_color']) && $stockholm_options['dropdown_background_color'] != "") ||
		   (isset($stockholm_options['dropdown_background_transparency'])) && $stockholm_options['dropdown_background_transparency'] != "") {
			
			//dropdown background and transparency styles
			$dropdown_bg_styles                 = '';
			$dropdown_bg_color                  = '';
			$dropdown_bg_color_initial          = '#ffffff';
			$dropdown_bg_transparency           = '';
			$dropdown_bg_transparency_initial   = 1;
			
			$dropdown_bg_color        = $stockholm_options['dropdown_background_color'] != "" ? $stockholm_options['dropdown_background_color'] : $dropdown_bg_color_initial;
			$dropdown_bg_transparency = $stockholm_options['dropdown_background_transparency'] != "" ? $stockholm_options['dropdown_background_transparency'] : $dropdown_bg_transparency_initial;
			
			$dropdown_bg_color_rgb    = stockholm_qode_hex2rgb($dropdown_bg_color);
			
			?>
			
			.drop_down .second .inner ul,
			.drop_down .second .inner ul li ul,
			.shopping_cart_dropdown,
			li.narrow .second .inner ul,
			.drop_down .wide.wide_background .second,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown{
			background-color: <?php echo esc_attr($dropdown_bg_color);  ?>;
			background-color: rgba(<?php echo esc_attr($dropdown_bg_color_rgb[0]); ?>,<?php echo esc_attr($dropdown_bg_color_rgb[1]); ?>,<?php echo esc_attr($dropdown_bg_color_rgb[2]); ?>,<?php echo esc_attr($dropdown_bg_transparency); ?>);
			}
		
		<?php  } //end dropdown background and transparency styles ?>
		
		<?php if(!empty($stockholm_options['disable_dropdown_top_separator']) && $stockholm_options['disable_dropdown_top_separator'] == "yes"){?>
			.drop_down .second,
			.drop_down .narrow .second .inner ul li ul,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown
			{
			border: 0 !important;
			}
			.drop_down .narrow .second .inner ul li ul{
			top: -14px;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['dropdown_separator_color'])){?>
			.drop_down .second .inner ul li a,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown li a{
			border-color: <?php echo esc_attr($stockholm_options['dropdown_separator_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['menu_color']) || !empty($stockholm_options['menu_fontsize']) || !empty($stockholm_options['menu_fontstyle']) || !empty($stockholm_options['menu_fontweight']) || !empty($stockholm_options['menu_texttransform']) || (isset($stockholm_options['menu_letter_spacing']) && $stockholm_options['menu_letter_spacing'] !== '') || $stockholm_options['menu_google_fonts'] != "-1") { ?>
			nav.main_menu > ul > li > a,
			.header_bottom .qode-login-register-widget.qode-user-not-logged-in .qode-login-opener,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-logged-in-user .qode-logged-in-user-inner > span{
			<?php if (!empty($stockholm_options['menu_color'])) { ?> color: <?php echo esc_attr($stockholm_options['menu_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['menu_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['menu_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['menu_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['menu_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['menu_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['menu_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['menu_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['menu_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['menu_fontweight'])) { ?> font-weight: <?php echo esc_attr($stockholm_options['menu_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['menu_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['menu_texttransform']);  ?>;  <?php } ?>
			<?php if ($stockholm_options['menu_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['menu_letterspacing']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['menu_hovercolor'])) { ?>
			nav.main_menu ul li:hover a,
			header:not(.with_hover_bg_color) nav.main_menu > ul > li:hover > a,
			.header_bottom .qode-login-register-widget.qode-user-not-logged-in .qode-login-opener:hover,
			.header_bottom .qode-login-register-widget.qode-user-logged-in:hover .qode-logged-in-user .qode-logged-in-user-inner > span{
			color: <?php echo esc_attr($stockholm_options['menu_hovercolor']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['menu_activecolor']) && $stockholm_options['menu_activecolor'] !== '') { ?>
			nav.main_menu > ul > li.active > a{
			color: <?php echo esc_attr($stockholm_options['menu_activecolor']); ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['menu_separator_color']) || !empty($stockholm_options['menu_fontsize'])) { ?>
			nav.main_menu > ul > li:not(:first-child):before,
			header.stick_with_left_right_menu.sticky nav.main_menu.right_side > ul > li:not(:first-child):before{
			<?php if (!empty($stockholm_options['menu_separator_color'])) { ?> color: <?php echo esc_attr($stockholm_options['menu_separator_color']); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['menu_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['menu_fontsize']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['menu_remove_separator_between_items']) && $stockholm_options['menu_remove_separator_between_items'] == 'yes'){ ?>
			nav.main_menu > ul > li:not(:first-child):before,
			header.stick_with_left_right_menu.sticky nav.main_menu.right_side > ul > li:not(:first-child):before,
			header.stick_with_left_right_menu.sticky nav.main_menu.right_side > ul > li:first-child:before{
			content: none;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['menu_padding_left_right'])){ ?>
			nav.main_menu > ul > li > a{
			<?php if (!empty($stockholm_options['menu_padding_left_right'])) { ?> padding: 0  <?php echo intval($stockholm_options['menu_padding_left_right']); ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['menu_hover_background_color']) && $stockholm_options['menu_hover_background_color'] !== '') {
			$menu_hover_background_color = $stockholm_options['menu_hover_background_color'];
			
			if(isset($stockholm_options['menu_hover_background_color_transparency']) && $stockholm_options['menu_hover_background_color_transparency'] !== '') {
				$menu_hover_background_color_rgb = stockholm_qode_hex2rgb($menu_hover_background_color);
				$menu_hover_background_color = 'rgba('.esc_attr($menu_hover_background_color_rgb[0]).', '.esc_attr($menu_hover_background_color_rgb[1]).', '.esc_attr($menu_hover_background_color_rgb[2]).', '.esc_attr($stockholm_options['menu_hover_background_color_transparency']).')';
			}
			?>
			nav.main_menu > ul > li:hover > a,
			header.sticky nav.main_menu > ul > li:hover > a {
			<?php if($stockholm_options['menu_hover_background_color'] !== '') { ?>
				background-color: <?php echo esc_attr($menu_hover_background_color); ?>;
			<?php } ?>
			}
			
			<?php
			
			if(isset($stockholm_options['menu_hovercolor']) && $stockholm_options['menu_hovercolor'] !== '') {
				?>
				header:not(.with_hover_bg_color) nav.main_menu > ul > li:hover > a,
				.dark nav.main_menu > ul > li:hover > a,
				.light header.sticky nav.main_menu > ul > li:hover > a {
				color: <?php echo esc_attr($stockholm_options['menu_hovercolor']); ?> !important;
				}
				<?php
			}
			?>
		<?php } ?>
		
		<?php if(!empty($stockholm_options['dropdown_color']) || !empty($stockholm_options['dropdown_fontsize']) || !empty($stockholm_options['dropdown_lineheight']) || !empty($stockholm_options['dropdown_fontstyle']) || !empty($stockholm_options['dropdown_fontweight']) || $stockholm_options['dropdown_google_fonts'] != "-1" || !empty($stockholm_options['dropdown_texttransform'])  || $stockholm_options['dropdown_letterspacing'] !== ''){ ?>
			.drop_down .second .inner > ul > li > a,
			.drop_down .second .inner > ul > li > h4,
			.drop_down .wide .second .inner > ul > li > h4,
			.drop_down .wide .second .inner > ul > li > a,
			.drop_down .wide .second ul li ul li.menu-item-has-children > a,
			.drop_down .wide .second .inner ul li.sub ul li.menu-item-has-children > a,
			.drop_down .wide .second .inner > ul li.sub .flexslider ul li  h4 a,
			.drop_down .wide .second .inner > ul li .flexslider ul li  h4 a,
			.drop_down .wide .second .inner > ul li.sub .flexslider ul li  h4,
			.drop_down .wide .second .inner > ul li .flexslider ul li  h4,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown li a{
			<?php if (!empty($stockholm_options['dropdown_color'])) { ?> color: <?php echo esc_attr($stockholm_options['dropdown_color']); ?>; <?php } ?>
			<?php if($stockholm_options['dropdown_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['dropdown_google_fonts']) ?>', sans-serif !important;
			<?php } ?>
			<?php if (!empty($stockholm_options['dropdown_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['dropdown_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['dropdown_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['dropdown_fontstyle']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['dropdown_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['dropdown_texttransform']);  ?>;  <?php } ?>
			<?php if ($stockholm_options['dropdown_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['dropdown_letterspacing']);  ?>px;  <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['dropdown_hovercolor'])) { ?>
			.drop_down .second .inner > ul > li > a:hover,
			.drop_down .wide .second ul li ul li.menu-item-has-children > a:hover,
			.drop_down .wide .second .inner ul li.sub ul li.menu-item-has-children > a:hover,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown li a:hover{
			color: <?php echo esc_attr($stockholm_options['dropdown_hovercolor']);  ?> !important;
			}
			.drop_down .second,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown{
			border-color: <?php echo esc_attr($stockholm_options['dropdown_hovercolor']);  ?>;
			}
		<?php } ?>
		<?php if(!empty($stockholm_options['dropdown_top_separator_color'])){?>
			.drop_down .second,
			.drop_down .narrow .second .inner ul li ul,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown{
			border-color: <?php echo esc_attr($stockholm_options['dropdown_top_separator_color']); ?>;
			}
		<?php } ?>
		<?php if(!empty($stockholm_options['dropdown_top_separator_thickness']) && $stockholm_options['dropdown_top_separator_thickness'] != ''){?>
			.drop_down .second,
			.drop_down .narrow .second .inner ul li ul,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown{
			border-width: <?php echo intval($stockholm_options['dropdown_top_separator_thickness']); ?>px;
			}
		<?php } ?>
		<?php if(!empty($stockholm_options['dropdown_padding_top_bottom'])){ ?>
			.drop_down .wide .second>.inner>ul>li.sub>ul>li>a,
			.drop_down .second .inner ul li a,
			.drop_down .wide .second ul li a,
			.drop_down .second .inner ul.right li a,
			.header_bottom .qode-login-register-widget.qode-user-logged-in .qode-login-dropdown li a{
			<?php if (!empty($stockholm_options['dropdown_padding_top_bottom'])) { ?> padding-top: <?php echo intval($stockholm_options['dropdown_padding_top_bottom']); ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_padding_top_bottom'])) { ?> padding-bottom: <?php echo intval($stockholm_options['dropdown_padding_top_bottom']); ?>px; <?php } ?>
			}
		<?php } ?>
		<?php if(isset($stockholm_options['dropdown_border_around']) && $stockholm_options['dropdown_border_around'] == "yes"){ ?>
			.drop_down .second .inner>ul, li.narrow .second .inner ul,
			.header_bottom .qode-login-register-widget .qode-login-dropdown{
			border-style:solid;
			border-width:1px;
			}
		
		<?php } ?>
		<?php if(!empty($stockholm_options['dropdown_wide_color']) || !empty($stockholm_options['dropdown_wide_fontsize']) || !empty($stockholm_options['dropdown_wide_lineheight']) || !empty($stockholm_options['dropdown_wide_fontstyle']) || !empty($stockholm_options['dropdown_wide_fontweight']) || $stockholm_options['dropdown_wide_google_fonts'] != "-1" || !empty($stockholm_options['dropdown_wide_texttransform'])  || $stockholm_options['dropdown_wide_letterspacing'] !== ''){ ?>
			.drop_down .wide .second .inner>ul>li>a{
			<?php if (!empty($stockholm_options['dropdown_wide_color'])) { ?> color: <?php echo esc_attr($stockholm_options['dropdown_wide_color']); ?>; <?php } ?>
			<?php if($stockholm_options['dropdown_wide_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['dropdown_wide_google_fonts']) ?>', sans-serif !important;
			<?php } ?>
			<?php if (!empty($stockholm_options['dropdown_wide_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['dropdown_wide_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_wide_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['dropdown_wide_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_wide_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['dropdown_wide_fontstyle']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_wide_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['dropdown_wide_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_wide_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['dropdown_wide_texttransform']);  ?>;  <?php } ?>
			<?php if ($stockholm_options['dropdown_wide_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['dropdown_wide_letterspacing']);  ?>px;  <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['dropdown_wide_hovercolor'])) { ?>
			.drop_down .wide .second .inner>ul>li>a:hover{
			color: <?php echo esc_attr($stockholm_options['dropdown_wide_hovercolor']);  ?> !important;
			}
		<?php } ?>
		<?php if(!empty($stockholm_options['dropdown_color_thirdlvl']) || !empty($stockholm_options['dropdown_fontsize_thirdlvl']) || !empty($stockholm_options['dropdown_lineheight_thirdlvl']) || !empty($stockholm_options['dropdown_fontstyle_thirdlvl']) || !empty($stockholm_options['dropdown_fontweight_thirdlvl']) || $stockholm_options['dropdown_google_fonts_thirdlvl'] != "-1" || !empty($stockholm_options['dropdown_texttransform_thirdlvl']) || $stockholm_options['dropdown_letterspacing_thirdlvl'] !== ''){ ?>
			.drop_down .wide .second .inner ul li.sub ul li a,
			.drop_down .wide .second ul li ul li a,
			.drop_down .second .inner ul li.sub ul li a{
			<?php if (!empty($stockholm_options['dropdown_color_thirdlvl'])) { ?> color: <?php echo esc_attr($stockholm_options['dropdown_color_thirdlvl']);  ?>;  <?php } ?>
			<?php if($stockholm_options['dropdown_google_fonts_thirdlvl'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['dropdown_google_fonts_thirdlvl']) ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['dropdown_fontsize_thirdlvl'])) { ?> font-size: <?php echo intval($stockholm_options['dropdown_fontsize_thirdlvl']);  ?>px;  <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_lineheight_thirdlvl'])) { ?> line-height: <?php echo intval($stockholm_options['dropdown_lineheight_thirdlvl']);  ?>px;  <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_fontstyle_thirdlvl'])) { ?> font-style: <?php echo esc_attr($stockholm_options['dropdown_fontstyle_thirdlvl']);  ?>;   <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_fontweight_thirdlvl'])) { ?> font-weight: <?php echo esc_attr($stockholm_options['dropdown_fontweight_thirdlvl']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['dropdown_texttransform_thirdlvl'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['dropdown_texttransform_thirdlvl']);  ?>;  <?php } ?>
			<?php if ($stockholm_options['dropdown_letterspacing_thirdlvl'] !== '') { ?> letter-spacing: <?php echo esc_attr($stockholm_options['dropdown_letterspacing_thirdlvl']);  ?>;  <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['dropdown_hovercolor_thirdlvl'])) { ?>
			.drop_down .second .inner ul li.sub ul li a:hover,
			.drop_down .second .inner ul li ul li a:hover{
			color: <?php echo esc_attr($stockholm_options['dropdown_hovercolor_thirdlvl']);  ?> !important;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['fixed_color']) || !empty($stockholm_options['fixed_fontsize']) || !empty($stockholm_options['fixed_lineheight']) || !empty($stockholm_options['fixed_fontstyle']) || !empty($stockholm_options['fixed_fontweight']) || !empty($stockholm_options['fixed_texttransform']) || (isset($stockholm_options['fixed_letterspacing']) && $stockholm_options['fixed_letterspacing'] !== '') || $stockholm_options['fixed_google_fonts'] != "-1"){ ?>
			header.scrolled nav.main_menu > ul > li > a,
			header.light.scrolled nav.main_menu > ul > li > a,
			header.dark.scrolled nav.main_menu > ul > li > a{
			<?php if (!empty($stockholm_options['fixed_color'])) { ?> color: <?php echo esc_attr($stockholm_options['fixed_color']); ?>; <?php } ?>
			<?php if($stockholm_options['fixed_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['fixed_google_fonts']) ?>', sans-serif !important;
			<?php } ?>
			<?php if (!empty($stockholm_options['fixed_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['fixed_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['fixed_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['fixed_lineheight']);  ?>px !important; <?php } ?>
			<?php if (!empty($stockholm_options['fixed_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['fixed_fontstyle']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['fixed_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['fixed_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['fixed_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['fixed_texttransform']);  ?>;  <?php } ?>
			<?php if ($stockholm_options['fixed_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['fixed_letterspacing']);  ?>px;  <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['fixed_color'])) { ?>
			header.scrolled .side_menu_button a {
			<?php if (!empty($stockholm_options['fixed_color'])) { ?> color: <?php echo esc_attr($stockholm_options['fixed_color']); ?> !important; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['fixed_hovercolor'])) { ?>
			header.scrolled nav.main_menu > ul > li > a:hover > span,
			header.scrolled nav.main_menu > ul > li:hover > a > span,
			header.scrolled nav.main_menu > ul > li.active > a > span,
			header.scrolled nav.main_menu > ul > li > a:hover > i,
			header.scrolled nav.main_menu > ul > li:hover > a > i,
			header.scrolled nav.main_menu > ul > li.active > a > i,
			header.scrolled .side_menu_button a:hover,
			.light.scrolled nav.main_menu > ul > li > a:hover,
			.light.scrolled nav.main_menu > ul > li.active > a,
			.light.scrolled .side_menu_button a:hover,
			.dark.scrolled nav.main_menu > ul > li > a:hover,
			.dark.scrolled nav.main_menu > ul > li.active > a,
			.dark.scrolled .side_menu_button a:hover {
			color: <?php echo esc_attr($stockholm_options['fixed_hovercolor']);  ?> !important;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['sticky_color']) || !empty($stockholm_options['sticky_fontsize']) || !empty($stockholm_options['sticky_lineheight']) || !empty($stockholm_options['sticky_fontstyle']) || !empty($stockholm_options['sticky_fontweight']) || !empty($stockholm_options['sticky_texttransform']) || (isset($stockholm_options['sticky_letterspacing']) && $stockholm_options['sticky_letterspacing'] !== '') || $stockholm_options['sticky_google_fonts'] != "-1"){ ?>
			header.sticky nav.main_menu > ul > li > a,
			header.light.sticky nav.main_menu > ul > li > a,
			header.dark.sticky nav.main_menu > ul > li > a{
			<?php if (!empty($stockholm_options['sticky_color'])) { ?> color: <?php echo esc_attr($stockholm_options['sticky_color']); ?>; <?php } ?>
			<?php if($stockholm_options['sticky_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['sticky_google_fonts']) ?>', sans-serif !important;
			<?php } ?>
			<?php if (!empty($stockholm_options['sticky_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['sticky_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['sticky_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['sticky_lineheight']);  ?>px !important; <?php } ?>
			<?php if (!empty($stockholm_options['sticky_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['sticky_fontstyle']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['sticky_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['sticky_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['sticky_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['sticky_texttransform']);  ?>;  <?php } ?>
			<?php if ($stockholm_options['sticky_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['sticky_letterspacing']);  ?>px;  <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['sticky_color'])) { ?>
			header.sticky .side_menu_button a,
			header.sticky .side_menu_button a:hover{
			<?php if (!empty($stockholm_options['sticky_color'])) { ?> color: <?php echo esc_attr($stockholm_options['sticky_color']); ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['sticky_hovercolor'])) { ?>
			header.sticky nav.main_menu > ul > li > a:hover span,
			header.sticky nav.main_menu > ul > li.active > a span,
			header.sticky nav.main_menu > ul > li:hover > a > span,
			header.sticky nav.main_menu > ul > li > a:hover > i,
			header.sticky nav.main_menu > ul > li:hover > a > i,
			header.sticky nav.main_menu > ul > li.active > a > i,
			.light.sticky nav.main_menu > ul > li > a:hover,
			.light.sticky nav.main_menu > ul > li.active > a,
			.dark.sticky nav.main_menu > ul > li > a:hover,
			.dark.sticky nav.main_menu > ul > li.active > a{
			color: <?php echo esc_attr($stockholm_options['sticky_hovercolor']);  ?> !important;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['mobile_color']) || !empty($stockholm_options['mobile_fontsize']) || !empty($stockholm_options['mobile_lineheight']) || !empty($stockholm_options['mobile_fontstyle']) || !empty($stockholm_options['mobile_fontweight']) || !empty($stockholm_options['mobile_texttransform']) || $stockholm_options['mobile_letter_spacing'] !== '' || $stockholm_options['mobile_google_fonts'] != "-1") { ?>
			nav.mobile_menu ul li a,
			nav.mobile_menu ul li h4{
			<?php if (!empty($stockholm_options['mobile_color'])) { ?> color: <?php echo esc_attr($stockholm_options['mobile_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['mobile_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['mobile_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['mobile_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['mobile_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['mobile_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['mobile_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['mobile_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['mobile_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['mobile_fontweight'])) { ?> font-weight: <?php echo esc_attr($stockholm_options['mobile_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['mobile_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['mobile_texttransform']);  ?>;  <?php } ?>
			<?php if ($stockholm_options['mobile_letter_spacing'] !== ''){ ?>letter-spacing: <?php echo intval($stockholm_options['mobile_letter_spacing']);  ?>px;<?php } ?>
			}
			<?php if (!empty($stockholm_options['mobile_color'])) { ?>
				nav.mobile_menu ul > li.has_sub > span.mobile_arrow{
				color: <?php echo esc_attr($stockholm_options['mobile_color']);  ?>;
				}
			<?php } ?>
		<?php } ?>
		
		<?php if (!empty($stockholm_options['mobile_hovercolor'])) { ?>
			nav.mobile_menu ul li a:hover,
			nav.mobile_menu ul li.active > a{
			color: <?php echo esc_attr($stockholm_options['mobile_hovercolor']);  ?>;
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['mobile_separator_color'])) { ?>
			nav.mobile_menu ul li,
			nav.mobile_menu ul li.open_sub > ul{
			border-color: <?php echo esc_attr($stockholm_options['mobile_separator_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['mobile_background_color'])) { ?>
			@media only screen and (max-width: 1000px){
			.header_bottom,
			nav.mobile_menu{
			background-color: <?php echo esc_attr($stockholm_options['mobile_background_color']);  ?> !important;
			}
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['vertical_mobile_background_color'])) { ?>
			@media only screen and (max-width: 1000px){
			.header_bottom,
			nav.mobile_menu{
			background-color: <?php echo esc_attr($stockholm_options['vertical_mobile_background_color']);  ?> !important;
			}
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['interactive_banners_background_color']) && !empty($stockholm_options['interactive_banners_background_color'])) { ?>
			<?php $shader_bg_color = stockholm_qode_hex2rgb($stockholm_options['interactive_banners_background_color']); ?>
			
			.q_image_with_text_over .shader{
			background-color: rgba(<?php echo esc_attr($shader_bg_color[0]); ?>,<?php echo esc_attr($shader_bg_color[1]); ?>,<?php echo esc_attr($shader_bg_color[2]); ?>,0.9);
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['interactive_banners_hover_background_color']) && !empty($stockholm_options['interactive_banners_hover_background_color'])) { ?>
			<?php $shader_hover_bg_color = stockholm_qode_hex2rgb($stockholm_options['interactive_banners_hover_background_color']); ?>
			
			.q_image_with_text_over:hover .shader{
			background-color: rgba(<?php echo esc_attr($shader_hover_bg_color[0]); ?>,<?php echo esc_attr($shader_hover_bg_color[1]); ?>,<?php echo esc_attr($shader_hover_bg_color[2]); ?>,0.9);
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['input_background_color']) || !empty($stockholm_options['input_border_color']) || !empty($stockholm_options['input_text_color'])) { ?>
			#respond textarea,
			#respond input[type='text'],
			#respond input[type='email'],
			.contact_form input[type='text'],
			.contact_form input[type='email'],
			.contact_form  textarea,
			.comment_holder #respond textarea,
			.comment_holder #respond input[type='text'],
			input.wpcf7-form-control.wpcf7-text,
			input.wpcf7-form-control.wpcf7-number,
			input.wpcf7-form-control.wpcf7-date,
			textarea.wpcf7-form-control.wpcf7-textarea,
			select.wpcf7-form-control.wpcf7-select,
			input.wpcf7-form-control.wpcf7-quiz,
			.qode-social-login-holder input[type="text"],
			.qode-social-login-holder input[type="email"],
			.qode-social-login-holder input[type="password"],
			.qode-social-register-holder input[type="text"],
			.qode-social-register-holder input[type="email"],
			.qode-social-register-holder input[type="password"],
			.qode-social-reset-password-holder input[type="text"],
			.qode-social-reset-password-holder input[type="email"],
			.qode-social-reset-password-holder input[type="password"],
			.qode-membership-input-holder .qode-membership-input{
			<?php if (!empty($stockholm_options['input_background_color'])) { ?>background-color: <?php echo esc_attr($stockholm_options['input_background_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['input_border_color'])) { ?>border: 1px solid <?php echo esc_attr($stockholm_options['input_border_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['input_text_color'])) { ?>color:<?php echo esc_attr($stockholm_options['input_text_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php
		
		if(!empty($stockholm_options['input_focus_text_color']) || !empty($stockholm_options['input_focus_background_color']) || !empty($stockholm_options['input_focus_border_color'])) { ?>
			#respond textarea:focus,
			#respond input[type='text']:focus,
			#respond input[type='email']:focus,
			.contact_form input[type='text']:focus,
			.contact_form input[type='email']:focus,
			.contact_form  textarea:focus,
			.comment_holder #respond textarea:focus,
			.comment_holder #respond input[type='text']:focus,
			input.wpcf7-form-control.wpcf7-text:focus,
			input.wpcf7-form-control.wpcf7-number:focus,
			input.wpcf7-form-control.wpcf7-date:focus,
			textarea.wpcf7-form-control.wpcf7-textarea:focus,
			select.wpcf7-form-control.wpcf7-select:focus,
			input.wpcf7-form-control.wpcf7-quiz:focus,
			.qode-social-login-holder input[type="text"]:focus,
			.qode-social-login-holder input[type="email"]:focus,
			.qode-social-login-holder input[type="password"]:focus,
			.qode-social-register-holder input[type="text"]:focus,
			.qode-social-register-holder input[type="email"]:focus,
			.qode-social-register-holder input[type="password"]:focus,
			.qode-social-reset-password-holder input[type="text"]:focus,
			.qode-social-reset-password-holder input[type="email"]:focus,
			.qode-social-reset-password-holder input[type="password"]:focus,
			.qode-membership-input-holder .qode-membership-input:focus{
			<?php if (!empty($stockholm_options['input_focus_text_color'])) { ?>color: <?php echo esc_attr($stockholm_options['input_focus_text_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['input_focus_background_color'])) { ?>background-color: <?php echo esc_attr($stockholm_options['input_focus_background_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['input_focus_border_color'])) { ?>border-color: <?php echo esc_attr($stockholm_options['input_focus_border_color']);  ?>; <?php } ?>
			}
		<?php }
		
		if(!empty($stockholm_options['input_focus_text_color'])) { ?>
			#respond textarea:focus::-webkit-input-placeholder,
			#respond input[type='text']:focus::-webkit-input-placeholder,
			#respond input[type='email']:focus::-webkit-input-placeholder,
			.contact_form input[type='text']:focus::-webkit-input-placeholder,
			.contact_form input[type='email']:focus::-webkit-input-placeholder,
			.contact_form  textarea:focus::-webkit-input-placeholder,
			input.wpcf7-form-control.wpcf7-text:focus::-webkit-input-placeholder,
			textarea.wpcf7-form-control.wpcf7-textarea:focus::-webkit-input-placeholder{
			color: <?php echo esc_attr($stockholm_options['input_focus_text_color']);  ?>;
			}
			
			#respond textarea:focus:-moz-placeholder,
			#respond input[type='text']:focus:-moz-placeholder,
			#respond input[type='email']:focus:-moz-placeholder,
			.contact_form input[type='text']:focus:-moz-placeholder,
			.contact_form input[type='email']:focus:-moz-placeholder,
			.contact_form  textarea:focus:-moz-placeholder,
			input.wpcf7-form-control.wpcf7-text:focus:-moz-placeholder,
			textarea.wpcf7-form-control.wpcf7-textarea:focus:-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['input_focus_text_color']);  ?>;
			}
			
			#respond textarea:focus::-moz-placeholder,
			#respond input[type='text']:focus::-moz-placeholder,
			#respond input[type='email']:focus::-moz-placeholder,
			.contact_form input[type='text']:focus::-moz-placeholder,
			.contact_form input[type='email']:focus::-moz-placeholder,
			.contact_form  textarea:focus::-moz-placeholder,
			input.wpcf7-form-control.wpcf7-text:focus::-moz-placeholder,
			textarea.wpcf7-form-control.wpcf7-textarea:focus::-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['input_focus_text_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['h1_color']) || !empty($stockholm_options['h1_fontsize']) || !empty($stockholm_options['h1_lineheight']) || !empty($stockholm_options['h1_fontstyle']) || !empty($stockholm_options['h1_fontweight']) || $stockholm_options['h1_letterspacing'] !== '' || $stockholm_options['h1_google_fonts'] != "-1" || !empty($stockholm_options['h1_texttransform'])) { ?>
			h1{
			<?php if (!empty($stockholm_options['h1_color'])) { ?>	color: <?php echo esc_attr($stockholm_options['h1_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['h1_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['h1_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['h1_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['h1_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h1_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['h1_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h1_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['h1_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['h1_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['h1_fontweight']);  ?>; <?php } ?>
			<?php if ($stockholm_options['h1_letterspacing'] !== '') { ?>letter-spacing: <?php echo intval($stockholm_options['h1_letterspacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h1_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['h1_texttransform']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['h2_color']) || !empty($stockholm_options['h2_fontsize']) || !empty($stockholm_options['h2_lineheight']) || !empty($stockholm_options['h2_fontstyle']) || !empty($stockholm_options['h2_fontweight']) || $stockholm_options['h2_letterspacing'] !== '' || $stockholm_options['h2_google_fonts'] != "-1" || !empty($stockholm_options['h2_texttransform'])) { ?>
			h2{
			<?php if($stockholm_options['h2_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['h2_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['h2_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['h2_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h2_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['h2_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h2_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['h2_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['h2_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['h2_fontweight']);  ?>; <?php } ?>
			<?php if ($stockholm_options['h2_letterspacing'] !== '') { ?>letter-spacing: <?php echo intval($stockholm_options['h2_letterspacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h2_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['h2_texttransform']);  ?>; <?php } ?>
			}
			h2, h2 a{
			<?php if (!empty($stockholm_options['h2_color'])) { ?>color: <?php echo esc_attr($stockholm_options['h2_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['h3_color']) || !empty($stockholm_options['h3_fontsize']) || !empty($stockholm_options['h3_lineheight']) || !empty($stockholm_options['h3_fontstyle']) || !empty($stockholm_options['h3_fontweight']) || $stockholm_options['h3_letterspacing'] !== '' || $stockholm_options['h3_google_fonts'] != "-1" || !empty($stockholm_options['h3_texttransform'])) { ?>
			h3{
			<?php if($stockholm_options['h3_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['h3_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['h3_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['h3_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h3_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['h3_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h3_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['h3_fontstyle']);?>; <?php } ?>
			<?php if (!empty($stockholm_options['h3_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['h3_fontweight']);  ?>; <?php } ?>
			<?php if ($stockholm_options['h3_letterspacing'] !== '') { ?>letter-spacing: <?php echo intval($stockholm_options['h3_letterspacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h3_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['h3_texttransform']);  ?>; <?php } ?>
			}
			h3, h3 a{
			<?php if (!empty($stockholm_options['h3_color'])) { ?>color: <?php echo esc_attr($stockholm_options['h3_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['h4_color']) || !empty($stockholm_options['h4_fontsize']) || !empty($stockholm_options['h4_lineheight']) || !empty($stockholm_options['h4_fontstyle']) || !empty($stockholm_options['h4_fontweight']) || $stockholm_options['h4_letterspacing'] !== '' || $stockholm_options['h4_google_fonts'] != "-1" || !empty($stockholm_options['h4_texttransform'])) { ?>
			h4{
			<?php if($stockholm_options['h4_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['h4_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['h4_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['h4_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h4_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['h4_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h4_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['h4_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['h4_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['h4_fontweight']);  ?>; <?php } ?>
			<?php if ($stockholm_options['h4_letterspacing'] !== '') { ?>letter-spacing: <?php echo intval($stockholm_options['h4_letterspacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h4_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['h4_texttransform']);  ?>; <?php } ?>
			}
			h4,	h4 a{
			<?php if (!empty($stockholm_options['h4_color'])) { ?>color: <?php echo esc_attr($stockholm_options['h4_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['h5_color']) || !empty($stockholm_options['h5_fontsize']) || !empty($stockholm_options['h5_lineheight']) || !empty($stockholm_options['h5_fontstyle']) || !empty($stockholm_options['h5_fontweight']) || $stockholm_options['h5_letterspacing'] !== '' || $stockholm_options['h5_google_fonts'] != "-1" || !empty($stockholm_options['h5_texttransform'])) { ?>
			h5{
			<?php if($stockholm_options['h5_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['h5_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['h5_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['h5_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h5_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['h5_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h5_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['h5_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['h5_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['h5_fontweight']);  ?>; <?php } ?>
			<?php if ($stockholm_options['h5_letterspacing'] !== '') { ?>letter-spacing: <?php echo intval($stockholm_options['h5_letterspacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h5_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['h5_texttransform']);  ?>; <?php } ?>
			}
			h5,	h5 a{
			<?php if (!empty($stockholm_options['h5_color'])) { ?>color: <?php echo esc_attr($stockholm_options['h5_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['h6_color']) || !empty($stockholm_options['h6_fontsize']) || !empty($stockholm_options['h6_lineheight']) || !empty($stockholm_options['h6_fontstyle']) || !empty($stockholm_options['h6_fontweight']) || $stockholm_options['h6_letterspacing'] !== '' || $stockholm_options['h6_google_fonts'] != "-1" || !empty($stockholm_options['h6_texttransform'])) { ?>
			h6{
			<?php if($stockholm_options['h6_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['h6_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['h6_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['h6_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h6_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['h6_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h6_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['h6_fontstyle']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['h6_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['h6_fontweight']);  ?>; <?php } ?>
			<?php if ($stockholm_options['h6_letterspacing'] !== '') { ?>letter-spacing: <?php echo intval($stockholm_options['h6_letterspacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['h6_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['h6_texttransform']);  ?>; <?php } ?>
			}
			h6, h6 a{
			<?php if (!empty($stockholm_options['h6_color'])) { ?>color: <?php echo esc_attr($stockholm_options['h6_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		
		<?php
		$text_style = array();
		
		if(isset($stockholm_options['text_google_fonts']) && $stockholm_options['text_google_fonts'] !== '-1') {
			$text_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['text_google_fonts']).'", sans-serif';
		}
		
		if(isset($stockholm_options['text_fontsize']) && $stockholm_options['text_fontsize'] !== '') {
			$text_style[] = 'font-size: '.intval($stockholm_options['text_fontsize']).'px';
		}
		
		if(isset($stockholm_options['text_lineheight']) && $stockholm_options['text_lineheight'] !== '') {
			$text_style[] = 'line-height: '.intval($stockholm_options['text_lineheight']).'px';
		}
		
		if(isset($stockholm_options['text_letter_spacing']) && $stockholm_options['text_letter_spacing'] !== '') {
			$text_style[] = 'letter-spacing: '.intval($stockholm_options['text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['text_fontweight']) && $stockholm_options['text_fontweight'] !== '') {
			$text_style[] = 'font-weight: '.esc_attr($stockholm_options['text_fontweight']);
		}
		
		if(isset($stockholm_options['text_fontstyle']) && $stockholm_options['text_fontstyle'] !== '') {
			$text_style[] = 'font-style: '.esc_attr($stockholm_options['text_fontstyle']);
		}
		
		if(isset($stockholm_options['text_text_transform']) && $stockholm_options['text_text_transform'] !== '') {
			$text_style[] = 'text-transform: '.esc_attr($stockholm_options['text_text_transform']);
		}
		
		if(isset($stockholm_options['text_color']) && $stockholm_options['text_color'] !== '') {
			$text_style[] = 'color: '.esc_attr($stockholm_options['text_color']);
		}
		
		if(isset($stockholm_options['text_margin']) && $stockholm_options['text_margin'] !== '') {
			$text_style[] = 'margin-top: '.intval($stockholm_options['text_margin']).'px';
			$text_style[] = 'margin-bottom: '.intval($stockholm_options['text_margin']).'px';
		}
		
		if(is_array($text_style) && count($text_style)) { ?>
			p{
			<?php echo implode(';', $text_style); ?>
			}
		<?php }  ?>
		
		<?php if(function_exists('is_woocommerce') && isset($stockholm_options['text_color']) && $stockholm_options['text_color'] !== ''){ ?>
			.woocommerce .select2-container.orderby .select2-choice,.woocommerce-page .select2-container.orderby .select2-choice,.woocommerce .select2-dropdown-open.select2-drop-above.orderby .select2-choice,.woocommerce .select2-dropdown-open.select2-drop-above.orderby .select2-choices,.woocommerce-page .select2-dropdown-open.select2-drop-above.orderby .select2-choice,.woocommerce-page .select2-dropdown-open.select2-drop-above.orderby .select2-choices, .woocommerce div.cart-collaterals .select2-container .select2-choice,.woocommerce-page div.cart-collaterals .select2-container .select2-choice,.woocommerce .chosen-container.chosen-container-single .chosen-single,.woocommerce-page .chosen-container.chosen-container-single .chosen-single,.woocommerce-checkout .chosen-container.chosen-container-single .chosen-single,.woocommerce select#pa_color,.woocommerce .product .woocommerce-product-rating .woocommerce-review-link,.woocommerce-cart table.cart tbody tr td a:not(.checkout-button),.woocommerce-checkout .checkout table tbody tr td a,.woocommerce table.cart tbody tr span.amount,.woocommerce-page table.cart tbody span.amount,.woocommerce  input[type="text"],.woocommerce-page input[type="text"],.woocommerce  textarea,.woocommerce-page textarea,.woocommerce input[type="password"], .woocommerce-page input[type="password"],.woocommerce form.checkout table.shop_table span.amount,.woocommerce-checkout table.shop_table td span.amount,.woocommerce-account table.shop_table td span.amount,.woocommerce .widget #searchform input[type='text'],.footer_top .woocommerce.widget .woocommerce-product-search .search-field,.footer_top .widget.widget_search form input[type="text"],aside.sidebar .widget_product_search .woocommerce-product-search .search-field,.woocommerce .widget #searchsubmit, aside.sidebar .widget_product_search .woocommerce-product-search input#searchsubmit,.select2-drop,.select2-container-multi .select2-choices .select2-search-choice{
			color: <?php echo esc_attr($stockholm_options['text_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['link_color']) || !empty($stockholm_options['link_fontstyle']) || !empty($stockholm_options['link_fontweight']) || !empty($stockholm_options['link_fontdecoration'])) { ?>
			a, p a{
			<?php if (!empty($stockholm_options['link_color'])) { ?>color: <?php echo esc_attr($stockholm_options['link_color']);  ?>;<?php } ?>
			<?php if (!empty($stockholm_options['link_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['link_fontstyle']);  ?>;<?php } ?>
			<?php if (!empty($stockholm_options['link_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['link_fontweight']);  ?>;<?php } ?>
			<?php if (!empty($stockholm_options['link_fontdecoration'])) { ?>text-decoration: <?php echo esc_attr($stockholm_options['link_fontdecoration']);  ?>;<?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['link_hovercolor']) || !empty($stockholm_options['link_fontdecoration'])) { ?>
			a:hover,
			p a:hover{
			<?php if (!empty($stockholm_options['link_hovercolor'])) { ?>color: <?php echo esc_attr($stockholm_options['link_hovercolor']);  ?>;<?php } ?>
			<?php if (!empty($stockholm_options['link_fontdecoration'])) { ?>text-decoration: <?php echo esc_attr($stockholm_options['link_fontdecoration']);  ?>;<?php } ?>
			}
		<?php } ?>
		
		<?php
		$blockquote_title_style = array();
		
		if(isset($stockholm_options['blockquote_font_family']) && $stockholm_options['blockquote_font_family'] !== '-1') {
			$blockquote_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blockquote_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blockquote_font_size']) && $stockholm_options['blockquote_font_size'] !== '') {
			$blockquote_title_style[] = 'font-size: '.intval($stockholm_options['blockquote_font_size']).'px';
		}
		
		if(isset($stockholm_options['blockquote_line_height']) && $stockholm_options['blockquote_line_height'] !== '') {
			$blockquote_title_style[] = 'line-height: '.intval($stockholm_options['blockquote_line_height']).'px';
		}
		
		if(isset($stockholm_options['blockquote_letter_spacing']) && $stockholm_options['blockquote_letter_spacing'] !== '') {
			$blockquote_title_style[] = 'letter-spacing: '.intval($stockholm_options['blockquote_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blockquote_font_weight']) && $stockholm_options['blockquote_font_weight'] !== '') {
			$blockquote_title_style[] = 'font-weight: '.esc_attr($stockholm_options['blockquote_font_weight']);
		}
		
		if(isset($stockholm_options['blockquote_font_style']) && $stockholm_options['blockquote_font_style'] !== '') {
			$blockquote_title_style[] = 'font-style: '.esc_attr($stockholm_options['blockquote_font_style']);
		}
		
		if(isset($stockholm_options['blockquote_text_transform']) && $stockholm_options['blockquote_text_transform'] !== '') {
			$blockquote_title_style[] = 'text-transform: '.esc_attr($stockholm_options['blockquote_text_transform']);
		}
		
		if(isset($stockholm_options['blockquote_color']) && $stockholm_options['blockquote_color'] !== '') {
			$blockquote_title_style[] = 'color: '.esc_attr($stockholm_options['blockquote_color']);
		}
		
		if(is_array($blockquote_title_style) && count($blockquote_title_style)) { ?>
			blockquote h3, blockquote > p{
			<?php echo implode(';', $blockquote_title_style); ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['blockquote_background_color']) || !empty($stockholm_options['blockquote_border_color'])) { ?>
			blockquote{
			border-color: <?php echo esc_attr($stockholm_options['blockquote_border_color']);  ?>;
			background-color: <?php echo esc_attr($stockholm_options['blockquote_background_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['blockquote_quote_icon_color'])) { ?>
			blockquote.with_quote_icon i{
			color: <?php echo esc_attr($stockholm_options['blockquote_quote_icon_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['page_title_color']) || !empty($stockholm_options['page_title_fontsize']) || !empty($stockholm_options['page_title_lineheight']) || !empty($stockholm_options['page_title_fontstyle']) || !empty($stockholm_options['page_title_fontweight']) || !empty($stockholm_options['page_title_texttransform']) || $stockholm_options['page_title_google_fonts'] != "-1" || isset($stockholm_options['page_title_letter_spacing']) && $stockholm_options['page_title_letter_spacing'] !== '') { ?>
			.title h1{
			<?php if (!empty($stockholm_options['page_title_color'])) { ?>color: <?php echo esc_attr($stockholm_options['page_title_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['page_title_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['page_title_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['page_title_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['page_title_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['page_title_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['page_title_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['page_title_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['page_title_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['page_title_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['page_title_fontweight']);  ?>; <?php } ?>
			<?php if ($stockholm_options['page_title_letter_spacing'] !== '') { ?>letter-spacing: <?php echo intval($stockholm_options['page_title_letter_spacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['page_title_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['page_title_texttransform']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['page_subtitle_color']) || !empty($stockholm_options['page_subtitle_fontsize']) || $stockholm_options['page_subtitle_google_fonts'] != "-1" || !empty($stockholm_options['page_subtitle_lineheight']) || !empty($stockholm_options['page_subtitle_fontstyle']) || !empty($stockholm_options['page_subtitle_fontweight']) || !empty($stockholm_options['page_subtitle_texttransform']) || $stockholm_options['page_subtitle_letter_spacing'] !== '') { ?>
			.subtitle{
			<?php if (!empty($stockholm_options['page_subtitle_color'])) { ?>color: <?php echo esc_attr($stockholm_options['page_subtitle_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['page_subtitle_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['page_subtitle_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['page_subtitle_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['page_subtitle_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['page_subtitle_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['page_subtitle_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['page_subtitle_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['page_subtitle_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['page_subtitle_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['page_subtitle_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['page_subtitle_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['page_subtitle_texttransform']);  ?>; <?php } ?>
			<?php if ($stockholm_options['page_subtitle_letter_spacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['page_subtitle_letter_spacing']); ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['page_breadcrumb_color']) || !empty($stockholm_options['page_breadcrumb_fontsize']) || $stockholm_options['page_breadcrumb_google_fonts'] != "-1" || !empty($stockholm_options['page_breadcrumb_lineheight']) || !empty($stockholm_options['page_breadcrumb_fontstyle']) || !empty($stockholm_options['page_breadcrumb_fontweight']) || !empty($stockholm_options['page_breadcrumb_texttransform']) || $stockholm_options['page_breadcrumb_letter_spacing'] !== '') { ?>
			.breadcrumb a, .breadcrumb span{
			<?php if (!empty($stockholm_options['page_breadcrumb_color'])) { ?>color: <?php echo esc_attr($stockholm_options['page_breadcrumb_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['page_breadcrumb_google_fonts'] !== "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['page_breadcrumb_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['page_breadcrumb_fontsize'])) { ?>font-size: <?php echo intval($stockholm_options['page_breadcrumb_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['page_breadcrumb_lineheight'])) { ?>line-height: <?php echo intval($stockholm_options['page_breadcrumb_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['page_breadcrumb_fontstyle'])) { ?>font-style: <?php echo esc_attr($stockholm_options['page_breadcrumb_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['page_breadcrumb_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['page_breadcrumb_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['page_breadcrumb_texttransform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['page_breadcrumb_texttransform']);  ?>; <?php } ?>
			<?php if ($stockholm_options['page_breadcrumb_letter_spacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['page_breadcrumb_letter_spacing']); ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['page_breadcrumb_color'])){ ?>
			.breadcrumb a:hover, .breadcrumb span.current{
			color: <?php echo esc_attr($stockholm_options['page_breadcrumb_hovercolor']);  ?>;
			}
		<?php } ?>
		
		<?php
		$blog_large_image_title_style = array();
		
		if(isset($stockholm_options['blog_large_image_title_google_fonts']) && $stockholm_options['blog_large_image_title_google_fonts'] !== '-1') {
			$blog_large_image_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_large_image_title_google_fonts']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_large_image_title_fontsize']) && $stockholm_options['blog_large_image_title_fontsize'] !== '') {
			$blog_large_image_title_style[] = 'font-size: '.intval($stockholm_options['blog_large_image_title_fontsize']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_title_lineheight']) && $stockholm_options['blog_large_image_title_lineheight'] !== '') {
			$blog_large_image_title_style[] = 'line-height: '.intval($stockholm_options['blog_large_image_title_lineheight']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_title_letter_spacing']) && $stockholm_options['blog_large_image_title_letter_spacing'] !== '') {
			$blog_large_image_title_style[] = 'letter-spacing: '.intval($stockholm_options['blog_large_image_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_title_fontweight']) && $stockholm_options['blog_large_image_title_fontweight'] !== '') {
			$blog_large_image_title_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_large_image_title_fontweight']);
		}
		
		if(isset($stockholm_options['blog_large_image_title_fontstyle']) && $stockholm_options['blog_large_image_title_fontstyle'] !== '') {
			$blog_large_image_title_style[] = 'font-style: '.esc_attr($stockholm_options['blog_large_image_title_fontstyle']);
		}
		
		if(isset($stockholm_options['blog_large_image_title_texttransform']) && $stockholm_options['blog_large_image_title_texttransform'] !== '') {
			$blog_large_image_title_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_large_image_title_texttransform']);
		}
		
		if(is_array($blog_large_image_title_style) && count($blog_large_image_title_style)) { ?>
			.blog_holder.blog_large_image article:not(.format-quote):not(.format-link) h2{
			<?php echo implode(';', $blog_large_image_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_large_image_title_color']) && !empty($stockholm_options['blog_large_image_title_color'])){ ?>
			.blog_holder.blog_large_image article:not(.format-quote):not(.format-link) h2 a{
			color: <?php echo esc_attr($stockholm_options['blog_large_image_title_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_large_image_title_hover_color']) && !empty($stockholm_options['blog_large_image_title_hover_color'])){ ?>
			.blog_holder.blog_large_image article:not(.format-quote):not(.format-link) h2 a:hover{
			color: <?php echo esc_attr($stockholm_options['blog_large_image_title_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$blog_large_image_ql_title_style = array();
		
		if(isset($stockholm_options['blog_large_image_ql_title_font_family']) && $stockholm_options['blog_large_image_ql_title_font_family'] !== '-1') {
			$blog_large_image_ql_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_large_image_ql_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_title_font_size']) && $stockholm_options['blog_large_image_ql_title_font_size'] !== '') {
			$blog_large_image_ql_title_style[] = 'font-size: '.intval($stockholm_options['blog_large_image_ql_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_title_line_height']) && $stockholm_options['blog_large_image_ql_title_line_height'] !== '') {
			$blog_large_image_ql_title_style[] = 'line-height: '.intval($stockholm_options['blog_large_image_ql_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_title_letter_spacing']) && $stockholm_options['blog_large_image_ql_title_letter_spacing'] !== '') {
			$blog_large_image_ql_title_style[] = 'letter-spacing: '.intval($stockholm_options['blog_large_image_ql_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_title_font_weight']) && $stockholm_options['blog_large_image_ql_title_font_weight'] !== '') {
			$blog_large_image_ql_title_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_large_image_ql_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_large_image_ql_title_font_style']) && $stockholm_options['blog_large_image_ql_title_font_style'] !== '') {
			$blog_large_image_ql_title_style[] = 'font-style: '.esc_attr($stockholm_options['blog_large_image_ql_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_large_image_ql_title_text_transform']) && $stockholm_options['blog_large_image_ql_title_text_transform'] !== '') {
			$blog_large_image_ql_title_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_large_image_ql_title_text_transform']);
		}
		
		if(is_array($blog_large_image_ql_title_style) && count($blog_large_image_ql_title_style)) { ?>
			.blog_holder.blog_large_image article.format-quote .post_text .post_title h3,
			.blog_holder.blog_large_image article.format-link .post_text .post_title h3,
			.blog_holder.blog_large_image article.format-quote .post_text .quote_author{
			<?php echo implode(';', $blog_large_image_ql_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_large_image_ql_title_color']) && !empty($stockholm_options['blog_large_image_ql_title_color'])){ ?>
			.blog_holder.blog_large_image article.format-quote .post_text .post_title h3 a,
			.blog_holder.blog_large_image article.format-link .post_text .post_title h3 a,
			.blog_holder.blog_large_image article.format-quote .post_text .quote_author{
			color: <?php echo esc_attr($stockholm_options['blog_large_image_ql_title_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_large_image_ql_title_hover_color']) && !empty($stockholm_options['blog_large_image_ql_title_hover_color'])){ ?>
			.blog_holder.blog_large_image article.format-quote .post_text .post_text_inner:hover .post_title h3 a,
			.blog_holder.blog_large_image article.format-link .post_text .post_text_inner:hover .post_title h3 a,
			.blog_holder.blog_large_image article.format-quote .post_text .post_text_inner:hover .quote_author{
			color: <?php echo esc_attr($stockholm_options['blog_large_image_ql_title_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$blog_masonry_title_style = array();
		
		if(isset($stockholm_options['blog_masonry_title_font_family']) && $stockholm_options['blog_masonry_title_font_family'] !== '-1') {
			$blog_masonry_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_masonry_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_masonry_title_font_size']) && $stockholm_options['blog_masonry_title_font_size'] !== '') {
			$blog_masonry_title_style[] = 'font-size: '.intval($stockholm_options['blog_masonry_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_title_line_height']) && $stockholm_options['blog_masonry_title_line_height'] !== '') {
			$blog_masonry_title_style[] = 'line-height: '.intval($stockholm_options['blog_masonry_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_title_letter_spacing']) && $stockholm_options['blog_masonry_title_letter_spacing'] !== '') {
			$blog_masonry_title_style[] = 'letter-spacing: '.intval($stockholm_options['blog_masonry_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_title_font_weight']) && $stockholm_options['blog_masonry_title_font_weight'] !== '') {
			$blog_masonry_title_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_masonry_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_masonry_title_font_style']) && $stockholm_options['blog_masonry_title_font_style'] !== '') {
			$blog_masonry_title_style[] = 'font-style: '.esc_attr($stockholm_options['blog_masonry_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_masonry_title_text_transform']) && $stockholm_options['blog_masonry_title_text_transform'] !== '') {
			$blog_masonry_title_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_masonry_title_text_transform']);
		}
		
		if(is_array($blog_masonry_title_style) && count($blog_masonry_title_style)) { ?>
			.blog_holder.masonry article:not(.format-quote):not(.format-link) h4,
			.blog_holder.masonry_full_width article:not(.format-quote):not(.format-link) h4,
			.latest_post_holder .latest_post_title{
			<?php echo implode(';', $blog_masonry_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_masonry_title_color']) && !empty($stockholm_options['blog_masonry_title_color'])){ ?>
			.blog_holder.masonry article:not(.format-quote):not(.format-link) h4 a,
			.blog_holder.masonry_full_width article:not(.format-quote):not(.format-link) h4 a,
			.latest_post_holder .latest_post_title a{
			color: <?php echo esc_attr($stockholm_options['blog_masonry_title_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_masonry_title_hover_color']) && !empty($stockholm_options['blog_masonry_title_hover_color'])){ ?>
			.blog_holder.masonry article:not(.format-quote):not(.format-link) h4 a:hover,
			.blog_holder.masonry_full_width article:not(.format-quote):not(.format-link) h4 a:hover,
			.latest_post_holder .latest_post_title a:hover{
			color: <?php echo esc_attr($stockholm_options['blog_masonry_title_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$blog_masonry_ql_title_style = array();
		
		if(isset($stockholm_options['blog_masonry_ql_title_font_family']) && $stockholm_options['blog_masonry_ql_title_font_family'] !== '-1') {
			$blog_masonry_ql_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_masonry_ql_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_masonry_ql_title_font_size']) && $stockholm_options['blog_masonry_ql_title_font_size'] !== '') {
			$blog_masonry_ql_title_style[] = 'font-size: '.intval($stockholm_options['blog_masonry_ql_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_ql_title_line_height']) && $stockholm_options['blog_masonry_ql_title_line_height'] !== '') {
			$blog_masonry_ql_title_style[] = 'line-height: '.intval($stockholm_options['blog_masonry_ql_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_ql_title_letter_spacing']) && $stockholm_options['blog_masonry_ql_title_letter_spacing'] !== '') {
			$blog_masonry_ql_title_style[] = 'letter-spacing: '.intval($stockholm_options['blog_masonry_ql_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_ql_title_font_weight']) && $stockholm_options['blog_masonry_ql_title_font_weight'] !== '') {
			$blog_masonry_ql_title_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_masonry_ql_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_masonry_ql_title_font_style']) && $stockholm_options['blog_masonry_ql_title_font_style'] !== '') {
			$blog_masonry_ql_title_style[] = 'font-style: '.esc_attr($stockholm_options['blog_masonry_ql_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_masonry_ql_title_text_transform']) && $stockholm_options['blog_masonry_ql_title_text_transform'] !== '') {
			$blog_masonry_ql_title_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_masonry_ql_title_text_transform']);
		}
		
		if(is_array($blog_masonry_ql_title_style) && count($blog_masonry_ql_title_style)) { ?>
			.blog_holder.masonry article.format-quote .post_text_inner h3,
			.blog_holder.masonry article.format-link .post_text .post_text_inner h3,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner h3,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner h3,
			.blog_holder.masonry article.format-quote .post_text_inner .quote_author,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner .quote_author{
			<?php echo implode(';', $blog_masonry_ql_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_masonry_ql_title_color']) && !empty($stockholm_options['blog_masonry_ql_title_color'])){ ?>
			.blog_holder.masonry article.format-quote .post_text_inner h3 a,
			.blog_holder.masonry article.format-link .post_text .post_text_inner h3 a,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner h3 a,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner h3 a,
			.blog_holder.masonry article.format-quote .post_text_inner .quote_author,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner .quote_author{
			color: <?php echo esc_attr($stockholm_options['blog_masonry_ql_title_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_masonry_ql_title_hover_color']) && !empty($stockholm_options['blog_masonry_ql_title_hover_color'])){ ?>
			.blog_holder.masonry article.format-quote .post_text .post_text_inner:hover h3 a,
			.blog_holder.masonry article.format-link .post_text .post_text_inner:hover h3 a,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner:hover h3 a,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner:hover h3 a,
			.blog_holder.masonry article.format-quote .post_text_inner:hover .quote_author,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner:hover .quote_author{
			color: <?php echo esc_attr($stockholm_options['blog_masonry_ql_title_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_masonry_article_overlay_color']) && !empty($stockholm_options['blog_masonry_article_overlay_color'])) { ?>
			<?php if(isset($stockholm_options['blog_masonry_article_overlay_transparency']) && $stockholm_options['blog_masonry_article_overlay_transparency'] != ''){
				$bm_overlay_transparency = $stockholm_options['blog_masonry_article_overlay_transparency'];
			} else {
				$bm_overlay_transparency = 1;
			}
			$bm_overlay_bg_color = stockholm_qode_hex2rgb($stockholm_options['blog_masonry_article_overlay_color']);
			?>
			
			.blog_holder.masonry article .post_image a .post_overlay,
			.blog_holder.masonry_full_width article .post_image a .post_overlay,
			.latest_post_holder .boxes_image a .latest_post_overlay{
			background-color: rgba(<?php echo esc_attr($bm_overlay_bg_color[0]); ?>,<?php echo esc_attr($bm_overlay_bg_color[1]); ?>,<?php echo esc_attr($bm_overlay_bg_color[2]); ?>,<?php echo esc_attr($bm_overlay_transparency); ?>);
			}
		<?php } ?>
		
		<?php
		$blog_masonry_ql_icon_style = array();
		$blog_masonry_ql_icon_hover_style = array();
		
		if(isset($stockholm_options['blog_masonry_ql_icon_color']) && !empty($stockholm_options['blog_masonry_ql_icon_color'])){
			$blog_masonry_ql_icon_style[] = 'color: '.esc_attr($stockholm_options['blog_masonry_ql_icon_color']);
		}
		
		if(isset($stockholm_options['blog_masonry_ql_icon_background_color']) && !empty($stockholm_options['blog_masonry_ql_icon_background_color'])){
			$blog_masonry_ql_icon_style[] = 'background-color: '.esc_attr($stockholm_options['blog_masonry_ql_icon_background_color']);
		}
		
		if(is_array($blog_masonry_ql_icon_style) && count($blog_masonry_ql_icon_style)) { ?>
			.blog_holder.masonry article.format-quote .post_text_inner .qoute_mark,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner .qoute_mark,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner .link_mark,
			.blog_holder.masonry article.format-link .post_text .post_text_inner .link_mark{
			<?php echo implode(';', $blog_masonry_ql_icon_style); ?>
			}
		<?php }
		
		if(isset($stockholm_options['blog_masonry_ql_icon_hover_color']) && !empty($stockholm_options['blog_masonry_ql_icon_hover_color'])){
			$blog_masonry_ql_icon_hover_style[] = 'color: '.esc_attr($stockholm_options['blog_masonry_ql_icon_hover_color']);
		}
		
		if(isset($stockholm_options['blog_masonry_ql_icon_background_hover_color']) && !empty($stockholm_options['blog_masonry_ql_icon_background_hover_color'])){
			$blog_masonry_ql_icon_hover_style[] = 'background-color: '.esc_attr($stockholm_options['blog_masonry_ql_icon_background_hover_color']);
		}
		if(is_array($blog_masonry_ql_icon_hover_style) && count($blog_masonry_ql_icon_hover_style)) { ?>
			.blog_holder.masonry article.format-quote .post_text_inner .qoute_mark:hover,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner .qoute_mark:hover,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner .link_mark:hover,
			.blog_holder.masonry article.format-link .post_text .post_text_inner .link_mark:hover{
			<?php echo implode(';', $blog_masonry_ql_icon_hover_style); ?>
			}
		<?php } ?>
		
		
		<?php
		$blog_masonry_icon_plus_style = array();
		
		if(isset($stockholm_options['blog_masonry_icon_plus_color']) && !empty($stockholm_options['blog_masonry_icon_plus_color'])) {
			$blog_masonry_icon_plus_style[] = 'color: '.esc_attr($stockholm_options['blog_masonry_icon_plus_color']);
		}
		
		if(isset($stockholm_options['blog_masonry_icon_plus_background_color']) && !empty($stockholm_options['blog_masonry_icon_plus_background_color'])) {
			$blog_masonry_icon_plus_style[] = 'background-color: '.esc_attr($stockholm_options['blog_masonry_icon_plus_background_color']);
		}
		
		if(is_array($blog_masonry_icon_plus_style) && count($blog_masonry_icon_plus_style)) { ?>
			.blog_holder.masonry article .post_image a .icon_plus,
			.blog_holder.masonry_full_width article .post_image a .icon_plus,
			.latest_post_holder .latest_post_overlay .icon_plus{
			<?php echo implode(';', $blog_masonry_icon_plus_style); ?>
			}
		<?php } ?>
		
		<?php
		if(isset($stockholm_options['blog_single_space_after_image']) && $stockholm_options['blog_single_space_after_image'] !== '') { ?>
			.blog_holder.blog_single article:not(.format-link):not(.format-quote) .post_text .post_text_inner {
			padding:  <?php echo  intval($stockholm_options['blog_single_space_after_image']); ?>px 0 0;
			}
		<?php } ?>
		
		<?php
		if(isset($stockholm_options['blog_single_space_after_title']) && $stockholm_options['blog_single_space_after_title'] !== '') { ?>
			.blog_holder.blog_single article h2 {
			margin-bottom:  <?php echo  intval($stockholm_options['blog_single_space_after_title']); ?>px;
			}
		<?php } ?>
		
		<?php
		if(isset($stockholm_options['blog_single_space_after_info']) && $stockholm_options['blog_single_space_after_info'] !== '') { ?>
			.blog_holder.blog_single article .post_info {
			margin-bottom:  <?php echo  intval($stockholm_options['blog_single_space_after_info']); ?>px;
			}
		<?php } ?>
		
		<?php
		if(isset($stockholm_options['blog_single_space_before_share']) && $stockholm_options['blog_single_space_before_share'] !== '') { ?>
			.blog_single.blog_holder article .post_text .post_social {
			margin-top:  <?php echo  intval($stockholm_options['blog_single_space_before_share']); ?>px;
			}
		<?php } ?>
		
		<?php
		$blog_single_title_style = array();
		
		if(isset($stockholm_options['blog_single_title_font_family']) && $stockholm_options['blog_single_title_font_family'] !== '-1') {
			$blog_single_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_single_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_single_title_font_size']) && $stockholm_options['blog_single_title_font_size'] !== '') {
			$blog_single_title_style[] = 'font-size: '.intval($stockholm_options['blog_single_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_single_title_line_height']) && $stockholm_options['blog_single_title_line_height'] !== '') {
			$blog_single_title_style[] = 'line-height: '.intval($stockholm_options['blog_single_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_single_title_letter_spacing']) && $stockholm_options['blog_single_title_letter_spacing'] !== '') {
			$blog_single_title_style[] = 'letter-spacing: '.intval($stockholm_options['blog_single_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_single_title_font_weight']) && $stockholm_options['blog_single_title_font_weight'] !== '') {
			$blog_single_title_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_single_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_single_title_font_style']) && $stockholm_options['blog_single_title_font_style'] !== '') {
			$blog_single_title_style[] = 'font-style: '.esc_attr($stockholm_options['blog_single_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_single_title_text_transform']) && $stockholm_options['blog_single_title_text_transform'] !== '') {
			$blog_single_title_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_single_title_text_transform']);
		}
		
		if(isset($stockholm_options['blog_single_title_color']) && $stockholm_options['blog_single_title_color'] !== '') {
			$blog_single_title_style[] = 'color: '.esc_attr($stockholm_options['blog_single_title_color']);
		}
		
		if(is_array($blog_single_title_style) && count($blog_single_title_style)) { ?>
			.blog_holder.blog_single article .post_content > h2{
			<?php echo implode(';', $blog_single_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$blog_single_ql_title_style = array();
		
		if(isset($stockholm_options['blog_single_ql_title_font_family']) && $stockholm_options['blog_single_ql_title_font_family'] !== '-1') {
			$blog_single_ql_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_single_ql_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_single_ql_title_font_size']) && $stockholm_options['blog_single_ql_title_font_size'] !== '') {
			$blog_single_ql_title_style[] = 'font-size: '.intval($stockholm_options['blog_single_ql_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_single_ql_title_line_height']) && $stockholm_options['blog_single_ql_title_line_height'] !== '') {
			$blog_single_ql_title_style[] = 'line-height: '.intval($stockholm_options['blog_single_ql_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_single_ql_title_letter_spacing']) && $stockholm_options['blog_single_ql_title_letter_spacing'] !== '') {
			$blog_single_ql_title_style[] = 'letter-spacing: '.intval($stockholm_options['blog_single_ql_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_single_ql_title_font_weight']) && $stockholm_options['blog_single_ql_title_font_weight'] !== '') {
			$blog_single_ql_title_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_single_ql_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_single_ql_title_font_style']) && $stockholm_options['blog_single_ql_title_font_style'] !== '') {
			$blog_single_ql_title_style[] = 'font-style: '.esc_attr($stockholm_options['blog_single_ql_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_single_ql_title_text_transform']) && $stockholm_options['blog_single_ql_title_text_transform'] !== '') {
			$blog_single_ql_title_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_single_ql_title_text_transform']);
		}
		
		if(isset($stockholm_options['blog_single_ql_title_color']) && $stockholm_options['blog_single_ql_title_color'] !== '') {
			$blog_single_ql_title_style[] = 'color: '.esc_attr($stockholm_options['blog_single_ql_title_color']);
		}
		
		if(is_array($blog_single_ql_title_style) && count($blog_single_ql_title_style)) { ?>
			.blog_holder.blog_single article.format-quote .post_text .post_title h3,
			.blog_holder.blog_single article.format-link .post_text .post_title h3 a,
			.blog_holder.blog_single article.format-quote .post_text .quote_author{
			<?php echo implode(';', $blog_single_ql_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_single_ql_title_hover_color']) && !empty($stockholm_options['blog_single_ql_title_hover_color'])){ ?>
			.blog_holder.blog_single article.format-quote .post_text .post_text_inner:hover .post_title h3,
			.blog_holder.blog_single article.format-link .post_text .post_text_inner:hover .post_title h3 a,
			.blog_holder.blog_single article.format-quote .post_text .post_text_inner:hover .quote_author{
			color: <?php echo esc_attr($stockholm_options['blog_single_ql_title_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$blog_list_info_style = array();
		
		if(isset($stockholm_options['blog_list_info_font_family']) && $stockholm_options['blog_list_info_font_family'] !== '-1') {
			$blog_list_info_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_list_info_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_list_info_font_size']) && $stockholm_options['blog_list_info_font_size'] !== '') {
			$blog_list_info_style[] = 'font-size: '.intval($stockholm_options['blog_list_info_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_list_info_line_height']) && $stockholm_options['blog_list_info_line_height'] !== '') {
			$blog_list_info_style[] = 'line-height: '.intval($stockholm_options['blog_list_info_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_list_info_letter_spacing']) && $stockholm_options['blog_list_info_letter_spacing'] !== '') {
			$blog_list_info_style[] = 'letter-spacing: '.intval($stockholm_options['blog_list_info_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_list_info_font_weight']) && $stockholm_options['blog_list_info_font_weight'] !== '') {
			$blog_list_info_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_list_info_font_weight']);
		}
		
		if(isset($stockholm_options['blog_list_info_font_style']) && $stockholm_options['blog_list_info_font_style'] !== '') {
			$blog_list_info_style[] = 'font-style: '.esc_attr($stockholm_options['blog_list_info_font_style']);
		}
		
		if(isset($stockholm_options['blog_list_info_text_transform']) && $stockholm_options['blog_list_info_text_transform'] !== '') {
			$blog_list_info_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_list_info_text_transform']);
		}
		
		if(isset($stockholm_options['blog_list_info_color']) && $stockholm_options['blog_list_info_color'] !== '') {
			$blog_list_info_style[] = 'color: '.esc_attr($stockholm_options['blog_list_info_color']);
		}
		
		if(is_array($blog_list_info_style) && count($blog_list_info_style)) { ?>
			.blog_holder article .post_info,
			.blog_holder.blog_chequered article .quote_author,
			.latest_post_holder .post_info_section span, .latest_post_holder .post_info_section a{
			<?php echo implode(';', $blog_list_info_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_list_info_color']) && !empty($stockholm_options['blog_list_info_color'])){ ?>
			.blog_holder article .post_info a,
			.latest_post_holder .post_info_section span, .latest_post_holder .post_info_section a, .latest_post_holder .post_info_section:before{
			color: <?php echo esc_attr($stockholm_options['blog_list_info_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_list_info_hover_color']) && !empty($stockholm_options['blog_list_info_hover_color'])){ ?>
			.blog_holder article .post_info a:hover,
			.latest_post_holder .post_info_section a:hover{
			color: <?php echo esc_attr($stockholm_options['blog_list_info_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$blog_large_image_ql_author_style = array();
		
		if(isset($stockholm_options['blog_large_image_ql_author_font_family']) && $stockholm_options['blog_large_image_ql_author_font_family'] !== '-1') {
			$blog_large_image_ql_author_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_large_image_ql_author_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_author_font_size']) && $stockholm_options['blog_large_image_ql_author_font_size'] !== '') {
			$blog_large_image_ql_author_style[] = 'font-size: '.intval($stockholm_options['blog_large_image_ql_author_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_author_line_height']) && $stockholm_options['blog_large_image_ql_author_line_height'] !== '') {
			$blog_large_image_ql_author_style[] = 'line-height: '.intval($stockholm_options['blog_large_image_ql_author_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_author_letter_spacing']) && $stockholm_options['blog_large_image_ql_author_letter_spacing'] !== '') {
			$blog_large_image_ql_author_style[] = 'letter-spacing: '.intval($stockholm_options['blog_large_image_ql_author_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_large_image_ql_author_font_weight']) && $stockholm_options['blog_large_image_ql_author_font_weight'] !== '') {
			$blog_large_image_ql_author_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_large_image_ql_author_font_weight']);
		}
		
		if(isset($stockholm_options['blog_large_image_ql_author_font_style']) && $stockholm_options['blog_large_image_ql_author_font_style'] !== '') {
			$blog_large_image_ql_author_style[] = 'font-style: '.esc_attr($stockholm_options['blog_large_image_ql_author_font_style']);
		}
		
		if(isset($stockholm_options['blog_large_image_ql_author_text_transform']) && $stockholm_options['blog_large_image_ql_author_text_transform'] !== '') {
			$blog_large_image_ql_author_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_large_image_ql_author_text_transform']);
		}
		
		if(isset($stockholm_options['blog_large_image_ql_author_color']) && $stockholm_options['blog_large_image_ql_author_color'] !== '') {
			$blog_large_image_ql_author_style[] = 'color: '.esc_attr($stockholm_options['blog_large_image_ql_author_color']);
		}
		
		if(is_array($blog_large_image_ql_author_style) && count($blog_large_image_ql_author_style)) { ?>
			.blog_holder.blog_large_image article.format-quote .post_text .quote_author{
			<?php echo implode(';', $blog_large_image_ql_author_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_large_image_ql_author_hover_color']) && !empty($stockholm_options['blog_large_image_ql_author_hover_color'])){ ?>
			.blog_holder.blog_large_image article.format-quote .post_text .post_text_inner:hover .quote_author{
			color: <?php echo esc_attr($stockholm_options['blog_large_image_ql_author_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$blog_masonry_author_style = array();
		
		if(isset($stockholm_options['blog_masonry_author_font_family']) && $stockholm_options['blog_masonry_author_font_family'] !== '-1') {
			$blog_masonry_author_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_masonry_author_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_masonry_author_font_size']) && $stockholm_options['blog_masonry_author_font_size'] !== '') {
			$blog_masonry_author_style[] = 'font-size: '.intval($stockholm_options['blog_masonry_author_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_author_line_height']) && $stockholm_options['blog_masonry_author_line_height'] !== '') {
			$blog_masonry_author_style[] = 'line-height: '.intval($stockholm_options['blog_masonry_author_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_author_letter_spacing']) && $stockholm_options['blog_masonry_author_letter_spacing'] !== '') {
			$blog_masonry_author_style[] = 'letter-spacing: '.intval($stockholm_options['blog_masonry_author_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_masonry_author_font_weight']) && $stockholm_options['blog_masonry_author_font_weight'] !== '') {
			$blog_masonry_author_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_masonry_author_font_weight']);
		}
		
		if(isset($stockholm_options['blog_masonry_author_font_style']) && $stockholm_options['blog_masonry_author_font_style'] !== '') {
			$blog_masonry_author_style[] = 'font-style: '.esc_attr($stockholm_options['blog_masonry_author_font_style']);
		}
		
		if(isset($stockholm_options['blog_masonry_author_text_transform']) && $stockholm_options['blog_masonry_author_text_transform'] !== '') {
			$blog_masonry_author_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_masonry_author_text_transform']);
		}
		
		if(isset($stockholm_options['blog_masonry_author_color']) && $stockholm_options['blog_masonry_author_color'] !== '') {
			$blog_masonry_author_style[] = 'color: '.esc_attr($stockholm_options['blog_masonry_author_color']);
		}
		
		if(is_array($blog_masonry_author_style) && count($blog_masonry_author_style)) { ?>
			.blog_holder.masonry .post_author,
			.blog_holder.masonry .post_author a,
			.blog_holder.masonry_full_width .post_author,
			.blog_holder.masonry_full_width .post_author a,
			.latest_post_holder .post_author, .latest_post_holder a.post_author_link,
			.blog_holder.pinterest_full_width .post_comments{
			<?php echo implode(';', $blog_masonry_author_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_masonry_author_hover_color']) && !empty($stockholm_options['blog_masonry_author_hover_color'])){ ?>
			.blog_holder.masonry .post_author:hover,
			.blog_holder.masonry .post_author a:hover,
			.blog_holder.masonry_full_width .post_author:hover,
			.blog_holder.masonry_full_width .post_author a:hover,
			.latest_post_holder .post_author a.post_author_link:hover,
			.blog_holder.pinterest_full_width .post_comments:hover{
			color: <?php echo esc_attr($stockholm_options['blog_masonry_author_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['blog_large_image_icon_color']) || !empty($stockholm_options['blog_large_image_icon_background_color'])){ ?>
			.blog_holder article .post_social .post_comments i,
			.blog_holder article .post_social .blog_like i,
			.blog_holder article .post_social .social_share_holder > a > i{
			<?php if(!empty($stockholm_options['blog_large_image_icon_color'])){ ?>color: <?php echo esc_attr($stockholm_options['blog_large_image_icon_color']); ?> <?php } ?>;
			<?php if(!empty($stockholm_options['blog_large_image_icon_background_color'])){ ?>background-color: <?php echo esc_attr($stockholm_options['blog_large_image_icon_background_color']); ?> <?php } ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['blog_large_image_icon_hover_color']) || !empty($stockholm_options['blog_large_image_icon_background_hover_color'])){ ?>
			.blog_holder article .post_social .post_comments:hover i,
			.blog_holder article .post_social .blog_like:hover i,
			.blog_holder article .post_social .blog_like .liked i,
			.blog_holder article .post_social .social_share_holder > a:hover > i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .post_comments:hover i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .social_share_holder > a:hover > i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .blog_like:hover i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .post_comments:hover i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .social_share_holder > a:hover > i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .blog_like:hover i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .blog_like .liked i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .blog_like .liked i{
			<?php if(!empty($stockholm_options['blog_large_image_icon_hover_color'])){ ?>color: <?php echo esc_attr($stockholm_options['blog_large_image_icon_hover_color']); ?> <?php } ?>;
			<?php if(!empty($stockholm_options['blog_large_image_icon_background_hover_color'])){ ?>background-color: <?php echo esc_attr($stockholm_options['blog_large_image_icon_background_hover_color']); ?> <?php } ?>;
			}
		<?php } ?>
		
		<?php
		$blog_chequered_image_style = array();
		
		if(isset($stockholm_options['blog_chequered_with_image_title_font_family']) && $stockholm_options['blog_chequered_with_image_title_font_family'] !== '-1') {
			$blog_chequered_image_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_chequered_with_image_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_chequered_with_image_title_font_size']) && $stockholm_options['blog_chequered_with_image_title_font_size'] !== '') {
			$blog_chequered_image_style[] = 'font-size: '.intval($stockholm_options['blog_chequered_with_image_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_chequered_with_image_title_line_height']) && $stockholm_options['blog_chequered_with_image_title_line_height'] !== '') {
			$blog_chequered_image_style[] = 'line-height: '.intval($stockholm_options['blog_chequered_with_image_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_chequered_with_image_title_letter_spacing']) && $stockholm_options['blog_chequered_with_image_title_letter_spacing'] !== '') {
			$blog_chequered_image_style[] = 'letter-spacing: '.intval($stockholm_options['blog_chequered_with_image_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_chequered_with_image_title_font_weight']) && $stockholm_options['blog_chequered_with_image_title_font_weight'] !== '') {
			$blog_chequered_image_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_chequered_with_image_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_chequered_with_image_title_font_style']) && $stockholm_options['blog_chequered_with_image_title_font_style'] !== '') {
			$blog_chequered_image_style[] = 'font-style: '.esc_attr($stockholm_options['blog_chequered_with_image_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_chequered_with_image_title_text_transform']) && $stockholm_options['blog_chequered_with_image_title_text_transform'] !== '') {
			$blog_chequered_image_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_chequered_with_image_title_text_transform']);
		}
		
		if(isset($stockholm_options['blog_chequered_with_image_title_color']) && $stockholm_options['blog_chequered_with_image_title_color'] !== '') {
			$blog_chequered_image_style[] = 'color: '.esc_attr($stockholm_options['blog_chequered_with_image_title_color']);
		}
		
		if(is_array($blog_chequered_image_style) && count($blog_chequered_image_style)) { ?>
			.blog_holder.blog_chequered article.qodef-with-bg-image .qodef-post-title {
			<?php echo implode(';', $blog_chequered_image_style); ?>
			}
		<?php } ?>
		
		<?php
		$blog_chequered_bgcolor_style = array();
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_font_family']) && $stockholm_options['blog_chequered_with_bgcolor_title_font_family'] !== '-1') {
			$blog_chequered_bgcolor_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_chequered_with_bgcolor_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_font_size']) && $stockholm_options['blog_chequered_with_bgcolor_title_font_size'] !== '') {
			$blog_chequered_bgcolor_style[] = 'font-size: '.intval($stockholm_options['blog_chequered_with_bgcolor_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_line_height']) && $stockholm_options['blog_chequered_with_bgcolor_title_line_height'] !== '') {
			$blog_chequered_bgcolor_style[] = 'line-height: '.intval($stockholm_options['blog_chequered_with_bgcolor_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_letter_spacing']) && $stockholm_options['blog_chequered_with_bgcolor_title_letter_spacing'] !== '') {
			$blog_chequered_bgcolor_style[] = 'letter-spacing: '.intval($stockholm_options['blog_chequered_with_bgcolor_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_font_weight']) && $stockholm_options['blog_chequered_with_bgcolor_title_font_weight'] !== '') {
			$blog_chequered_bgcolor_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_chequered_with_bgcolor_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_font_style']) && $stockholm_options['blog_chequered_with_bgcolor_title_font_style'] !== '') {
			$blog_chequered_bgcolor_style[] = 'font-style: '.esc_attr($stockholm_options['blog_chequered_with_bgcolor_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_text_transform']) && $stockholm_options['blog_chequered_with_bgcolor_title_text_transform'] !== '') {
			$blog_chequered_bgcolor_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_chequered_with_bgcolor_title_text_transform']);
		}
		
		if(isset($stockholm_options['blog_chequered_with_bgcolor_title_color']) && $stockholm_options['blog_chequered_with_bgcolor_title_color'] !== '') {
			$blog_chequered_bgcolor_style[] = 'color: '.esc_attr($stockholm_options['blog_chequered_with_bgcolor_title_color']);
		}
		
		if(is_array($blog_chequered_bgcolor_style) && count($blog_chequered_bgcolor_style)) { ?>
			.blog_holder.blog_chequered article.qodef-with-bg-color .qodef-post-title {
			<?php echo implode(';', $blog_chequered_bgcolor_style); ?>
			}
		<?php } ?>
		
		<?php
		$blog_animated_style = array();
		
		if(isset($stockholm_options['blog_animated_title_font_family']) && $stockholm_options['blog_animated_title_font_family'] !== '-1') {
			$blog_animated_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_animated_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_animated_title_font_size']) && $stockholm_options['blog_animated_title_font_size'] !== '') {
			$blog_animated_style[] = 'font-size: '.intval($stockholm_options['blog_animated_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_animated_title_line_height']) && $stockholm_options['blog_animated_title_line_height'] !== '') {
			$blog_animated_style[] = 'line-height: '.intval($stockholm_options['blog_animated_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_animated_title_letter_spacing']) && $stockholm_options['blog_animated_title_letter_spacing'] !== '') {
			$blog_animated_style[] = 'letter-spacing: '.intval($stockholm_options['blog_animated_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_animated_title_font_weight']) && $stockholm_options['blog_animated_title_font_weight'] !== '') {
			$blog_animated_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_animated_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_animated_title_font_style']) && $stockholm_options['blog_animated_title_font_style'] !== '') {
			$blog_animated_style[] = 'font-style: '.esc_attr($stockholm_options['blog_animated_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_animated_title_text_transform']) && $stockholm_options['blog_animated_title_text_transform'] !== '') {
			$blog_animated_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_animated_title_text_transform']);
		}
		
		if(isset($stockholm_options['blog_animated_title_color']) && $stockholm_options['blog_animated_title_color'] !== '') {
			$blog_animated_style[] = 'color: '.esc_attr($stockholm_options['blog_animated_title_color']);
		}
		
		if(is_array($blog_animated_style) && count($blog_animated_style)) { ?>
			.blog_holder.blog_animated article .qodef-post-title {
			<?php echo implode(';', $blog_animated_style); ?>
			}
		<?php } ?>
		
		<?php
		$blog_centered_style = array();
		
		if(isset($stockholm_options['blog_centered_title_font_family']) && $stockholm_options['blog_centered_title_font_family'] !== '-1') {
			$blog_centered_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_centered_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_centered_title_font_size']) && $stockholm_options['blog_centered_title_font_size'] !== '') {
			$blog_centered_style[] = 'font-size: '.intval($stockholm_options['blog_centered_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_centered_title_line_height']) && $stockholm_options['blog_centered_title_line_height'] !== '') {
			$blog_centered_style[] = 'line-height: '.intval($stockholm_options['blog_centered_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_centered_title_letter_spacing']) && $stockholm_options['blog_centered_title_letter_spacing'] !== '') {
			$blog_centered_style[] = 'letter-spacing: '.intval($stockholm_options['blog_centered_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_centered_title_font_weight']) && $stockholm_options['blog_centered_title_font_weight'] !== '') {
			$blog_centered_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_centered_title_font_weight']);
		}
		
		if(isset($stockholm_options['blog_centered_title_font_style']) && $stockholm_options['blog_centered_title_font_style'] !== '') {
			$blog_centered_style[] = 'font-style: '.esc_attr($stockholm_options['blog_centered_title_font_style']);
		}
		
		if(isset($stockholm_options['blog_centered_title_text_transform']) && $stockholm_options['blog_centered_title_text_transform'] !== '') {
			$blog_centered_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_centered_title_text_transform']);
		}
		
		if(isset($stockholm_options['blog_centered_title_color']) && $stockholm_options['blog_centered_title_color'] !== '') {
			$blog_centered_style[] = 'color: '.esc_attr($stockholm_options['blog_centered_title_color']);
		}
		
		if(is_array($blog_centered_style) && count($blog_centered_style)) { ?>
			.blog_holder.blog_centered article .qodef-post-title {
			<?php echo implode(';', $blog_centered_style); ?>
			}
		<?php } ?>
		
		<?php
		if(isset($stockholm_options['blog_centered_title_color']) && $stockholm_options['blog_centered_title_color'] !== '') { ?>
			.blog_holder.blog_centered article .qodef-post-title a {
			color: <?php echo esc_attr($stockholm_options['blog_centered_title_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$blog_centered_info_style = array();
		
		if(isset($stockholm_options['blog_centered_info_font_family']) && $stockholm_options['blog_centered_info_font_family'] !== '-1') {
			$blog_centered_info_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['blog_centered_info_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['blog_centered_info_font_size']) && $stockholm_options['blog_centered_info_font_size'] !== '') {
			$blog_centered_info_style[] = 'font-size: '.intval($stockholm_options['blog_centered_info_font_size']).'px';
		}
		
		if(isset($stockholm_options['blog_centered_info_line_height']) && $stockholm_options['blog_centered_info_line_height'] !== '') {
			$blog_centered_info_style[] = 'line-height: '.intval($stockholm_options['blog_centered_info_line_height']).'px';
		}
		
		if(isset($stockholm_options['blog_centered_info_letter_spacing']) && $stockholm_options['blog_centered_info_letter_spacing'] !== '') {
			$blog_centered_info_style[] = 'letter-spacing: '.intval($stockholm_options['blog_centered_info_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['blog_centered_info_font_weight']) && $stockholm_options['blog_centered_info_font_weight'] !== '') {
			$blog_centered_info_style[] = 'font-weight: '.esc_attr($stockholm_options['blog_centered_info_font_weight']);
		}
		
		if(isset($stockholm_options['blog_centered_info_font_style']) && $stockholm_options['blog_centered_info_font_style'] !== '') {
			$blog_centered_info_style[] = 'font-style: '.esc_attr($stockholm_options['blog_centered_info_font_style']);
		}
		
		if(isset($stockholm_options['blog_centered_info_text_transform']) && $stockholm_options['blog_centered_info_text_transform'] !== '') {
			$blog_centered_info_style[] = 'text-transform: '.esc_attr($stockholm_options['blog_centered_info_text_transform']);
		}
		
		if(isset($stockholm_options['blog_centered_info_color']) && $stockholm_options['blog_centered_info_color'] !== '') {
			$blog_centered_info_style[] = 'color: '.esc_attr($stockholm_options['blog_centered_info_color']);
		}
		
		if(is_array($blog_centered_info_style) && count($blog_centered_info_style)) { ?>
			.blog_holder.blog_centered article .post_text .post_category,
			.blog_holder.blog_centered article .post_text .post_category a,
			.blog_holder.blog_centered article .post_text .post_author,
			.blog_holder.blog_centered article .post_text .post_author a,
			.blog_holder.blog_centered article .post_text .post_info_bottom .time {
			<?php echo implode(';', $blog_centered_info_style); ?>
			}
		<?php } ?>
		
		<?php
		$contact_form_heading_style = array();
		
		if(isset($stockholm_options['contact_form_heading_google_fonts']) && $stockholm_options['contact_form_heading_google_fonts'] !== '-1') {
			$contact_form_heading_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['contact_form_heading_google_fonts']).', sans-serif';
		}
		
		if(isset($stockholm_options['contact_form_heading_fontsize']) && $stockholm_options['contact_form_heading_fontsize'] !== '') {
			$contact_form_heading_style[] = 'font-size: '.intval($stockholm_options['contact_form_heading_fontsize']).'px';
		}
		
		if(isset($stockholm_options['contact_form_heading_lineheight']) && $stockholm_options['contact_form_heading_lineheight'] !== '') {
			$contact_form_heading_style[] = 'line-height: '.intval($stockholm_options['contact_form_heading_lineheight']).'px';
		}
		
		if(isset($stockholm_options['contact_form_heading_letter_spacing']) && $stockholm_options['contact_form_heading_letter_spacing'] !== '') {
			$contact_form_heading_style[] = 'letter-spacing: '.intval($stockholm_options['contact_form_heading_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['contact_form_heading_fontweight']) && $stockholm_options['contact_form_heading_fontweight'] !== '') {
			$contact_form_heading_style[] = 'font-weight: '.esc_attr($stockholm_options['contact_form_heading_fontweight']);
		}
		
		if(isset($stockholm_options['contact_form_heading_fontstyle']) && $stockholm_options['contact_form_heading_fontstyle'] !== '') {
			$contact_form_heading_style[] = 'font-style: '.esc_attr($stockholm_options['contact_form_heading_fontstyle']);
		}
		
		if(isset($stockholm_options['contact_form_heading_texttransform']) && $stockholm_options['contact_form_heading_texttransform'] !== '') {
			$contact_form_heading_style[] = 'text-transform: '.esc_attr($stockholm_options['contact_form_heading_texttransform']);
		}
		
		if(isset($stockholm_options['contact_form_heading_color']) && $stockholm_options['contact_form_heading_color'] !== '') {
			$contact_form_heading_style[] = 'color: '.esc_attr($stockholm_options['contact_form_heading_color']);
		}
		
		if(is_array($contact_form_heading_style) && count($contact_form_heading_style)) { ?>
			.contact_form h5{
			<?php echo implode(';', $contact_form_heading_style); ?>
			}
		<?php } ?>
		
		<?php
		$contact_form_section_title_style = array();
		
		if(isset($stockholm_options['contact_form_section_title_google_fonts']) && $stockholm_options['contact_form_section_title_google_fonts'] !== '-1') {
			$contact_form_section_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['contact_form_section_title_google_fonts']).', sans-serif';
		}
		
		if(isset($stockholm_options['contact_form_section_title_font_size']) && $stockholm_options['contact_form_section_title_font_size'] !== '') {
			$contact_form_section_title_style[] = 'font-size: '.intval($stockholm_options['contact_form_section_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['contact_form_section_title_line_height']) && $stockholm_options['contact_form_section_title_line_height'] !== '') {
			$contact_form_section_title_style[] = 'line-height: '.intval($stockholm_options['contact_form_section_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['contact_form_section_title_letter_spacing']) && $stockholm_options['contact_form_section_title_letter_spacing'] !== '') {
			$contact_form_section_title_style[] = 'letter-spacing: '.intval($stockholm_options['contact_form_section_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['contact_form_section_title_font_weight']) && $stockholm_options['contact_form_section_title_font_weight'] !== '') {
			$contact_form_section_title_style[] = 'font-weight: '.esc_attr($stockholm_options['contact_form_section_title_font_weight']);
		}
		
		if(isset($stockholm_options['contact_form_section_title_font_style']) && $stockholm_options['contact_form_section_title_font_style'] !== '') {
			$contact_form_section_title_style[] = 'font-style: '.esc_attr($stockholm_options['contact_form_section_title_font_style']);
		}
		
		if(isset($stockholm_options['contact_form_section_title_text_transform']) && $stockholm_options['contact_form_section_title_text_transform'] !== '') {
			$contact_form_section_title_style[] = 'text-transform: '.esc_attr($stockholm_options['contact_form_section_title_text_transform']);
		}
		
		if(isset($stockholm_options['contact_form_section_title_color']) && $stockholm_options['contact_form_section_title_color'] !== '') {
			$contact_form_section_title_style[] = 'color: '.esc_attr($stockholm_options['contact_form_section_title_color']);
		}
		
		if(is_array($contact_form_section_title_style) && count($contact_form_section_title_style)) { ?>
			.contact_section h2{
			<?php echo implode(';', $contact_form_section_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$contact_form_section_subtitle_style = array();
		
		if(isset($stockholm_options['contact_form_section_subtitle_google_fonts']) && $stockholm_options['contact_form_section_subtitle_google_fonts'] !== '-1') {
			$contact_form_section_subtitle_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['contact_form_section_subtitle_google_fonts']).', sans-serif';
		}
		
		if(isset($stockholm_options['contact_form_section_subtitle_font_size']) && $stockholm_options['contact_form_section_subtitle_font_size'] !== '') {
			$contact_form_section_subtitle_style[] = 'font-size: '.intval($stockholm_options['contact_form_section_subtitle_font_size']).'px';
		}
		
		if(isset($stockholm_options['contact_form_section_subtitle_line_height']) && $stockholm_options['contact_form_section_subtitle_line_height'] !== '') {
			$contact_form_section_subtitle_style[] = 'line-height: '.intval($stockholm_options['contact_form_section_subtitle_line_height']).'px';
		}
		
		if(isset($stockholm_options['contact_form_section_subtitle_letter_spacing']) && $stockholm_options['contact_form_section_subtitle_letter_spacing'] !== '') {
			$contact_form_section_subtitle_style[] = 'letter-spacing: '.intval($stockholm_options['contact_form_section_subtitle_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['contact_form_section_subtitle_font_weight']) && $stockholm_options['contact_form_section_subtitle_font_weight'] !== '') {
			$contact_form_section_subtitle_style[] = 'font-weight: '.esc_attr($stockholm_options['contact_form_section_subtitle_font_weight']);
		}
		
		if(isset($stockholm_options['contact_form_section_subtitle_font_style']) && $stockholm_options['contact_form_section_subtitle_font_style'] !== '') {
			$contact_form_section_subtitle_style[] = 'font-style: '.esc_attr($stockholm_options['contact_form_section_subtitle_font_style']);
		}
		
		if(isset($stockholm_options['contact_form_section_subtitle_text_transform']) && $stockholm_options['contact_form_section_subtitle_text_transform'] !== '') {
			$contact_form_section_subtitle_style[] = 'text-transform: '.esc_attr($stockholm_options['contact_form_section_subtitle_text_transform']);
		}
		
		if(isset($stockholm_options['contact_form_section_subtitle_color']) && $stockholm_options['contact_form_section_subtitle_color'] !== '') {
			$contact_form_section_subtitle_style[] = 'color: '.esc_attr($stockholm_options['contact_form_section_subtitle_color']);
		}
		
		if(is_array($contact_form_section_subtitle_style) && count($contact_form_section_subtitle_style)) { ?>
			.contact_section h4{
			<?php echo implode(';', $contact_form_section_subtitle_style); ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['pricing_table_background_color'])) { ?>
			.price_table_inner ul li.pricing_table_content,
			.price_table_inner ul li.prices{
			<?php if (!empty($stockholm_options['pricing_table_background_color'])) { ?>	background-color: <?php echo esc_attr($stockholm_options['pricing_table_background_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['pricing_table_separator_color'])) { ?>
			.price_table_inner ul li.pricing_table_content li{
			<?php if (!empty($stockholm_options['pricing_table_separator_color'])) { ?>	border-color: <?php echo esc_attr($stockholm_options['pricing_table_separator_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['pricing_table_separator_thickness'])) { ?>
			.price_table_inner ul li.pricing_table_content li{
			<?php if (!empty($stockholm_options['pricing_table_separator_thickness'])) { ?>	border-width: <?php echo intval($stockholm_options['pricing_table_separator_thickness']); ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php
		$pricing_tables_active_text_styles = array();
		
		if(isset($stockholm_options['pricing_tables_active_text_font_family']) && $stockholm_options['pricing_tables_active_text_font_family'] !== '-1') {
			$pricing_tables_active_text_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_tables_active_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_tables_active_text_font_size']) && $stockholm_options['pricing_tables_active_text_font_size'] !== '') {
			$pricing_tables_active_text_styles[] = 'font-size: '.intval($stockholm_options['pricing_tables_active_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_active_text_line_height']) && $stockholm_options['pricing_tables_active_text_line_height'] !== '') {
			$pricing_tables_active_text_styles[] = 'line-height: '.intval($stockholm_options['pricing_tables_active_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_active_text_letter_spacing']) && $stockholm_options['pricing_tables_active_text_letter_spacing'] !== '') {
			$pricing_tables_active_text_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_tables_active_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_active_text_font_weight']) && $stockholm_options['pricing_tables_active_text_font_weight'] !== '') {
			$pricing_tables_active_text_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_tables_active_text_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_tables_active_text_font_style']) && $stockholm_options['pricing_tables_active_text_font_style'] !== '') {
			$pricing_tables_active_text_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_tables_active_text_font_style']);
		}
		
		if(isset($stockholm_options['pricing_tables_active_text_text_transform']) && $stockholm_options['pricing_tables_active_text_text_transform'] !== '') {
			$pricing_tables_active_text_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_tables_active_text_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_tables_active_text_color']) && $stockholm_options['pricing_tables_active_text_color'] !== '') {
			$pricing_tables_active_text_styles[] = 'color: '.esc_attr($stockholm_options['pricing_tables_active_text_color']);
		}
		
		if(is_array($pricing_tables_active_text_styles) && count($pricing_tables_active_text_styles)) { ?>
			.q_price_table.active .active_text span span{
			<?php echo implode(';', $pricing_tables_active_text_styles); ?>
			}
		<?php } ?>
		
		<?php
		$pricing_tables_title_styles = array();
		
		if(isset($stockholm_options['pricing_tables_title_font_family']) && $stockholm_options['pricing_tables_title_font_family'] !== '-1') {
			$pricing_tables_title_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_tables_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_tables_title_font_size']) && $stockholm_options['pricing_tables_title_font_size'] !== '') {
			$pricing_tables_title_styles[] = 'font-size: '.intval($stockholm_options['pricing_tables_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_title_line_height']) && $stockholm_options['pricing_tables_title_line_height'] !== '') {
			$pricing_tables_title_styles[] = 'line-height: '.intval($stockholm_options['pricing_tables_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_title_letter_spacing']) && $stockholm_options['pricing_tables_title_letter_spacing'] !== '') {
			$pricing_tables_title_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_tables_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_title_font_weight']) && $stockholm_options['pricing_tables_title_font_weight'] !== '') {
			$pricing_tables_title_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_tables_title_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_tables_title_font_style']) && $stockholm_options['pricing_tables_title_font_style'] !== '') {
			$pricing_tables_title_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_tables_title_font_style']);
		}
		
		if(isset($stockholm_options['pricing_tables_title_text_transform']) && $stockholm_options['pricing_tables_title_text_transform'] !== '') {
			$pricing_tables_title_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_tables_title_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_tables_title_color']) && $stockholm_options['pricing_tables_title_color'] !== '') {
			$pricing_tables_title_styles[] = 'color: '.esc_attr($stockholm_options['pricing_tables_title_color']);
		}
		
		if(is_array($pricing_tables_title_styles) && count($pricing_tables_title_styles)) { ?>
			.price_table_inner ul li.table_title .title_content{
			<?php echo implode(';', $pricing_tables_title_styles); ?>
			}
		<?php } ?>
		
		<?php
		$pricing_tables_period_styles = array();
		
		if(isset($stockholm_options['pricing_tables_period_font_family']) && $stockholm_options['pricing_tables_period_font_family'] !== '-1') {
			$pricing_tables_period_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_tables_period_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_tables_period_font_size']) && $stockholm_options['pricing_tables_period_font_size'] !== '') {
			$pricing_tables_period_styles[] = 'font-size: '.intval($stockholm_options['pricing_tables_period_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_period_line_height']) && $stockholm_options['pricing_tables_period_line_height'] !== '') {
			$pricing_tables_period_styles[] = 'line-height: '.intval($stockholm_options['pricing_tables_period_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_period_letter_spacing']) && $stockholm_options['pricing_tables_period_letter_spacing'] !== '') {
			$pricing_tables_period_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_tables_period_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_period_font_weight']) && $stockholm_options['pricing_tables_period_font_weight'] !== '') {
			$pricing_tables_period_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_tables_period_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_tables_period_font_style']) && $stockholm_options['pricing_tables_period_font_style'] !== '') {
			$pricing_tables_period_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_tables_period_font_style']);
		}
		
		if(isset($stockholm_options['pricing_tables_period_text_transform']) && $stockholm_options['pricing_tables_period_text_transform'] !== '') {
			$pricing_tables_period_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_tables_period_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_tables_period_color']) && $stockholm_options['pricing_tables_period_color'] !== '') {
			$pricing_tables_period_styles[] = 'color: '.esc_attr($stockholm_options['pricing_tables_period_color']);
		}
		
		if(is_array($pricing_tables_period_styles) && count($pricing_tables_period_styles)) { ?>
			.price_in_table .mark{
			<?php echo implode(';', $pricing_tables_period_styles); ?>
			}
		<?php } ?>
		
		<?php
		$pricing_tables_price_styles = array();
		
		if(isset($stockholm_options['pricing_tables_price_font_family']) && $stockholm_options['pricing_tables_price_font_family'] !== '-1') {
			$pricing_tables_price_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_tables_price_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_tables_price_font_size']) && $stockholm_options['pricing_tables_price_font_size'] !== '') {
			$pricing_tables_price_styles[] = 'font-size: '.intval($stockholm_options['pricing_tables_price_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_price_line_height']) && $stockholm_options['pricing_tables_price_line_height'] !== '') {
			$pricing_tables_price_styles[] = 'line-height: '.intval($stockholm_options['pricing_tables_price_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_price_letter_spacing']) && $stockholm_options['pricing_tables_price_letter_spacing'] !== '') {
			$pricing_tables_price_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_tables_price_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_price_font_weight']) && $stockholm_options['pricing_tables_price_font_weight'] !== '') {
			$pricing_tables_price_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_tables_price_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_tables_price_font_style']) && $stockholm_options['pricing_tables_price_font_style'] !== '') {
			$pricing_tables_price_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_tables_price_font_style']);
		}
		
		if(isset($stockholm_options['pricing_tables_price_text_transform']) && $stockholm_options['pricing_tables_price_text_transform'] !== '') {
			$pricing_tables_price_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_tables_price_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_tables_price_color']) && $stockholm_options['pricing_tables_price_color'] !== '') {
			$pricing_tables_price_styles[] = 'color: '.esc_attr($stockholm_options['pricing_tables_price_color']);
		}
		
		if(is_array($pricing_tables_price_styles) && count($pricing_tables_price_styles)) { ?>
			.price_in_table .price{
			<?php echo implode(';', $pricing_tables_price_styles); ?>
			}
		<?php } ?>
		
		<?php
		$pricing_tables_currency_styles = array();
		
		if(isset($stockholm_options['pricing_tables_currency_font_family']) && $stockholm_options['pricing_tables_currency_font_family'] !== '-1') {
			$pricing_tables_currency_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_tables_currency_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_tables_currency_font_size']) && $stockholm_options['pricing_tables_currency_font_size'] !== '') {
			$pricing_tables_currency_styles[] = 'font-size: '.intval($stockholm_options['pricing_tables_currency_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_currency_line_height']) && $stockholm_options['pricing_tables_currency_line_height'] !== '') {
			$pricing_tables_currency_styles[] = 'line-height: '.intval($stockholm_options['pricing_tables_currency_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_currency_letter_spacing']) && $stockholm_options['pricing_tables_currency_letter_spacing'] !== '') {
			$pricing_tables_currency_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_tables_currency_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_currency_font_weight']) && $stockholm_options['pricing_tables_currency_font_weight'] !== '') {
			$pricing_tables_currency_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_tables_currency_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_tables_currency_font_style']) && $stockholm_options['pricing_tables_currency_font_style'] !== '') {
			$pricing_tables_currency_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_tables_currency_font_style']);
		}
		
		if(isset($stockholm_options['pricing_tables_currency_text_transform']) && $stockholm_options['pricing_tables_currency_text_transform'] !== '') {
			$pricing_tables_currency_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_tables_currency_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_tables_currency_color']) && $stockholm_options['pricing_tables_currency_color'] !== '') {
			$pricing_tables_currency_styles[] = 'color: '.esc_attr($stockholm_options['pricing_tables_currency_color']);
		}
		
		if(is_array($pricing_tables_currency_styles) && count($pricing_tables_currency_styles)) { ?>
			.price_in_table .value{
			<?php echo implode(';', $pricing_tables_currency_styles); ?>
			}
		<?php } ?>
		
		<?php
		$pricing_tables_button_styles = array();
		
		if(isset($stockholm_options['pricing_tables_button_font_family']) && $stockholm_options['pricing_tables_button_font_family'] !== '-1') {
			$pricing_tables_button_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_tables_button_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_tables_button_font_size']) && $stockholm_options['pricing_tables_button_font_size'] !== '') {
			$pricing_tables_button_styles[] = 'font-size: '.intval($stockholm_options['pricing_tables_button_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_button_line_height']) && $stockholm_options['pricing_tables_button_line_height'] !== '') {
			$pricing_tables_button_styles[] = 'line-height: '.intval($stockholm_options['pricing_tables_button_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_button_letter_spacing']) && $stockholm_options['pricing_tables_button_letter_spacing'] !== '') {
			$pricing_tables_button_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_tables_button_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_tables_button_font_weight']) && $stockholm_options['pricing_tables_button_font_weight'] !== '') {
			$pricing_tables_button_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_tables_button_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_tables_button_font_style']) && $stockholm_options['pricing_tables_button_font_style'] !== '') {
			$pricing_tables_button_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_tables_button_font_style']);
		}
		
		if(isset($stockholm_options['pricing_tables_button_text_transform']) && $stockholm_options['pricing_tables_button_text_transform'] !== '') {
			$pricing_tables_button_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_tables_button_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_tables_button_color']) && $stockholm_options['pricing_tables_button_color'] !== '') {
			$pricing_tables_button_styles[] = 'color: '.esc_attr($stockholm_options['pricing_tables_button_color']);
		}
		
		if(is_array($pricing_tables_button_styles) && count($pricing_tables_button_styles)) { ?>
			.price_table_inner .price_button a{
			<?php echo implode(';', $pricing_tables_button_styles); ?>
			}
		<?php } ?>
		
		
		
		<?php
		if(isset($stockholm_options['enable_menu_hover_animation']) && $stockholm_options['enable_menu_hover_animation'] =='yes') {
			if(isset($stockholm_options['menu_hover_animation_color']) && $stockholm_options['menu_hover_animation_color'] !='') { ?>
				.menu-animation-line-through nav.main_menu > ul > li > a .menu-text:after,
				.menu-animation-line-through nav.main_menu > ul > li:hover > a .menu-text:before,
				.menu-animation-underline nav.main_menu > ul > li > a .menu-text:before,
				.menu-animation-underline-bottom nav.main_menu > ul > li > a .menu-text:before{
				background-color: <?php echo esc_attr($stockholm_options['menu_hover_animation_color']); ?>
				}
				
				.menu-animation-line-through nav.main_menu > ul > li:hover > a .menu-text:after{
				background: transparent;
				}
			
			<?php }
		}
		?>
		
		<?php
		if(isset($stockholm_options['enable_fullscreen_menu_hover_animation']) && $stockholm_options['enable_fullscreen_menu_hover_animation'] =='yes') {
			if(isset($stockholm_options['fullscreen_menu_hover_animation_color']) && $stockholm_options['fullscreen_menu_hover_animation_color'] !='') { ?>
				.fs-menu-animation-line-through nav.popup_menu > ul > li > a span:before,
				.fs-menu-animation-underline nav.popup_menu > ul > li > a span:before{
				background-color: <?php echo esc_attr($stockholm_options['fullscreen_menu_hover_animation_color']); ?>
				}
			
			<?php }
		}
		?>
		
		
		
		<?php
		if(isset($stockholm_options['enable_vertical_menu_hover_animation']) && $stockholm_options['enable_vertical_menu_hover_animation'] =='yes') {
			if(isset($stockholm_options['vertical_menu_hover_animation_color']) && $stockholm_options['vertical_menu_hover_animation_color'] !='') { ?>
				.menu-animation-line-through nav.vertical_menu > ul > li > a .menu-text:after,
				.menu-animation-line-through nav.vertical_menu > ul > li:hover > a .menu-text:before,
				.menu-animation-underline nav.vertical_menu > ul > li > a .menu-text:before{
				background-color: <?php echo esc_attr($stockholm_options['vertical_menu_hover_animation_color']); ?>
				}
				
				.menu-animation-line-through nav.vertical_menu > ul > li:hover > a .menu-text:after{
				background: transparent;
				}
			
			<?php }
		}
		?>
		
		
		
		<?php
		$pricing_lists_text_styles = array();
		
		if(isset($stockholm_options['pricing_lists_text_font_family']) && $stockholm_options['pricing_lists_text_font_family'] !== '-1') {
			$pricing_lists_text_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_lists_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_lists_text_font_size']) && $stockholm_options['pricing_lists_text_font_size'] !== '') {
			$pricing_lists_text_styles[] = 'font-size: '.intval($stockholm_options['pricing_lists_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_text_line_height']) && $stockholm_options['pricing_lists_text_line_height'] !== '') {
			$pricing_lists_text_styles[] = 'line-height: '.intval($stockholm_options['pricing_lists_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_text_letter_spacing']) && $stockholm_options['pricing_lists_text_letter_spacing'] !== '') {
			$pricing_lists_text_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_lists_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_text_font_weight']) && $stockholm_options['pricing_lists_text_font_weight'] !== '') {
			$pricing_lists_text_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_lists_text_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_lists_text_font_style']) && $stockholm_options['pricing_lists_text_font_style'] !== '') {
			$pricing_lists_text_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_lists_text_font_style']);
		}
		
		if(isset($stockholm_options['pricing_lists_text_text_transform']) && $stockholm_options['pricing_lists_text_text_transform'] !== '') {
			$pricing_lists_text_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_lists_text_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_lists_text_color']) && $stockholm_options['pricing_lists_text_color'] !== '') {
			$pricing_lists_text_styles[] = 'color: '.esc_attr($stockholm_options['pricing_lists_text_color']);
		}
		
		if(is_array($pricing_lists_text_styles) && count($pricing_lists_text_styles)) { ?>
			.qode-pricing-list .qode-pricing-list-text{
			<?php echo implode(';', $pricing_lists_text_styles); ?>
			}
		<?php } ?>
		
		
		
		<?php
		$pricing_lists_title_styles = array();
		
		if(isset($stockholm_options['pricing_lists_title_font_family']) && $stockholm_options['pricing_lists_title_font_family'] !== '-1') {
			$pricing_lists_title_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_lists_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_lists_title_font_size']) && $stockholm_options['pricing_lists_title_font_size'] !== '') {
			$pricing_lists_title_styles[] = 'font-size: '.intval($stockholm_options['pricing_lists_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_title_line_height']) && $stockholm_options['pricing_lists_title_line_height'] !== '') {
			$pricing_lists_title_styles[] = 'line-height: '.intval($stockholm_options['pricing_lists_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_title_letter_spacing']) && $stockholm_options['pricing_lists_title_letter_spacing'] !== '') {
			$pricing_lists_title_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_lists_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_title_font_weight']) && $stockholm_options['pricing_lists_title_font_weight'] !== '') {
			$pricing_lists_title_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_lists_title_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_lists_title_font_style']) && $stockholm_options['pricing_lists_title_font_style'] !== '') {
			$pricing_lists_title_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_lists_title_font_style']);
		}
		
		if(isset($stockholm_options['pricing_lists_title_text_transform']) && $stockholm_options['pricing_lists_title_text_transform'] !== '') {
			$pricing_lists_title_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_lists_title_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_lists_title_color']) && $stockholm_options['pricing_lists_title_color'] !== '') {
			$pricing_lists_title_styles[] = 'color: '.esc_attr($stockholm_options['pricing_lists_title_color']);
		}
		
		if(is_array($pricing_lists_title_styles) && count($pricing_lists_title_styles)) { ?>
			.qode-pricing-list .qode-pricing-item-title{
			<?php echo implode(';', $pricing_lists_title_styles); ?>
			}
		<?php } ?>
		
		
		
		<?php
		$pricing_lists_price_styles = array();
		
		if(isset($stockholm_options['pricing_lists_price_font_family']) && $stockholm_options['pricing_lists_price_font_family'] !== '-1') {
			$pricing_lists_price_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_lists_price_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_lists_price_font_size']) && $stockholm_options['pricing_lists_price_font_size'] !== '') {
			$pricing_lists_price_styles[] = 'font-size: '.intval($stockholm_options['pricing_lists_price_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_price_line_height']) && $stockholm_options['pricing_lists_price_line_height'] !== '') {
			$pricing_lists_price_styles[] = 'line-height: '.intval($stockholm_options['pricing_lists_price_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_price_letter_spacing']) && $stockholm_options['pricing_lists_price_letter_spacing'] !== '') {
			$pricing_lists_price_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_lists_price_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_price_font_weight']) && $stockholm_options['pricing_lists_price_font_weight'] !== '') {
			$pricing_lists_price_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_lists_price_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_lists_price_font_style']) && $stockholm_options['pricing_lists_price_font_style'] !== '') {
			$pricing_lists_price_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_lists_price_font_style']);
		}
		
		if(isset($stockholm_options['pricing_lists_price_text_transform']) && $stockholm_options['pricing_lists_price_text_transform'] !== '') {
			$pricing_lists_price_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_lists_price_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_lists_price_color']) && $stockholm_options['pricing_lists_price_color'] !== '') {
			$pricing_lists_price_styles[] = 'color: '.esc_attr($stockholm_options['pricing_lists_price_color']);
		}
		
		if(is_array($pricing_lists_price_styles) && count($pricing_lists_price_styles)) { ?>
			.qode-pricing-list .qode-pricing-item-price{
			<?php echo implode(';', $pricing_lists_price_styles); ?>
			}
		<?php } ?>
		
		
		
		
		<?php
		$pricing_lists_highlighted_text_styles = array();
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_font_family']) && $stockholm_options['pricing_lists_highlighted_text_font_family'] !== '-1') {
			$pricing_lists_highlighted_text_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pricing_lists_highlighted_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_font_size']) && $stockholm_options['pricing_lists_highlighted_text_font_size'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'font-size: '.intval($stockholm_options['pricing_lists_highlighted_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_line_height']) && $stockholm_options['pricing_lists_highlighted_text_line_height'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'line-height: '.intval($stockholm_options['pricing_lists_highlighted_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_letter_spacing']) && $stockholm_options['pricing_lists_highlighted_text_letter_spacing'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'letter-spacing: '.intval($stockholm_options['pricing_lists_highlighted_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_font_weight']) && $stockholm_options['pricing_lists_highlighted_text_font_weight'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'font-weight: '.esc_attr($stockholm_options['pricing_lists_highlighted_text_font_weight']);
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_font_style']) && $stockholm_options['pricing_lists_highlighted_text_font_style'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'font-style: '.esc_attr($stockholm_options['pricing_lists_highlighted_text_font_style']);
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_text_transform']) && $stockholm_options['pricing_lists_highlighted_text_text_transform'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'text-transform: '.esc_attr($stockholm_options['pricing_lists_highlighted_text_text_transform']);
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_text_color']) && $stockholm_options['pricing_lists_highlighted_text_color'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'color: '.esc_attr($stockholm_options['pricing_lists_highlighted_text_color']);
		}
		
		if(isset($stockholm_options['pricing_lists_highlighted_background_color']) && $stockholm_options['pricing_lists_highlighted_background_color'] !== '') {
			$pricing_lists_highlighted_text_styles[] = 'background-color: '.esc_attr($stockholm_options['pricing_lists_highlighted_background_color']);
		}
		
		if(is_array($pricing_lists_highlighted_text_styles) && count($pricing_lists_highlighted_text_styles)) { ?>
			.qode-pricing-list .qode-pricing-list-highlited span{
			<?php echo implode(';', $pricing_lists_highlighted_text_styles); ?>
			}
		<?php } ?>
		
		
		
		
		
		
		
		
		<?php if (!empty($stockholm_options['separator_thickness']) || !empty($stockholm_options['separator_topmargin']) || !empty($stockholm_options['separator_bottommargin']) || !empty($stockholm_options['separator_color']) || !empty($stockholm_options['separator_type'])) { ?>
			.separator.normal{
			<?php if (!empty($stockholm_options['separator_thickness'])) { ?>	border-width: <?php echo intval($stockholm_options['separator_thickness']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['separator_topmargin'])) { ?>	margin-top: <?php echo intval($stockholm_options['separator_topmargin']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['separator_bottommargin'])) { ?>	margin-bottom: <?php echo intval($stockholm_options['separator_bottommargin']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['separator_color'])) { ?>	border-color: <?php echo esc_attr($stockholm_options['separator_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['separator_type'])) { ?>	border-style: <?php echo esc_attr($stockholm_options['separator_type']);  ?>; <?php } ?>
			}
		
		<?php } ?>
		<?php if (!empty($stockholm_options['separator_small_thickness']) || !empty($stockholm_options['separator_small_topmargin']) || !empty($stockholm_options['separator_small_bottommargin']) || !empty($stockholm_options['separator_small_color'])  || !empty($stockholm_options['separator_small_width'])  || !empty($stockholm_options['separator_small_type'])) { ?>
			.separator.small,
			.wpb_column>.wpb_wrapper .separator.small
			{
			<?php if (!empty($stockholm_options['separator_small_thickness'])) { ?>	border-width: <?php echo intval($stockholm_options['separator_small_thickness']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['separator_small_topmargin'])) { ?>	margin-top: <?php echo intval($stockholm_options['separator_small_topmargin']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['separator_small_bottommargin'])) { ?>	margin-bottom: <?php echo intval($stockholm_options['separator_small_bottommargin']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['separator_small_color'])) { ?>	border-color: <?php echo esc_attr($stockholm_options['separator_small_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['separator_small_width'])) { ?>	width: <?php echo intval($stockholm_options['separator_small_width']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['separator_small_type'])) { ?>	border-style: <?php echo esc_attr($stockholm_options['separator_small_type']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['message_backgroundcolor'])) { ?>
			.q_message{
			<?php if (!empty($stockholm_options['message_backgroundcolor'])) { ?>background-color: <?php echo esc_attr($stockholm_options['message_backgroundcolor']);  ?><?php } ?>;
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['message_title_color']) || !empty($stockholm_options['message_title_fontsize']) || !empty($stockholm_options['message_title_lineheight']) || !empty($stockholm_options['message_title_fontstyle']) || !empty($stockholm_options['message_title_fontweight']) || $stockholm_options['message_title_google_fonts'] != "-1") { ?>
			.q_message .message_text{
			<?php if (!empty($stockholm_options['message_title_color'])) { ?>	color: <?php echo esc_attr($stockholm_options['message_title_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['message_title_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['message_title_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['message_title_fontsize'])) { ?>	font-size: <?php echo intval($stockholm_options['message_title_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['message_title_lineheight'])) { ?>	line-height: <?php echo intval($stockholm_options['message_title_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['message_title_fontstyle'])) { ?>	font-style: <?php echo esc_attr($stockholm_options['message_title_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['message_title_fontweight'])) { ?>	font-weight: <?php echo esc_attr($stockholm_options['message_title_fontweight']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['message_icon_fontsize']) && !empty($stockholm_options['message_icon_color'])) { ?>
			.q_message.with_icon .q_message_icon_inner > i,
			.q_message.with_icon .q_message_icon_inner > .q_font_elegant_icon {
			<?php if (!empty($stockholm_options['message_icon_color'])) { ?> color:  <?php echo esc_attr($stockholm_options['message_icon_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['message_icon_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['message_icon_fontsize']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php
		$pagination_styles = array();
		$pagination_number_styles = array();
		
		if(isset($stockholm_options['pagination_font_family']) && $stockholm_options['pagination_font_family'] !== '-1') {
			$pagination_number_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['pagination_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['pagination_font_size']) && $stockholm_options['pagination_font_size'] !== '') {
			$pagination_styles[] = 'font-size: '.intval($stockholm_options['pagination_font_size']).'px';
		}
		
		if(isset($stockholm_options['pagination_line_height']) && $stockholm_options['pagination_line_height'] !== '') {
			$pagination_styles[] = 'line-height: '.intval($stockholm_options['pagination_line_height']).'px';
		}
		
		if(isset($stockholm_options['pagination_letter_spacing']) && $stockholm_options['pagination_letter_spacing'] !== '') {
			$pagination_number_styles[] = 'letter-spacing: '.intval($stockholm_options['pagination_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['pagination_font_weight']) && $stockholm_options['pagination_font_weight'] !== '') {
			$pagination_styles[] = 'font-weight: '.esc_attr($stockholm_options['pagination_font_weight']);
		}
		
		if(isset($stockholm_options['pagination_font_style']) && $stockholm_options['pagination_font_style'] !== '') {
			$pagination_number_styles[] = 'font-style: '.esc_attr($stockholm_options['pagination_font_style']);
		}
		
		if(isset($stockholm_options['pagination_text_transform']) && $stockholm_options['pagination_text_transform'] !== '') {
			$pagination_number_styles[] = 'text-transform: '.esc_attr($stockholm_options['pagination_text_transform']);
		}
		
		if(isset($stockholm_options['pagination_color']) && $stockholm_options['pagination_color'] !== '') {
			$pagination_styles[] = 'color: '.esc_attr($stockholm_options['pagination_color']);
		}
		
		if(is_array($pagination_styles) && count($pagination_styles)) { ?>
			.pagination ul li > span, .pagination ul li > a, .pagination ul li.active span, .pagination ul li > a.inactive, .single_links_pages span,
			.woocommerce-pagination ul.page-numbers li>a, .woocommerce-pagination ul.page-numbers li>span, .woocommerce-pagination ul.page-numbers li span.current, .woocommerce-pagination ul.page-numbers li a{
			<?php echo implode(';', $pagination_styles); ?>
			}
		<?php }
		
		if(is_array($pagination_number_styles) && count($pagination_number_styles)) { ?>
			.pagination ul li.active span, .pagination ul li > a.inactive, .woocommerce-pagination ul.page-numbers li span.current, .woocommerce-pagination ul.page-numbers li a{
			<?php echo implode(';', $pagination_number_styles); ?>
			}
		<?php } ?>
		
		
		<?php if(!empty($stockholm_options['pagination_hover_color'])) { ?>
			.pagination ul li a:hover, .pagination ul li.active span, .woocommerce-pagination ul.page-numbers li span.current, .woocommerce-pagination ul.page-numbers li a:hover, .single_links_pages > span, .single_links_pages a:hover span{
			color: <?php echo esc_attr($stockholm_options['pagination_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$Portfolio_pagination_styles = array();
		
		if(isset($stockholm_options['portfolio_pagination_font_size']) && $stockholm_options['portfolio_pagination_font_size'] !== '') {
			$Portfolio_pagination_styles[] = 'font-size: '.intval($stockholm_options['portfolio_pagination_font_size']).'px';
		}
		
		if(isset($stockholm_options['portfolio_pagination_color']) && $stockholm_options['portfolio_pagination_color'] !== '') {
			$Portfolio_pagination_styles[] = 'color: '.esc_attr($stockholm_options['portfolio_pagination_color']);
		}
		
		if(is_array($Portfolio_pagination_styles) && count($Portfolio_pagination_styles)) { ?>
			.portfolio_navigation a{
			<?php echo implode(';', $Portfolio_pagination_styles); ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['portfolio_pagination_hover_color'])) { ?>
			.portfolio_navigation a:hover{
			color: <?php echo esc_attr($stockholm_options['portfolio_pagination_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['social_icon_color']) || !empty($stockholm_options['social_icon_background_color']) || !empty($stockholm_options['social_icon_border_color'])) { ?>
			.q_social_icon_holder .fa-stack{
			<?php if (!empty($stockholm_options['social_icon_color'])) { ?> color: <?php echo esc_attr($stockholm_options['social_icon_color']); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['social_icon_background_color'])) { ?> background-color: <?php echo esc_attr($stockholm_options['social_icon_background_color']); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['social_icon_border_color'])) { ?> border: 1px solid <?php echo esc_attr($stockholm_options['social_icon_border_color']); ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['social_icon_hover_color']) || !empty($stockholm_options['social_icon_hover_background_color']) || !empty($stockholm_options['social_icon_hover_border_color'])) { ?>
			.q_social_icon_holder:hover .fa-stack{
			<?php if (!empty($stockholm_options['social_icon_hover_color'])) { ?> color: <?php echo esc_attr($stockholm_options['social_icon_hover_color']); ?> !important; <?php } ?>
			<?php if (!empty($stockholm_options['social_icon_hover_background_color'])) { ?> background-color: <?php echo esc_attr($stockholm_options['social_icon_hover_background_color']); ?> !important; <?php } ?>
			<?php if (!empty($stockholm_options['social_icon_hover_border_color'])) { ?> border: <?php echo esc_attr($stockholm_options['social_icon_hover_border_color']); ?> !important; <?php } ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['social_color'])) { ?>
			.q_social_icon_holder.normal_social .simple_social,
			.q_social_icon_holder.normal_social.with_link .simple_social{
			color: <?php echo esc_attr($stockholm_options['social_color']); ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['social_hover_color'])) { ?>
			.q_social_icon_holder.normal_social:hover .simple_social{
			color: <?php echo esc_attr($stockholm_options['social_hover_color']); ?> !important;
			}
		<?php } ?>
		
		<?php
		
		/* ==========================================================================
   Buttons custom styles
   ========================================================================== */
		//generate button styles
		$default_btn_styles 		= '';
		$default_btn_hover_styles 	= '';
		
		$small_btn_styles			= '';
		
		$large_btn_styles			= '';
		
		$extra_large_btn_styles		= '';
		
		$white_btn_styles			= '';
		$white_btn_hover_styles		= '';
		
		//generate default button styles
		if(isset($stockholm_options['button_title_color']) && $stockholm_options['button_title_color'] !== '') {
			$default_btn_styles .= 'color: '.esc_attr($stockholm_options['button_title_color']).';';
		}
		
		if(isset($stockholm_options['button_title_fontsize']) && $stockholm_options['button_title_fontsize'] !== '') {
			$default_btn_styles .= 'font-size: '.intval($stockholm_options['button_title_fontsize']).'px;';
		}
		
		if(isset($stockholm_options['button_title_lineheight']) && $stockholm_options['button_title_lineheight'] !== '') {
			$default_btn_styles .= 'line-height: '.intval($stockholm_options['button_title_lineheight']).'px;';
			$default_btn_styles .= 'height: '.intval($stockholm_options['button_title_lineheight']).'px;'; ?>
			
			.qbutton .button_icon.custom_icon_size,
			.qbutton.medium .button_icon.custom_icon_size {
			line-height: <?php echo intval($stockholm_options['button_title_lineheight']); ?>px;
			}
		<?php }
		
		if(isset($stockholm_options['button_title_fontstyle']) && $stockholm_options['button_title_fontstyle'] !== '') {
			$default_btn_styles .= 'font-style: '.esc_attr($stockholm_options['button_title_fontstyle']).';';
		}
		
		if(isset($stockholm_options['button_title_fontweight']) && $stockholm_options['button_title_fontweight'] !== '') {
			$default_btn_styles .= 'font-weight: '.esc_attr($stockholm_options['button_title_fontweight']).';';
		}
		
		if(isset($stockholm_options['button_title_google_fonts']) && $stockholm_options['button_title_google_fonts'] !== '-1') {
			$default_btn_styles .= 'font-family: '.str_replace('+', ' ', $stockholm_options['button_title_google_fonts']).';';
		}
		
		if(isset($stockholm_options['button_title_letter_spacing']) && $stockholm_options['button_title_letter_spacing'] !== '') {
			$default_btn_styles .= 'letter-spacing: '.intval($stockholm_options['button_title_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['button_title_texttransform']) && $stockholm_options['button_title_texttransform'] !== '') {
			$default_btn_styles .= 'text-transform: '.esc_attr($stockholm_options['button_title_texttransform']).';';
		}
		
		if(isset($stockholm_options['button_border_color']) && $stockholm_options['button_border_color'] !== '') {
			$default_btn_styles .= 'border-color: '.esc_attr($stockholm_options['button_border_color']).';';
		}
		
		if(isset($stockholm_options['button_border_width']) && $stockholm_options['button_border_width'] !== '') {
			$default_btn_styles .= 'border-width: '.intval($stockholm_options['button_border_width']).'px;';
		}
		
		if(isset($stockholm_options['button_border_radius']) && $stockholm_options['button_border_radius'] !== '') {
			$default_btn_styles .= 'border-radius: '.intval($stockholm_options['button_border_radius']).'px;';
			$default_btn_styles .= '-moz-border-radius: '.intval($stockholm_options['button_border_radius']).'px;';
			$default_btn_styles .= '-webkit-border-radius: '.intval($stockholm_options['button_border_radius']).'px;';
		}
		if(isset($stockholm_options['button_padding']) && $stockholm_options['button_padding'] !== '') {
			$default_btn_styles .= 'padding-left: '.intval($stockholm_options['button_padding']).'px;';
			$default_btn_styles .= 'padding-right: '.intval($stockholm_options['button_padding']).'px;';
		}
		if(isset($stockholm_options['button_backgroundcolor']) && $stockholm_options['button_backgroundcolor'] !== '') {
			$default_btn_styles .= 'background-color: '.esc_attr($stockholm_options['button_backgroundcolor']).';';
		}
		
		//print default button styles
		if($default_btn_styles !== '') {
			?>
			.qbutton,
			.qbutton.medium,
			#submit_comment,
			.load_more a,
			.blog_load_more_button a,
			.blog_holder article .post_text a.more-link span{
			<?php echo esc_attr($default_btn_styles); ?>
			}
			<?php
		}
		
		//generate default button hover styles
		if(isset($stockholm_options['button_title_hovercolor']) && $stockholm_options['button_title_hovercolor'] !== '') {
			$default_btn_hover_styles .= 'color: '.esc_attr($stockholm_options['button_title_hovercolor']).';';
		}
		
		if(isset($stockholm_options['button_backgroundcolor_hover']) && $stockholm_options['button_backgroundcolor_hover'] !== '') {
			$default_btn_hover_styles .= 'background-color: '.esc_attr($stockholm_options['button_backgroundcolor_hover']).';';
		}
		
		if(isset($stockholm_options['button_border_hover_color']) && $stockholm_options['button_border_hover_color'] !== '') {
			$default_btn_hover_styles .= 'border-color: '.esc_attr($stockholm_options['button_border_hover_color']).';';
		}
		
		//print default button hover styles
		if($default_btn_hover_styles !== '') {
			?>
			.qbutton:hover,
			.qbutton:not(.white):hover,
			.qbutton.medium:hover,
			#submit_comment:hover,
			.load_more a:hover,
			.blog_load_more_button a:hover,
			.blog_holder article .post_text a.more-link:hover span{
			<?php echo esc_attr($default_btn_hover_styles); ?>
			}
			<?php
		}
		
		//generate small button styles
		if(isset($stockholm_options['small_button_fontsize']) && $stockholm_options['small_button_fontsize'] !== '') {
			$small_btn_styles .= 'font-size: '.intval($stockholm_options['small_button_fontsize']).'px;';
		}
		
		if(isset($stockholm_options['small_button_lineheight']) && $stockholm_options['small_button_lineheight'] !== '') {
			$small_btn_styles .= 'line-height: '.intval($stockholm_options['small_button_lineheight']).'px;';
			$small_btn_styles .= 'height: '.intval($stockholm_options['small_button_lineheight']).'px;'; ?>
			
			.qbutton.small .button_icon.custom_icon_size {
			line-height: <?php echo intval($stockholm_options['small_button_lineheight']); ?>px;
			}
		<?php }
		
		if(isset($stockholm_options['small_button_fontweight']) && $stockholm_options['small_button_fontweight'] !== '') {
			$small_btn_styles .= 'font-weight: '.esc_attr($stockholm_options['small_button_fontweight']).';';
		}
		
		if(isset($stockholm_options['small_button_padding']) && $stockholm_options['small_button_padding'] !== '') {
			$small_btn_styles .= 'padding-left: '.intval($stockholm_options['small_button_padding']).'px;';
			$small_btn_styles .= 'padding-right: '.intval($stockholm_options['small_button_padding']).'px;';
		}
		
		if(isset($stockholm_options['small_button_border_radius']) && $stockholm_options['small_button_border_radius'] !== '') {
			$small_btn_styles .= '-webkit-border-radius: '.intval($stockholm_options['small_button_border_radius']).'px;';
			$small_btn_styles .= '-moz-border-radius: '.intval($stockholm_options['small_button_border_radius']).'px;';
			$small_btn_styles .= 'border-radius: '.intval($stockholm_options['small_button_border_radius']).'px;';
		}
		
		//print small button styles
		if($small_btn_styles !== '') {
			?>
			.qbutton.small,
			.blog_holder article .post_text a.more-link span{
			<?php echo esc_attr($small_btn_styles); ?>
			}
			<?php
		}
		
		//generate large button styles
		if(isset($stockholm_options['large_button_fontsize']) && $stockholm_options['large_button_fontsize'] !== '') {
			$large_btn_styles .= 'font-size: '.intval($stockholm_options['large_button_fontsize']).'px;';
		}
		
		if(isset($stockholm_options['large_button_lineheight']) && $stockholm_options['large_button_lineheight'] !== '') {
			$large_btn_styles .= 'line-height: '.intval($stockholm_options['large_button_lineheight']).'px;';
			$large_btn_styles .= 'height: '.intval($stockholm_options['large_button_lineheight']).'px;'; ?>
			
			.qbutton.large .button_icon.custom_icon_size {
			line-height: <?php echo intval($stockholm_options['large_button_lineheight']); ?>px;
			}
		<?php }
		
		if(isset($stockholm_options['large_button_fontweight']) && $stockholm_options['large_button_fontweight'] !== '') {
			$large_btn_styles .= 'font-weight: '.esc_attr($stockholm_options['large_button_fontweight']).';';
		}
		
		if(isset($stockholm_options['large_button_padding']) && $stockholm_options['large_button_padding'] !== '') {
			$large_btn_styles .= 'padding-left: '.intval($stockholm_options['large_button_padding']).'px;';
			$large_btn_styles .= 'padding-right: '.intval($stockholm_options['large_button_padding']).'px;';
		}
		
		if(isset($stockholm_options['large_button_border_radius']) && $stockholm_options['large_button_border_radius'] !== '') {
			$large_btn_styles .= '-webkit-border-radius: '.intval($stockholm_options['large_button_border_radius']).'px;';
			$large_btn_styles .= '-moz-border-radius: '.intval($stockholm_options['large_button_border_radius']).'px;';
			$large_btn_styles .= 'border-radius: '.intval($stockholm_options['large_button_border_radius']).'px;';
		}
		
		//print large button styles
		if($large_btn_styles !== '') {
			?>
			.qbutton.large {
			<?php echo esc_attr($large_btn_styles); ?>
			}
			<?php
		}
		
		//generate extra large button styles
		if(isset($stockholm_options['big_large_button_fontsize']) && $stockholm_options['big_large_button_fontsize'] !== '') {
			$extra_large_btn_styles .= 'font-size: '.intval($stockholm_options['big_large_button_fontsize']).'px;';
		}
		
		if(isset($stockholm_options['big_large_button_lineheight']) && $stockholm_options['big_large_button_lineheight'] !== '') {
			$extra_large_btn_styles .= 'line-height: '.intval($stockholm_options['big_large_button_lineheight']).'px;';
			$extra_large_btn_styles .= 'height: '.intval($stockholm_options['big_large_button_lineheight']).'px;'; ?>
			
			.qbutton.big_large .button_icon.custom_icon_size,
			.qbutton.big_large_full_width .button_icon.custom_icon_size {
			line-height: <?php echo intval($stockholm_options['big_large_button_lineheight']); ?>px;
			}
		<?php }
		
		if(isset($stockholm_options['big_large_button_fontweight']) && $stockholm_options['big_large_button_fontweight'] !== '') {
			$extra_large_btn_styles .= 'font-weight: '.esc_attr($stockholm_options['big_large_button_fontweight']).';';
		}
		
		if(isset($stockholm_options['big_large_button_padding']) && $stockholm_options['big_large_button_padding'] !== '') {
			$extra_large_btn_styles .= 'padding-left: '.intval($stockholm_options['big_large_button_padding']).'px;';
			$extra_large_btn_styles .= 'padding-right: '.intval($stockholm_options['big_large_button_padding']).'px;';
		}
		
		if(isset($stockholm_options['big_large_button_border_radius']) && $stockholm_options['big_large_button_border_radius'] !== '') {
			$extra_large_btn_styles .= '-webkit-border-radius: '.intval($stockholm_options['big_large_button_border_radius']).'px;';
			$extra_large_btn_styles .= '-moz-border-radius: '.intval($stockholm_options['big_large_button_border_radius']).'px;';
			$extra_large_btn_styles .= 'border-radius: '.intval($stockholm_options['big_large_button_border_radius']).'px;';
		}
		
		//print extra large button styles
		if($extra_large_btn_styles !== '') {
			?>
			.qbutton.big_large,
			.qbutton.big_large_full_width,
			.wpb_row .qbutton.big_large_full_width {
			<?php echo esc_attr($extra_large_btn_styles); ?>
			}
			<?php
		}
		
		//generate white button styles
		if(isset($stockholm_options['button_white_border_color']) && $stockholm_options['button_white_border_color'] !== '') {
			$white_btn_styles .= 'border-color: '.esc_attr($stockholm_options['button_white_border_color']).';';
		}
		
		if(isset($stockholm_options['button_white_text_color']) && $stockholm_options['button_white_text_color'] !== '') {
			$white_btn_styles .= 'color: '.esc_attr($stockholm_options['button_white_text_color']).';';
		}
		
		if(isset($stockholm_options['button_white_background_color']) && $stockholm_options['button_white_background_color'] !== '') {
			$white_btn_styles .= 'background-color: '.esc_attr($stockholm_options['button_white_background_color']).';';
		}
		
		//print white button styles
		if($white_btn_styles !== '') {
			?>
			.qbutton.white {
			<?php echo esc_attr($white_btn_styles); ?>
			}
			<?php
		}
		
		//generate white button hover styles
		if(isset($stockholm_options['button_white_border_color_hover']) && $stockholm_options['button_white_border_color_hover'] !== '') {
			$white_btn_hover_styles .= 'border-color: '.esc_attr($stockholm_options['button_white_border_color_hover']).';';
		}
		
		if(isset($stockholm_options['button_white_text_color_hover']) && $stockholm_options['button_white_text_color_hover'] !== '') {
			$white_btn_hover_styles .= 'color: '.esc_attr($stockholm_options['button_white_text_color_hover']).';';
		}
		
		if(isset($stockholm_options['button_white_background_color_hover']) && $stockholm_options['button_white_background_color_hover'] !== '') {
			$white_btn_hover_styles .= 'background-color: '.esc_attr($stockholm_options['button_white_background_color_hover']).';';
		}
		
		if($white_btn_hover_styles !== '') {
			?>
			.qbutton.white:hover {
			<?php echo esc_attr($white_btn_hover_styles); ?>
			}
			<?php
		}
		
		
		$underlined_btn_styles = '';
		$underlined_btn_hover_styles = '';
		//generate underlined button styles
		if(isset($stockholm_options['button_underlined_border_color']) && $stockholm_options['button_underlined_border_color'] !== '') {
			$underlined_btn_styles .= 'border-bottom-color: '.esc_attr($stockholm_options['button_underlined_border_color']).';';
		}
		
		if(isset($stockholm_options['button_underlined_text_color']) && $stockholm_options['button_underlined_text_color'] !== '') {
			$underlined_btn_styles .= 'color: '.esc_attr($stockholm_options['button_underlined_text_color']).';';
		}
		
		//print underlined button styles
		if($underlined_btn_styles !== '') {
			?>
			.qbutton.underlined {
			<?php echo esc_attr($underlined_btn_styles); ?>
			}
			<?php
		}
		
		
		
		if(isset($stockholm_options['button_underlined_text_color_hover']) && $stockholm_options['button_underlined_text_color_hover'] !== '') {
			$underlined_btn_hover_styles .= 'color: '.esc_attr($stockholm_options['button_underlined_text_color_hover']).' !important;';
		}
		
		if($underlined_btn_hover_styles !== '') {
			?>
			.qbutton.underlined:hover {
			<?php echo esc_attr($underlined_btn_hover_styles); ?>
			}
			<?php
		}
		
		//generate underlined button hover styles
		if(isset($stockholm_options['button_underlined_border_color_hover']) && $stockholm_options['button_underlined_border_color_hover'] !== '') { ?>
			.qbutton.underlined .qode-underlined-button-span {
			background-color:<?php echo esc_attr($stockholm_options['button_underlined_border_color_hover']); ?>;
			}
		
		<?php }
		
		/* ==========================================================================
   End of button custom styles
   ========================================================================== */
		?>
		
		<?php
		$q_back_to_top_styles = '';
		
		if(isset($stockholm_options['back_to_top_arrow_size']) && !empty($stockholm_options['back_to_top_arrow_size'])) {
			$q_back_to_top_styles .= 'font-size: '.intval($stockholm_options['back_to_top_arrow_size']).'px;';
		}
		
		if(isset($stockholm_options['back_to_top_arrow_color']) && !empty($stockholm_options['back_to_top_arrow_color'])) {
			$q_back_to_top_styles .= 'color: '.esc_attr($stockholm_options['back_to_top_arrow_color']).';';
		}
		
		if(isset($stockholm_options['back_to_top_background_color']) && !empty($stockholm_options['back_to_top_background_color'])) {
			$q_back_to_top_styles .= 'background-color: '.esc_attr($stockholm_options['back_to_top_background_color']).';';
		}
		
		if(isset($stockholm_options['back_to_top_border_color']) && !empty($stockholm_options['back_to_top_border_color'])) {
			$q_back_to_top_styles .= 'border-color: '.esc_attr($stockholm_options['back_to_top_border_color']).';';
		}
		
		if(isset($stockholm_options['back_to_top_border_width']) && !empty($stockholm_options['back_to_top_border_width'])) {
			$q_back_to_top_styles .= 'border-width: '.intval($stockholm_options['back_to_top_border_width']).'px;';
		}
		
		if(isset($stockholm_options['back_to_top_width']) && !empty($stockholm_options['back_to_top_width'])) {
			$q_back_to_top_styles .= 'width: '.intval($stockholm_options['back_to_top_width']).'px;';
		}
		
		if(isset($stockholm_options['back_to_top_height']) && !empty($stockholm_options['back_to_top_height'])) {
			$q_back_to_top_styles .= 'height: '.intval($stockholm_options['back_to_top_height']).'px;';
			
			if(isset($stockholm_options['back_to_top_border_width']) && !empty($stockholm_options['back_to_top_border_width'])) {
				$q_back_to_top_styles .= 'line-height: '.intval(($stockholm_options['back_to_top_height'] - 2*$stockholm_options['back_to_top_border_width'])).'px;';
			} else {
				$q_back_to_top_styles .= 'line-height: '.intval($stockholm_options['back_to_top_height']).'px;';
			}
			
		}
		
		if($q_back_to_top_styles !== '') {
			?>
			#back_to_top > span{
			<?php echo esc_attr($q_back_to_top_styles); ?>
			}
			<?php
		}
		?>
		
		<?php
		$q_back_to_top_hover_styles = '';
		
		if(isset($stockholm_options['back_to_top_arrow_hover_color']) && !empty($stockholm_options['back_to_top_arrow_hover_color'])) {
			$q_back_to_top_hover_styles .= 'color: '.esc_attr($stockholm_options['back_to_top_arrow_hover_color']).';';
		}
		
		if(isset($stockholm_options['back_to_top_background_hover_color']) && !empty($stockholm_options['back_to_top_background_hover_color'])) {
			$q_back_to_top_hover_styles .= 'background-color: '.esc_attr($stockholm_options['back_to_top_background_hover_color']).';';
		}
		
		if(isset($stockholm_options['back_to_top_border_hover_color']) && !empty($stockholm_options['back_to_top_border_hover_color'])) {
			$q_back_to_top_hover_styles .= 'border-color: '.esc_attr($stockholm_options['back_to_top_border_hover_color']).';';
		}
		
		if($q_back_to_top_hover_styles !== '') {
			?>
			#back_to_top:hover > span{
			<?php echo esc_attr($q_back_to_top_hover_styles); ?>
			}
			<?php
		}
		?>
		
		<?php
		$q_navigation_styles = '';
		$q_prettyphoto_styles = '';
		$q_prettyphoto_arrow_styles = '';
		
		if(isset($stockholm_options['navigation_arrow_size']) && !empty($stockholm_options['navigation_arrow_size'])) {
			$q_navigation_styles .= 'font-size: '.intval($stockholm_options['navigation_arrow_size']).'px;';
			
			/* PrettyPhoto arrow style */
			$q_prettyphoto_arrow_styles .= 'display: inline-block;';
			$q_prettyphoto_arrow_styles .= 'width: 100%;';
			$q_prettyphoto_arrow_styles .= 'height: 100%;';
			$q_prettyphoto_arrow_styles .= 'left: 0;';
			
			$q_prettyphoto_arrow_styles .= 'font-size: '.intval($stockholm_options['navigation_arrow_size']).'px;';
			
		}
		
		if(isset($stockholm_options['navigation_arrow_color']) && !empty($stockholm_options['navigation_arrow_color'])) {
			$q_navigation_styles .= 'color: '.esc_attr($stockholm_options['navigation_arrow_color']).';';
			$q_prettyphoto_arrow_styles .= 'color: '.esc_attr($stockholm_options['navigation_arrow_color']).';';
		}
		
		if(isset($stockholm_options['navigation_background_color']) && !empty($stockholm_options['navigation_background_color'])) {
			$q_navigation_styles .= 'background-color: '.esc_attr($stockholm_options['navigation_background_color']).';';
			$q_prettyphoto_styles .= 'background-color: '.esc_attr($stockholm_options['navigation_background_color']).' !important;';
		}
		
		if(isset($stockholm_options['navigation_border_color']) && !empty($stockholm_options['navigation_border_color'])) {
			$q_navigation_styles .= 'border: 1px solid '.esc_attr($stockholm_options['navigation_border_color']).';';
		}
		
		if(isset($stockholm_options['navigation_border_width']) && !empty($stockholm_options['navigation_border_width'])) {
			$q_navigation_styles .= 'border-width: '.intval($stockholm_options['navigation_border_width']).'px;';
		}
		
		if(isset($stockholm_options['navigation_width']) && !empty($stockholm_options['navigation_width'])) {
			$q_navigation_styles .= 'width: '.intval($stockholm_options['navigation_width']).'px;';
		}
		if(isset($stockholm_options['navigation_height']) && !empty($stockholm_options['navigation_height'])) {
			$q_navigation_styles .= 'height: '.intval($stockholm_options['navigation_height']).'px;';
			$q_navigation_styles .= 'line-height: '.intval($stockholm_options['navigation_height']).'px;';
			if(isset($stockholm_options['navigation_border_width']) && !empty($stockholm_options['navigation_border_width'])) {
				$q_navigation_styles .= 'margin-top: -'.intval($stockholm_options['navigation_height']/2 + $stockholm_options['navigation_border_width']) . 'px;';
			} else {
				$q_navigation_styles .= 'margin-top: -'.intval($stockholm_options['navigation_height']/2) . 'px;';
			}
			
		}
		
		if($q_navigation_styles !== '') {
			?>
			.qode_image_gallery_no_space .controls a.prev-slide,
			.qode_image_gallery_no_space .controls a.next-slide,
			.qode_carousels .caroufredsel-direction-nav .caroufredsel-navigation-item,
			.flex-direction-nav a, .caroufredsel-direction-nav a,
			.portfolio_single .owl-carousel .owl-prev,
			.portfolio_single .owl-carousel .owl-next,
			.qode-owl-slider .owl-nav .owl-prev,
			.qode-owl-slider .owl-nav .owl-next,
			.qode_carousels.carousel_owl .owl-nav .owl-prev,
			.qode_carousels.carousel_owl .owl-nav .owl-next,
			body div.pp_default a.pp_next,
			body div.pp_default a.pp_previous{
			<?php echo esc_attr($q_navigation_styles); ?>
			}
			<?php
			if($q_prettyphoto_styles !== '') {
				?>
				body div.pp_default a.pp_next,
				body div.pp_default a.pp_previous{
				<?php echo esc_attr($q_prettyphoto_styles); ?>
				}
				<?php
				
			}
			if($q_prettyphoto_arrow_styles !== '') {
				?>
				body div.pp_default a.pp_next:after,
				body div.pp_default a.pp_previous:after{
				<?php echo esc_attr($q_prettyphoto_arrow_styles); ?>
				}
				<?php
				
			}
		}
		?>
		
		<?php
		$q_navigation_hover_styles = '';
		$q_prettyphoto_hover_styles = '';
		$q_prettyphoto_hover_arrow_styles = '';
		
		if(isset($stockholm_options['navigation_arrow_hover_color']) && !empty($stockholm_options['navigation_arrow_hover_color'])) {
			$q_navigation_hover_styles .= 'color: '.esc_attr($stockholm_options['navigation_arrow_hover_color']).';';
			$q_prettyphoto_hover_arrow_styles .= 'color: '.esc_attr($stockholm_options['navigation_arrow_hover_color']).';';
		}
		
		if(isset($stockholm_options['navigation_background_hover_color']) && !empty($stockholm_options['navigation_background_hover_color'])) {
			$q_navigation_hover_styles .= 'background-color: '.esc_attr($stockholm_options['navigation_background_hover_color']).';';
			$q_prettyphoto_hover_styles .= 'background-color: '.esc_attr($stockholm_options['navigation_background_hover_color']).' !important;';
		}
		
		if(isset($stockholm_options['navigation_border_hover_color']) && !empty($stockholm_options['navigation_border_hover_color'])) {
			$q_navigation_hover_styles .= 'border-color: '.esc_attr($stockholm_options['navigation_border_hover_color']).';';
		}
		
		if($q_navigation_hover_styles !== '') {
			?>
			.qode_image_gallery_no_space .controls a.prev-slide:hover,
			.qode_image_gallery_no_space .controls a.next-slide:hover,
			.qode_carousels .caroufredsel-direction-nav .caroufredsel-navigation-item:hover,
			.flexslider:hover .flex-direction-nav a:hover,
			.portfolio_slider:hover .caroufredsel-direction-nav a:hover,
			.portfolio_single .owl-carousel .owl-prev:hover,
			.portfolio_single .owl-carousel .owl-next:hover,
			.qode-owl-slider .owl-nav .owl-prev:hover,
			.qode-owl-slider .owl-nav .owl-next:hover,
			.qode_carousels.carousel_owl .owl-nav .owl-prev:hover,
			.qode_carousels.carousel_owl .owl-nav .owl-next:hover,
			body div.pp_default a.pp_next:hover,
			body div.pp_default a.pp_previous:hover{
			<?php echo esc_attr($q_navigation_hover_styles); ?>
			}
			<?php
			if($q_prettyphoto_hover_styles !== '') {
				?>
				body div.pp_default a.pp_next:hover,
				body div.pp_default a.pp_previous:hover{
				<?php echo esc_attr($q_prettyphoto_hover_styles); ?>
				}
				<?php
				
			}
			if($q_prettyphoto_hover_arrow_styles !== '') {
				?>
				body div.pp_default a.pp_next:hover:after,
				body div.pp_default a.pp_previous:hover:after{
				<?php echo esc_attr($q_prettyphoto_hover_arrow_styles); ?>
				}
				<?php
				
			}
		}
		?>
		
		<?php
		$fs_navigation_styles = '';
		
		if(isset($stockholm_options['fs_navigation_arrow_size']) && !empty($stockholm_options['fs_navigation_arrow_size'])) {
			$fs_navigation_styles .= 'font-size: '.intval($stockholm_options['fs_navigation_arrow_size']).'px;';
		}
		
		if(isset($stockholm_options['fs_navigation_arrow_color']) && !empty($stockholm_options['fs_navigation_arrow_color'])) {
			$fs_navigation_styles .= 'color: '.esc_attr($stockholm_options['fs_navigation_arrow_color']).';';
		}
		
		if($fs_navigation_styles !== '') { ?>
			.full_screen_navigation_inner a{
			<?php echo esc_attr($fs_navigation_styles); ?>
			}
		<?php } ?>
		
		<?php
		$fs_navigation_hover_styles = '';
		
		if(isset($stockholm_options['fs_navigation_arrow_hover_color']) && !empty($stockholm_options['fs_navigation_arrow_hover_color'])) {
			$fs_navigation_hover_styles .= 'color: '.esc_attr($stockholm_options['fs_navigation_arrow_hover_color']).';';
		}
		
		if($fs_navigation_hover_styles !== '') { ?>
			.full_screen_navigation_inner a:hover{
			<?php echo esc_attr($fs_navigation_hover_styles); ?>
			}
		<?php } ?>
		
		<?php
		$tags_styles = array();
		$tags_hover_styles = array();
		
		if(isset($stockholm_options['tags_font_family']) && $stockholm_options['tags_font_family'] !== '-1') {
			$tags_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['tags_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['tags_font_size']) && $stockholm_options['tags_font_size'] !== '') {
			$tags_styles[] = 'font-size: '.intval($stockholm_options['tags_font_size']).'px !important';
		}
		
		if(isset($stockholm_options['tags_line_height']) && $stockholm_options['tags_line_height'] !== '') {
			$tags_styles[] = 'line-height: '.intval($stockholm_options['tags_line_height']).'px';
		}
		
		if(isset($stockholm_options['tags_letter_spacing']) && $stockholm_options['tags_letter_spacing'] !== '') {
			$tags_styles[] = 'letter-spacing: '.intval($stockholm_options['tags_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['tags_font_weight']) && $stockholm_options['tags_font_weight'] !== '') {
			$tags_styles[] = 'font-weight: '.esc_attr($stockholm_options['tags_font_weight']);
		}
		
		if(isset($stockholm_options['tags_font_style']) && $stockholm_options['tags_font_style'] !== '') {
			$tags_styles[] = 'font-style: '.esc_attr($stockholm_options['tags_font_style']);
		}
		
		if(isset($stockholm_options['tags_text_transform']) && $stockholm_options['tags_text_transform'] !== '') {
			$tags_styles[] = 'text-transform: '.esc_attr($stockholm_options['tags_text_transform']);
		}
		
		if(isset($stockholm_options['tags_color']) && $stockholm_options['tags_color'] !== '') {
			$tags_styles[] = 'color: '.esc_attr($stockholm_options['tags_color']);
		}
		
		if(isset($stockholm_options['tags_background_color']) && $stockholm_options['tags_background_color'] !== '') {
			$tags_styles[] = 'background-color: '.esc_attr($stockholm_options['tags_background_color']);
		}
		
		if(isset($stockholm_options['tags_border_color']) && $stockholm_options['tags_border_color'] !== '') {
			$tags_styles[] = 'border-color: '.esc_attr($stockholm_options['tags_border_color']);
		}
		
		if(isset($stockholm_options['tags_border_width']) && $stockholm_options['tags_border_width'] !== '') {
			$tags_styles[] = 'border-width: '.intval($stockholm_options['tags_border_width']).'px';
		}
		
		if(is_array($tags_styles) && count($tags_styles)) { ?>
			.single_tags a,
			aside.sidebar .widget:not(.woocommerce) .tagcloud a,
			.widget .tagcloud a{
			<?php echo implode(';', $tags_styles); ?>
			}
		<?php }
		
		if(isset($stockholm_options['tags_hover_color']) && $stockholm_options['tags_hover_color'] !== '') {
			$tags_hover_styles[] = 'color: '.esc_attr($stockholm_options['tags_hover_color']);
		}
		
		if(isset($stockholm_options['tags_background_hover_color']) && $stockholm_options['tags_background_hover_color'] !== '') {
			$tags_hover_styles[] = 'background-color: '.esc_attr($stockholm_options['tags_background_hover_color']);
		}
		
		if(isset($stockholm_options['tags_border_hover_color']) && $stockholm_options['tags_border_hover_color'] !== '') {
			$tags_hover_styles[] = 'border-color: '.esc_attr($stockholm_options['tags_border_hover_color']);
		}
		
		if(is_array($tags_hover_styles) && count($tags_hover_styles)) { ?>
			.single_tags a:hover,
			aside.sidebar .widget:not(.woocommerce) .tagcloud a:hover,
			.widget .tagcloud a:hover{
			<?php echo implode(';', $tags_hover_styles); ?>
			}
		<?php } ?>
		
		<?php
		$testimonials_title_styles = array();
		
		if(isset($stockholm_options['testimonials_title_font_family']) && $stockholm_options['testimonials_title_font_family'] !== '-1') {
			$testimonials_title_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['testimonials_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['testimonials_title_font_size']) && $stockholm_options['testimonials_title_font_size'] !== '') {
			$testimonials_title_styles[] = 'font-size: '.intval($stockholm_options['testimonials_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['testimonials_title_line_height']) && $stockholm_options['testimonials_title_line_height'] !== '') {
			$testimonials_title_styles[] = 'line-height: '.intval($stockholm_options['testimonials_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['testimonials_title_letter_spacing']) && $stockholm_options['testimonials_title_letter_spacing'] !== '') {
			$testimonials_title_styles[] = 'letter-spacing: '.intval($stockholm_options['testimonials_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['testimonials_title_font_weight']) && $stockholm_options['testimonials_title_font_weight'] !== '') {
			$testimonials_title_styles[] = 'font-weight: '.esc_attr($stockholm_options['testimonials_title_font_weight']);
		}
		
		if(isset($stockholm_options['testimonials_title_font_style']) && $stockholm_options['testimonials_title_font_style'] !== '') {
			$testimonials_title_styles[] = 'font-style: '.esc_attr($stockholm_options['testimonials_title_font_style']);
		}
		
		if(isset($stockholm_options['testimonials_title_text_transform']) && $stockholm_options['testimonials_title_text_transform'] !== '') {
			$testimonials_title_styles[] = 'text-transform: '.esc_attr($stockholm_options['testimonials_title_text_transform']);
		}
		
		if(isset($stockholm_options['testimonials_title_color']) && $stockholm_options['testimonials_title_color'] !== '') {
			$testimonials_title_styles[] = 'color: '.esc_attr($stockholm_options['testimonials_title_color']);
		}
		
		if(is_array($testimonials_title_styles) && count($testimonials_title_styles)) { ?>
			.testimonials .testimonial_text_inner p.testimonial_title{
			<?php echo implode(';', $testimonials_title_styles); ?>
			}
		<?php } ?>
		
		<?php
		$testimonials_text_styles = array();
		
		if(isset($stockholm_options['testimonials_text_font_family']) && $stockholm_options['testimonials_text_font_family'] !== '-1') {
			$testimonials_text_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['testimonials_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['testimonials_text_font_size']) && $stockholm_options['testimonials_text_font_size'] !== '') {
			$testimonials_text_styles[] = 'font-size: '.intval($stockholm_options['testimonials_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['testimonials_text_line_height']) && $stockholm_options['testimonials_text_line_height'] !== '') {
			$testimonials_text_styles[] = 'line-height: '.intval($stockholm_options['testimonials_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['testimonials_text_letter_spacing']) && $stockholm_options['testimonials_text_letter_spacing'] !== '') {
			$testimonials_text_styles[] = 'letter-spacing: '.intval($stockholm_options['testimonials_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['testimonials_text_font_weight']) && $stockholm_options['testimonials_text_font_weight'] !== '') {
			$testimonials_text_styles[] = 'font-weight: '.esc_attr($stockholm_options['testimonials_text_font_weight']);
		}
		
		if(isset($stockholm_options['testimonials_text_font_style']) && $stockholm_options['testimonials_text_font_style'] !== '') {
			$testimonials_text_styles[] = 'font-style: '.esc_attr($stockholm_options['testimonials_text_font_style']);
		}
		
		if(isset($stockholm_options['testimonials_text_text_transform']) && $stockholm_options['testimonials_text_text_transform'] !== '') {
			$testimonials_text_styles[] = 'text-transform: '.esc_attr($stockholm_options['testimonials_text_text_transform']);
		}
		
		if(isset($stockholm_options['testimonials_text_color']) && $stockholm_options['testimonials_text_color'] !== '') {
			$testimonials_text_styles[] = 'color: '.esc_attr($stockholm_options['testimonials_text_color']);
		}
		
		if(is_array($testimonials_text_styles) && count($testimonials_text_styles)) { ?>
			.testimonials .testimonial_text_inner p:not(.testimonial_author):not(.testimonial_title){
			<?php echo implode(';', $testimonials_text_styles); ?>
			}
		<?php } ?>
		
		<?php
		$testimonials_author_styles = array();
		
		if(isset($stockholm_options['testimonials_author_font_family']) && $stockholm_options['testimonials_author_font_family'] !== '-1') {
			$testimonials_author_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['testimonials_author_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['testimonials_author_font_size']) && $stockholm_options['testimonials_author_font_size'] !== '') {
			$testimonials_author_styles[] = 'font-size: '.intval($stockholm_options['testimonials_author_font_size']).'px';
		}
		
		if(isset($stockholm_options['testimonials_author_line_height']) && $stockholm_options['testimonials_author_line_height'] !== '') {
			$testimonials_author_styles[] = 'line-height: '.intval($stockholm_options['testimonials_author_line_height']).'px';
		}
		
		if(isset($stockholm_options['testimonials_author_letter_spacing']) && $stockholm_options['testimonials_author_letter_spacing'] !== '') {
			$testimonials_author_styles[] = 'letter-spacing: '.intval($stockholm_options['testimonials_author_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['testimonials_author_font_weight']) && $stockholm_options['testimonials_author_font_weight'] !== '') {
			$testimonials_author_styles[] = 'font-weight: '.esc_attr($stockholm_options['testimonials_author_font_weight']);
		}
		
		if(isset($stockholm_options['testimonials_author_font_style']) && $stockholm_options['testimonials_author_font_style'] !== '') {
			$testimonials_author_styles[] = 'font-style: '.esc_attr($stockholm_options['testimonials_author_font_style']);
		}
		
		if(isset($stockholm_options['testimonials_author_text_transform']) && $stockholm_options['testimonials_author_text_transform'] !== '') {
			$testimonials_author_styles[] = 'text-transform: '.esc_attr($stockholm_options['testimonials_author_text_transform']);
		}
		
		if(isset($stockholm_options['testimonials_author_color']) && $stockholm_options['testimonials_author_color'] !== '') {
			$testimonials_author_styles[] = 'color: '.esc_attr($stockholm_options['testimonials_author_color']);
		}
		
		if(is_array($testimonials_author_styles) && count($testimonials_author_styles)) { ?>
			.testimonials .testimonial_text_inner p.testimonial_author{
			<?php echo implode(';', $testimonials_author_styles); ?>
			}
		<?php } ?>
		
		<?php
		$testimonials_author_job_styles = array();
		
		if(isset($stockholm_options['testimonials_author_job_font_family']) && $stockholm_options['testimonials_author_job_font_family'] !== '-1') {
			$testimonials_author_job_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['testimonials_author_job_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['testimonials_author_job_font_size']) && $stockholm_options['testimonials_author_job_font_size'] !== '') {
			$testimonials_author_job_styles[] = 'font-size: '.intval($stockholm_options['testimonials_author_job_font_size']).'px';
		}
		
		if(isset($stockholm_options['testimonials_author_job_line_height']) && $stockholm_options['testimonials_author_job_line_height'] !== '') {
			$testimonials_author_job_styles[] = 'line-height: '.intval($stockholm_options['testimonials_author_job_line_height']).'px';
		}
		
		if(isset($stockholm_options['testimonials_author_job_letter_spacing']) && $stockholm_options['testimonials_author_job_letter_spacing'] !== '') {
			$testimonials_author_job_styles[] = 'letter-spacing: '.intval($stockholm_options['testimonials_author_job_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['testimonials_author_job_font_weight']) && $stockholm_options['testimonials_author_job_font_weight'] !== '') {
			$testimonials_author_job_styles[] = 'font-weight: '.esc_attr($stockholm_options['testimonials_author_job_font_weight']);
		}
		
		if(isset($stockholm_options['testimonials_author_job_font_style']) && $stockholm_options['testimonials_author_job_font_style'] !== '') {
			$testimonials_author_job_styles[] = 'font-style: '.esc_attr($stockholm_options['testimonials_author_job_font_style']);
		}
		
		if(isset($stockholm_options['testimonials_author_job_text_transform']) && $stockholm_options['testimonials_author_job_text_transform'] !== '') {
			$testimonials_author_job_styles[] = 'text-transform: '.esc_attr($stockholm_options['testimonials_author_job_text_transform']);
		}
		
		if(isset($stockholm_options['testimonials_author_job_color']) && $stockholm_options['testimonials_author_job_color'] !== '') {
			$testimonials_author_job_styles[] = 'color: '.esc_attr($stockholm_options['testimonials_author_job_color']);
		}
		
		if(is_array($testimonials_author_job_styles) && count($testimonials_author_job_styles)) { ?>
			.testimonials .testimonial_text_inner p.testimonial_author .testimonial_author_job{
			<?php echo implode(';', $testimonials_author_job_styles); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['testimonials_navigation_color']) && !empty($stockholm_options['testimonials_navigation_color'])) { ?>
			.testimonials_holder .flex-control-paging li a{
			background-color: <?php echo esc_attr($stockholm_options['testimonials_navigation_color']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['testimonials_navigation_active_color']) && !empty($stockholm_options['testimonials_navigation_active_color'])) { ?>
			.testimonials_holder .flex-control-paging li a.flex-active,
			.testimonials_holder.light .flex-control-paging li a.flex-active{
			background-color: <?php echo esc_attr($stockholm_options['testimonials_navigation_active_color']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['testimonaials_navigation_border_radius']) && $stockholm_options['testimonaials_navigation_border_radius'] !== '') { ?>
			.testimonials_holder .flex-control-paging li a {
			border-radius: <?php echo intval($stockholm_options['testimonaials_navigation_border_radius']);  ?>px;
			}
		<?php } ?>
		
		<?php
		$testimonials_grouped_elements_styles = array();
		if (isset($stockholm_options['testimonials_grouped_background_color']) && $stockholm_options['testimonials_grouped_background_color'] !== '') {
			if (isset($stockholm_options['testimonials_grouped_background_transparency']) && $stockholm_options['testimonials_grouped_background_transparency'] !== '') {
				$testimonials_grouped_background_color = stockholm_qode_hex2rgb($stockholm_options['testimonials_grouped_background_color']);
				$testimonials_grouped_elements_styles[] = 'background-color: rgba(' . esc_attr($testimonials_grouped_background_color[0]) . ',' . esc_attr($testimonials_grouped_background_color[1]) . ',' . esc_attr($testimonials_grouped_background_color[2]) . ',' . esc_attr($stockholm_options['testimonials_grouped_background_transparency']) . ');';
			} else {
				$testimonials_grouped_elements_styles[] = 'background-color: ' . esc_attr($stockholm_options['testimonials_grouped_background_color']) . ';';
			}
		}
		
		if (isset($stockholm_options['testimonials_grouped_border_color']) && $stockholm_options['testimonials_grouped_border_color'] !== '') {
			$testimonials_grouped_elements_styles[] = 'border-color: ' . esc_attr($stockholm_options['testimonials_grouped_border_color']);
		}
		
		if (isset($stockholm_options['testimonials_grouped_border_width']) && $stockholm_options['testimonials_grouped_border_width'] !== '') {
			$testimonials_grouped_elements_styles[] = 'border-width: ' . esc_attr($stockholm_options['testimonials_grouped_border_width']) . 'px';
		}
		
		if(is_array($testimonials_grouped_elements_styles) && count($testimonials_grouped_elements_styles)) { ?>
			.testimonial_content_grouped_item{
			<?php echo implode(';', $testimonials_grouped_elements_styles); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['counter_color']) || isset($stockholm_options['counters_fontweight']) || isset($stockholm_options['counters_font_size']) || isset($stockholm_options['counters_font_family'])) { ?>
			.q_counter_holder span.counter{
			<?php if (isset($stockholm_options['counter_color']) && !empty($stockholm_options['counter_color'])) { ?>	color: <?php echo esc_attr($stockholm_options['counter_color']);  ?>; <?php } ?>
			<?php if (isset($stockholm_options['counters_fontweight']) && !empty($stockholm_options['counters_fontweight'])) { ?>	font-weight: <?php echo esc_attr($stockholm_options['counters_fontweight']);  ?>; <?php } ?>
			<?php if (isset($stockholm_options['counters_font_size']) && !empty($stockholm_options['counters_font_size'])) { ?>	font-size: <?php echo intval($stockholm_options['counters_font_size']);  ?>px; <?php } ?>
			<?php if(isset($stockholm_options['counters_font_family']) && $stockholm_options['counters_font_family'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['counters_font_family']); ?>', sans-serif;
			<?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['counter_text_color']) || !empty($stockholm_options['counters_text_fontweight']) || !empty($stockholm_options['counters_text_texttransform']) || $stockholm_options['counters_text_letterspacing'] !== '' || !empty($stockholm_options['counters_text_font_size'])) { ?>
			.q_counter_holder p.counter_text{
			<?php if (!empty($stockholm_options['counter_text_color'])) { ?>	color: <?php echo esc_attr($stockholm_options['counter_text_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['counters_text_fontweight'])) { ?>	font-weight: <?php echo esc_attr($stockholm_options['counters_text_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['counters_text_texttransform'])) { ?>	text-transform: <?php echo esc_attr($stockholm_options['counters_text_texttransform']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['counters_text_letterspacing'])) { ?>	letter-spacing: <?php echo intval($stockholm_options['counters_text_letterspacing']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['counters_text_font_size'])) { ?>	font-size: <?php echo intval($stockholm_options['counters_text_font_size']);  ?>px; line-height: 1.25em; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['counter_separator_color'])) { ?>
			.wpb_column>.wpb_wrapper .q_counter_holder .separator.small{
			<?php if (!empty($stockholm_options['counter_separator_color'])) { ?>	border-color: <?php echo esc_attr($stockholm_options['counter_separator_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['countdown_color']) || !empty($stockholm_options['countdown_fontweight']) || !empty($stockholm_options['countdown_font_size'])) { ?>
			.countdown-amount{
			<?php if (!empty($stockholm_options['countdown_color'])) { ?>	color: <?php echo esc_attr($stockholm_options['countdown_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['countdown_fontweight'])) { ?>	font-weight: <?php echo esc_attr($stockholm_options['countdown_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['countdown_font_size'])) { ?>	font-size: <?php echo intval($stockholm_options['countdown_font_size']);  ?>px; <?php } ?>
			}
		<?php } ?>
		<?php if (isset($stockholm_options['countdown_text_color']) || isset($stockholm_options['countdown_text_fontweight']) || isset($stockholm_options['countdown_text_texttransform']) || isset($stockholm_options['countdown_text_letterspacing']) || isset($stockholm_options['countdown_text_font_size'])) { ?>
			.countdown-period{
			<?php if (isset($stockholm_options['countdown_text_color']) && !empty($stockholm_options['countdown_text_color'])) { ?>	color: <?php echo esc_attr($stockholm_options['countdown_text_color']);  ?>; <?php } ?>
			<?php if (isset($stockholm_options['countdown_text_fontweight']) && !empty($stockholm_options['countdown_text_fontweight'])) { ?>	font-weight: <?php echo esc_attr($stockholm_options['countdown_text_fontweight']);  ?>; <?php } ?>
			<?php if (isset($stockholm_options['countdown_text_texttransform']) && !empty($stockholm_options['countdown_text_texttransform'])) { ?>	text-transform: <?php echo esc_attr($stockholm_options['countdown_text_texttransform']);  ?>; <?php } ?>
			<?php if (isset($stockholm_options['countdown_text_letterspacing']) && !empty($stockholm_options['countdown_text_letterspacing'])) { ?>	letter-spacing: <?php echo intval($stockholm_options['countdown_text_letterspacing']);  ?>px; <?php } ?>
			<?php if (isset($stockholm_options['countdown_text_font_size']) && !empty($stockholm_options['countdown_text_font_size'])) { ?>	font-size: <?php echo intval($stockholm_options['countdown_text_font_size']);  ?>px; line-height: 1.25em; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['progress_bar_horizontal_fontsize']) || !empty($stockholm_options['progress_bar_horizontal_fontweight'])) { ?>
			.q_progress_bar .progress_number{
			<?php if (!empty($stockholm_options['progress_bar_horizontal_fontsize'])) { ?>	font-size: <?php echo intval($stockholm_options['progress_bar_horizontal_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['progress_bar_horizontal_fontweight'])) { ?>	font-weight: <?php echo esc_attr($stockholm_options['progress_bar_horizontal_fontweight']);  ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['pie_charts_fontsize']) || !empty($stockholm_options['pie_charts_fontweight'])) { ?>
			.q_pie_chart_holder .tocounter{
			<?php if (!empty($stockholm_options['pie_charts_fontsize'])) { ?>	font-size: <?php echo intval($stockholm_options['pie_charts_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['pie_charts_fontweight'])) { ?>	font-weight: <?php echo esc_attr($stockholm_options['pie_charts_fontweight']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['tabs_text_size']) || !empty($stockholm_options['tabs_fontweight']) || !empty($stockholm_options['tab_text_text_transform']) || (isset($stockholm_options['tabs_nav_font_family']) && $stockholm_options['tabs_nav_font_family'] != '-1') || !empty($stockholm_options['tab_text_color']) || !empty($stockholm_options['tab_background_color'])) { ?>
			.q_tabs .tabs-nav li a{
			<?php if (!empty($stockholm_options['tabs_text_size'])) { ?>font-size: <?php echo intval($stockholm_options['tabs_text_size']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['tabs_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['tabs_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['tab_text_text_transform'])) { ?>text-transform: <?php echo esc_attr($stockholm_options['tab_text_text_transform']);  ?>; <?php } ?>
			<?php if($stockholm_options['tabs_nav_font_family'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['tabs_nav_font_family']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['tab_text_color'])) { ?>color: <?php echo esc_attr($stockholm_options['tab_text_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['tab_background_color'])) { ?>background-color: <?php echo esc_attr($stockholm_options['tab_background_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if ( !empty($stockholm_options['tab_active_text_color']) || !empty($stockholm_options['tab_active_background_color'])) { ?>
			.q_tabs .tabs-nav li.active a,
			.q_tabs .tabs-nav li a:hover{
			<?php if (!empty($stockholm_options['tab_active_text_color'])) { ?>color: <?php echo esc_attr($stockholm_options['tab_active_text_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['tab_active_background_color'])) { ?>background-color: <?php echo esc_attr($stockholm_options['tab_active_background_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (stockholm_qode_options()->getOptionValue('tab_bottom_border_color') !== '') {?>
			.q_tabs .tabs-nav li a{
			background-color: transparent !important;
			}
			
			.q_tabs .tabs-nav li.active a:after{
			background-color: transparent;
			transition: background-color 0.3s ease;
			-webkit-transition: background-color 0.3s ease;
			}
			
			.q_tabs .tabs-nav li.active a:after,
			.q_tabs .tabs-nav li a:hover:after{
			content: "";
			width: 100%;
			height: 5px;
			position: absolute;
			bottom: 0;
			left: 0;
			z-index: 10;
			background-color: <?php echo esc_attr(stockholm_qode_options()->getOptionValue('tab_bottom_border_color'))?>;
			}
			
			.q_tabs .tabs-nav:after{
			content: "";
			width: 100%;
			height: 1px;
			position: absolute;
			bottom: 2px;
			left: 0;
			background-color: #d6d6d6;
			}
		<?php } ?>
		
		<?php if ( !empty($stockholm_options['tabs_content_text_size']) || !empty($stockholm_options['tabs_content_background_color'])) { ?>
			.q_tabs .tabs-container{
			<?php if (!empty($stockholm_options['tabs_content_text_size'])) { ?>font-size: <?php echo intval($stockholm_options['tabs_content_text_size']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['tabs_content_background_color'])) { ?>background-color: <?php echo esc_attr($stockholm_options['tabs_content_background_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php
		if(isset($stockholm_options['google_maps_height'])){
			if (intval($stockholm_options['google_maps_height']) > 0) {
				?>
				.q_google_map{
				height: <?php echo intval($stockholm_options['google_maps_height']); ?>px;
				}
				<?php
			}
		}
		?>
		<?php if (!empty($stockholm_options['footer_top_background_color'])) { ?>
			.footer_top_holder{
			background-color:<?php echo esc_attr($stockholm_options['footer_top_background_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['footer_top_padding']) || !empty($stockholm_options['footer_bottom_padding'])) { ?>
			.footer_top,
			.footer_top.footer_top_full{
			<?php if (!empty($stockholm_options['footer_top_padding'])){ ?>padding-top: <?php echo intval($stockholm_options['footer_top_padding']); ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['footer_bottom_padding'])){ ?>padding-bottom: <?php echo intval($stockholm_options['footer_bottom_padding']); ?>px; <?php } ?>
			}
		<?php } ?>
		<?php if (!empty($stockholm_options['footer_left_padding']) || !empty($stockholm_options['footer_right_padding'])) { ?>
			.footer_top.footer_top_full {
			<?php if (!empty($stockholm_options['footer_left_padding'])){ ?>padding-left: <?php echo esc_attr($stockholm_options['footer_left_padding']); echo esc_attr( $px_string = (stockholm_qode_string_ends_with($stockholm_options['footer_left_padding'], 'px') || stockholm_qode_string_ends_with($stockholm_options['footer_left_padding'], '%')) ? '' : 'px' ); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['footer_right_padding'])){ ?>padding-right: <?php echo esc_attr($stockholm_options['footer_right_padding']); echo esc_attr( $px_string = (stockholm_qode_string_ends_with($stockholm_options['footer_right_padding'], 'px') || stockholm_qode_string_ends_with($stockholm_options['footer_right_padding'], '%')) ? '' : 'px' ); ?>; <?php } ?>
			}
		<?php } ?>
		<?php if (isset($stockholm_options['footer_columns_border_color']) && !empty($stockholm_options['footer_columns_border_color'])) { ?>
			footer.footer_border_columns .qode_column{
			border-color:<?php echo esc_attr($stockholm_options['footer_columns_border_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$footer_top_text_style = array();
		
		if(isset($stockholm_options['footer_top_text_font_family']) && $stockholm_options['footer_top_text_font_family'] !== '-1') {
			$footer_top_text_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['footer_top_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['footer_top_text_font_size']) && $stockholm_options['footer_top_text_font_size'] !== '') {
			$footer_top_text_style[] = 'font-size: '.intval($stockholm_options['footer_top_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['footer_top_text_line_height']) && $stockholm_options['footer_top_text_line_height'] !== '') {
			$footer_top_text_style[] = 'line-height: '.intval($stockholm_options['footer_top_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['footer_top_text_letter_spacing']) && $stockholm_options['footer_top_text_letter_spacing'] !== '') {
			$footer_top_text_style[] = 'letter-spacing: '.intval($stockholm_options['footer_top_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['footer_top_text_font_weight']) && $stockholm_options['footer_top_text_font_weight'] !== '') {
			$footer_top_text_style[] = 'font-weight: '.esc_attr($stockholm_options['footer_top_text_font_weight']);
		}
		
		if(isset($stockholm_options['footer_top_text_font_style']) && $stockholm_options['footer_top_text_font_style'] !== '') {
			$footer_top_text_style[] = 'font-style: '.esc_attr($stockholm_options['footer_top_text_font_style']);
		}
		
		if(isset($stockholm_options['footer_top_text_text_transform']) && $stockholm_options['footer_top_text_text_transform'] !== '') {
			$footer_top_text_style[] = 'text-transform: '.esc_attr($stockholm_options['footer_top_text_text_transform']);
		}
		
		if(isset($stockholm_options['footer_top_text_color']) && $stockholm_options['footer_top_text_color'] !== '') {
			$footer_top_text_style[] = 'color: '.esc_attr($stockholm_options['footer_top_text_color']);
		}
		
		if(is_array($footer_top_text_style) && count($footer_top_text_style)) { ?>
			.footer_top,
			.footer_top p,
			.footer_top span:not(.q_social_icon_holder):not(.fa-stack):not(.social_icon):not(.social_twitter):not(.q_font_elegant_icon),
			.footer_top li,
			.footer_top .textwidget,
			.footer_top .widget_recent_entries>ul>li>span{
			<?php echo implode(';', $footer_top_text_style); ?>
			}
		<?php } ?>
		
		<?php
		$footer_top_link_style = array();
		
		if(isset($stockholm_options['footer_top_link_font_family']) && $stockholm_options['footer_top_link_font_family'] !== '-1') {
			$footer_top_link_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['footer_top_link_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['footer_top_link_font_size']) && $stockholm_options['footer_top_link_font_size'] !== '') {
			$footer_top_link_style[] = 'font-size: '.intval($stockholm_options['footer_top_link_font_size']).'px';
		}
		
		if(isset($stockholm_options['footer_top_link_line_height']) && $stockholm_options['footer_top_link_line_height'] !== '') {
			$footer_top_link_style[] = 'line-height: '.intval($stockholm_options['footer_top_link_line_height']).'px';
		}
		
		if(isset($stockholm_options['footer_top_link_letter_spacing']) && $stockholm_options['footer_top_link_letter_spacing'] !== '') {
			$footer_top_link_style[] = 'letter-spacing: '.intval($stockholm_options['footer_top_link_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['footer_top_link_font_weight']) && $stockholm_options['footer_top_link_font_weight'] !== '') {
			$footer_top_link_style[] = 'font-weight: '.esc_attr($stockholm_options['footer_top_link_font_weight']);
		}
		
		if(isset($stockholm_options['footer_top_link_font_style']) && $stockholm_options['footer_top_link_font_style'] !== '') {
			$footer_top_link_style[] = 'font-style: '.esc_attr($stockholm_options['footer_top_link_font_style']);
		}
		
		if(isset($stockholm_options['footer_top_link_text_transform']) && $stockholm_options['footer_top_link_text_transform'] !== '') {
			$footer_top_link_style[] = 'text-transform: '.esc_attr($stockholm_options['footer_top_link_text_transform']);
		}
		
		if(isset($stockholm_options['footer_top_link_color']) && $stockholm_options['footer_top_link_color'] !== '') {
			$footer_top_link_style[] = 'color: '.esc_attr($stockholm_options['footer_top_link_color']);
		}
		
		if(is_array($footer_top_link_style) && count($footer_top_link_style)) { ?>
			.footer_top a{
			<?php echo implode(';', $footer_top_link_style); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['footer_top_link_hover_color']) && !empty($stockholm_options['footer_top_link_hover_color'])) { ?>
			.footer_top a:hover,
			.footer_top .qode_twitter_widget li .tweet_content_holder .qode_tweet_text a {
			color: <?php echo esc_attr($stockholm_options['footer_top_link_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['footer_bottom_background_color'])) { ?>
			.footer_bottom_holder{
			background-color:<?php echo esc_attr($stockholm_options['footer_bottom_background_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$footer_bottom_text_style = array();
		
		if(isset($stockholm_options['footer_bottom_text_font_family']) && $stockholm_options['footer_bottom_text_font_family'] !== '-1') {
			$footer_bottom_text_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['footer_bottom_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['footer_bottom_text_font_size']) && $stockholm_options['footer_bottom_text_font_size'] !== '') {
			$footer_bottom_text_style[] = 'font-size: '.intval($stockholm_options['footer_bottom_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['footer_bottom_text_line_height']) && $stockholm_options['footer_bottom_text_line_height'] !== '') {
			$footer_bottom_text_style[] = 'line-height: '.intval($stockholm_options['footer_bottom_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['footer_bottom_text_letter_spacing']) && $stockholm_options['footer_bottom_text_letter_spacing'] !== '') {
			$footer_bottom_text_style[] = 'letter-spacing: '.intval($stockholm_options['footer_bottom_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['footer_bottom_text_font_weight']) && $stockholm_options['footer_bottom_text_font_weight'] !== '') {
			$footer_bottom_text_style[] = 'font-weight: '.esc_attr($stockholm_options['footer_bottom_text_font_weight']);
		}
		
		if(isset($stockholm_options['footer_bottom_text_font_style']) && $stockholm_options['footer_bottom_text_font_style'] !== '') {
			$footer_bottom_text_style[] = 'font-style: '.esc_attr($stockholm_options['footer_bottom_text_font_style']);
		}
		
		if(isset($stockholm_options['footer_bottom_text_text_transform']) && $stockholm_options['footer_bottom_text_text_transform'] !== '') {
			$footer_bottom_text_style[] = 'text-transform: '.esc_attr($stockholm_options['footer_bottom_text_text_transform']);
		}
		
		if(isset($stockholm_options['footer_bottom_text_color']) && $stockholm_options['footer_bottom_text_color'] !== '') {
			$footer_bottom_text_style[] = 'color: '.esc_attr($stockholm_options['footer_bottom_text_color']);
		}
		
		if(is_array($footer_bottom_text_style) && count($footer_bottom_text_style)) { ?>
			
			.footer_bottom,
			.footer_bottom span:not(.q_social_icon_holder):not(.fa-stack):not(.social_icon):not(.q_font_elegant_icon),
			.footer_bottom p{
			<?php echo implode(';', $footer_bottom_text_style); ?>
			}
		<?php } ?>
		
		<?php
		$footer_bottom_link_style = array();
		
		if(isset($stockholm_options['footer_bottom_link_font_family']) && $stockholm_options['footer_bottom_link_font_family'] !== '-1') {
			$footer_bottom_link_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['footer_bottom_link_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['footer_bottom_link_font_size']) && $stockholm_options['footer_bottom_link_font_size'] !== '') {
			$footer_bottom_link_style[] = 'font-size: '.intval($stockholm_options['footer_bottom_link_font_size']).'px';
		}
		
		if(isset($stockholm_options['footer_bottom_link_line_height']) && $stockholm_options['footer_bottom_link_line_height'] !== '') {
			$footer_bottom_link_style[] = 'line-height: '.intval($stockholm_options['footer_bottom_link_line_height']).'px';
		}
		
		if(isset($stockholm_options['footer_bottom_link_letter_spacing']) && $stockholm_options['footer_bottom_link_letter_spacing'] !== '') {
			$footer_bottom_link_style[] = 'letter-spacing: '.intval($stockholm_options['footer_bottom_link_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['footer_bottom_link_font_weight']) && $stockholm_options['footer_bottom_link_font_weight'] !== '') {
			$footer_bottom_link_style[] = 'font-weight: '.esc_attr($stockholm_options['footer_bottom_link_font_weight']);
		}
		
		if(isset($stockholm_options['footer_bottom_link_font_style']) && $stockholm_options['footer_bottom_link_font_style'] !== '') {
			$footer_bottom_link_style[] = 'font-style: '.esc_attr($stockholm_options['footer_bottom_link_font_style']);
		}
		
		if(isset($stockholm_options['footer_bottom_link_text_transform']) && $stockholm_options['footer_bottom_link_text_transform'] !== '') {
			$footer_bottom_link_style[] = 'text-transform: '.esc_attr($stockholm_options['footer_bottom_link_text_transform']);
		}
		
		if(isset($stockholm_options['footer_bottom_link_color']) && $stockholm_options['footer_bottom_link_color'] !== '') {
			$footer_bottom_link_style[] = 'color: '.esc_attr($stockholm_options['footer_bottom_link_color']);
		}
		
		if(is_array($footer_bottom_link_style) && count($footer_bottom_link_style)) { ?>
			.footer_bottom a,
			.footer_bottom ul li a{
			<?php echo implode(';', $footer_bottom_link_style); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['footer_bottom_link_hover_color']) && !empty($stockholm_options['footer_bottom_link_hover_color'])) { ?>
			.footer_bottom a:hover,
			.footer_bottom ul li a:hover{
			color:<?php echo esc_attr($stockholm_options['footer_bottom_link_hover_color']);  ?>; !important;
			}
		<?php } ?>
		
		<?php
		$footer_title_styles = array();
		
		if(isset($stockholm_options['footer_title_font_family']) && $stockholm_options['footer_title_font_family'] !== '-1') {
			$footer_title_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['footer_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['footer_title_font_size']) && $stockholm_options['footer_title_font_size'] !== '') {
			$footer_title_styles[] = 'font-size: '.intval($stockholm_options['footer_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['footer_title_line_height']) && $stockholm_options['footer_title_line_height'] !== '') {
			$footer_title_styles[] = 'line-height: '.intval($stockholm_options['footer_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['footer_title_letter_spacing']) && $stockholm_options['footer_title_letter_spacing'] !== '') {
			$footer_title_styles[] = 'letter-spacing: '.intval($stockholm_options['footer_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['footer_title_font_weight']) && $stockholm_options['footer_title_font_weight'] !== '') {
			$footer_title_styles[] = 'font-weight: '.esc_attr($stockholm_options['footer_title_font_weight']);
		}
		
		if(isset($stockholm_options['footer_title_font_style']) && $stockholm_options['footer_title_font_style'] !== '') {
			$footer_title_styles[] = 'font-style: '.esc_attr($stockholm_options['footer_title_font_style']);
		}
		
		if(isset($stockholm_options['footer_title_text_transform']) && $stockholm_options['footer_title_text_transform'] !== '') {
			$footer_title_styles[] = 'text-transform: '.esc_attr($stockholm_options['footer_title_text_transform']);
		}
		
		if(isset($stockholm_options['footer_title_color']) && $stockholm_options['footer_title_color'] !== '') {
			$footer_title_styles[] = 'color: '.esc_attr($stockholm_options['footer_title_color']);
		}
		
		if(is_array($footer_title_styles) && count($footer_title_styles)) { ?>
			.footer_top .widget h4 {
			<?php echo implode(';', $footer_title_styles); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['footer_bottom_height']) && $stockholm_options['footer_bottom_height'] !== '') { ?>
			.footer_bottom { height: <?php echo intval($stockholm_options['footer_bottom_height']); ?>px; }
		<?php } ?>
		
		<?php if (!empty($stockholm_options['content_bottom_background_color'])) { ?>
			.content_bottom{
			background-color:<?php echo esc_attr($stockholm_options['content_bottom_background_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['sidebar_title_background']) && !empty($stockholm_options['sidebar_title_background'])) { ?>
			aside.sidebar .widget h4{
			background-color:<?php echo esc_attr($stockholm_options['sidebar_title_background']);  ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['sidebar_title_border_color']) && !empty($stockholm_options['sidebar_title_border_color'])) { ?>
			aside.sidebar .widget h4{
			border: 1px solid <?php echo esc_attr($stockholm_options['sidebar_title_border_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$sidebar_title_styles = array();
		
		if(isset($stockholm_options['sidebar_title_font_family']) && $stockholm_options['sidebar_title_font_family'] !== '-1') {
			$sidebar_title_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['sidebar_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['sidebar_title_font_size']) && $stockholm_options['sidebar_title_font_size'] !== '') {
			$sidebar_title_styles[] = 'font-size: '.intval($stockholm_options['sidebar_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['sidebar_title_line_height']) && $stockholm_options['sidebar_title_line_height'] !== '') {
			$sidebar_title_styles[] = 'line-height: '.intval($stockholm_options['sidebar_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['sidebar_title_letter_spacing']) && $stockholm_options['sidebar_title_letter_spacing'] !== '') {
			$sidebar_title_styles[] = 'letter-spacing: '.intval($stockholm_options['sidebar_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['sidebar_title_font_weight']) && $stockholm_options['sidebar_title_font_weight'] !== '') {
			$sidebar_title_styles[] = 'font-weight: '.esc_attr($stockholm_options['sidebar_title_font_weight']);
		}
		
		if(isset($stockholm_options['sidebar_title_font_style']) && $stockholm_options['sidebar_title_font_style'] !== '') {
			$sidebar_title_styles[] = 'font-style: '.esc_attr($stockholm_options['sidebar_title_font_style']);
		}
		
		if(isset($stockholm_options['sidebar_title_text_transform']) && $stockholm_options['sidebar_title_text_transform'] !== '') {
			$sidebar_title_styles[] = 'text-transform: '.esc_attr($stockholm_options['sidebar_title_text_transform']);
		}
		
		if(isset($stockholm_options['sidebar_title_color']) && $stockholm_options['sidebar_title_color'] !== '') {
			$sidebar_title_styles[] = 'color: '.esc_attr($stockholm_options['sidebar_title_color']);
		}
		
		if(is_array($sidebar_title_styles) && count($sidebar_title_styles)) { ?>
			aside.sidebar .widget h4,
			.woocommerce aside.sidebar .widget h4{
			<?php echo implode(';', $sidebar_title_styles); ?>
			}
		<?php } ?>
		
		<?php
		$sidebar_link_styles = array();
		
		if(isset($stockholm_options['sidebar_link_font_family']) && $stockholm_options['sidebar_link_font_family'] !== '-1') {
			$sidebar_link_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['sidebar_link_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['sidebar_link_font_size']) && $stockholm_options['sidebar_link_font_size'] !== '') {
			$sidebar_link_styles[] = 'font-size: '.intval($stockholm_options['sidebar_link_font_size']).'px';
		}
		
		if(isset($stockholm_options['sidebar_link_line_height']) && $stockholm_options['sidebar_link_line_height'] !== '') {
			$sidebar_link_styles[] = 'line-height: '.intval($stockholm_options['sidebar_link_line_height']).'px';
		}
		
		if(isset($stockholm_options['sidebar_link_letter_spacing']) && $stockholm_options['sidebar_link_letter_spacing'] !== '') {
			$sidebar_link_styles[] = 'letter-spacing: '.intval($stockholm_options['sidebar_link_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['sidebar_link_font_weight']) && $stockholm_options['sidebar_link_font_weight'] !== '') {
			$sidebar_link_styles[] = 'font-weight: '.esc_attr($stockholm_options['sidebar_link_font_weight']);
		}
		
		if(isset($stockholm_options['sidebar_link_font_style']) && $stockholm_options['sidebar_link_font_style'] !== '') {
			$sidebar_link_styles[] = 'font-style: '.esc_attr($stockholm_options['sidebar_link_font_style']);
		}
		
		if(isset($stockholm_options['sidebar_link_text_transform']) && $stockholm_options['sidebar_link_text_transform'] !== '') {
			$sidebar_link_styles[] = 'text-transform: '.esc_attr($stockholm_options['sidebar_link_text_transform']);
		}
		
		if(isset($stockholm_options['sidebar_link_color']) && $stockholm_options['sidebar_link_color'] !== '') {
			$sidebar_link_styles[] = 'color: '.esc_attr($stockholm_options['sidebar_link_color']);
		}
		
		if(is_array($sidebar_link_styles) && count($sidebar_link_styles)) { ?>
			aside.sidebar .widget:not(.woocommerce) a,
			.woocommerce aside.sidebar .woocommerce.widget ul.product-categories a,
			.woocommerce-page aside.sidebar .woocommerce.widget ul.product-categories a,
			.woocommerce aside.sidebar .woocommerce.widget ul.product-categories ul.children li a,
			aside ul.product-categories ul.children li a,
			.woocommerce-page aside.sidebar .woocommerce.widget ul.product-categories ul.children li a,
			aside.sidebar .widget.woocommerce ul.product-categories li a,
			aside.sidebar .widget.widget_layered_nav  li a{
			<?php echo implode(';', $sidebar_link_styles); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['sidebar_link_hover_color']) && !empty($stockholm_options['sidebar_link_hover_color'])) { ?>
			aside.sidebar .widget:not(.wooocommerce) a:hover,
			.woocommerce aside.sidebar .woocommerce.widget ul.product-categories ul.children li a:hover,
			aside ul.product-categories ul.children li a:hover, .woocommerce-page aside.sidebar .woocommerce.widget ul.product-categories ul.children li a:hover,
			.woocommerce aside.sidebar .woocommerce.widget ul.product-categories a:hover,
			aside ul.product-categories a:hover, .woocommerce-page aside.sidebar .woocommerce.widget ul.product-categories li a:hover,
			aside.sidebar .widget.woocommerce ul.product-categories li a:hover,
			aside.sidebar .widget.widget_layered_nav  li a:hover{
			color:<?php echo esc_attr($stockholm_options['sidebar_link_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['side_area_background_color']) && !empty($stockholm_options['side_area_background_color'])) { ?>
			.side_menu{
			background-color:<?php echo esc_attr($stockholm_options['side_area_background_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['side_area_text_color']) && !empty($stockholm_options['side_area_text_color'])) { ?>
			.side_menu .widget,
			.side_menu .widget.widget_search form,
			.side_menu .widget.widget_search form input[type="text"],
			.side_menu .widget.widget_search form input[type="submit"],
			.side_menu .widget h6,
			.side_menu .widget h6 a,
			.side_menu .widget p,
			.side_menu .widget li a,
			.side_menu .widget.widget_rss li a.rsswidget,
			.side_menu #wp-calendar caption,
			.side_menu .widget li,
			.side_menu_title h3,
			.side_menu .widget.widget_archive select,
			.side_menu .widget.widget_categories select,
			.side_menu .widget.widget_text select,
			.side_menu .widget.widget_search form input[type="submit"],
			.side_menu #wp-calendar th,
			.side_menu #wp-calendar td,
			.side_menu .q_social_icon_holder i.simple_social
			{
			color: <?php echo esc_attr($stockholm_options['side_area_text_color']);  ?>;
			}
		<?php } ?>
		
		<?php
		$side_area_title_styles = array();
		
		if(isset($stockholm_options['side_area_title_google_fonts']) && $stockholm_options['side_area_title_google_fonts'] !== '-1') {
			$side_area_title_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['side_area_title_google_fonts']).', sans-serif';
		}
		
		if(isset($stockholm_options['side_area_title_fontsize']) && $stockholm_options['side_area_title_fontsize'] !== '') {
			$side_area_title_styles[] = 'font-size: '.intval($stockholm_options['side_area_title_fontsize']).'px';
		}
		
		if(isset($stockholm_options['side_area_title_lineheight']) && $stockholm_options['side_area_title_lineheight'] !== '') {
			$side_area_title_styles[] = 'line-height: '.intval($stockholm_options['side_area_title_lineheight']).'px';
		}
		
		if(isset($stockholm_options['side_area_title_letterspacing']) && $stockholm_options['side_area_title_letterspacing'] !== '') {
			$side_area_title_styles[] = 'letter-spacing: '.intval($stockholm_options['side_area_title_letterspacing']).'px';
		}
		
		if(isset($stockholm_options['side_area_title_fontweight']) && $stockholm_options['side_area_title_fontweight'] !== '') {
			$side_area_title_styles[] = 'font-weight: '.esc_attr($stockholm_options['side_area_title_fontweight']);
		}
		
		if(isset($stockholm_options['side_area_title_fontstyle']) && $stockholm_options['side_area_title_fontstyle'] !== '') {
			$side_area_title_styles[] = 'font-style: '.esc_attr($stockholm_options['side_area_title_fontstyle']);
		}
		
		if(isset($stockholm_options['side_area_title_texttransform']) && $stockholm_options['side_area_title_texttransform'] !== '') {
			$side_area_title_styles[] = 'text-transform: '.esc_attr($stockholm_options['side_area_title_texttransform']);
		}
		
		if(isset($stockholm_options['side_area_title_color']) && $stockholm_options['side_area_title_color'] !== '') {
			$side_area_title_styles[] = 'color: '.esc_attr($stockholm_options['side_area_title_color']);
		}
		
		if(is_array($side_area_title_styles) && count($side_area_title_styles)) { ?>
			.side_menu h4, .side_menu h5 { <?php echo implode(';', $side_area_title_styles); ?> }
		<?php } ?>
		
		<?php
		$side_area_title_styles = array();
		
		if(isset($stockholm_options['sidearea_link_font_family']) && $stockholm_options['sidearea_link_font_family'] !== '-1') {
			$side_area_title_styles[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['sidearea_link_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['sidearea_link_font_size']) && $stockholm_options['sidearea_link_font_size'] !== '') {
			$side_area_title_styles[] = 'font-size: '.intval($stockholm_options['sidearea_link_font_size']).'px';
		}
		
		if(isset($stockholm_options['sidearea_link_line_height']) && $stockholm_options['sidearea_link_line_height'] !== '') {
			$side_area_title_styles[] = 'line-height: '.intval($stockholm_options['sidearea_link_line_height']).'px';
		}
		
		if(isset($stockholm_options['sidearea_link_letter_spacing']) && $stockholm_options['sidearea_link_letter_spacing'] !== '') {
			$side_area_title_styles[] = 'letter-spacing: '.intval($stockholm_options['sidearea_link_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['sidearea_link_font_weight']) && $stockholm_options['sidearea_link_font_weight'] !== '') {
			$side_area_title_styles[] = 'font-weight: '.esc_attr($stockholm_options['sidearea_link_font_weight']);
		}
		
		if(isset($stockholm_options['sidearea_link_font_style']) && $stockholm_options['sidearea_link_font_style'] !== '') {
			$side_area_title_styles[] = 'font-style: '.esc_attr($stockholm_options['sidearea_link_font_style']);
		}
		
		if(isset($stockholm_options['sidearea_link_text_transform']) && $stockholm_options['sidearea_link_text_transform'] !== '') {
			$side_area_title_styles[] = 'text-transform: '.esc_attr($stockholm_options['sidearea_link_text_transform']);
		}
		
		if(isset($stockholm_options['sidearea_link_color']) && $stockholm_options['sidearea_link_color'] !== '') {
			$side_area_title_styles[] = 'color: '.esc_attr($stockholm_options['sidearea_link_color']);
		}
		
		if(is_array($side_area_title_styles) && count($side_area_title_styles)) { ?>
			.side_menu .widget li a, .side_menu .widget a { <?php echo implode(';', $side_area_title_styles); ?> }
		<?php } ?>
		
		<?php if(isset($stockholm_options['sidearea_link_hover_color']) && !empty($stockholm_options['sidearea_link_hover_color'])){ ?>
			.side_menu .widget a:hover, .side_menu .widget li:hover, .side_menu .widget li:hover>a{
			color: <?php echo esc_attr($stockholm_options['sidearea_link_hover_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['sidearea_close_icon_color']) && !empty($stockholm_options['sidearea_close_icon_color'])){ ?>
			.side_menu a.close_side_menu_fold .line:before,
			.side_menu a.close_side_menu_fold .line:after {
			background-color: <?php echo esc_attr($stockholm_options['sidearea_close_icon_color']);  ?>;
			}
		<?php } ?>
		<?php if(isset($stockholm_options['sidearea_close_icon_size']) && !empty($stockholm_options['sidearea_close_icon_size'])){ ?>
			.side_menu a.close_side_menu_fold {
			width: <?php echo intval($stockholm_options['sidearea_close_icon_size']);  ?>px;
			height: <?php echo intval($stockholm_options['sidearea_close_icon_size']);  ?>px;
			line-height: <?php echo intval($stockholm_options['sidearea_close_icon_size']);  ?>px;
			}
			.side_menu a.close_side_menu_fold .line,
			.side_menu a.close_side_menu_fold .line:after,
			.side_menu a.close_side_menu_fold .line:before
			{
			width: <?php echo intval($stockholm_options['sidearea_close_icon_size']);  ?>px;
			}
		<?php } ?>
		<?php if(isset($stockholm_options['sidearea_text_alignment']) && !empty($stockholm_options['sidearea_text_alignment'])){ ?>
			.side_menu  {
			text-align: <?php echo esc_attr($stockholm_options['sidearea_text_alignment']);  ?>;
			}
		<?php } ?>
		
		<?php if((isset($stockholm_options['sidearea_padding_top']) && $stockholm_options['sidearea_padding_top'] != '')
		         || (isset($stockholm_options['sidearea_padding_right']) && $stockholm_options['sidearea_padding_right'] != '')
		         || (isset($stockholm_options['sidearea_padding_bottom']) && $stockholm_options['sidearea_padding_bottom'] != '')
		         || (isset($stockholm_options['sidearea_padding_left']) && $stockholm_options['sidearea_padding_left'] != '')
		){ ?>
			.side_menu  {
			<?php if($stockholm_options['sidearea_padding_top'] != '') {?>
				padding-top: <?php echo esc_attr($stockholm_options['sidearea_padding_top']);  ?>;
			<?php } ?>
			<?php if($stockholm_options['sidearea_padding_right'] != '') {?>
				padding-right: <?php echo esc_attr($stockholm_options['sidearea_padding_right']);  ?>;
			<?php } ?>
			<?php if($stockholm_options['sidearea_padding_bottom'] != '') {?>
				padding-bottom: <?php echo esc_attr($stockholm_options['sidearea_padding_bottom']);  ?>;
			<?php } ?>
			<?php if($stockholm_options['sidearea_padding_left'] != '') {?>
				padding-left: <?php echo esc_attr($stockholm_options['sidearea_padding_left']);  ?>;
			<?php } ?>
			}
		<?php } ?>
		
		
		
		<?php if(isset($stockholm_options['sidearea_close_icon_position_top']) && !empty($stockholm_options['sidearea_close_icon_position_top'])){ ?>
			.side_menu a.close_side_menu_fold,
			.side_menu a.close_side_menu{
			top: <?php echo esc_attr($stockholm_options['sidearea_close_icon_position_top']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['sidearea_close_icon_position_right']) && !empty($stockholm_options['sidearea_close_icon_position_right'])){ ?>
			.side_menu a.close_side_menu_fold,
			.side_menu a.close_side_menu{
			right: <?php echo esc_attr($stockholm_options['sidearea_close_icon_position_right']);  ?>;
			}
		<?php } ?>
		
		
		<?php
		$blog_box_style = array();
		
		if(isset($stockholm_options['blog_box_background_color']) && $stockholm_options['blog_box_background_color'] !== '') {
			$blog_box_style[] = 'background-color: '.esc_attr($stockholm_options['blog_box_background_color']);
		}
		
		if(isset($stockholm_options['blog_box_border_color']) && $stockholm_options['blog_box_border_color'] !== '') {
			$blog_box_style[] = 'border-color: '.esc_attr($stockholm_options['blog_box_border_color']);
		}
		
		if(is_array($blog_box_style) && count($blog_box_style)) { ?>
			.blog_holder article .post_text .post_text_inner,
			.blog_single.blog_holder article.format-link .post_content,
			.blog_single.blog_holder article.format-quote .post_content {
			<?php echo implode(';', $blog_box_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_ql_background_color']) && !empty($stockholm_options['blog_ql_background_color'])){ ?>
			.blog_holder article.format-link .post_text .post_text_inner,
			.blog_holder article.format-quote .post_text .post_text_inner{
			background-color: <?php echo esc_attr($stockholm_options['blog_ql_background_color']);  ?>;
			}
			.blog_holder.masonry article.format-quote  .post_text_inner .qoute_mark,
			.blog_holder.masonry article.format-link .post_text .post_text_inner .link_mark,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner .qoute_mark,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner .link_mark,
			.blog_holder article.format-link .post_text .post_text_inner .post_social .post_comments i,
			.blog_holder article.format-link .post_text .post_text_inner .post_social .blog_like i,
			.blog_holder article.format-link .post_text .post_text_inner .post_social .social_share_holder > a > i,
			.blog_holder article.format-quote .post_text .post_text_inner .post_social .post_comments i,
			.blog_holder article.format-quote .post_text .post_text_inner .post_social .blog_like i,
			.blog_holder article.format-quote .post_text .post_text_inner .post_social .social_share_holder > a > i{
			color: <?php echo esc_attr($stockholm_options['blog_ql_background_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['blog_ql_hover_background_color']) && !empty($stockholm_options['blog_ql_hover_background_color'])){ ?>
			.blog_holder article.format-link .post_text .post_text_inner:hover,
			.blog_holder article.format-quote .post_text .post_text_inner:hover{
			background-color: <?php echo esc_attr($stockholm_options['blog_ql_hover_background_color']);  ?>;
			}
			.blog_holder.masonry article.format-quote  .post_text_inner:hover .qoute_mark,
			.blog_holder.masonry article.format-link .post_text .post_text_inner:hover .link_mark,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner:hover .qoute_mark,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner:hover .link_mark,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .post_comments i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .blog_like i,
			.blog_holder article.format-link .post_text .post_text_inner:hover .post_social .social_share_holder > a > i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .post_comments i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .blog_like i,
			.blog_holder article.format-quote .post_text .post_text_inner:hover .post_social .social_share_holder > a > i{
			color: <?php echo esc_attr($stockholm_options['blog_ql_hover_background_color']);  ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['blog_disable_text_box']) && $stockholm_options['blog_disable_text_box'] == "yes") { ?>
			.blog_holder article:not(.format-link):not(.format-quote) .post_text .post_text_inner,
			.blog_holder.masonry article:not(.format-link):not(.format-quote) .post_text .post_text_inner,
			.blog_holder.masonry_full_width article:not(.format-link):not(.format-quote) .post_text .post_text_inner,
			.blog_single.blog_holder article.format-link .post_content,
			.blog_single.blog_holder article.format-quote .post_content{
			background-color: transparent;
			}
			
			.blog_holder article:not(.format-link):not(.format-quote) .post_text .post_text_inner{
			padding: 45px 0 0;
			}
			
			.blog_holder.masonry article:not(.format-link):not(.format-quote) .post_text .post_text_inner,
			.blog_holder.masonry_full_width article:not(.format-link):not(.format-quote) .post_text .post_text_inner{
			padding: 25px 0 5px;
			}
			
			.blog_holder.blog_single article .post_text .post_text_inner {
			padding: 30px 0 0;
			}
			
			.blog_single.blog_holder article.format-link .post_content,
			.blog_single.blog_holder article.format-quote .post_content {
			padding: 0;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['disable_quote_link_mark']) && $stockholm_options['disable_quote_link_mark'] == "yes") { ?>
			.blog_holder.masonry article.format-quote .post_text_inner .qoute_mark,
			.blog_holder.masonry article.format-link .post_text .post_text_inner .link_mark,
			.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner .link_mark,
			.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner .qoute_mark{
			display: none !important;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['qs_slider_height_tablet']) && !empty($stockholm_options['qs_slider_height_tablet'])) { ?>
			@media only screen and (min-width: 480px) and (max-width: 768px){
			.q_slider .carousel, .qode_slider_preloader, .carousel-inner>.item{
			height: <?php echo intval($stockholm_options['qs_slider_height_tablet']); ?>px !important;
			}
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['qs_slider_height_mobile']) && !empty($stockholm_options['qs_slider_height_mobile'])) { ?>
			@media only screen and (max-width: 480px){
			.q_slider .carousel, .qode_slider_preloader, .carousel-inner>.item{
			height: <?php echo intval($stockholm_options['qs_slider_height_mobile']); ?>px !important;
			}
			}
		<?php } ?>
		
		<?php
		//generate Select Slider navigation styles
		$qs_navigation_styles          = '';
		$qs_navigation_hover_styles    = '';
		$qs_navigation_control_styles    = '';
		if(isset($stockholm_options['qs_navigation_color']) && $stockholm_options['qs_navigation_color']) {
			$qs_navigation_styles .= 'color: '.esc_attr($stockholm_options['qs_navigation_color']).';';
		}
		
		if(isset($stockholm_options['qs_navigation_background_color']) && $stockholm_options['qs_navigation_background_color']) {
			$qs_navigation_styles .= 'background-color: '.esc_attr($stockholm_options['qs_navigation_background_color']).';';
		}
		if(isset($stockholm_options['qs_navigation_border_color']) && $stockholm_options['qs_navigation_border_color']) {
			$qs_navigation_styles .= 'border-color: '.esc_attr($stockholm_options['qs_navigation_border_color']).';';
		}
		
		if(isset($stockholm_options['qs_navigation_hover_color']) && $stockholm_options['qs_navigation_hover_color'] != '') {
			$qs_navigation_hover_styles .= 'color: '.esc_attr($stockholm_options['qs_navigation_hover_color']).';';
		}
		
		if(isset($stockholm_options['qs_navigation_background_hover_color']) && $stockholm_options['qs_navigation_background_hover_color'] != '') {
			$qs_navigation_hover_styles .= 'background-color: '.esc_attr($stockholm_options['qs_navigation_background_hover_color']).';';
		}
		
		if(isset($stockholm_options['qs_navigation_border_hover_color']) && $stockholm_options['qs_navigation_border_hover_color'] != '') {
			$qs_navigation_hover_styles .= 'border-color: '.esc_attr($stockholm_options['qs_navigation_border_hover_color']).';';
		}
		
		if(isset($stockholm_options['qs_navigation_control_color']) && $stockholm_options['qs_navigation_control_color'] != '') {
			$qs_navigation_control_styles .= 'background-color: '.esc_attr($stockholm_options['qs_navigation_control_color']).';';
		}
		
		if($qs_navigation_styles != ""){ ?>
			.carousel-control .prev_nav, .carousel-control .next_nav{
			<?php echo esc_attr($qs_navigation_styles); ?>
			}
		<?php }
		
		if($qs_navigation_hover_styles != "") { ?>
			.carousel-control .prev_nav:hover, .carousel-control .next_nav:hover{
			<?php echo esc_attr($qs_navigation_hover_styles); ?>
			}
		<?php }
		
		if($qs_navigation_control_styles != "") { ?>
			.carousel-indicators li{
			<?php echo esc_attr($qs_navigation_control_styles); ?>
			}
		<?php } ?>
		
		<?php
		//generate Select Slider buttons styles
		$qs_button1_styles       = '';
		$qs_button1_hover_styles = '';
		$qs_button2_styles       = '';
		$qs_button2_hover_styles = '';
		
		if(isset($stockholm_options['qs_button_color']) && $stockholm_options['qs_button_color']) {
			$qs_button1_styles .= 'color: '.esc_attr($stockholm_options['qs_button_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button_background_color']) && $stockholm_options['qs_button_background_color']) {
			$qs_button1_styles .= 'background-color: '.esc_attr($stockholm_options['qs_button_background_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button_border_color']) && $stockholm_options['qs_button_border_color']) {
			$qs_button1_styles .= 'border-color: '.esc_attr($stockholm_options['qs_button_border_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button_border_width']) && $stockholm_options['qs_button_border_width']) {
			$qs_button1_styles .= 'border-width: '.intval($stockholm_options['qs_button_border_width']).'px !important;';
		}
		
		if(isset($stockholm_options['qs_button_border_radius']) && $stockholm_options['qs_button_border_radius']) {
			$qs_button1_styles .= 'border-radius: '.intval($stockholm_options['qs_button_border_radius']).'px !important;';
		}
		
		if(isset($stockholm_options['qs_button_hover_color']) && $stockholm_options['qs_button_hover_color'] != '') {
			$qs_button1_hover_styles .= 'color: '.esc_attr($stockholm_options['qs_button_hover_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button_hover_background_color']) && $stockholm_options['qs_button_hover_background_color'] != '') {
			$qs_button1_hover_styles .= 'background-color: '.esc_attr($stockholm_options['qs_button_hover_background_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button_hover_border_color']) && $stockholm_options['qs_button_hover_border_color'] != '') {
			$qs_button1_hover_styles .= 'border-color: '.esc_attr($stockholm_options['qs_button_hover_border_color']).' !important;';
		}
		
		if($qs_button1_styles != ""){ ?>
			.carousel-inner .slider_content .slide_buttons_holder .qbutton:not(.white){
			<?php echo esc_attr($qs_button1_styles); ?>
			}
		<?php }
		
		if($qs_button1_hover_styles != "") { ?>
			.carousel-inner .slider_content .slide_buttons_holder .qbutton:not(.white):hover{
			<?php echo esc_attr($qs_button1_hover_styles); ?>
			}
		<?php }
		
		if(isset($stockholm_options['qs_button2_color']) && $stockholm_options['qs_button2_color']) {
			$qs_button2_styles .= 'color: '.esc_attr($stockholm_options['qs_button2_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button2_background_color']) && $stockholm_options['qs_button2_background_color']) {
			$qs_button2_styles .= 'background-color: '.esc_attr($stockholm_options['qs_button2_background_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button2_border_color']) && $stockholm_options['qs_button2_border_color']) {
			$qs_button2_styles .= 'border-color: '.esc_attr($stockholm_options['qs_button2_border_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button2_border_width']) && $stockholm_options['qs_button2_border_width']) {
			$qs_button2_styles .= 'border-width: '.intval($stockholm_options['qs_button2_border_width']).'px !important;';
		}
		
		if(isset($stockholm_options['qs_button2_border_radius']) && $stockholm_options['qs_button2_border_radius']) {
			$qs_button2_styles .= 'border-radius: '.intval($stockholm_options['qs_button2_border_radius']).'px !important;';
		}
		
		if(isset($stockholm_options['qs_button2_hover_color']) && $stockholm_options['qs_button2_hover_color'] != '') {
			$qs_button2_hover_styles .= 'color: '.esc_attr($stockholm_options['qs_button2_hover_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button2_hover_background_color']) && $stockholm_options['qs_button2_hover_background_color'] != '') {
			$qs_button2_hover_styles .= 'background-color: '.esc_attr($stockholm_options['qs_button2_hover_background_color']).' !important;';
		}
		
		if(isset($stockholm_options['qs_button2_hover_border_color']) && $stockholm_options['qs_button2_hover_border_color'] != '') {
			$qs_button2_hover_styles .= 'border-color: '.esc_attr($stockholm_options['qs_button2_hover_border_color']).' !important;';
		}
		
		if($qs_button2_styles != ""){ ?>
			.carousel-inner .slider_content .slide_buttons_holder .qbutton.white{
			<?php echo esc_attr($qs_button2_styles); ?>
			}
		<?php }
		
		if($qs_button2_hover_styles != "") { ?>
			.carousel-inner .slider_content .slide_buttons_holder .qbutton.white:hover{
			<?php echo esc_attr($qs_button2_hover_styles); ?>
			}
		<?php } ?>
		
		<?php
		//generate header buttons styles
		$header_buttons_styles          = '';
		$header_buttons_hover_styles    = '';
		if(isset($stockholm_options['header_buttons_color']) && $stockholm_options['header_buttons_color']) {
			$header_buttons_styles .= 'color: '.esc_attr($stockholm_options['header_buttons_color']).';';
		}
		
		if(isset($stockholm_options['header_buttons_font_size']) && $stockholm_options['header_buttons_font_size']) {
			$header_buttons_styles .= 'font-size: '.intval($stockholm_options['header_buttons_font_size']).'px;';
		}
		
		if(isset($stockholm_options['header_buttons_hover_color']) && $stockholm_options['header_buttons_hover_color'] != '') {
			$header_buttons_hover_styles .= 'color: '.esc_attr($stockholm_options['header_buttons_hover_color']).';';
		}
		
		if($header_buttons_styles != ""){ ?>
			.side_menu_button > a,
			.header_bottom  .qode-wishlist-widget-holder a,
			.mobile_menu_button span,
			.shopping_cart_header .header_cart i{
			<?php echo esc_attr($header_buttons_styles); ?>
			}
			
			.popup_menu .line,
			.popup_menu .line:after, .popup_menu .line:before{
			background-color: <?php echo esc_attr($stockholm_options['header_buttons_color']); ?>;
			}
		<?php }
		
		if($header_buttons_hover_styles != "") { ?>
			.side_menu_button > a:hover,
			.header_bottom  .qode-wishlist-widget-holder a:hover,
			.mobile_menu_button span:hover,
			.popup_menu:hover .line,
			.popup_menu:hover .line:after,
			.popup_menu:hover .line:before,
			.shopping_cart_header .header_cart:hover i{ <?php echo esc_attr($header_buttons_hover_styles); ?> }
			
			.popup_menu:hover .line,
			.popup_menu:hover .line:after, .popup_menu:hover .line:before{
			background-color: <?php echo esc_attr($stockholm_options['header_buttons_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['vertical_area_background'])){ ?>
			aside.vertical_menu_area{
			background-color: <?php echo esc_attr($stockholm_options['vertical_area_background']); ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['vertical_area_padding']) && $stockholm_options['vertical_area_padding'] !== ''){ ?>
			aside.vertical_menu_area{
			padding: <?php echo esc_attr($stockholm_options['vertical_area_padding']); ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['vertical_area_alignment']) && $stockholm_options['vertical_area_alignment'] !== ''){ ?>
			.vertical_logo_wrapper,
			nav.vertical_menu,
			aside.vertical_menu_area .vertical_menu_area_widget_holder{
			text-align: <?php echo esc_attr($stockholm_options['vertical_area_alignment']); ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['vertical_area_text_color'])){ ?>
			aside .vertical_menu_area_widget_holder,
			aside .vertical_menu_area_widget_holder p,
			aside .vertical_menu_area_widget_holder span{
			color: <?php echo esc_attr($stockholm_options['vertical_area_text_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['vertical_menu_color']) || !empty($stockholm_options['vertical_menu_fontsize']) || !empty($stockholm_options['vertical_menu_lineheight']) || !empty($stockholm_options['vertical_menu_fontstyle']) || !empty($stockholm_options['vertical_menu_fontweight']) || !empty($stockholm_options['vertical_menu_texttransform']) || (isset($stockholm_options['vertical_menu_letterspacing']) && $stockholm_options['vertical_menu_letterspacing'] !== '') || $stockholm_options['vertical_menu_google_fonts'] != "-1") { ?>
			nav.vertical_menu > ul > li > a{
			<?php if (!empty($stockholm_options['vertical_menu_color'])) { ?> color: <?php echo esc_attr($stockholm_options['vertical_menu_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['vertical_menu_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['vertical_menu_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['vertical_menu_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['vertical_menu_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['vertical_menu_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['vertical_menu_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['vertical_menu_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['vertical_menu_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['vertical_menu_fontweight'])) { ?> font-weight: <?php echo esc_attr($stockholm_options['vertical_menu_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['vertical_menu_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['vertical_menu_texttransform']);  ?>; <?php } ?>
			<?php if ($stockholm_options['vertical_menu_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['vertical_menu_letterspacing']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['vertical_menu_hovercolor'])) { ?>
			nav.vertical_menu > ul > li.active > a,
			nav.vertical_menu > ul > li:hover > a{
			color: <?php echo esc_attr($stockholm_options['vertical_menu_hovercolor']);  ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['vertical_menu_hover_background_color'])) { ?>
			nav.vertical_menu > ul > li.active > a,
			nav.vertical_menu > ul > li:hover > a{
			background-color: <?php echo esc_attr($stockholm_options['vertical_menu_hover_background_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['vertical_dropdown_color']) || !empty($stockholm_options['vertical_dropdown_fontsize']) || !empty($stockholm_options['vertical_dropdown_lineheight']) || !empty($stockholm_options['vertical_dropdown_fontstyle']) || !empty($stockholm_options['vertical_dropdown_fontweight']) || !empty($stockholm_options['vertical_dropdown_texttransform']) || (isset($stockholm_options['vertical_dropdown_letterspacing']) && $stockholm_options['vertical_dropdown_letterspacing'] !== '') || $stockholm_options['vertical_dropdown_google_fonts'] != "-1"){ ?>
			.vertical_menu .second .inner > ul > li > a,
			.vertical_menu .wide .second .inner > ul > li > a{
			<?php if (!empty($stockholm_options['vertical_dropdown_color'])) { ?> color: <?php echo esc_attr($stockholm_options['vertical_dropdown_color']); ?>; <?php } ?>
			<?php if($stockholm_options['vertical_dropdown_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['vertical_dropdown_google_fonts']) ?>', sans-serif !important;
			<?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['vertical_dropdown_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['vertical_dropdown_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['vertical_dropdown_fontstyle']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_fontweight'])) { ?>font-weight: <?php echo esc_attr($stockholm_options['vertical_dropdown_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['vertical_dropdown_texttransform']);  ?>; <?php } ?>
			<?php if ($stockholm_options['vertical_dropdown_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['vertical_dropdown_letterspacing']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['vertical_dropdown_hovercolor'])) { ?>
			.vertical_menu .second .inner > ul > li > a:hover{
			color: <?php echo esc_attr($stockholm_options['vertical_dropdown_hovercolor']);  ?> !important;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['vertical_dropdown_color_thirdlvl']) || !empty($stockholm_options['vertical_dropdown_fontsize_thirdlvl']) || !empty($stockholm_options['vertical_dropdown_lineheight_thirdlvl']) || !empty($stockholm_options['vertical_dropdown_fontstyle_thirdlvl']) || !empty($stockholm_options['vertical_dropdown_fontweight_thirdlvl']) || !empty($stockholm_options['vertical_dropdown_texttransform_thirdlvl']) || (isset($stockholm_options['vertical_dropdown_letterspacing_thirdlvl']) && $stockholm_options['vertical_dropdown_letterspacing_thirdlvl'] !== '') || $stockholm_options['vertical_dropdown_google_fonts_thirdlvl'] != "-1"){ ?>
			.vertical_menu .second .inner ul li.sub ul li a{
			<?php if (!empty($stockholm_options['vertical_dropdown_color_thirdlvl'])) { ?> color: <?php echo esc_attr($stockholm_options['vertical_dropdown_color_thirdlvl']);  ?>;  <?php } ?>
			<?php if($stockholm_options['vertical_dropdown_google_fonts_thirdlvl'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['vertical_dropdown_google_fonts_thirdlvl']) ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_fontsize_thirdlvl'])) { ?> font-size: <?php echo intval($stockholm_options['vertical_dropdown_fontsize_thirdlvl']);  ?>px;  <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_lineheight_thirdlvl'])) { ?> line-height: <?php echo intval($stockholm_options['vertical_dropdown_lineheight_thirdlvl']);  ?>px;  <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_fontstyle_thirdlvl'])) { ?> font-style: <?php echo esc_attr($stockholm_options['vertical_dropdown_fontstyle_thirdlvl']);  ?>;   <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_fontweight_thirdlvl'])) { ?> font-weight: <?php echo esc_attr($stockholm_options['vertical_dropdown_fontweight_thirdlvl']);  ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['vertical_dropdown_texttransform_thirdlvl'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['vertical_dropdown_texttransform_thirdlvl']);  ?>; <?php } ?>
			<?php if ($stockholm_options['vertical_dropdown_letterspacing_thirdlvl'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['vertical_dropdown_letterspacing_thirdlvl']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['vertical_dropdown_hovercolor_thirdlvl'])) { ?>
			.vertical_menu .second .inner ul li.sub ul li a:hover{
			color: <?php echo esc_attr($stockholm_options['vertical_dropdown_hovercolor_thirdlvl']);  ?> !important;
			}
		<?php } ?>
		
		<?php if ((isset($stockholm_options['popup_menu_color']) && !empty($stockholm_options['popup_menu_color'])) ||
		          (isset($stockholm_options['popup_menu_google_fonts']) && $stockholm_options['popup_menu_google_fonts'] != "-1") ||
		          (isset($stockholm_options['popup_menu_fontsize']) && !empty($stockholm_options['popup_menu_fontsize'])) ||
		          (isset($stockholm_options['popup_menu_lineheight']) && !empty($stockholm_options['popup_menu_lineheight'])) ||
		          (isset($stockholm_options['popup_menu_fontstyle']) && !empty($stockholm_options['popup_menu_fontstyle'])) ||
		          (isset($stockholm_options['popup_menu_fontweight']) && !empty($stockholm_options['popup_menu_fontweight'])) ||
		          (isset($stockholm_options['popup_menu_texttransform']) && !empty($stockholm_options['popup_menu_texttransform'])) ||
		          (isset($stockholm_options['popup_menu_letterspacing']) && $stockholm_options['popup_menu_letterspacing'] !== '')) { ?>
			
			nav.popup_menu > ul > li > a,
			nav.popup_menu > ul > li > h6{
			<?php if (!empty($stockholm_options['popup_menu_color'])) { ?> color: <?php echo esc_attr($stockholm_options['popup_menu_color']);  ?>; <?php } ?>
			<?php if($stockholm_options['popup_menu_google_fonts'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['popup_menu_google_fonts']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_fontsize'])) { ?> font-size: <?php echo intval($stockholm_options['popup_menu_fontsize']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_lineheight'])) { ?> line-height: <?php echo intval($stockholm_options['popup_menu_lineheight']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_fontstyle'])) { ?> font-style: <?php echo esc_attr($stockholm_options['popup_menu_fontstyle']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_fontweight'])) { ?> font-weight: <?php echo esc_attr($stockholm_options['popup_menu_fontweight']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_texttransform'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['popup_menu_texttransform']);  ?>; <?php } ?>
			<?php if ($stockholm_options['popup_menu_letterspacing'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['popup_menu_letterspacing']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['popup_menu_color']) && !empty($stockholm_options['popup_menu_color'])) { ?>
			.popup_menu.opened .line:after,
			.popup_menu.opened .line:before{
			background-color: <?php echo esc_attr($stockholm_options['popup_menu_color']);  ?>;
			}
		
		<?php } ?>
		
		<?php if ((isset($stockholm_options['popup_menu_hover_color']) && !empty($stockholm_options['popup_menu_hover_color'])) || (isset($stockholm_options['popup_menu_hover_background_color']) && !empty($stockholm_options['popup_menu_hover_background_color']))) { ?>
			nav.popup_menu > ul > li > a:hover,
			nav.popup_menu > ul > li > h6:hover{
			<?php if (!empty($stockholm_options['popup_menu_hover_color'])) { ?>  color: <?php echo esc_attr($stockholm_options['popup_menu_hover_color']);  ?>;<?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_hover_background_color'])) { ?> background-color: <?php echo esc_attr($stockholm_options['popup_menu_hover_background_color']);  ?>; <?php } ?>
			}
		
		<?php } ?>
		
		<?php if ((isset($stockholm_options['popup_menu_color_2nd']) && !empty($stockholm_options['popup_menu_color_2nd'])) ||
		          (isset($stockholm_options['popup_menu_google_fonts_2nd']) && $stockholm_options['popup_menu_google_fonts_2nd'] != "-1") ||
		          (isset($stockholm_options['popup_menu_fontsize_2nd']) && !empty($stockholm_options['popup_menu_fontsize_2nd'])) ||
		          (isset($stockholm_options['popup_menu_lineheight_2nd']) && !empty($stockholm_options['popup_menu_lineheight_2nd'])) ||
		          (isset($stockholm_options['popup_menu_fontstyle_2nd']) && !empty($stockholm_options['popup_menu_fontstyle_2nd'])) ||
		          (isset($stockholm_options['popup_menu_fontweight_2nd']) && !empty($stockholm_options['popup_menu_fontweight_2nd'])) ||
		          (isset($stockholm_options['popup_menu_texttransform_2nd']) && !empty($stockholm_options['popup_menu_texttransform_2nd'])) ||
		          (isset($stockholm_options['popup_menu_letterspacing_2nd']) && $stockholm_options['popup_menu_letterspacing_2nd'] !== '')) { ?>
			
			nav.popup_menu ul li ul li a,
			nav.popup_menu ul li ul li h6{
			<?php if (!empty($stockholm_options['popup_menu_color_2nd'])) { ?> color: <?php echo esc_attr($stockholm_options['popup_menu_color_2nd']);  ?>; <?php } ?>
			<?php if($stockholm_options['popup_menu_google_fonts_2nd'] != "-1"){ ?>
				font-family: '<?php echo str_replace('+', ' ', $stockholm_options['popup_menu_google_fonts_2nd']); ?>', sans-serif;
			<?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_fontsize_2nd'])) { ?> font-size: <?php echo intval($stockholm_options['popup_menu_fontsize_2nd']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_lineheight_2nd'])) { ?> line-height: <?php echo intval($stockholm_options['popup_menu_lineheight_2nd']);  ?>px; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_fontstyle_2nd'])) { ?> font-style: <?php echo esc_attr($stockholm_options['popup_menu_fontstyle_2nd']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_fontweight_2nd'])) { ?> font-weight: <?php echo esc_attr($stockholm_options['popup_menu_fontweight_2nd']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_texttransform_2nd'])) { ?> text-transform: <?php echo esc_attr($stockholm_options['popup_menu_texttransform_2nd']);  ?>; <?php } ?>
			<?php if ($stockholm_options['popup_menu_letterspacing_2nd'] !== '') { ?> letter-spacing: <?php echo intval($stockholm_options['popup_menu_letterspacing_2nd']);  ?>px; <?php } ?>
			}
		<?php } ?>
		
		<?php if ((isset($stockholm_options['popup_menu_hover_color_2nd']) && !empty($stockholm_options['popup_menu_hover_color_2nd'])) || (isset($stockholm_options['popup_menu_hover_background_color_2nd']) && !empty($stockholm_options['popup_menu_hover_background_color_2nd']))) { ?>
			nav.popup_menu ul li ul li a:hover,
			nav.popup_menu ul li ul li h6:hover{
			<?php if (!empty($stockholm_options['popup_menu_hover_color_2nd'])) { ?>  color: <?php echo esc_attr($stockholm_options['popup_menu_hover_color_2nd']);  ?>;<?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_hover_background_color_2nd'])) { ?> background-color: <?php echo esc_attr($stockholm_options['popup_menu_hover_background_color_2nd']);  ?>; <?php } ?>
			}
		
		<?php } ?>
		
		<?php
		$popup_menu_3rd_style = array();
		
		if(isset($stockholm_options['popup_menu_3rd_google_fonts']) && $stockholm_options['popup_menu_3rd_google_fonts'] !== '-1') {
			$popup_menu_3rd_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['popup_menu_3rd_google_fonts']).', sans-serif';
		}
		
		if(isset($stockholm_options['popup_menu_3rd_fontsize']) && $stockholm_options['popup_menu_3rd_fontsize'] !== '') {
			$popup_menu_3rd_style[] = 'font-size: '.intval($stockholm_options['popup_menu_3rd_fontsize']).'px';
		}
		
		if(isset($stockholm_options['popup_menu_3rd_lineheight']) && $stockholm_options['popup_menu_3rd_lineheight'] !== '') {
			$popup_menu_3rd_style[] = 'line-height: '.intval($stockholm_options['popup_menu_3rd_lineheight']).'px';
		}
		
		if(isset($stockholm_options['popup_menu_3rd_letterspacing']) && $stockholm_options['popup_menu_3rd_letterspacing'] !== '') {
			$popup_menu_3rd_style[] = 'letter-spacing: '.intval($stockholm_options['popup_menu_3rd_letterspacing']).'px';
		}
		
		if(isset($stockholm_options['popup_menu_3rd_font_weight']) && $stockholm_options['popup_menu_3rd_font_weight'] !== '') {
			$popup_menu_3rd_style[] = 'font-weight: '.esc_attr($stockholm_options['popup_menu_3rd_font_weight']);
		}
		
		if(isset($stockholm_options['popup_menu_3rd_fontstyle']) && $stockholm_options['popup_menu_3rd_fontstyle'] !== '') {
			$popup_menu_3rd_style[] = 'font-style: '.esc_attr($stockholm_options['popup_menu_3rd_fontstyle']);
		}
		
		if(isset($stockholm_options['popup_menu_3rd_texttransform']) && $stockholm_options['popup_menu_3rd_texttransform'] !== '') {
			$popup_menu_3rd_style[] = 'text-transform: '.esc_attr($stockholm_options['popup_menu_3rd_texttransform']);
		}
		
		if(isset($stockholm_options['popup_menu_3rd_color']) && $stockholm_options['popup_menu_3rd_color'] !== '') {
			$popup_menu_3rd_style[] = 'color: '.esc_attr($stockholm_options['popup_menu_3rd_color']);
		}
		
		if(is_array($popup_menu_3rd_style) && count($popup_menu_3rd_style)) { ?>
			nav.popup_menu ul li ul li ul li a{
			<?php echo implode(';', $popup_menu_3rd_style); ?>
			}
		<?php } ?>
		
		<?php if ((isset($stockholm_options['popup_menu_3rd_hover_color']) && !empty($stockholm_options['popup_menu_3rd_hover_color'])) || (isset($stockholm_options['popup_menu_3rd_hover_background_color']) && !empty($stockholm_options['popup_menu_3rd_hover_background_color']))) { ?>
			nav.popup_menu ul li ul li ul li a:hover{
			<?php if (!empty($stockholm_options['popup_menu_3rd_hover_color'])) { ?>  color: <?php echo esc_attr($stockholm_options['popup_menu_3rd_hover_color']);  ?>;<?php } ?>
			<?php if (!empty($stockholm_options['popup_menu_3rd_hover_background_color'])) { ?> background-color: <?php echo esc_attr($stockholm_options['popup_menu_3rd_hover_background_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['popup_menu_background_color']) || !empty($stockholm_options['pattern_image_popup'])) { ?>
			<?php $popup_menu_background_color = stockholm_qode_hex2rgb($stockholm_options['popup_menu_background_color']); ?>
			
			.popup_menu_holder{
			<?php if (!empty($stockholm_options['popup_menu_background_color'])) { ?>  background-color: rgba(<?php echo esc_attr($popup_menu_background_color[0]); ?>,<?php echo esc_attr($popup_menu_background_color[1]); ?>,<?php echo esc_attr($popup_menu_background_color[2]); ?>,0.9); <?php } ?>
			<?php if (!empty($stockholm_options['pattern_image_popup'])) { ?> background-image:url('<?php echo esc_url($stockholm_options['pattern_image_popup']); ?>');  <?php } ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['popup_menu_background_color']) || !empty($stockholm_options['pattern_image_popup'])) { ?>
			.popup-menu-slide-from-left .popup_menu_holder,
			.popup-menu-text-from-top .popup_menu_holder{
			<?php if (!empty($stockholm_options['popup_menu_background_color'])) { ?> background-color:<?php echo esc_attr($stockholm_options['popup_menu_background_color']); ?>;  <?php } ?>
			<?php if (!empty($stockholm_options['pattern_image_popup'])) { ?> background-image:url('<?php echo esc_url($stockholm_options['pattern_image_popup']); ?>');  <?php } ?>
			}
		<?php } ?>
		
		<?php if(stockholm_qode_is_paspartu_enabled()) { ?>
			.paspartu_enabled header{
			padding-top: 2%;
			}
			.paspartu_enabled .wrapper{
			padding-left:2%;
			padding-right:2%;
			padding-bottom:2%;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['paspartu_color'])  && $stockholm_options['paspartu_color'] != '') { ?>
			.paspartu_enabled .paspartu_top,
			.paspartu_enabled .paspartu_bottom,
			.paspartu_enabled .paspartu_left,
			.paspartu_enabled .paspartu_right{
			background-color:  <?php echo esc_attr($stockholm_options['paspartu_color']);  ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['paspartu_width'])  && $stockholm_options['paspartu_width'] != '') { ?>
			.paspartu_enabled header{
			padding-top: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			.paspartu_enabled .paspartu_top{
			padding-top:  <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			.paspartu_enabled .paspartu_bottom{
			padding-bottom: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			.paspartu_enabled .paspartu_left{
			width: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			.paspartu_enabled .paspartu_right{
			width: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			
			.paspartu_enabled .wrapper{
			padding-left: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			padding-right: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			padding-bottom: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			
			.paspartu_enabled aside.vertical_menu_area,
			.paspartu_enabled  .vertical_area_background{
			left: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			
			.paspartu_enabled footer.uncover{
			padding-bottom: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			
			.paspartu_enabled.vertical_menu_enabled .content .content_inner{
			padding-top: <?php echo esc_attr($stockholm_options['paspartu_width']);  ?>%;
			}
			
			.paspartu_enabled #back_to_top,
			.paspartu_enabled #back_to_top.on {
			right: calc(<?php echo esc_attr($stockholm_options['paspartu_width']);  ?>% + 10px);
			}
		
		<?php } ?>
		
		
		
		<?php if (isset($stockholm_options['portfolio_list_box_background_color']) && !empty($stockholm_options['portfolio_list_box_background_color'])) { ?>
			.projects_holder article .portfolio_description{
			background-color: <?php echo esc_attr($stockholm_options['portfolio_list_box_background_color']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['portfolio_disable_text_box']) && $stockholm_options['portfolio_disable_text_box'] == "yes") { ?>
			.projects_holder article .portfolio_description{
			background-color: transparent;
			}
			
			.projects_holder.standard article .portfolio_description,
			.projects_holder.standard_no_space article .portfolio_description {
			padding-left: 0;
			padding-right: 0;
			}
			
			.masonry_with_space .projects_holder article .portfolio_description {
			padding: 35px 0 43px;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['portfolio_shader_color']) && !empty($stockholm_options['portfolio_shader_color'])) { ?>
			<?php if(isset($stockholm_options['portfolio_shader_transparency']) && $stockholm_options['portfolio_shader_transparency'] != ''){
				$portfolio_shader_transparency = $stockholm_options['portfolio_shader_transparency'];
			} else {
				$portfolio_shader_transparency = 1;
			}
			$shader_bg_color = stockholm_qode_hex2rgb($stockholm_options['portfolio_shader_color']);
			?>
			
			.projects_holder article .portfolio_shader, .projects_masonry_holder article .portfolio_shader, .portfolio_slides .portfolio_shader{
			background-color: rgba(<?php echo esc_attr($shader_bg_color[0]); ?>,<?php echo esc_attr($shader_bg_color[1]); ?>,<?php echo esc_attr($shader_bg_color[2]); ?>,<?php echo esc_attr($portfolio_shader_transparency); ?>);
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['portfolio_list_hide_category']) && $stockholm_options['portfolio_list_hide_category'] == 'yes') { ?>
			.projects_holder article .portfolio_title, .projects_masonry_holder article .portfolio_title{
			margin: 0;
			}
			.projects_holder.standard article .portfolio_description, .projects_holder.standard_no_space article .portfolio_description{
			padding-bottom: 20px;
			}
			.masonry_with_space .projects_holder article .portfolio_description{
			padding-bottom: 35px;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['portfolio_box_background_color']) && !empty($stockholm_options['portfolio_box_background_color'])) { ?>
			.portfolio_single.big-slider .portfolio_container, .portfolio_single.big-images .portfolio_container, .portfolio_single.gallery .portfolio_container{
			background-color: <?php echo esc_attr($stockholm_options['portfolio_box_background_color']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['portfolio_box_lr_padding']) && $stockholm_options['portfolio_box_lr_padding'] !== '') { ?>
			.portfolio_single.big-slider .portfolio_container, .portfolio_single.big-images .portfolio_container, .portfolio_single.gallery .portfolio_container{
			padding-left: <?php echo intval($stockholm_options['portfolio_box_lr_padding']); ?>px;
			padding-right: <?php echo intval($stockholm_options['portfolio_box_lr_padding']); ?>px;
			}
		<?php } ?>
		
		<?php
		$portfolio_single_big_title_style = array();
		
		if(isset($stockholm_options['portfolio_single_big_title_font_family']) && $stockholm_options['portfolio_single_big_title_font_family'] !== '-1') {
			$portfolio_single_big_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_single_big_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['portfolio_single_big_title_font_size']) && $stockholm_options['portfolio_single_big_title_font_size'] !== '') {
			$portfolio_single_big_title_style[] = 'font-size: '.intval($stockholm_options['portfolio_single_big_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_big_title_line_height']) && $stockholm_options['portfolio_single_big_title_line_height'] !== '') {
			$portfolio_single_big_title_style[] = 'line-height: '.intval($stockholm_options['portfolio_single_big_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_big_title_letter_spacing']) && $stockholm_options['portfolio_single_big_title_letter_spacing'] !== '') {
			$portfolio_single_big_title_style[] = 'letter-spacing: '.intval($stockholm_options['portfolio_single_big_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_big_title_font_weight']) && $stockholm_options['portfolio_single_big_title_font_weight'] !== '') {
			$portfolio_single_big_title_style[] = 'font-weight: '.esc_attr($stockholm_options['portfolio_single_big_title_font_weight']);
		}
		
		if(isset($stockholm_options['portfolio_single_big_title_font_style']) && $stockholm_options['portfolio_single_big_title_font_style'] !== '') {
			$portfolio_single_big_title_style[] = 'font-style: '.esc_attr($stockholm_options['portfolio_single_big_title_font_style']);
		}
		
		if(isset($stockholm_options['portfolio_single_big_title_text_transform']) && $stockholm_options['portfolio_single_big_title_text_transform'] !== '') {
			$portfolio_single_big_title_style[] = 'text-transform: '.esc_attr($stockholm_options['portfolio_single_big_title_text_transform']);
		}
		
		if(isset($stockholm_options['portfolio_single_big_title_color']) && $stockholm_options['portfolio_single_big_title_color'] !== '') {
			$portfolio_single_big_title_style[] = 'color: '.esc_attr($stockholm_options['portfolio_single_big_title_color']);
		}
		
		if(is_array($portfolio_single_big_title_style) && count($portfolio_single_big_title_style)) { ?>
			.portfolio_single.big-slider h2.portfolio_single_text_title,
			.portfolio_single.big-images h2.portfolio_single_text_title,
			.portfolio_single.gallery h2.portfolio_single_text_title,
			.portfolio_single.masonry-gallery h2.portfolio_single_text_title,
			.portfolio_single.portfolio_fullwidth_slider h2.portfolio_single_text_title{
			<?php echo implode(';', $portfolio_single_big_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$portfolio_single_small_title_style = array();
		
		if(isset($stockholm_options['portfolio_single_small_title_font_family']) && $stockholm_options['portfolio_single_small_title_font_family'] !== '-1') {
			$portfolio_single_small_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_single_small_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['portfolio_single_small_title_font_size']) && $stockholm_options['portfolio_single_small_title_font_size'] !== '') {
			$portfolio_single_small_title_style[] = 'font-size: '.intval($stockholm_options['portfolio_single_small_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_small_title_line_height']) && $stockholm_options['portfolio_single_small_title_line_height'] !== '') {
			$portfolio_single_small_title_style[] = 'line-height: '.intval($stockholm_options['portfolio_single_small_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_small_title_letter_spacing']) && $stockholm_options['portfolio_single_small_title_letter_spacing'] !== '') {
			$portfolio_single_small_title_style[] = 'letter-spacing: '.intval($stockholm_options['portfolio_single_small_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_small_title_font_weight']) && $stockholm_options['portfolio_single_small_title_font_weight'] !== '') {
			$portfolio_single_small_title_style[] = 'font-weight: '.esc_attr($stockholm_options['portfolio_single_small_title_font_weight']);
		}
		
		if(isset($stockholm_options['portfolio_single_small_title_font_style']) && $stockholm_options['portfolio_single_small_title_font_style'] !== '') {
			$portfolio_single_small_title_style[] = 'font-style: '.esc_attr($stockholm_options['portfolio_single_small_title_font_style']);
		}
		
		if(isset($stockholm_options['portfolio_single_small_title_text_transform']) && $stockholm_options['portfolio_single_small_title_text_transform'] !== '') {
			$portfolio_single_small_title_style[] = 'text-transform: '.esc_attr($stockholm_options['portfolio_single_small_title_text_transform']);
		}
		
		if(isset($stockholm_options['portfolio_single_small_title_color']) && $stockholm_options['portfolio_single_small_title_color'] !== '') {
			$portfolio_single_small_title_style[] = 'color: '.esc_attr($stockholm_options['portfolio_single_small_title_color']);
		}
		
		if(is_array($portfolio_single_small_title_style) && count($portfolio_single_small_title_style)) { ?>
			.portfolio_single.small-slider .portfolio_detail > h3.info_section_title,
			.portfolio_single.small-images .portfolio_detail > h3.info_section_title,
			.portfolio_single.gallery-right h2.portfolio_single_text_title,
			.portfolio_single.gallery-left h2.portfolio_single_text_title,
			.portfolio_single.fixed-left .portfolio_detail > h3.info_section_title,
			.portfolio_single.fixed-right .portfolio_detail > h3.info_section_title{
			<?php echo implode(';', $portfolio_single_small_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$portfolio_single_meta_title_style = array();
		
		if(isset($stockholm_options['portfolio_single_meta_title_font_family']) && $stockholm_options['portfolio_single_meta_title_font_family'] !== '-1') {
			$portfolio_single_meta_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_single_meta_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_title_font_size']) && $stockholm_options['portfolio_single_meta_title_font_size'] !== '') {
			$portfolio_single_meta_title_style[] = 'font-size: '.intval($stockholm_options['portfolio_single_meta_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_title_font_size']) && $stockholm_options['portfolio_single_meta_title_font_size'] !== '') {
			$portfolio_single_meta_title_style[] = 'line-height: '.intval($stockholm_options['portfolio_single_meta_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_title_letter_spacing']) && $stockholm_options['portfolio_single_meta_title_letter_spacing'] !== '') {
			$portfolio_single_meta_title_style[] = 'letter-spacing: '.intval($stockholm_options['portfolio_single_meta_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_title_font_weight']) && $stockholm_options['portfolio_single_meta_title_font_weight'] !== '') {
			$portfolio_single_meta_title_style[] = 'font-weight: '.esc_attr($stockholm_options['portfolio_single_meta_title_font_weight']);
		}
		
		if(isset($stockholm_options['portfolio_single_meta_title_font_style']) && $stockholm_options['portfolio_single_meta_title_font_style'] !== '') {
			$portfolio_single_meta_title_style[] = 'font-style: '.esc_attr($stockholm_options['portfolio_single_meta_title_font_style']);
		}
		
		if(isset($stockholm_options['portfolio_single_meta_title_text_transform']) && $stockholm_options['portfolio_single_meta_title_text_transform'] !== '') {
			$portfolio_single_meta_title_style[] = 'text-transform: '.esc_attr($stockholm_options['portfolio_single_meta_title_text_transform']);
		}
		
		if(isset($stockholm_options['portfolio_single_meta_title_color']) && $stockholm_options['portfolio_single_meta_title_color'] !== '') {
			$portfolio_single_meta_title_style[] = 'color: '.esc_attr($stockholm_options['portfolio_single_meta_title_color']);
		}
		
		if(is_array($portfolio_single_meta_title_style) && count($portfolio_single_meta_title_style)) { ?>
			.portfolio_detail .info .info_section_title,
			.portfolio_single.portfolio_fullscreen_slider .qodef-portfolio-slider-content-info .qodef-hidden-info .info_section_title{
			<?php echo implode(';', $portfolio_single_meta_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$portfolio_single_meta_text_style = array();
		
		if(isset($stockholm_options['portfolio_single_meta_text_font_family']) && $stockholm_options['portfolio_single_meta_text_font_family'] !== '-1') {
			$portfolio_single_meta_text_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_single_meta_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_text_font_size']) && $stockholm_options['portfolio_single_meta_text_font_size'] !== '') {
			$portfolio_single_meta_text_style[] = 'font-size: '.intval($stockholm_options['portfolio_single_meta_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_text_line_height']) && $stockholm_options['portfolio_single_meta_text_line_height'] !== '') {
			$portfolio_single_meta_text_style[] = 'line-height: '.intval($stockholm_options['portfolio_single_meta_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_text_letter_spacing']) && $stockholm_options['portfolio_single_meta_text_letter_spacing'] !== '') {
			$portfolio_single_meta_text_style[] = 'letter-spacing: '.intval($stockholm_options['portfolio_single_meta_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['portfolio_single_meta_text_font_weight']) && $stockholm_options['portfolio_single_meta_text_font_weight'] !== '') {
			$portfolio_single_meta_text_style[] = 'font-weight: '.esc_attr($stockholm_options['portfolio_single_meta_text_font_weight']);
		}
		
		if(isset($stockholm_options['portfolio_single_meta_text_font_style']) && $stockholm_options['portfolio_single_meta_text_font_style'] !== '') {
			$portfolio_single_meta_text_style[] = 'font-style: '.esc_attr($stockholm_options['portfolio_single_meta_text_font_style']);
		}
		
		if(isset($stockholm_options['portfolio_single_meta_text_text_transform']) && $stockholm_options['portfolio_single_meta_text_text_transform'] !== '') {
			$portfolio_single_meta_text_style[] = 'text-transform: '.esc_attr($stockholm_options['portfolio_single_meta_text_text_transform']);
		}
		
		if(isset($stockholm_options['portfolio_single_meta_text_color']) && $stockholm_options['portfolio_single_meta_text_color'] !== '') {
			$portfolio_single_meta_text_style[] = 'color: '.esc_attr($stockholm_options['portfolio_single_meta_text_color']);
		}
		
		if(is_array($portfolio_single_meta_text_style) && count($portfolio_single_meta_text_style)) { ?>
			.portfolio_detail .info > p{
			<?php echo implode(';', $portfolio_single_meta_text_style); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['portfolio_single_gallery_color']) && !empty($stockholm_options['portfolio_single_gallery_color'])) { ?>
			<?php if(isset($stockholm_options['portfolio_single_gallery_transparency']) && $stockholm_options['portfolio_single_gallery_transparency'] != ''){
				$portfolio_single_gallery_transparency = $stockholm_options['portfolio_single_gallery_transparency'];
			} else {
				$portfolio_single_gallery_transparency = 1;
			}
			$portfolio_single_gallery_bg_color = stockholm_qode_hex2rgb($stockholm_options['portfolio_single_gallery_color']);
			?>
			
			.portfolio_gallery a .gallery_text_holder{
			background-color: rgba(<?php echo esc_attr($portfolio_single_gallery_bg_color[0]); ?>,<?php echo esc_attr($portfolio_single_gallery_bg_color[1]); ?>,<?php echo esc_attr($portfolio_single_gallery_bg_color[2]); ?>,<?php echo esc_attr($portfolio_single_gallery_transparency); ?>);
			}
		<?php } ?>
		
		<?php
		//generate top header styles
		$top_header_font_styles 		= '';
		$top_header_font_hover_styles 	= '';
		$top_header_border_styles 		= '';
		if(isset($stockholm_options['top_header_text_color']) && $stockholm_options['top_header_text_color'] !== '') {
			$top_header_font_styles .= 'color: '.esc_attr($stockholm_options['top_header_text_color']).';';
		}
		
		if(isset($stockholm_options['top_header_text_hover_color']) && $stockholm_options['top_header_text_hover_color'] !== '') {
			$top_header_font_hover_styles .= 'color: '.esc_attr($stockholm_options['top_header_text_hover_color']).';';
		}
		
		if(isset($stockholm_options['top_header_text_font_family']) && $stockholm_options['top_header_text_font_family'] !== '-1') {
			$top_header_font_styles .= 'font-family: "'.str_replace('+', ' ', $stockholm_options['top_header_text_font_family']).'";';
		}
		
		if(isset($stockholm_options['top_header_text_font_size']) && $stockholm_options['top_header_text_font_size'] !== '') {
			$top_header_font_styles .= 'font-size: '.intval($stockholm_options['top_header_text_font_size']).'px;';
		}
		
		if(isset($stockholm_options['top_header_text_line_height']) && $stockholm_options['top_header_text_line_height'] !== '') {
			$top_header_font_styles .= 'line-height: '.intval($stockholm_options['top_header_text_line_height']).'px;';
		}
		
		if(isset($stockholm_options['top_header_text_texttransform']) && $stockholm_options['top_header_text_texttransform'] !== '') {
			$top_header_font_styles .= 'text-transform: '.esc_attr($stockholm_options['top_header_text_texttransform']).';';
		}
		
		if(isset($stockholm_options['top_header_text_font_style']) && $stockholm_options['top_header_text_font_style'] !== '') {
			$top_header_font_styles .= 'font-style: '.esc_attr($stockholm_options['top_header_text_font_style']).';';
		}
		
		if(isset($stockholm_options['top_header_text_font_weight']) && $stockholm_options['top_header_text_font_weight'] !== '') {
			$top_header_font_styles .= 'font-weight: '.esc_attr($stockholm_options['top_header_text_font_weight']).';';
		}
		
		if(isset($stockholm_options['top_header_text_letter_spacing']) && $stockholm_options['top_header_text_letter_spacing'] !== '') {
			$top_header_font_styles .= 'letter-spacing: '.intval($stockholm_options['top_header_text_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['top_header_border_color']) && $stockholm_options['top_header_border_color'] !== '') {
			$top_header_border_styles .= 'border-bottom: 1px solid '.esc_attr($stockholm_options['top_header_border_color']).';';
		}
		
		if(isset($stockholm_options['top_header_border_weight']) && $stockholm_options['top_header_border_weight'] !== '') {
			$top_header_border_styles .= 'border-width: '.intval($stockholm_options['top_header_border_weight']).'px;';
		}
		
		if($top_header_font_styles !== '') {
			?>
			.header_top .header-widget,
			.header_top .header-widget.widget_nav_menu ul.menu>li>a,
			.header_top .header-widget p,
			.header_top .header-widget a,
			.header_top .header-widget span:not(.q_social_icon_holder):not(.social_icon):not(.q_font_elegant_icon){
			<?php echo esc_attr($top_header_font_styles); ?>
			}
			
			<?php
		}
		
		if($top_header_font_hover_styles !== '') {
			?>
			.header_top .header-widget:hover,
			.header_top .header-widget.widget_nav_menu ul.menu>li>a:hover,
			.header_top .header-widget p:hover,
			.header_top .header-widget a:hover,
			.header_top .header-widget span:not(.q_social_icon_holder):not(.social_icon):hover {
			<?php echo esc_attr($top_header_font_hover_styles); ?>
			}
			
			<?php
		}
		
		if($top_header_border_styles !== '') {
			?>
			.header_top {
			<?php echo esc_attr($top_header_border_styles); ?>
			}
			
			<?php
		}
		?>
		
		<?php if (!empty($stockholm_options['portfolio_filter_margin_bottom'])) { ?>
			.filter_outer{
			margin-bottom: <?php echo intval($stockholm_options['portfolio_filter_margin_bottom']); ?>px !important;
			}
		<?php } ?>
		
		<?php
		//generate categories filter title styles
		$portfolio_filter_title_font_styles = '';
		if(isset($stockholm_options['portfolio_filter_title_color']) && $stockholm_options['portfolio_filter_title_color'] !== '') {
			$portfolio_filter_title_font_styles .= 'color: '.esc_attr($stockholm_options['portfolio_filter_title_color']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_title_font_family']) && $stockholm_options['portfolio_filter_title_font_family'] !== '-1') {
			$portfolio_filter_title_font_styles .= 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_filter_title_font_family']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_title_font_size']) && $stockholm_options['portfolio_filter_title_font_size'] !== '') {
			$portfolio_filter_title_font_styles .= 'font-size: '.intval($stockholm_options['portfolio_filter_title_font_size']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_filter_title_line_height']) && $stockholm_options['portfolio_filter_title_line_height'] !== '') {
			$portfolio_filter_title_font_styles .= 'line-height: '.intval($stockholm_options['portfolio_filter_title_line_height']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_filter_title_font_style']) && $stockholm_options['portfolio_filter_title_font_style'] !== '') {
			$portfolio_filter_title_font_styles .= 'font-style: '.esc_attr($stockholm_options['portfolio_filter_title_font_style']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_title_font_weight']) && $stockholm_options['portfolio_filter_title_font_weight'] !== '') {
			$portfolio_filter_title_font_styles .= 'font-weight: '.esc_attr($stockholm_options['portfolio_filter_title_font_weight']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_title_letter_spacing']) && $stockholm_options['portfolio_filter_title_letter_spacing'] !== '') {
			$portfolio_filter_title_font_styles .= 'letter-spacing: '.intval($stockholm_options['portfolio_filter_title_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_filter_title_text_transform']) && $stockholm_options['portfolio_filter_title_text_transform'] !== '') {
			$portfolio_filter_title_font_styles .= 'text-transform: '.esc_attr($stockholm_options['portfolio_filter_title_text_transform']).';';
		}
		
		if($portfolio_filter_title_font_styles !== '') {
			?>
			.filter_holder ul li.filter_title span{
			<?php echo esc_attr($portfolio_filter_title_font_styles); ?>
			}
		<?php } ?>
		
		<?php
		//generate categories filter styles
		$portfolio_filter_font_styles = '';
		$portfolio_filter_font_hover_styles = '';
		if(isset($stockholm_options['portfolio_filter_color']) && $stockholm_options['portfolio_filter_color'] !== '') {
			$portfolio_filter_font_styles .= 'color: '.esc_attr($stockholm_options['portfolio_filter_color']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_hovercolor']) && $stockholm_options['portfolio_filter_hovercolor'] !== '') {
			$portfolio_filter_font_hover_styles .= 'color: '.esc_attr($stockholm_options['portfolio_filter_hovercolor']).' !important;';
		}
		
		if(isset($stockholm_options['portfolio_filter_font_family']) && $stockholm_options['portfolio_filter_font_family'] !== '-1') {
			$portfolio_filter_font_styles .= 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_filter_font_family']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_font_size']) && $stockholm_options['portfolio_filter_font_size'] !== '') {
			$portfolio_filter_font_styles .= 'font-size: '.intval($stockholm_options['portfolio_filter_font_size']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_filter_line_height']) && $stockholm_options['portfolio_filter_line_height'] !== '') {
			$portfolio_filter_font_styles .= 'line-height: '.intval($stockholm_options['portfolio_filter_line_height']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_filter_font_style']) && $stockholm_options['portfolio_filter_font_style'] !== '') {
			$portfolio_filter_font_styles .= 'font-style: '.esc_attr($stockholm_options['portfolio_filter_font_style']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_font_weight']) && $stockholm_options['portfolio_filter_font_weight'] !== '') {
			$portfolio_filter_font_styles .= 'font-weight: '.esc_attr($stockholm_options['portfolio_filter_font_weight']).';';
		}
		
		if(isset($stockholm_options['portfolio_filter_letter_spacing']) && $stockholm_options['portfolio_filter_letter_spacing'] !== '') {
			$portfolio_filter_font_styles .= 'letter-spacing: '.intval($stockholm_options['portfolio_filter_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_filter_text_transform']) && $stockholm_options['portfolio_filter_text_transform'] !== '') {
			$portfolio_filter_font_styles .= 'text-transform: '.esc_attr($stockholm_options['portfolio_filter_text_transform']).';';
		}
		
		if($portfolio_filter_font_styles !== '') {
			?>
			.filter_holder ul li:not(.filter_title) span{
			<?php echo esc_attr($portfolio_filter_font_styles); ?>
			}
			<?php
			if($stockholm_options['portfolio_filter_color'] !== ''){
				?>
				.filter_holder ul li span:after{
				color: <?php echo esc_attr($stockholm_options['portfolio_filter_color']); ?>;
				}
				<?php
			}
		}
		if($portfolio_filter_font_hover_styles !== '') {
			?>
			.filter_holder ul li.current span,
			.filter_holder ul li:not(.filter_title):hover span{
			<?php echo esc_attr($portfolio_filter_font_hover_styles); ?>
			}
			
			<?php
		}
		?>
		
		<?php
		//generate title on portfolio standard and pinterest lists styles
		$portfolio_title_standard_list_font_styles = '';
		if(isset($stockholm_options['portfolio_title_standard_list_color']) && $stockholm_options['portfolio_title_standard_list_color'] !== '') {
			$portfolio_title_standard_list_font_styles .= 'color: '.esc_attr($stockholm_options['portfolio_title_standard_list_color']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_standard_list_font_family']) && $stockholm_options['portfolio_title_standard_list_font_family'] !== '-1') {
			$portfolio_title_standard_list_font_styles .= 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_title_standard_list_font_family']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_standard_list_font_size']) && $stockholm_options['portfolio_title_standard_list_font_size'] !== '') {
			$portfolio_title_standard_list_font_styles .= 'font-size: '.intval($stockholm_options['portfolio_title_standard_list_font_size']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_title_standard_list_line_height']) && $stockholm_options['portfolio_title_standard_list_line_height'] !== '') {
			$portfolio_title_standard_list_font_styles .= 'line-height: '.intval($stockholm_options['portfolio_title_standard_list_line_height']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_title_standard_list_font_style']) && $stockholm_options['portfolio_title_standard_list_font_style'] !== '') {
			$portfolio_title_standard_list_font_styles .= 'font-style: '.esc_attr($stockholm_options['portfolio_title_standard_list_font_style']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_standard_list_font_weight']) && $stockholm_options['portfolio_title_standard_list_font_weight'] !== '') {
			$portfolio_title_standard_list_font_styles .= 'font-weight: '.esc_attr($stockholm_options['portfolio_title_standard_list_font_weight']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_standard_list_letter_spacing']) && $stockholm_options['portfolio_title_standard_list_letter_spacing'] !== '') {
			$portfolio_title_standard_list_font_styles .= 'letter-spacing: '.intval($stockholm_options['portfolio_title_standard_list_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_title_standard_list_text_transform']) && $stockholm_options['portfolio_title_standard_list_text_transform'] !== '') {
			$portfolio_title_standard_list_font_styles .= 'text-transform: '.esc_attr($stockholm_options['portfolio_title_standard_list_text_transform']).';';
		}
		
		if($portfolio_title_standard_list_font_styles !== '') {
			?>
			.projects_holder.standard article .portfolio_title, .projects_holder.standard article .portfolio_title a, .projects_holder.standard_no_space article .portfolio_title, .projects_holder.standard_no_space article .portfolio_title a, .projects_holder .pinterest_info_on_hover .portfolio_title{
			<?php echo esc_attr($portfolio_title_standard_list_font_styles); ?>
			}
			<?php
		}
		
		$portfolio_title_standard_list_hover_styles = '';
		if(isset($stockholm_options['portfolio_title_standard_list_hover_color']) && $stockholm_options['portfolio_title_standard_list_hover_color'] !== '') {
			$portfolio_title_standard_list_hover_styles .= 'color: '.esc_attr($stockholm_options['portfolio_title_standard_list_hover_color']).';';
		}
		
		if($portfolio_title_standard_list_hover_styles !== '') {
			?>
			.projects_holder.standard article .portfolio_title a:hover, .projects_holder.standard_no_space article .portfolio_title a:hover, .projects_holder .pinterest_info_on_hover .portfolio_title a:hover{
			<?php echo esc_attr($portfolio_title_standard_list_hover_styles); ?>
			}
			<?php
		}
		?>
		
		<?php
		//generate categories on portfolio standard and pinterest lists styles
		$portfolio_category_standard_list_font_styles = '';
		if(isset($stockholm_options['portfolio_category_standard_list_color']) && $stockholm_options['portfolio_category_standard_list_color'] !== '') {
			$portfolio_category_standard_list_font_styles .= 'color: '.esc_attr($stockholm_options['portfolio_category_standard_list_color']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_standard_list_font_family']) && $stockholm_options['portfolio_category_standard_list_font_family'] !== '-1') {
			$portfolio_category_standard_list_font_styles .= 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_category_standard_list_font_family']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_standard_list_font_size']) && $stockholm_options['portfolio_category_standard_list_font_size'] !== '') {
			$portfolio_category_standard_list_font_styles .= 'font-size: '.intval($stockholm_options['portfolio_category_standard_list_font_size']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_category_standard_list_line_height']) && $stockholm_options['portfolio_category_standard_list_line_height'] !== '') {
			$portfolio_category_standard_list_font_styles .= 'line-height: '.intval($stockholm_options['portfolio_category_standard_list_line_height']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_category_standard_list_font_style']) && $stockholm_options['portfolio_category_standard_list_font_style'] !== '') {
			$portfolio_category_standard_list_font_styles .= 'font-style: '.esc_attr($stockholm_options['portfolio_category_standard_list_font_style']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_standard_list_font_weight']) && $stockholm_options['portfolio_category_standard_list_font_weight'] !== '') {
			$portfolio_category_standard_list_font_styles .= 'font-weight: '.esc_attr($stockholm_options['portfolio_category_standard_list_font_weight']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_standard_list_letter_spacing']) && $stockholm_options['portfolio_category_standard_list_letter_spacing'] !== '') {
			$portfolio_category_standard_list_font_styles .= 'letter-spacing: '.intval($stockholm_options['portfolio_category_standard_list_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_category_standard_list_text_transform']) && $stockholm_options['portfolio_category_standard_list_text_transform'] !== '') {
			$portfolio_category_standard_list_font_styles .= 'text-transform: '.esc_attr($stockholm_options['portfolio_category_standard_list_text_transform']).';';
		}
		
		if($portfolio_category_standard_list_font_styles !== '') {
			?>
			.projects_holder.standard article .project_category, .projects_holder.standard_no_space article .project_category,.projects_holder.standard .pinterest_info_on_hover .project_category{
			<?php echo esc_attr($portfolio_category_standard_list_font_styles); ?>
			}
			<?php
		}
		?>
		
		<?php
		//generate title on portfolio gallery and masonry lists styles
		$portfolio_title_list_font_styles = '';
		if(isset($stockholm_options['portfolio_title_list_color']) && $stockholm_options['portfolio_title_list_color'] !== '') {
			$portfolio_title_list_font_styles .= 'color: '.esc_attr($stockholm_options['portfolio_title_list_color']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_list_font_family']) && $stockholm_options['portfolio_title_list_font_family'] !== '-1') {
			$portfolio_title_list_font_styles .= 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_title_list_font_family']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_list_font_size']) && $stockholm_options['portfolio_title_list_font_size'] !== '') {
			$portfolio_title_list_font_styles .= 'font-size: '.intval($stockholm_options['portfolio_title_list_font_size']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_title_list_line_height']) && $stockholm_options['portfolio_title_list_line_height'] !== '') {
			$portfolio_title_list_font_styles .= 'line-height: '.intval($stockholm_options['portfolio_title_list_line_height']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_title_list_font_style']) && $stockholm_options['portfolio_title_list_font_style'] !== '') {
			$portfolio_title_list_font_styles .= 'font-style: '.esc_attr($stockholm_options['portfolio_title_list_font_style']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_list_font_weight']) && $stockholm_options['portfolio_title_list_font_weight'] !== '') {
			$portfolio_title_list_font_styles .= 'font-weight: '.esc_attr($stockholm_options['portfolio_title_list_font_weight']).';';
		}
		
		if(isset($stockholm_options['portfolio_title_list_letter_spacing']) && $stockholm_options['portfolio_title_list_letter_spacing'] !== '') {
			$portfolio_title_list_font_styles .= 'letter-spacing: '.intval($stockholm_options['portfolio_title_list_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_title_list_text_transform']) && $stockholm_options['portfolio_title_list_text_transform'] !== '') {
			$portfolio_title_list_font_styles .= 'text-transform: '.esc_attr($stockholm_options['portfolio_title_list_text_transform']).';';
		}
		
		if($portfolio_title_list_font_styles !== '') {
			?>
			.projects_holder.hover_text article .portfolio_title, .projects_masonry_holder article .portfolio_title, .portfolio_slides .portfolio_title{
			<?php echo esc_attr($portfolio_title_list_font_styles); ?>
			}
			<?php
		}
		?>
		
		<?php
		//generate categories on portfolio gallery and masonry lists styles
		$portfolio_category_list_font_styles = '';
		if(isset($stockholm_options['portfolio_category_list_color']) && $stockholm_options['portfolio_category_list_color'] !== '') {
			$portfolio_category_list_font_styles .= 'color: '.esc_attr($stockholm_options['portfolio_category_list_color']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_list_font_family']) && $stockholm_options['portfolio_category_list_font_family'] !== '-1') {
			$portfolio_category_list_font_styles .= 'font-family: '.str_replace('+', ' ', $stockholm_options['portfolio_category_list_font_family']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_list_font_size']) && $stockholm_options['portfolio_category_list_font_size'] !== '') {
			$portfolio_category_list_font_styles .= 'font-size: '.intval($stockholm_options['portfolio_category_list_font_size']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_category_list_line_height']) && $stockholm_options['portfolio_category_list_line_height'] !== '') {
			$portfolio_category_list_font_styles .= 'line-height: '.intval($stockholm_options['portfolio_category_list_line_height']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_category_list_font_style']) && $stockholm_options['portfolio_category_list_font_style'] !== '') {
			$portfolio_category_list_font_styles .= 'font-style: '.esc_attr($stockholm_options['portfolio_category_list_font_style']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_list_font_weight']) && $stockholm_options['portfolio_category_list_font_weight'] !== '') {
			$portfolio_category_list_font_styles .= 'font-weight: '.esc_attr($stockholm_options['portfolio_category_list_font_weight']).';';
		}
		
		if(isset($stockholm_options['portfolio_category_list_letter_spacing']) && $stockholm_options['portfolio_category_list_letter_spacing'] !== '') {
			$portfolio_category_list_font_styles .= 'letter-spacing: '.intval($stockholm_options['portfolio_category_list_letter_spacing']).'px;';
		}
		
		if(isset($stockholm_options['portfolio_category_list_text_transform']) && $stockholm_options['portfolio_category_list_text_transform'] !== '') {
			$portfolio_category_list_font_styles .= 'text-transform: '.esc_attr($stockholm_options['portfolio_category_list_text_transform']).';';
		}
		
		if($portfolio_category_list_font_styles !== '') {
			?>
			.projects_holder.hover_text article .project_category, .portfolio_slides .project_category, .projects_masonry_holder .project_category{
			<?php echo esc_attr($portfolio_category_list_font_styles); ?>
			}
			<?php
		}
		?>
		
		<?php if (!empty($stockholm_options['portfolio_list_icons_color']) || !empty($stockholm_options['portfolio_list_icons_background_color']) || !empty($stockholm_options['portfolio_list_icons_border_color']) || (isset($stockholm_options['portfolio_list_icons_border_radius']) && $stockholm_options['portfolio_list_icons_border_radius'] !== '')) { ?>
			.projects_holder article .icons_holder a,
			.projects_masonry_holder article .icons_holder a,
			.portfolio_slides .icons_holder a{
			<?php if (!empty($stockholm_options['portfolio_list_icons_color'])) { ?> color: <?php echo esc_attr($stockholm_options['portfolio_list_icons_color']); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['portfolio_list_icons_background_color'])) { ?> background-color: <?php echo esc_attr($stockholm_options['portfolio_list_icons_background_color']); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['portfolio_list_icons_border_color'])) { ?> border-color: <?php echo esc_attr($stockholm_options['portfolio_list_icons_border_color']); ?>; <?php } ?>
			<?php if(isset($stockholm_options['portfolio_list_icons_border_radius']) && $stockholm_options['portfolio_list_icons_border_radius'] !== '') { ?> border-radius: <?php echo esc_attr($stockholm_options['portfolio_list_icons_border_radius'].'px'); ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['portfolio_list_icons_hover_color']) || !empty($stockholm_options['portfolio_list_icons_background_hover_color']) || !empty($stockholm_options['portfolio_list_icons_border_hover_color'])) { ?>
			.projects_holder article .icons_holder a:hover,
			.projects_masonry_holder article .icons_holder a:hover,
			.portfolio_slides .icons_holder a:hover{
			<?php if (!empty($stockholm_options['portfolio_list_icons_hover_color'])) { ?> color: <?php echo esc_attr($stockholm_options['portfolio_list_icons_hover_color']); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['portfolio_list_icons_background_hover_color'])) { ?> background-color: <?php echo esc_attr($stockholm_options['portfolio_list_icons_background_hover_color']); ?>; <?php } ?>
			<?php if (!empty($stockholm_options['portfolio_list_icons_border_hover_color'])) { ?> border-color: <?php echo esc_attr($stockholm_options['portfolio_list_icons_border_hover_color']); ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['portfolio_single_social_color'])) { ?>
			.portfolio_social_holder .social_share_list_holder ul li i{
			color: <?php echo esc_attr($stockholm_options['portfolio_single_social_color']); ?>;
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['portfolio_single_social_hover_color'])) { ?>
			.portfolio_social_holder .social_share_list_holder ul li i:hover{
			color: <?php echo esc_attr($stockholm_options['portfolio_single_social_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php
		
		$expandable_section_style		= array();
		$expandable_label_style 		= array();
		$expandable_label_hover_style 	= array();
		
		if(isset($stockholm_options['expandable_background_color']) && $stockholm_options['expandable_background_color'] !== '') {
			$expandable_section_style[] = 'background-color: '.esc_attr($stockholm_options['expandable_background_color']);
		}
		
		if(isset($stockholm_options['expandable_label_color']) && $stockholm_options['expandable_label_color'] !== '') {
			$expandable_label_style[] = 'color: '.esc_attr($stockholm_options['expandable_label_color']);
		}
		
		if(isset($stockholm_options['expandable_label_font_family']) && $stockholm_options['expandable_label_font_family'] !== '-1') {
			$expandable_label_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['expandable_label_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['expandable_label_font_size']) && $stockholm_options['expandable_label_font_size'] !== '') {
			$expandable_label_style[] = 'font-size: '.intval($stockholm_options['expandable_label_font_size']).'px';
		}
		
		if(isset($stockholm_options['expandable_label_letter_spacing']) && $stockholm_options['expandable_label_letter_spacing'] !== '') {
			$expandable_label_style[] = 'letter-spacing: '.intval($stockholm_options['expandable_label_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['expandable_label_font_weight']) && $stockholm_options['expandable_label_font_weight'] !== '') {
			$expandable_label_style[] = 'font-weight: '.esc_attr($stockholm_options['expandable_label_font_weight']);
		}
		
		if(isset($stockholm_options['expandable_label_text_transform']) && $stockholm_options['expandable_label_text_transform'] !== '') {
			$expandable_label_style[] = 'text-transform: '.esc_attr($stockholm_options['expandable_label_text_transform']);
		}
		
		if(isset($stockholm_options['expandable_label_hover_color']) && $stockholm_options['expandable_label_hover_color'] !== '') {
			$expandable_label_hover_style[] = 'color: '.esc_attr($stockholm_options['expandable_label_hover_color']);
		}
		
		if(is_array($expandable_section_style) && count($expandable_section_style)) { ?>
			.more_facts_holder {
			<?php echo implode(';', $expandable_section_style); ?>
			}
		
		<?php }
		
		if(is_array($expandable_label_style) && count($expandable_label_style)) { ?>
			.more_facts_button, .more_facts_button{
			<?php echo implode(';', $expandable_label_style); ?>
			}
		<?php }
		
		if(is_array($expandable_label_hover_style) && count($expandable_label_hover_style)) { ?>
			more_facts_button:hover, .more_facts_button:hover {
			<?php echo implode(';', $expandable_label_hover_style); ?>
			}
		<?php } ?>
		
		<?php
		$page_404_title_style = array();
		
		if(isset($stockholm_options['404_title_font_family']) && $stockholm_options['404_title_font_family'] !== '-1') {
			$page_404_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['404_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['404_title_font_size']) && $stockholm_options['404_title_font_size'] !== '') {
			$page_404_title_style[] = 'font-size: '.intval($stockholm_options['404_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['404_title_line_height']) && $stockholm_options['404_title_line_height'] !== '') {
			$page_404_title_style[] = 'line-height: '.intval($stockholm_options['404_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['404_title_letter_spacing']) && $stockholm_options['404_title_letter_spacing'] !== '') {
			$page_404_title_style[] = 'letter-spacing: '.intval($stockholm_options['404_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['404_title_font_weight']) && $stockholm_options['404_title_font_weight'] !== '') {
			$page_404_title_style[] = 'font-weight: '.esc_attr($stockholm_options['404_title_font_weight']);
		}
		
		if(isset($stockholm_options['404_title_font_style']) && $stockholm_options['404_title_font_style'] !== '') {
			$page_404_title_style[] = 'font-style: '.esc_attr($stockholm_options['404_title_font_style']);
		}
		
		if(isset($stockholm_options['404_title_text_transform']) && $stockholm_options['404_title_text_transform'] !== '') {
			$page_404_title_style[] = 'text-transform: '.esc_attr($stockholm_options['404_title_text_transform']);
		}
		
		if(isset($stockholm_options['404_title_color']) && $stockholm_options['404_title_color'] !== '') {
			$page_404_title_style[] = 'color: '.esc_attr($stockholm_options['404_title_color']);
		}
		
		if(is_array($page_404_title_style) && count($page_404_title_style)) { ?>
			.page_not_found h2{
			<?php echo implode(';', $page_404_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$page_404_text_style = array();
		
		if(isset($stockholm_options['404_text_font_family']) && $stockholm_options['404_text_font_family'] !== '-1') {
			$page_404_text_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['404_text_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['404_text_font_size']) && $stockholm_options['404_text_font_size'] !== '') {
			$page_404_text_style[] = 'font-size: '.intval($stockholm_options['404_text_font_size']).'px';
		}
		
		if(isset($stockholm_options['404_text_line_height']) && $stockholm_options['404_text_line_height'] !== '') {
			$page_404_text_style[] = 'line-height: '.intval($stockholm_options['404_text_line_height']).'px';
		}
		
		if(isset($stockholm_options['404_text_letter_spacing']) && $stockholm_options['404_text_letter_spacing'] !== '') {
			$page_404_text_style[] = 'letter-spacing: '.intval($stockholm_options['404_text_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['404_text_font_weight']) && $stockholm_options['404_text_font_weight'] !== '') {
			$page_404_text_style[] = 'font-weight: '.esc_attr($stockholm_options['404_text_font_weight']);
		}
		
		if(isset($stockholm_options['404_text_font_style']) && $stockholm_options['404_text_font_style'] !== '') {
			$page_404_text_style[] = 'font-style: '.esc_attr($stockholm_options['404_text_font_style']);
		}
		
		if(isset($stockholm_options['404_text_text_transform']) && $stockholm_options['404_text_text_transform'] !== '') {
			$page_404_text_style[] = 'text-transform: '.esc_attr($stockholm_options['404_text_text_transform']);
		}
		
		if(isset($stockholm_options['404_text_color']) && $stockholm_options['404_text_color'] !== '') {
			$page_404_text_style[] = 'color: '.esc_attr($stockholm_options['404_text_color']);
		}
		
		if(is_array($page_404_text_style) && count($page_404_text_style)) { ?>
			.page_not_found h4{
			<?php echo implode(';', $page_404_text_style); ?>
			}
		<?php } ?>
		
		<?php if (!empty($stockholm_options['woo_input_background_color']) || !empty($stockholm_options['woo_input_border_color']) || !empty($stockholm_options['woo_input_text_color'])) { ?>
			.woocommerce input[type="text"]:not(.qode_search_field):not(.qty), .woocommerce-page input[type="text"]:not(.qode_search_field):not(.qty), .woocommerce input[type="email"],.woocommerce-page input[type="email"],.woocommerce-page input[type="tel"], .woocommerce textarea,.woocommerce-page textarea, .woocommerce input[type="password"], .woocommerce-page input[type="password"], .woocommerce .chosen-container.chosen-container-single .chosen-single, .woocommerce-page .chosen-container.chosen-container-single .chosen-single, .woocommerce-checkout .chosen-container.chosen-container-single .chosen-single, .woocommerce table.cart div.coupon .input-text, .woocommerce-page table.cart div.coupon .input-text, .woocommerce div.cart-collaterals .select2-container .select2-choice, .woocommerce-page div.cart-collaterals .select2-container .select2-choice, .woocommerce div.product .summary table.variations td.value select, .woocommerce-checkout .select2-container .select2-choice, .woocommerce-account .select2-container .select2-choice, .woocommerce-checkout .select2-container--default .select2-selection--single, .woocommerce-account .select2-container--default .select2-selection--single{
			<?php if (!empty($stockholm_options['woo_input_background_color'])) { ?>background-color: <?php echo esc_attr($stockholm_options['woo_input_background_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['woo_input_border_color'])) { ?>border-color: <?php echo esc_attr($stockholm_options['woo_input_border_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['woo_input_text_color'])) { ?>color:<?php echo esc_attr($stockholm_options['woo_input_text_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['woo_input_border_width'])) { ?>
			.woocommerce input[type="text"]:not(.qode_search_field):not(.qty), .woocommerce-page input[type="text"]:not(.qode_search_field):not(.qty), .woocommerce input[type="email"], .woocommerce-page input[type="email"],.woocommerce-page input[type="tel"], .woocommerce textarea, .woocommerce-page textarea, .woocommerce input[type="password"], .woocommerce-page input[type="password"], .woocommerce table.cart div.coupon .input-text, .woocommerce-page table.cart div.coupon .input-text, .select2-container .select2-choice, .woocommerce-checkout .select2-container--default .select2-selection--single, .woocommerce-account .select2-container--default .select2-selection--single, .woocommerce .chosen-container.chosen-container-single .chosen-single, .woocommerce-page .chosen-container.chosen-container-single .chosen-single, .woocommerce-checkout .chosen-container.chosen-container-single .chosen-single, .woocommerce select#pa_color, .woocommerce div.product .summary table.variations td.value select{
			border-width: <?php echo intval($stockholm_options['woo_input_border_width']); ?>px;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['woo_input_text_color'])) { ?>
			.woocommerce-page input[type="text"]::-webkit-input-placeholder,
			.woocommerce-page input[type="email"]::-webkit-input-placeholder,
			.woocommerce-page input[type="tel"]::-webkit-input-placeholder,
			.woocommerce-page textarea::-webkit-input-placeholder,
			.woocommerce-page input[type="password"]::-webkit-input-placeholder,
			.woocommerce table.cart div.coupon .input-text::-webkit-input-placeholder,
			.woocommerce-page table.cart div.coupon .input-text::-webkit-input-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_text_color']); ?>;
			}
			
			.woocommerce-page input[type="text"]:-moz-placeholder,
			.woocommerce-page input[type="email"]:-moz-placeholder,
			.woocommerce-page input[type="tel"]:-moz-placeholder,
			.woocommerce-page textarea:-moz-placeholder,
			.woocommerce-page input[type="password"]:-moz-placeholder,
			.woocommerce table.cart div.coupon .input-text:-moz-placeholder,
			.woocommerce-page table.cart div.coupon .input-text:-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_text_color']); ?>;
			}
			
			.woocommerce-page input[type="text"]::-moz-placeholder,
			.woocommerce-page input[type="email"]::-moz-placeholder,
			.woocommerce-page input[type="tel"]::-moz-placeholder,
			.woocommerce-page textarea::-moz-placeholder,
			.woocommerce-page input[type="password"]::-moz-placeholder,
			.woocommerce table.cart div.coupon .input-text::-moz-placeholder,
			.woocommerce-page table.cart div.coupon .input-text::-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_text_color']); ?>;
			}
			
			.woocommerce-page input[type="text"]:-ms-input-placeholder,
			.woocommerce-page input[type="email"]:-ms-input-placeholder,
			.woocommerce-page input[type="tel"]:-ms-input-placeholder,
			.woocommerce-page textarea:-ms-input-placeholder,
			.woocommerce-page input[type="password"]:-ms-input-placeholder,
			.woocommerce table.cart div.coupon .input-text:-ms-input-placeholder,
			.woocommerce-page table.cart div.coupon .input-text:-ms-input-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_text_color']); ?>;
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['woo_input_focus_text_color']) || !empty($stockholm_options['woo_input_focus_background_color']) || !empty($stockholm_options['woo_input_focus_border_color'])) { ?>
			.woocommerce input[type="text"]:not(.qode_search_field):not(.qty):focus,.woocommerce-page input[type="text"]:not(.qode_search_field):not(.qty):focus,
			.woocommerce input[type="email"]:focus,.woocommerce-page input[type="email"]:focus,
			.woocommerce-page input[type="tel"]:focus,
			.woocommerce textarea:focus,.woocommerce-page textarea:focus,
			.woocommerce input[type="password"]:focus,.woocommerce-page input[type="password"]:focus,
			.woocommerce table.cart div.coupon .input-text:focus,.woocommerce-page table.cart div.coupon .input-text:focus{
			<?php if (!empty($stockholm_options['woo_input_focus_text_color'])) { ?>color: <?php echo esc_attr($stockholm_options['woo_input_focus_text_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['woo_input_focus_background_color'])) { ?>background-color: <?php echo esc_attr($stockholm_options['woo_input_focus_background_color']);  ?>; <?php } ?>
			<?php if (!empty($stockholm_options['woo_input_focus_border_color'])) { ?>border-color: <?php echo esc_attr($stockholm_options['woo_input_focus_border_color']);  ?>; <?php } ?>
			}
		<?php } ?>
		
		<?php if(!empty($stockholm_options['woo_input_focus_text_color'])) { ?>
			.woocommerce-page input[type="text"]:focus::-webkit-input-placeholder,
			.woocommerce-page input[type="email"]:focus::-webkit-input-placeholder,
			.woocommerce-page input[type="tel"]:focus::-webkit-input-placeholder,
			.woocommerce-page textarea:focus::-webkit-input-placeholder,
			.woocommerce-page input[type="password"]:focus::-webkit-input-placeholder,
			.woocommerce table.cart div.coupon .input-text:focus::-webkit-input-placeholder,
			.woocommerce-page table.cart div.coupon .input-text:focus::-webkit-input-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_focus_text_color']); ?>;
			}
			
			.woocommerce-page input[type="text"]:focus:-moz-placeholder,
			.woocommerce-page input[type="email"]:focus:-moz-placeholder,
			.woocommerce-page input[type="tel"]:focus:-moz-placeholder,
			.woocommerce-page textarea:focus:-moz-placeholder,
			.woocommerce-page input[type="password"]:focus:-moz-placeholder,
			.woocommerce table.cart div.coupon .input-text:focus:-moz-placeholder,
			.woocommerce-page table.cart div.coupon .input-text:focus:-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_focus_text_color']); ?>;
			}
			
			.woocommerce-page input[type="text"]:focus::-moz-placeholder,
			.woocommerce-page input[type="email"]:focus::-moz-placeholder,
			.woocommerce-page input[type="tel"]:focus::-moz-placeholder,
			.woocommerce-page textarea:focus::-moz-placeholder,
			.woocommerce-page input[type="password"]:focus::-moz-placeholder,
			.woocommerce table.cart div.coupon .input-text:focus::-moz-placeholder,
			.woocommerce-page table.cart div.coupon .input-text:focus::-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_focus_text_color']); ?>;
			}
			
			.woocommerce-page input[type="text"]:focus:-ms-input-placeholder,
			.woocommerce-page input[type="email"]:focus:-ms-input-placeholder,
			.woocommerce-page input[type="tel"]:focus:-ms-input-placeholder,
			.woocommerce-page textarea:focus:-ms-input-placeholder,
			.woocommerce-page input[type="password"]:focus:-ms-input-placeholder,
			.woocommerce table.cart div.coupon .input-text:focus:-ms-input-placeholder,
			.woocommerce-page table.cart div.coupon .input-text:focus:-ms-input-placeholder{
			color: <?php echo esc_attr($stockholm_options['woo_input_focus_text_color']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_products_box_text_align']) && !empty($stockholm_options['woo_products_box_text_align'])) { ?>
			.woocommerce-page ul.products li.product,
			.woocommerce ul.products li.product{
			text-align: <?php echo esc_attr($stockholm_options['woo_products_box_text_align']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_products_box_background_color']) && !empty($stockholm_options['woo_products_box_background_color'])) { ?>
			.woocommerce-page ul.products li.product .product_info_box,
			.woocommerce ul.products li.product .product_info_box{
			background-color: <?php echo esc_attr($stockholm_options['woo_products_box_background_color']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_products_box_border_color']) && !empty($stockholm_options['woo_products_box_border_color'])) { ?>
			.woocommerce-page ul.products li.product,
			.woocommerce ul.products li.product,
			.woocommerce .product .images a{
			border: 1px solid <?php echo esc_attr($stockholm_options['woo_products_box_border_color']); ?>;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_products_disable_box']) && $stockholm_options['woo_products_disable_box'] == "yes") { ?>
			.woocommerce-page ul.products li.product .product_info_box,
			.woocommerce ul.products li.product .product_info_box{
			background-color: transparent !important;
			padding-left: 0;
			padding-right: 0;
			}
		<?php } ?>
		
		<?php
		$woo_products_category_style = array();
		
		if(isset($stockholm_options['woo_products_category_font_family']) && $stockholm_options['woo_products_category_font_family'] !== '-1') {
			$woo_products_category_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_category_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_category_font_size']) && $stockholm_options['woo_products_category_font_size'] !== '') {
			$woo_products_category_style[] = 'font-size: '.intval($stockholm_options['woo_products_category_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_category_line_height']) && $stockholm_options['woo_products_category_line_height'] !== '') {
			$woo_products_category_style[] = 'line-height: '.intval($stockholm_options['woo_products_category_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_category_letter_spacing']) && $stockholm_options['woo_products_category_letter_spacing'] !== '') {
			$woo_products_category_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_category_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_category_font_weight']) && $stockholm_options['woo_products_category_font_weight'] !== '') {
			$woo_products_category_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_category_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_category_font_style']) && $stockholm_options['woo_products_category_font_style'] !== '') {
			$woo_products_category_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_category_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_category_text_transform']) && $stockholm_options['woo_products_category_text_transform'] !== '') {
			$woo_products_category_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_category_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_color']) && $stockholm_options['woo_products_category_color'] !== '') {
			$woo_products_category_style[] = 'color: '.esc_attr($stockholm_options['woo_products_category_color']);
		}
		
		if(is_array($woo_products_category_style) && count($woo_products_category_style)) { ?>
			.woocommerce ul.products li.product span.product-categories a,
			.woocommerce-page ul.products li.product span.product-categories a{
			<?php echo implode(';', $woo_products_category_style); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_products_category_hide_category']) && $stockholm_options['woo_products_category_hide_category'] == "yes") { ?>
			.woocommerce ul.products li.product span.product-categories,
			.woocommerce-page ul.products li.product span.product-categories{
			display:none;
			}
			.woocommerce-page ul.products li.product .product_info_box,
			.woocommerce ul.products li.product .product_info_box{
			padding-top:18px;
			}
		<?php } ?>
		
		<?php
		$woo_products_title_style = array();
		
		if(isset($stockholm_options['woo_products_title_font_family']) && $stockholm_options['woo_products_title_font_family'] !== '-1') {
			$woo_products_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_title_font_size']) && $stockholm_options['woo_products_title_font_size'] !== '') {
			$woo_products_title_style[] = 'font-size: '.intval($stockholm_options['woo_products_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_title_line_height']) && $stockholm_options['woo_products_title_line_height'] !== '') {
			$woo_products_title_style[] = 'line-height: '.intval($stockholm_options['woo_products_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_title_letter_spacing']) && $stockholm_options['woo_products_title_letter_spacing'] !== '') {
			$woo_products_title_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_title_font_weight']) && $stockholm_options['woo_products_title_font_weight'] !== '') {
			$woo_products_title_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_title_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_title_font_style']) && $stockholm_options['woo_products_title_font_style'] !== '') {
			$woo_products_title_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_title_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_title_text_transform']) && $stockholm_options['woo_products_title_text_transform'] !== '') {
			$woo_products_title_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_title_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_title_color']) && $stockholm_options['woo_products_title_color'] !== '') {
			$woo_products_title_style[] = 'color: '.esc_attr($stockholm_options['woo_products_title_color']);
		}
		
		if(is_array($woo_products_title_style) && count($woo_products_title_style)) { ?>
			.woocommerce ul.products li.product span.product-title,
			.woocommerce aside ul.product_list_widget li a,
			aside ul.product_list_widget li a{
			<?php echo implode(';', $woo_products_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['woo_products_title_hover_color']) && !empty($stockholm_options['woo_products_title_hover_color'])) { ?>
			.woocommerce ul.products li.product span.product-title:hover,
			.woocommerce aside ul.product_list_widget li > a:hover,
			aside ul.product_list_widget li > a:hover{
			color: <?php echo esc_attr($stockholm_options['woo_products_title_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$woo_products_price_style = array();
		
		if(isset($stockholm_options['woo_products_price_font_family']) && $stockholm_options['woo_products_price_font_family'] !== '-1') {
			$woo_products_price_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['woo_products_price_font_family']).'", sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_price_font_size']) && $stockholm_options['woo_products_price_font_size'] !== '') {
			$woo_products_price_style[] = 'font-size: '.intval($stockholm_options['woo_products_price_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_price_line_height']) && $stockholm_options['woo_products_price_line_height'] !== '') {
			$woo_products_price_style[] = 'line-height: '.intval($stockholm_options['woo_products_price_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_price_letter_spacing']) && $stockholm_options['woo_products_price_letter_spacing'] !== '') {
			$woo_products_price_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_price_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_price_font_weight']) && $stockholm_options['woo_products_price_font_weight'] !== '') {
			$woo_products_price_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_price_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_price_font_style']) && $stockholm_options['woo_products_price_font_style'] !== '') {
			$woo_products_price_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_price_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_price_text_transform']) && $stockholm_options['woo_products_price_text_transform'] !== '') {
			$woo_products_price_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_price_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_price_color']) && $stockholm_options['woo_products_price_color'] !== '') {
			$woo_products_price_style[] = 'color: '.esc_attr($stockholm_options['woo_products_price_color']);
		}
		
		if(is_array($woo_products_price_style) && count($woo_products_price_style)) { ?>
			.woocommerce ul.products li.product .price,
			.woocommerce-page ul.products li.product .price,
			.woocommerce ul.products li.product del .amount, .woocommerce-page ul.products li.product del .amount,
			.woocommerce aside ul.product_list_widget li span.amount,
			aside ul.product_list_widget li span.amount{
			<?php echo implode(';', $woo_products_price_style); ?>
			}
		<?php }
		
		if(isset($stockholm_options['woo_products_price_old_color']) && !empty($stockholm_options['woo_products_price_old_color'])) { ?>
			.woocommerce-page ul.products li.product del .amount,
			.woocommerce ul.products li.product del .amount,
			.woocommerce-page ul.products li.product del,
			.woocommerce ul.products li.product del,
			.woocommerce aside ul.product_list_widget li del span.amount,
			aside ul.product_list_widget li del span.amount{
			color: <?php echo esc_attr($stockholm_options['woo_products_price_old_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$woo_products_sale_style = array();
		
		if(isset($stockholm_options['woo_products_sale_font_family']) && $stockholm_options['woo_products_sale_font_family'] !== '-1') {
			$woo_products_sale_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_sale_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_sale_font_size']) && $stockholm_options['woo_products_sale_font_size'] !== '') {
			$woo_products_sale_style[] = 'font-size: '.intval($stockholm_options['woo_products_sale_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_sale_line_height']) && $stockholm_options['woo_products_sale_line_height'] !== '') {
			$woo_products_sale_style[] = 'line-height: '.intval($stockholm_options['woo_products_sale_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_sale_letter_spacing']) && $stockholm_options['woo_products_sale_letter_spacing'] !== '') {
			$woo_products_sale_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_sale_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_sale_font_weight']) && $stockholm_options['woo_products_sale_font_weight'] !== '') {
			$woo_products_sale_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_sale_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_sale_font_style']) && $stockholm_options['woo_products_sale_font_style'] !== '') {
			$woo_products_sale_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_sale_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_sale_text_transform']) && $stockholm_options['woo_products_sale_text_transform'] !== '') {
			$woo_products_sale_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_sale_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_sale_color']) && $stockholm_options['woo_products_sale_color'] !== '') {
			$woo_products_sale_style[] = 'color: '.esc_attr($stockholm_options['woo_products_sale_color']);
		}
		
		if(isset($stockholm_options['woo_products_sale_background_color']) && $stockholm_options['woo_products_sale_background_color'] !== '') {
			$woo_products_sale_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_sale_background_color']);
		}
		
		if(is_array($woo_products_sale_style) && count($woo_products_sale_style)) { ?>
			.woocommerce .product .onsale:not(.out-of-stock-button), .woocommerce .product .single-onsale,
			.woocommerce ul.products.standard li.product .onsale:not(.out-of-stock-button){
			<?php echo implode(';', $woo_products_sale_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_products_out_of_stock_style = array();
		
		if(isset($stockholm_options['woo_products_out_of_stock_font_family']) && $stockholm_options['woo_products_out_of_stock_font_family'] !== '-1') {
			$woo_products_out_of_stock_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_out_of_stock_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_font_size']) && $stockholm_options['woo_products_out_of_stock_font_size'] !== '') {
			$woo_products_out_of_stock_style[] = 'font-size: '.intval($stockholm_options['woo_products_out_of_stock_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_line_height']) && $stockholm_options['woo_products_out_of_stock_line_height'] !== '') {
			$woo_products_out_of_stock_style[] = 'line-height: '.intval($stockholm_options['woo_products_out_of_stock_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_letter_spacing']) && $stockholm_options['woo_products_out_of_stock_letter_spacing'] !== '') {
			$woo_products_out_of_stock_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_out_of_stock_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_font_weight']) && $stockholm_options['woo_products_out_of_stock_font_weight'] !== '') {
			$woo_products_out_of_stock_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_out_of_stock_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_font_style']) && $stockholm_options['woo_products_out_of_stock_font_style'] !== '') {
			$woo_products_out_of_stock_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_out_of_stock_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_text_transform']) && $stockholm_options['woo_products_out_of_stock_text_transform'] !== '') {
			$woo_products_out_of_stock_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_out_of_stock_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_color']) && $stockholm_options['woo_products_out_of_stock_color'] !== '') {
			$woo_products_out_of_stock_style[] = 'color: '.esc_attr($stockholm_options['woo_products_out_of_stock_color']);
		}
		
		if(isset($stockholm_options['woo_products_out_of_stock_background_color']) && $stockholm_options['woo_products_out_of_stock_background_color'] !== '') {
			$woo_products_out_of_stock_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_out_of_stock_background_color']);
		}
		
		if(is_array($woo_products_out_of_stock_style) && count($woo_products_out_of_stock_style)) { ?>
			.woocommerce .product .onsale.out-of-stock-button,
			.woocommerce ul.products.standard li.product .onsale.out-of-stock-button{
			<?php echo implode(';', $woo_products_out_of_stock_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_products_sorting_style = array();
		
		if(isset($stockholm_options['woo_products_sorting_color']) && $stockholm_options['woo_products_sorting_color'] !== '') {
			$woo_products_sorting_style[] = 'color: '.esc_attr($stockholm_options['woo_products_sorting_color']);
		}
		
		if(isset($stockholm_options['woo_products_sorting_background_color']) && $stockholm_options['woo_products_sorting_background_color'] !== '') {
			$woo_products_sorting_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_sorting_background_color']);
		}
		
		if(isset($stockholm_options['woo_products_sorting_border_color']) && $stockholm_options['woo_products_sorting_border_color'] !== '') {
			$woo_products_sorting_style[] = 'border-color: '.esc_attr($stockholm_options['woo_products_sorting_border_color']);
		}
		
		if(isset($stockholm_options['woo_products_sorting_border_width']) && $stockholm_options['woo_products_sorting_border_width'] !== '') {
			$woo_products_sorting_style[] = 'border-width: '.intval($stockholm_options['woo_products_sorting_border_width']).'px';
		}
		
		if(is_array($woo_products_sorting_style) && count($woo_products_sorting_style)) { ?>
			body.archive.woocommerce-page .select2-container.orderby .select2-choice{
			<?php echo implode(';', $woo_products_sorting_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['woo_products_sorting_color']) && !empty($stockholm_options['woo_products_sorting_color'])) { ?>
			body.archive.woocommerce-page .select2-drop{
			color: <?php echo esc_attr($stockholm_options['woo_products_sorting_color']); ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['woo_products_sorting_dropdown_background_color']) && !empty($stockholm_options['woo_products_sorting_dropdown_background_color'])) { ?>
			body.archive.woocommerce-page .select2-drop{
			background-color: <?php echo esc_attr($stockholm_options['woo_products_sorting_dropdown_background_color']); ?>;
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['woo_products_sorting_hover_color']) && !empty($stockholm_options['woo_products_sorting_hover_color'])) { ?>
			body.archive.woocommerce-page .select2-results li.select2-highlighted,
			body.archive.woocommerce-page .select2-container .select2-choice .select2-arrow:after{
			color: <?php echo esc_attr($stockholm_options['woo_products_sorting_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$woo_products_sorting_result_style = array();
		
		if(isset($stockholm_options['woo_products_sorting_result_font_family']) && $stockholm_options['woo_products_sorting_result_font_family'] !== '-1') {
			$woo_products_sorting_result_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_sorting_result_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_sorting_result_font_size']) && $stockholm_options['woo_products_sorting_result_font_size'] !== '') {
			$woo_products_sorting_result_style[] = 'font-size: '.intval($stockholm_options['woo_products_sorting_result_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_sorting_result_line_height']) && $stockholm_options['woo_products_sorting_result_line_height'] !== '') {
			$woo_products_sorting_result_style[] = 'line-height: '.intval($stockholm_options['woo_products_sorting_result_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_sorting_result_letter_spacing']) && $stockholm_options['woo_products_sorting_result_letter_spacing'] !== '') {
			$woo_products_sorting_result_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_sorting_result_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_sorting_result_font_weight']) && $stockholm_options['woo_products_sorting_result_font_weight'] !== '') {
			$woo_products_sorting_result_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_sorting_result_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_sorting_result_font_style']) && $stockholm_options['woo_products_sorting_result_font_style'] !== '') {
			$woo_products_sorting_result_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_sorting_result_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_sorting_result_text_transform']) && $stockholm_options['woo_products_sorting_result_text_transform'] !== '') {
			$woo_products_sorting_result_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_sorting_result_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_sorting_result_color']) && $stockholm_options['woo_products_sorting_result_color'] !== '') {
			$woo_products_sorting_result_style[] = 'color: '.esc_attr($stockholm_options['woo_products_sorting_result_color']);
		}
		
		if(is_array($woo_products_sorting_result_style) && count($woo_products_sorting_result_style)) { ?>
			.woocommerce .woocommerce-result-count{
			<?php echo implode(';', $woo_products_sorting_result_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_products_list_add_to_cart_style = array();
		$woo_products_list_add_to_cart_hover_style = array();
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_font_family']) && $stockholm_options['woo_products_list_add_to_cart_font_family'] !== '-1') {
			$woo_products_list_add_to_cart_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_list_add_to_cart_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_font_size']) && $stockholm_options['woo_products_list_add_to_cart_font_size'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'font-size: '.intval($stockholm_options['woo_products_list_add_to_cart_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_line_height']) && $stockholm_options['woo_products_list_add_to_cart_line_height'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'line-height: '.intval($stockholm_options['woo_products_list_add_to_cart_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_letter_spacing']) && $stockholm_options['woo_products_list_add_to_cart_letter_spacing'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_list_add_to_cart_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_font_weight']) && $stockholm_options['woo_products_list_add_to_cart_font_weight'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_font_style']) && $stockholm_options['woo_products_list_add_to_cart_font_style'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_text_transform']) && $stockholm_options['woo_products_list_add_to_cart_text_transform'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_color']) && !empty($stockholm_options['woo_products_list_add_to_cart_color'])) {
			$woo_products_list_add_to_cart_style[] = 'color: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_color']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_background_color']) && !empty($stockholm_options['woo_products_list_add_to_cart_background_color'])) {
			$woo_products_list_add_to_cart_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_background_color']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_border_width']) && $stockholm_options['woo_products_list_add_to_cart_border_width'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'border-width: '.intval($stockholm_options['woo_products_list_add_to_cart_border_width']).'px';
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_border_color']) && !empty($stockholm_options['woo_products_list_add_to_cart_border_color'])) {
			$woo_products_list_add_to_cart_style[] = 'border-color: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_border_color']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_border_radius']) && $stockholm_options['woo_products_list_add_to_cart_border_radius'] !== '') {
			$woo_products_list_add_to_cart_style[] = 'border-radius: '.intval($stockholm_options['woo_products_list_add_to_cart_border_radius']).'px';
		}
		
		if(is_array($woo_products_list_add_to_cart_style) && count($woo_products_list_add_to_cart_style)) { ?>
			.woocommerce ul.products li.product .add-to-cart-button,
			.woocommerce ul.products li.product .added_to_cart,
			.woocommerce .widget_price_filter .button,
			.woocommerce-page .widget_price_filter .button,
			.woocommerce .widget_shopping_cart_content p.buttons a.button,
			.woocommerce .button,
			.woocommerce-page .button,
			.woocommerce button.button,
			.woocommerce-page button.button,
			.woocommerce-page input[type="submit"]:not(.qode_search_field),
			.woocommerce input[type="submit"]:not(.qode_search_field),
			.woocommerce ul.products li.product .added_to_cart{
			<?php echo implode(';', $woo_products_list_add_to_cart_style); ?>
			}
		<?php }
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_hover_color']) && !empty($stockholm_options['woo_products_list_add_to_cart_hover_color'])) {
			$woo_products_list_add_to_cart_hover_style[] = 'color: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_hover_color']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_background_hover_color']) && !empty($stockholm_options['woo_products_list_add_to_cart_background_hover_color'])) {
			$woo_products_list_add_to_cart_hover_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_background_hover_color']);
		}
		
		if(isset($stockholm_options['woo_products_list_add_to_cart_border_hover_color']) && !empty($stockholm_options['woo_products_list_add_to_cart_border_hover_color'])) {
			$woo_products_list_add_to_cart_hover_style[] = 'border-color: '.esc_attr($stockholm_options['woo_products_list_add_to_cart_border_hover_color']);
		}
		
		if(is_array($woo_products_list_add_to_cart_hover_style) && count($woo_products_list_add_to_cart_hover_style)) { ?>
			.woocommerce ul.products li.product a.add_to_cart_button:hover,
			.woocommerce-page ul.products li.product a.add_to_cart_button:hover,
			.woocommerce ul.products li.product .added_to_cart:hover,
			.woocommerce-page ul.products li.product .added_to_cart:hover,
			.woocommerce .widget_price_filter .button:hover,
			.woocommerce-page .widget_price_filter .button:hover,
			.woocommerce .widget_shopping_cart_content p.buttons a.button:hover,
			.woocommerce .button:hover,
			.woocommerce-page .button:hover,
			.woocommerce button.button:hover,
			.woocommerce-page button.button:hover,
			.woocommerce #submit:hover,
			.woocommerce ul.products li.product a.qbutton:hover,
			.woocommerce-page ul.products li.product a.qbutton:hover,
			.woocommerce ul.products li.product .added_to_cart:hover,
			.woocommerce-page input[type="submit"]:not(.qode_search_field):hover,
			.woocommerce input[type="submit"]:not(.qode_search_field):hover{
			<?php echo implode(';', $woo_products_list_add_to_cart_hover_style); ?>
			}
		<?php } ?>
		
		<?php
		if(isset($stockholm_options['woo_products_price_filter_background_color']) && !empty($stockholm_options['woo_products_price_filter_background_color'])) { ?>
			.woocommerce .widget_price_filter .ui-slider-horizontal .ui-slider-range,
			.woocommerce-page .widget_price_filter .ui-slider-horizontal .ui-slider-range {
			background-color: <?php echo esc_attr($stockholm_options['woo_products_price_filter_background_color']); ?>;
			}
		<?php } ?>
		
		<?php
		
		if(isset($stockholm_options['woo_products_price_filter_background_active_color']) && !empty($stockholm_options['woo_products_price_filter_background_active_color'])) { ?>
			.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
			.woocommerce-page .widget_price_filter .price_slider_wrapper .ui-widget-content {
			background-color: <?php echo esc_attr($stockholm_options['woo_products_price_filter_background_active_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$woo_products_price_filter_arrows_style = array();
		
		if(isset($stockholm_options['woo_products_price_filter_arrows_color']) && !empty($stockholm_options['woo_products_price_filter_arrows_color'])) {
			$woo_products_price_filter_arrows_style[] = 'background-color: '.esc_attr( $stockholm_options['woo_products_price_filter_arrows_color']);
		}
		
		if(isset($stockholm_options['woo_products_price_filter_arrows_height']) && !empty($stockholm_options['woo_products_price_filter_arrows_height'])) {
			$woo_products_price_filter_arrows_style[] = 'height: '.intval( $stockholm_options['woo_products_price_filter_arrows_height'] ). 'px';
			$woo_products_price_filter_arrows_style[] = 'width: '.intval( $stockholm_options['woo_products_price_filter_arrows_height'] ). 'px';
			
			if(isset($stockholm_options['woo_products_price_filter_slider_height']) && !empty($stockholm_options['woo_products_price_filter_slider_height'])) {
				$woo_products_price_filter_arrows_style[] = 'top: -'.intval( ($stockholm_options['woo_products_price_filter_arrows_height'] - $stockholm_options['woo_products_price_filter_slider_height']) / 2 ). 'px';
			}
		}
		
		if(isset($stockholm_options['woo_products_price_filter_arrows_border_radius']) && !empty($stockholm_options['woo_products_price_filter_arrows_border_radius'])) {
			$woo_products_price_filter_arrows_style[] = 'border-radius: '.intval( $stockholm_options['woo_products_price_filter_arrows_border_radius'] ). 'px';
		}
		
		if(is_array($woo_products_price_filter_arrows_style) && count($woo_products_price_filter_arrows_style) > 0) {
			?>
			.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
			.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
			<?php echo implode(';', $woo_products_price_filter_arrows_style); ?>
			}
			.woocommerce .widget_price_filter .ui-slider .ui-slider-handle:last-child,
			.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle:last-child {
			margin-left: 0em;
			}
		<?php } ?>
		<?php if(isset($stockholm_options['woo_products_price_filter_slider_height']) && !empty($stockholm_options['woo_products_price_filter_slider_height'])) { ?>
			.woocommerce .widget_price_filter .ui-slider-horizontal,
			.woocommerce-page .widget_price_filter .ui-slider-horizontal {
			height: <?php echo intval($stockholm_options['woo_products_price_filter_slider_height']); ?>px;
			}
		<?php } ?>
		<?php
		$woo_product_single_meta_title_style = array();
		
		if(isset($stockholm_options['woo_product_single_meta_title_font_family']) && $stockholm_options['woo_product_single_meta_title_font_family'] !== '-1') {
			$woo_product_single_meta_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_product_single_meta_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_title_font_size']) && $stockholm_options['woo_product_single_meta_title_font_size'] !== '') {
			$woo_product_single_meta_title_style[] = 'font-size: '.intval($stockholm_options['woo_product_single_meta_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_title_line_height']) && $stockholm_options['woo_product_single_meta_title_line_height'] !== '') {
			$woo_product_single_meta_title_style[] = 'line-height: '.intval($stockholm_options['woo_product_single_meta_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_title_letter_spacing']) && $stockholm_options['woo_product_single_meta_title_letter_spacing'] !== '') {
			$woo_product_single_meta_title_style[] = 'letter-spacing: '.intval($stockholm_options['woo_product_single_meta_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_title_font_weight']) && $stockholm_options['woo_product_single_meta_title_font_weight'] !== '') {
			$woo_product_single_meta_title_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_product_single_meta_title_font_weight']);
		}
		
		if(isset($stockholm_options['woo_product_single_meta_title_font_style']) && $stockholm_options['woo_product_single_meta_title_font_style'] !== '') {
			$woo_product_single_meta_title_style[] = 'font-style: '.esc_attr($stockholm_options['woo_product_single_meta_title_font_style']);
		}
		
		if(isset($stockholm_options['woo_product_single_meta_title_text_transform']) && $stockholm_options['woo_product_single_meta_title_text_transform'] !== '') {
			$woo_product_single_meta_title_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_product_single_meta_title_text_transform']);
		}
		
		if(isset($stockholm_options['woo_product_single_meta_title_color']) && $stockholm_options['woo_product_single_meta_title_color'] !== '') {
			$woo_product_single_meta_title_style[] = 'color: '.esc_attr($stockholm_options['woo_product_single_meta_title_color']);
		}
		
		if(is_array($woo_product_single_meta_title_style) && count($woo_product_single_meta_title_style)) { ?>
			.woocommerce div.product div.product_meta > span,
			.woocommerce div.product div.product_meta > .social_share_list_holder > span{
			<?php echo implode(';', $woo_product_single_meta_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_product_single_meta_info_style = array();
		
		if(isset($stockholm_options['woo_product_single_meta_info_font_family']) && $stockholm_options['woo_product_single_meta_info_font_family'] !== '-1') {
			$woo_product_single_meta_info_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_product_single_meta_info_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_info_font_size']) && $stockholm_options['woo_product_single_meta_info_font_size'] !== '') {
			$woo_product_single_meta_info_style[] = 'font-size: '.intval($stockholm_options['woo_product_single_meta_info_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_info_line_height']) && $stockholm_options['woo_product_single_meta_info_line_height'] !== '') {
			$woo_product_single_meta_info_style[] = 'line-height: '.intval($stockholm_options['woo_product_single_meta_info_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_info_letter_spacing']) && $stockholm_options['woo_product_single_meta_info_letter_spacing'] !== '') {
			$woo_product_single_meta_info_style[] = 'letter-spacing: '.intval($stockholm_options['woo_product_single_meta_info_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_meta_info_font_weight']) && $stockholm_options['woo_product_single_meta_info_font_weight'] !== '') {
			$woo_product_single_meta_info_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_product_single_meta_info_font_weight']);
		}
		
		if(isset($stockholm_options['woo_product_single_meta_info_font_style']) && $stockholm_options['woo_product_single_meta_info_font_style'] !== '') {
			$woo_product_single_meta_info_style[] = 'font-style: '.esc_attr($stockholm_options['woo_product_single_meta_info_font_style']);
		}
		
		if(isset($stockholm_options['woo_product_single_meta_info_text_transform']) && $stockholm_options['woo_product_single_meta_info_text_transform'] !== '') {
			$woo_product_single_meta_info_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_product_single_meta_info_text_transform']);
		}
		
		if(isset($stockholm_options['woo_product_single_meta_info_color']) && $stockholm_options['woo_product_single_meta_info_color'] !== '') {
			$woo_product_single_meta_info_style[] = 'color: '.esc_attr($stockholm_options['woo_product_single_meta_info_color']);
		}
		
		if(is_array($woo_product_single_meta_info_style) && count($woo_product_single_meta_info_style)) { ?>
			.woocommerce div.product div.product_meta > span span,
			.woocommerce div.product div.product_meta > span a{
			<?php echo implode(';', $woo_product_single_meta_info_style); ?>
			}
		<?php } ?>
		
		
		<?php
		$woo_product_single_title_style = array();
		
		if(isset($stockholm_options['woo_product_single_title_font_family']) && $stockholm_options['woo_product_single_title_font_family'] !== '-1') {
			$woo_product_single_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_product_single_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_product_single_title_font_size']) && $stockholm_options['woo_product_single_title_font_size'] !== '') {
			$woo_product_single_title_style[] = 'font-size: '.intval($stockholm_options['woo_product_single_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_title_line_height']) && $stockholm_options['woo_product_single_title_line_height'] !== '') {
			$woo_product_single_title_style[] = 'line-height: '.intval($stockholm_options['woo_product_single_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_title_letter_spacing']) && $stockholm_options['woo_product_single_title_letter_spacing'] !== '') {
			$woo_product_single_title_style[] = 'letter-spacing: '.intval($stockholm_options['woo_product_single_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_title_font_weight']) && $stockholm_options['woo_product_single_title_font_weight'] !== '') {
			$woo_product_single_title_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_product_single_title_font_weight']);
		}
		
		if(isset($stockholm_options['woo_product_single_title_font_style']) && $stockholm_options['woo_product_single_title_font_style'] !== '') {
			$woo_product_single_title_style[] = 'font-style: '.esc_attr($stockholm_options['woo_product_single_title_font_style']);
		}
		
		if(isset($stockholm_options['woo_product_single_title_text_transform']) && $stockholm_options['woo_product_single_title_text_transform'] !== '') {
			$woo_product_single_title_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_product_single_title_text_transform']);
		}
		
		if(isset($stockholm_options['woo_product_single_title_color']) && $stockholm_options['woo_product_single_title_color'] !== '') {
			$woo_product_single_title_style[] = 'color: '.esc_attr($stockholm_options['woo_product_single_title_color']);
		}
		
		if(is_array($woo_product_single_title_style) && count($woo_product_single_title_style)) { ?>
			.woocommerce .product h2.product_title{
			<?php echo implode(';', $woo_product_single_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['woo_product_single_title_hover_color']) && !empty($stockholm_options['woo_product_single_title_hover_color'])) { ?>
			.woocommerce .product h2.product_title:hover{
			color: <?php echo esc_attr($stockholm_options['woo_product_single_title_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php
		
		$woo_products_single_qunatity_style = array();
		
		$woo_products_single_qunatity_width = stockholm_qode_options()->getOptionValue('woo_products_single_qunatity_width');
		if($woo_products_single_qunatity_width !== '') {
			$woo_products_single_qunatity_style['width'] = $woo_products_single_qunatity_width.'px';
			$woo_products_single_qunatity_style['height'] = $woo_products_single_qunatity_width.'px';
			$woo_products_single_qunatity_style['line-height'] = ($woo_products_single_qunatity_width-2).'px';
		}
		
		if (count($woo_products_single_qunatity_style)){
			echo stockholm_qode_dynamic_css('.woocommerce div.product .cart .quantity',array('height' => intval($woo_products_single_qunatity_width).'px', 'line-height' => intval($woo_products_single_qunatity_width).'px'));
			
			echo stockholm_qode_dynamic_css('.woocommerce .product .quantity .minus,
	                       .woocommerce .product .quantity .plus,
	                       .woocommerce .product .quantity input.qty',
				$woo_products_single_qunatity_style);
		}
		?>
		
		<?php
		$woo_products_single_add_to_cart_style = array();
		$woo_products_single_add_to_cart_hover_style = array();
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_font_family']) && $stockholm_options['woo_products_single_add_to_cart_font_family'] !== '-1') {
			$woo_products_single_add_to_cart_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_single_add_to_cart_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_font_size']) && $stockholm_options['woo_products_single_add_to_cart_font_size'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'font-size: '.intval($stockholm_options['woo_products_single_add_to_cart_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_line_height']) && $stockholm_options['woo_products_single_add_to_cart_line_height'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'line-height: '.intval($stockholm_options['woo_products_single_add_to_cart_line_height']).'px';
			$woo_products_single_add_to_cart_style[] = 'height: '.intval($stockholm_options['woo_products_single_add_to_cart_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_letter_spacing']) && $stockholm_options['woo_products_single_add_to_cart_letter_spacing'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_single_add_to_cart_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_font_weight']) && $stockholm_options['woo_products_single_add_to_cart_font_weight'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_font_style']) && $stockholm_options['woo_products_single_add_to_cart_font_style'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_text_transform']) && $stockholm_options['woo_products_single_add_to_cart_text_transform'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_color']) && !empty($stockholm_options['woo_products_single_add_to_cart_color'])) {
			$woo_products_single_add_to_cart_style[] = 'color: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_color']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_background_color']) && !empty($stockholm_options['woo_products_single_add_to_cart_background_color'])) {
			$woo_products_single_add_to_cart_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_background_color']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_border_width']) && $stockholm_options['woo_products_single_add_to_cart_border_width'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'border-width: '.intval($stockholm_options['woo_products_single_add_to_cart_border_width']).'px';
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_border_color']) && !empty($stockholm_options['woo_products_single_add_to_cart_border_color'])) {
			$woo_products_single_add_to_cart_style[] = 'border-color: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_border_color']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_border_radius']) && $stockholm_options['woo_products_single_add_to_cart_border_radius'] !== '') {
			$woo_products_single_add_to_cart_style[] = 'border-radius: '.intval($stockholm_options['woo_products_single_add_to_cart_border_radius']).'px';
		}
		
		if(is_array($woo_products_single_add_to_cart_style) && count($woo_products_single_add_to_cart_style)) { ?>
			.woocommerce.single-product button.single_add_to_cart_button,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a{
			<?php echo implode(';', $woo_products_single_add_to_cart_style); ?>
			}
			
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a:after,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:after,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:after{
			<?php
			if(isset($stockholm_options['woo_products_single_add_to_cart_line_height']) && $stockholm_options['woo_products_single_add_to_cart_line_height'] !== '') {
				echo 'line-height:'.intval(($stockholm_options['woo_products_single_add_to_cart_line_height']-3)).'px';
			}
			?>
			}
		<?php }
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_hover_color']) && !empty($stockholm_options['woo_products_single_add_to_cart_hover_color'])) {
			$woo_products_single_add_to_cart_hover_style[] = 'color: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_hover_color']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_background_hover_color']) && !empty($stockholm_options['woo_products_single_add_to_cart_background_hover_color'])) {
			$woo_products_single_add_to_cart_hover_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_background_hover_color']);
		}
		
		if(isset($stockholm_options['woo_products_single_add_to_cart_border_hover_color']) && !empty($stockholm_options['woo_products_single_add_to_cart_border_hover_color'])) {
			$woo_products_single_add_to_cart_hover_style[] = 'border-color: '.esc_attr($stockholm_options['woo_products_single_add_to_cart_border_hover_color']);
		}
		
		if(is_array($woo_products_single_add_to_cart_hover_style) && count($woo_products_single_add_to_cart_hover_style)) { ?>
			.woocommerce.single-product button.single_add_to_cart_button:hover,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a:hover,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover,
			.product .summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover{
			<?php echo implode(';', $woo_products_single_add_to_cart_hover_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_product_single_price_style = array();
		
		if(isset($stockholm_options['woo_product_single_price_font_family']) && $stockholm_options['woo_product_single_price_font_family'] !== '-1') {
			$woo_product_single_price_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['woo_product_single_price_font_family']).'", sans-serif';
		}
		
		if(isset($stockholm_options['woo_product_single_price_font_size']) && $stockholm_options['woo_product_single_price_font_size'] !== '') {
			$woo_product_single_price_style[] = 'font-size: '.intval($stockholm_options['woo_product_single_price_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_price_line_height']) && $stockholm_options['woo_product_single_price_line_height'] !== '') {
			$woo_product_single_price_style[] = 'line-height: '.intval($stockholm_options['woo_product_single_price_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_price_letter_spacing']) && $stockholm_options['woo_product_single_price_letter_spacing'] !== '') {
			$woo_product_single_price_style[] = 'letter-spacing: '.intval($stockholm_options['woo_product_single_price_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_price_font_weight']) && $stockholm_options['woo_product_single_price_font_weight'] !== '') {
			$woo_product_single_price_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_product_single_price_font_weight']);
		}
		
		if(isset($stockholm_options['woo_product_single_price_font_style']) && $stockholm_options['woo_product_single_price_font_style'] !== '') {
			$woo_product_single_price_style[] = 'font-style: '.esc_attr($stockholm_options['woo_product_single_price_font_style']);
		}
		
		if(isset($stockholm_options['woo_product_single_price_text_transform']) && $stockholm_options['woo_product_single_price_text_transform'] !== '') {
			$woo_product_single_price_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_product_single_price_text_transform']);
		}
		
		if(isset($stockholm_options['woo_product_single_price_color']) && $stockholm_options['woo_product_single_price_color'] !== '') {
			$woo_product_single_price_style[] = 'color: '.esc_attr($stockholm_options['woo_product_single_price_color']);
		}
		
		if(is_array($woo_product_single_price_style) && count($woo_product_single_price_style)) { ?>
			.woocommerce div.product .summary p.price span.amount{
			<?php echo implode(';', $woo_product_single_price_style); ?>
			}
		<?php }
		
		if(isset($stockholm_options['woo_product_single_price_old_color']) && !empty($stockholm_options['woo_product_single_price_old_color'])) { ?>
			.woocommerce div.product .summary p.price del,
			.woocommerce div.product .summary p.price del span.amount{
			color: <?php echo esc_attr($stockholm_options['woo_product_single_price_old_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$woo_product_single_related_style = array();
		
		if(isset($stockholm_options['woo_product_single_related_font_family']) && $stockholm_options['woo_product_single_related_font_family'] !== '-1') {
			$woo_product_single_related_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_product_single_related_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_product_single_related_font_size']) && $stockholm_options['woo_product_single_related_font_size'] !== '') {
			$woo_product_single_related_style[] = 'font-size: '.intval($stockholm_options['woo_product_single_related_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_related_line_height']) && $stockholm_options['woo_product_single_related_line_height'] !== '') {
			$woo_product_single_related_style[] = 'line-height: '.intval($stockholm_options['woo_product_single_related_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_related_letter_spacing']) && $stockholm_options['woo_product_single_related_letter_spacing'] !== '') {
			$woo_product_single_related_style[] = 'letter-spacing: '.intval($stockholm_options['woo_product_single_related_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_product_single_related_font_weight']) && $stockholm_options['woo_product_single_related_font_weight'] !== '') {
			$woo_product_single_related_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_product_single_related_font_weight']);
		}
		
		if(isset($stockholm_options['woo_product_single_related_font_style']) && $stockholm_options['woo_product_single_related_font_style'] !== '') {
			$woo_product_single_related_style[] = 'font-style: '.esc_attr($stockholm_options['woo_product_single_related_font_style']);
		}
		
		if(isset($stockholm_options['woo_product_single_related_text_transform']) && $stockholm_options['woo_product_single_related_text_transform'] !== '') {
			$woo_product_single_related_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_product_single_related_text_transform']);
		}
		
		if(isset($stockholm_options['woo_product_single_related_color']) && $stockholm_options['woo_product_single_related_color'] !== '') {
			$woo_product_single_related_style[] = 'color: '.esc_attr($stockholm_options['woo_product_single_related_color']);
		}
		
		if(is_array($woo_product_single_related_style) && count($woo_product_single_related_style)) { ?>
			.woocommerce div.product div.upsells .related-products-title,
			.woocommerce div.product div.related .related-products-title{
			<?php echo implode(';', $woo_product_single_related_style); ?>
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_product_single_disable_tab_content_box']) && $stockholm_options['woo_product_single_disable_tab_content_box'] == "yes") { ?>
			.q_tabs.boxed.woocommerce-tabs .tabs-container,
			.q_tabs.boxed.woocommerce-tabs .tabs-container{
			padding:35px 0;
			background-color: transparent;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_products_category_hide_category']) && $stockholm_options['woo_products_category_hide_category'] == "yes") { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top .qodef-product-list-categories {
			display: none;
			}
		<?php } ?>
		
		<?php if (isset($stockholm_options['woo_product_single_font_size']) && $stockholm_options['woo_product_single_font_size'] != "") { ?>
			.woocommerce.single-product .social_share_list_holder ul li i, .woocommerce-page.single-product .social_share_list_holder ul li i {
			font-size: <?php echo intval($stockholm_options['woo_product_single_font_size']) ?>px;
			line-height: <?php echo intval($stockholm_options['woo_product_single_font_size']) ?>px;
			}
		<?php } ?>
		
		
		<?php
		$woo_products_standard_category_style = array();
		
		if(isset($stockholm_options['woo_products_standard_category_font_family']) && $stockholm_options['woo_products_standard_category_font_family'] !== '-1') {
			$woo_products_standard_category_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_standard_category_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_standard_category_font_size']) && $stockholm_options['woo_products_standard_category_font_size'] !== '') {
			$woo_products_standard_category_style[] = 'font-size: '.intval($stockholm_options['woo_products_standard_category_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_category_line_height']) && $stockholm_options['woo_products_standard_category_line_height'] !== '') {
			$woo_products_standard_category_style[] = 'line-height: '.intval($stockholm_options['woo_products_standard_category_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_category_letter_spacing']) && $stockholm_options['woo_products_standard_category_letter_spacing'] !== '') {
			$woo_products_standard_category_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_standard_category_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_category_font_weight']) && $stockholm_options['woo_products_standard_category_font_weight'] !== '') {
			$woo_products_standard_category_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_standard_category_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_standard_category_font_style']) && $stockholm_options['woo_products_standard_category_font_style'] !== '') {
			$woo_products_standard_category_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_standard_category_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_standard_category_text_transform']) && $stockholm_options['woo_products_standard_category_text_transform'] !== '') {
			$woo_products_standard_category_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_standard_category_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_standard_category_color']) && $stockholm_options['woo_products_standard_category_color'] !== '') {
			$woo_products_standard_category_style[] = 'color: '.esc_attr($stockholm_options['woo_products_standard_category_color']);
		}
		
		if(is_array($woo_products_standard_category_style) && count($woo_products_standard_category_style)) { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top .qodef-product-list-categories a{
			<?php echo implode(';', $woo_products_standard_category_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_products_standard_title_style = array();
		
		if(isset($stockholm_options['woo_products_standard_title_font_family']) && $stockholm_options['woo_products_standard_title_font_family'] !== '-1') {
			$woo_products_standard_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_standard_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_standard_title_font_size']) && $stockholm_options['woo_products_standard_title_font_size'] !== '') {
			$woo_products_standard_title_style[] = 'font-size: '.intval($stockholm_options['woo_products_standard_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_title_line_height']) && $stockholm_options['woo_products_standard_title_line_height'] !== '') {
			$woo_products_standard_title_style[] = 'line-height: '.intval($stockholm_options['woo_products_standard_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_title_letter_spacing']) && $stockholm_options['woo_products_standard_title_letter_spacing'] !== '') {
			$woo_products_standard_title_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_standard_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_title_font_weight']) && $stockholm_options['woo_products_standard_title_font_weight'] !== '') {
			$woo_products_standard_title_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_standard_title_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_standard_title_font_style']) && $stockholm_options['woo_products_standard_title_font_style'] !== '') {
			$woo_products_standard_title_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_standard_title_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_standard_title_text_transform']) && $stockholm_options['woo_products_standard_title_text_transform'] !== '') {
			$woo_products_standard_title_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_standard_title_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_standard_title_color']) && $stockholm_options['woo_products_standard_title_color'] !== '') {
			$woo_products_standard_title_style[] = 'color: '.esc_attr($stockholm_options['woo_products_standard_title_color']);
		}
		
		if(is_array($woo_products_standard_title_style) && count($woo_products_standard_title_style)) { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top .qodef-product-standard-title a{
			<?php echo implode(';', $woo_products_standard_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['woo_products_standard_title_hover_color']) && !empty($stockholm_options['woo_products_standard_title_hover_color'])) { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top h3:hover{
			color: <?php echo esc_attr($stockholm_options['woo_products_standard_title_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$woo_products_standard_price_style = array();
		
		if(isset($stockholm_options['woo_products_standard_price_font_family']) && $stockholm_options['woo_products_standard_price_font_family'] !== '-1') {
			$woo_products_standard_price_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['woo_products_standard_price_font_family']).'", sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_standard_price_font_size']) && $stockholm_options['woo_products_standard_price_font_size'] !== '') {
			$woo_products_standard_price_style[] = 'font-size: '.intval($stockholm_options['woo_products_standard_price_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_price_line_height']) && $stockholm_options['woo_products_standard_price_line_height'] !== '') {
			$woo_products_standard_price_style[] = 'line-height: '.intval($stockholm_options['woo_products_standard_price_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_price_letter_spacing']) && $stockholm_options['woo_products_standard_price_letter_spacing'] !== '') {
			$woo_products_standard_price_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_standard_price_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_price_font_weight']) && $stockholm_options['woo_products_standard_price_font_weight'] !== '') {
			$woo_products_standard_price_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_standard_price_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_standard_price_font_style']) && $stockholm_options['woo_products_standard_price_font_style'] !== '') {
			$woo_products_standard_price_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_standard_price_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_standard_price_text_transform']) && $stockholm_options['woo_products_standard_price_text_transform'] !== '') {
			$woo_products_standard_price_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_standard_price_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_standard_price_color']) && $stockholm_options['woo_products_standard_price_color'] !== '') {
			$woo_products_standard_price_style[] = 'color: '.esc_attr($stockholm_options['woo_products_standard_price_color']);
			?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top .price ins .amount {
			color: <?php echo esc_attr($stockholm_options['woo_products_standard_price_color']); ?>
			}
			<?php
		}
		
		if(isset($stockholm_options['woo_products_standard_price_old_color']) && $stockholm_options['woo_products_standard_price_old_color'] !== '') { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top .price del,
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top .price del .amount {
			color: <?php echo esc_attr($stockholm_options['woo_products_standard_price_old_color']); ?>
			}
		<?php }
		
		if(is_array($woo_products_standard_price_style) && count($woo_products_standard_price_style)) { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-info-top .price{
			<?php echo implode(';', $woo_products_standard_price_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_products_standard_list_add_to_cart_style = array();
		$woo_products_standard_list_add_to_cart_hover_style = array();
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_font_family']) && $stockholm_options['woo_products_standard_list_add_to_cart_font_family'] !== '-1') {
			$woo_products_standard_list_add_to_cart_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_standard_list_add_to_cart_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_font_size']) && $stockholm_options['woo_products_standard_list_add_to_cart_font_size'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'font-size: '.intval($stockholm_options['woo_products_standard_list_add_to_cart_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_line_height']) && $stockholm_options['woo_products_standard_list_add_to_cart_line_height'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'line-height: '.intval($stockholm_options['woo_products_standard_list_add_to_cart_line_height']).'px';
			if(isset($stockholm_options['woo_products_standard_list_add_to_cart_border_width']) && $stockholm_options['woo_products_standard_list_add_to_cart_border_width'] !== '') {
				$height = $stockholm_options['woo_products_standard_list_add_to_cart_line_height'] + 2 * $stockholm_options['woo_products_standard_list_add_to_cart_border_width'];
			}
			else {
				$height = $stockholm_options['woo_products_standard_list_add_to_cart_line_height'] + 2 * 2; /* default border width */
			}
			$woo_products_standard_list_add_to_cart_style[] = 'height: '.intval( $height ).'px';
			$woo_products_standard_list_add_to_cart_style[] = 'bottom: -'.intval( $height ).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_letter_spacing']) && $stockholm_options['woo_products_standard_list_add_to_cart_letter_spacing'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_standard_list_add_to_cart_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_font_weight']) && $stockholm_options['woo_products_standard_list_add_to_cart_font_weight'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_font_style']) && $stockholm_options['woo_products_standard_list_add_to_cart_font_style'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_text_transform']) && $stockholm_options['woo_products_standard_list_add_to_cart_text_transform'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_color']) && !empty($stockholm_options['woo_products_standard_list_add_to_cart_color'])) {
			$woo_products_standard_list_add_to_cart_style[] = 'color: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_color']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_background_color']) && !empty($stockholm_options['woo_products_standard_list_add_to_cart_background_color'])) {
			$woo_products_standard_list_add_to_cart_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_background_color']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_border_width']) && $stockholm_options['woo_products_standard_list_add_to_cart_border_width'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'border-width: '.intval($stockholm_options['woo_products_standard_list_add_to_cart_border_width']).'px';
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_border_color']) && !empty($stockholm_options['woo_products_standard_list_add_to_cart_border_color'])) {
			$woo_products_standard_list_add_to_cart_style[] = 'border-color: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_border_color']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_border_radius']) && $stockholm_options['woo_products_standard_list_add_to_cart_border_radius'] !== '') {
			$woo_products_standard_list_add_to_cart_style[] = 'border-radius: '.intval($stockholm_options['woo_products_standard_list_add_to_cart_border_radius']).'px';
		}
		
		if(is_array($woo_products_standard_list_add_to_cart_style) && count($woo_products_standard_list_add_to_cart_style)) { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-button-holder .add-to-cart-button,
			.woocommerce ul.products.standard li.product .qodef-product-standard-button-holder .added_to_cart{
			<?php echo implode(';', $woo_products_standard_list_add_to_cart_style); ?>
			}
		<?php }
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_hover_color']) && !empty($stockholm_options['woo_products_standard_list_add_to_cart_hover_color'])) {
			$woo_products_standard_list_add_to_cart_hover_style[] = 'color: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_hover_color']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_background_hover_color']) && !empty($stockholm_options['woo_products_standard_list_add_to_cart_background_hover_color'])) {
			$woo_products_standard_list_add_to_cart_hover_style[] = 'background-color: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_background_hover_color']);
		}
		
		if(isset($stockholm_options['woo_products_standard_list_add_to_cart_border_hover_color']) && !empty($stockholm_options['woo_products_standard_list_add_to_cart_border_hover_color'])) {
			$woo_products_standard_list_add_to_cart_hover_style[] = 'border-color: '.esc_attr($stockholm_options['woo_products_standard_list_add_to_cart_border_hover_color']);
		}
		
		if(is_array($woo_products_standard_list_add_to_cart_hover_style) && count($woo_products_standard_list_add_to_cart_hover_style)) { ?>
			.woocommerce ul.products.standard li.product .qodef-product-standard-button-holder .add-to-cart-button:hover,
			.woocommerce ul.products.standard li.product .qodef-product-standard-button-holder .added_to_cart:hover{
			<?php echo implode(';', $woo_products_standard_list_add_to_cart_hover_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_products_simple_title_style = array();
		
		if(isset($stockholm_options['woo_products_simple_title_font_family']) && $stockholm_options['woo_products_simple_title_font_family'] !== '-1') {
			$woo_products_simple_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_products_simple_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_simple_title_font_size']) && $stockholm_options['woo_products_simple_title_font_size'] !== '') {
			$woo_products_simple_title_style[] = 'font-size: '.intval($stockholm_options['woo_products_simple_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_simple_title_line_height']) && $stockholm_options['woo_products_simple_title_line_height'] !== '') {
			$woo_products_simple_title_style[] = 'line-height: '.intval($stockholm_options['woo_products_simple_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_simple_title_letter_spacing']) && $stockholm_options['woo_products_simple_title_letter_spacing'] !== '') {
			$woo_products_simple_title_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_simple_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_simple_title_font_weight']) && $stockholm_options['woo_products_simple_title_font_weight'] !== '') {
			$woo_products_simple_title_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_simple_title_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_simple_title_font_style']) && $stockholm_options['woo_products_simple_title_font_style'] !== '') {
			$woo_products_simple_title_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_simple_title_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_simple_title_text_transform']) && $stockholm_options['woo_products_simple_title_text_transform'] !== '') {
			$woo_products_simple_title_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_simple_title_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_simple_title_color']) && $stockholm_options['woo_products_simple_title_color'] !== '') {
			$woo_products_simple_title_style[] = 'color: '.esc_attr($stockholm_options['woo_products_simple_title_color']);
		}
		
		if(is_array($woo_products_simple_title_style) && count($woo_products_simple_title_style)) { ?>
			.woocommerce ul.products.simple li.product .qodef-product-standard-title a h2{
			<?php echo implode(';', $woo_products_simple_title_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['woo_products_simple_title_hover_color']) && !empty($stockholm_options['woo_products_simple_title_hover_color'])) { ?>
			.woocommerce ul.products.simple li.product .qodef-product-standard-title h3:hover{
			color: <?php echo esc_attr($stockholm_options['woo_products_simple_title_hover_color']); ?>;
			}
		<?php } ?>
		
		<?php
		$woo_products_simple_price_style = array();
		
		if(isset($stockholm_options['woo_products_simple_price_font_family']) && $stockholm_options['woo_products_simple_price_font_family'] !== '-1') {
			$woo_products_simple_price_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['woo_products_simple_price_font_family']).'", sans-serif';
		}
		
		if(isset($stockholm_options['woo_products_simple_price_font_size']) && $stockholm_options['woo_products_simple_price_font_size'] !== '') {
			$woo_products_simple_price_style[] = 'font-size: '.intval($stockholm_options['woo_products_simple_price_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_products_simple_price_line_height']) && $stockholm_options['woo_products_simple_price_line_height'] !== '') {
			$woo_products_simple_price_style[] = 'line-height: '.intval($stockholm_options['woo_products_simple_price_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_products_simple_price_letter_spacing']) && $stockholm_options['woo_products_simple_price_letter_spacing'] !== '') {
			$woo_products_simple_price_style[] = 'letter-spacing: '.intval($stockholm_options['woo_products_simple_price_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_products_simple_price_font_weight']) && $stockholm_options['woo_products_simple_price_font_weight'] !== '') {
			$woo_products_simple_price_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_products_simple_price_font_weight']);
		}
		
		if(isset($stockholm_options['woo_products_simple_price_font_style']) && $stockholm_options['woo_products_simple_price_font_style'] !== '') {
			$woo_products_simple_price_style[] = 'font-style: '.esc_attr($stockholm_options['woo_products_simple_price_font_style']);
		}
		
		if(isset($stockholm_options['woo_products_simple_price_text_transform']) && $stockholm_options['woo_products_simple_price_text_transform'] !== '') {
			$woo_products_simple_price_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_products_simple_price_text_transform']);
		}
		
		if(isset($stockholm_options['woo_products_simple_price_color']) && $stockholm_options['woo_products_simple_price_color'] !== '') {
			$woo_products_simple_price_style[] = 'color: '.esc_attr($stockholm_options['woo_products_simple_price_color']);
			?>
			.woocommerce ul.products.simple li.product .price ins .amount {
			color: <?php echo esc_attr($stockholm_options['woo_products_simple_price_color']); ?>
			}
			<?php
		}
		
		if(isset($stockholm_options['woo_products_simple_price_old_color']) && $stockholm_options['woo_products_simple_price_old_color'] !== '') { ?>
			.woocommerce ul.products.simple li.product .price del,
			.woocommerce ul.products.simple li.product .price del .amount {
			color: <?php echo esc_attr($stockholm_options['woo_products_simple_price_old_color']); ?>
			}
		<?php }
		
		if(is_array($woo_products_simple_price_style) && count($woo_products_simple_price_style)) { ?>
			.woocommerce ul.products.simple li.product .price {
			<?php echo implode(';', $woo_products_simple_price_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_shop_category_showcase_category_title_style = array();
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_font_family']) && $stockholm_options['woo_shop_category_showcase_category_title_font_family'] !== '-1') {
			$woo_shop_category_showcase_category_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_shop_category_showcase_category_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_font_size']) && $stockholm_options['woo_shop_category_showcase_category_title_font_size'] !== '') {
			$woo_shop_category_showcase_category_title_style[] = 'font-size: '.intval($stockholm_options['woo_shop_category_showcase_category_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_line_height']) && $stockholm_options['woo_shop_category_showcase_category_title_line_height'] !== '') {
			$woo_shop_category_showcase_category_title_style[] = 'line-height: '.intval($stockholm_options['woo_shop_category_showcase_category_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_letter_spacing']) && $stockholm_options['woo_shop_category_showcase_category_title_letter_spacing'] !== '') {
			$woo_shop_category_showcase_category_title_style[] = 'letter-spacing: '.intval($stockholm_options['woo_shop_category_showcase_category_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_font_weight']) && $stockholm_options['woo_shop_category_showcase_category_title_font_weight'] !== '') {
			$woo_shop_category_showcase_category_title_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_shop_category_showcase_category_title_font_weight']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_font_style']) && $stockholm_options['woo_shop_category_showcase_category_title_font_style'] !== '') {
			$woo_shop_category_showcase_category_title_style[] = 'font-style: '.esc_attr($stockholm_options['woo_shop_category_showcase_category_title_font_style']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_text_transform']) && $stockholm_options['woo_shop_category_showcase_category_title_text_transform'] !== '') {
			$woo_shop_category_showcase_category_title_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_shop_category_showcase_category_title_text_transform']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_category_title_color']) && $stockholm_options['woo_shop_category_showcase_category_title_color'] !== '') {
			$woo_shop_category_showcase_category_title_style[] = 'color: '.esc_attr($stockholm_options['woo_shop_category_showcase_category_title_color']);
		}
		
		if(is_array($woo_shop_category_showcase_category_title_style) && count($woo_shop_category_showcase_category_title_style)) { ?>
			.qode_shop_category_showcase .qode_category_showcase_category_name {
			<?php echo implode(';', $woo_shop_category_showcase_category_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_shop_category_showcase_product_title_style = array();
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_font_family']) && $stockholm_options['woo_shop_category_showcase_product_title_font_family'] !== '-1') {
			$woo_shop_category_showcase_product_title_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_shop_category_showcase_product_title_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_font_size']) && $stockholm_options['woo_shop_category_showcase_product_title_font_size'] !== '') {
			$woo_shop_category_showcase_product_title_style[] = 'font-size: '.intval($stockholm_options['woo_shop_category_showcase_product_title_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_line_height']) && $stockholm_options['woo_shop_category_showcase_product_title_line_height'] !== '') {
			$woo_shop_category_showcase_product_title_style[] = 'line-height: '.intval($stockholm_options['woo_shop_category_showcase_product_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_letter_spacing']) && $stockholm_options['woo_shop_category_showcase_product_title_letter_spacing'] !== '') {
			$woo_shop_category_showcase_product_title_style[] = 'letter-spacing: '.intval($stockholm_options['woo_shop_category_showcase_product_title_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_font_weight']) && $stockholm_options['woo_shop_category_showcase_product_title_font_weight'] !== '') {
			$woo_shop_category_showcase_product_title_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_title_font_weight']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_font_style']) && $stockholm_options['woo_shop_category_showcase_product_title_font_style'] !== '') {
			$woo_shop_category_showcase_product_title_style[] = 'font-style: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_title_font_style']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_text_transform']) && $stockholm_options['woo_shop_category_showcase_product_title_text_transform'] !== '') {
			$woo_shop_category_showcase_product_title_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_title_text_transform']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_title_color']) && $stockholm_options['woo_shop_category_showcase_product_title_color'] !== '') {
			$woo_shop_category_showcase_product_title_style[] = 'color: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_title_color']);
		}
		
		if(is_array($woo_shop_category_showcase_product_title_style) && count($woo_shop_category_showcase_product_title_style)) { ?>
			.qode_shop_category_showcase .qode_category_showcase_product_title {
			<?php echo implode(';', $woo_shop_category_showcase_product_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$woo_shop_category_showcase_product_price_style = array();
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_font_family']) && $stockholm_options['woo_shop_category_showcase_product_price_font_family'] !== '-1') {
			$woo_shop_category_showcase_product_price_style[] = 'font-family: '.str_replace('+', ' ', $stockholm_options['woo_shop_category_showcase_product_price_font_family']).', sans-serif';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_font_size']) && $stockholm_options['woo_shop_category_showcase_product_price_font_size'] !== '') {
			$woo_shop_category_showcase_product_price_style[] = 'font-size: '.intval($stockholm_options['woo_shop_category_showcase_product_price_font_size']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_line_height']) && $stockholm_options['woo_shop_category_showcase_product_price_line_height'] !== '') {
			$woo_shop_category_showcase_product_price_style[] = 'line-height: '.intval($stockholm_options['woo_shop_category_showcase_product_price_line_height']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_letter_spacing']) && $stockholm_options['woo_shop_category_showcase_product_price_letter_spacing'] !== '') {
			$woo_shop_category_showcase_product_price_style[] = 'letter-spacing: '.intval($stockholm_options['woo_shop_category_showcase_product_price_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_font_weight']) && $stockholm_options['woo_shop_category_showcase_product_price_font_weight'] !== '') {
			$woo_shop_category_showcase_product_price_style[] = 'font-weight: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_price_font_weight']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_font_style']) && $stockholm_options['woo_shop_category_showcase_product_price_font_style'] !== '') {
			$woo_shop_category_showcase_product_price_style[] = 'font-style: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_price_font_style']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_text_transform']) && $stockholm_options['woo_shop_category_showcase_product_price_text_transform'] !== '') {
			$woo_shop_category_showcase_product_price_style[] = 'text-transform: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_price_text_transform']);
		}
		
		if(isset($stockholm_options['woo_shop_category_showcase_product_price_color']) && $stockholm_options['woo_shop_category_showcase_product_price_color'] !== '') {
			$woo_shop_category_showcase_product_price_style[] = 'color: '.esc_attr($stockholm_options['woo_shop_category_showcase_product_price_color']);
		}
		
		if(is_array($woo_shop_category_showcase_product_price_style) && count($woo_shop_category_showcase_product_price_style)) { ?>
			.qode_shop_category_showcase .qode_category_showcase_product_price {
			<?php echo implode(';', $woo_shop_category_showcase_product_price_style); ?>
			}
		<?php } ?>
		
		<?php
		$gf_title_style = array();
		
		if(isset($stockholm_options['gf_title_font_family']) && $stockholm_options['gf_title_font_family'] !== '-1') {
			$gf_title_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['gf_title_font_family']).'", sans-serif';
		}
		
		if(isset($stockholm_options['gf_title_font_size']) && $stockholm_options['gf_title_font_size'] !== '') {
			$gf_title_style[] = 'font-size: '.intval($stockholm_options['gf_title_font_size']).'px !important';
		}
		
		if(isset($stockholm_options['gf_title_line_height']) && $stockholm_options['gf_title_line_height'] !== '') {
			$gf_title_style[] = 'line-height: '.intval($stockholm_options['gf_title_line_height']).'px';
		}
		
		if(isset($stockholm_options['gf_title_letter_spacing']) && $stockholm_options['gf_title_letter_spacing'] !== '') {
			$gf_title_style[] = 'letter-spacing: '.intval($stockholm_options['gf_title_letter_spacing']).'px !important';
		}
		
		if(isset($stockholm_options['gf_title_font_weight']) && $stockholm_options['gf_title_font_weight'] !== '') {
			$gf_title_style[] = 'font-weight: '.esc_attr($stockholm_options['gf_title_font_weight']).'!important';
		}
		
		if(isset($stockholm_options['gf_title_font_style']) && $stockholm_options['gf_title_font_style'] !== '') {
			$gf_title_style[] = 'font-style: '.esc_attr($stockholm_options['gf_title_font_style']);
		}
		
		if(isset($stockholm_options['gf_title_text_transform']) && $stockholm_options['gf_title_text_transform'] !== '') {
			$gf_title_style[] = 'text-transform: '.esc_attr($stockholm_options['gf_title_text_transform']);
		}
		
		if(isset($stockholm_options['gf_title_color']) && $stockholm_options['gf_title_color'] !== '') {
			$gf_title_style[] = 'color: '.esc_attr($stockholm_options['gf_title_color']);
		}
		
		if(is_array($gf_title_style) && count($gf_title_style)) { ?>
			.gform_wrapper .gsection .gfield_label,.gform_wrapper h2.gsection_title,.gform_wrapper h3.gform_title{
			<?php echo implode(';', $gf_title_style); ?>
			}
		<?php } ?>
		
		<?php
		$gf_label_style = array();
		
		if(isset($stockholm_options['gf_label_font_family']) && $stockholm_options['gf_label_font_family'] !== '-1') {
			$gf_label_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['gf_label_font_family']).'", sans-serif';
		}
		
		if(isset($stockholm_options['gf_label_font_size']) && $stockholm_options['gf_label_font_size'] !== '') {
			$gf_label_style[] = 'font-size: '.intval($stockholm_options['gf_label_font_size']).'px';
		}
		
		if(isset($stockholm_options['gf_label_line_height']) && $stockholm_options['gf_label_line_height'] !== '') {
			$gf_label_style[] = 'line-height: '.intval($stockholm_options['gf_label_line_height']).'px !important';
		}
		
		if(isset($stockholm_options['gf_label_letter_spacing']) && $stockholm_options['gf_label_letter_spacing'] !== '') {
			$gf_label_style[] = 'letter-spacing: '.intval($stockholm_options['gf_label_letter_spacing']).'px';
		}
		
		if(isset($stockholm_options['gf_label_font_weight']) && $stockholm_options['gf_label_font_weight'] !== '') {
			$gf_label_style[] = 'font-weight: '.esc_attr($stockholm_options['gf_label_font_weight']).'!important';
		}
		
		if(isset($stockholm_options['gf_label_font_style']) && $stockholm_options['gf_label_font_style'] !== '') {
			$gf_label_style[] = 'font-style: '.esc_attr($stockholm_options['gf_label_font_style']);
		}
		
		if(isset($stockholm_options['gf_label_text_transform']) && $stockholm_options['gf_label_text_transform'] !== '') {
			$gf_label_style[] = 'text-transform: '.esc_attr($stockholm_options['gf_label_text_transform']);
		}
		
		if(isset($stockholm_options['gf_label_color']) && $stockholm_options['gf_label_color'] !== '') {
			$gf_label_style[] = 'color: '.esc_attr($stockholm_options['gf_label_color']);
		}
		
		if(is_array($gf_label_style) && count($gf_label_style)) { ?>
			.gform_wrapper .top_label .gfield_label{
			<?php echo implode(';', $gf_label_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['gf_label_require_color']) && $stockholm_options['gf_label_require_color'] !== '') { ?>
			.gform_wrapper .gfield_required{
			color: <?php echo esc_attr($stockholm_options['gf_label_require_color']); ?> !important;
			}
		<?php } ?>
		
		<?php
		$gf_description_style = array();
		
		if(isset($stockholm_options['gf_description_font_family']) && $stockholm_options['gf_description_font_family'] !== '-1') {
			$gf_description_style[] = 'font-family: "'.str_replace('+', ' ', $stockholm_options['gf_description_font_family']).'", sans-serif !important';
		}
		
		if(isset($stockholm_options['gf_description_font_size']) && $stockholm_options['gf_description_font_size'] !== '') {
			$gf_description_style[] = 'font-size: '.intval($stockholm_options['gf_description_font_size']).'px !important';
		}
		
		if(isset($stockholm_options['gf_description_line_height']) && $stockholm_options['gf_description_line_height'] !== '') {
			$gf_description_style[] = 'line-height: '.intval($stockholm_options['gf_description_line_height']).'px !important';
		}
		
		if(isset($stockholm_options['gf_description_letter_spacing']) && $stockholm_options['gf_description_letter_spacing'] !== '') {
			$gf_description_style[] = 'letter-spacing: '.intval($stockholm_options['gf_description_letter_spacing']).'px !important';
		}
		
		if(isset($stockholm_options['gf_description_font_weight']) && $stockholm_options['gf_description_font_weight'] !== '') {
			$gf_description_style[] = 'font-weight: '.esc_attr($stockholm_options['gf_description_font_weight']).'!important';
		}
		
		if(isset($stockholm_options['gf_description_font_style']) && $stockholm_options['gf_description_font_style'] !== '') {
			$gf_description_style[] = 'font-style: '.esc_attr($stockholm_options['gf_description_font_style']).'!important';
		}
		
		if(isset($stockholm_options['gf_description_text_transform']) && $stockholm_options['gf_description_text_transform'] !== '') {
			$gf_description_style[] = 'text-transform: '.esc_attr($stockholm_options['gf_description_text_transform']).'!important';
		}
		
		if(isset($stockholm_options['gf_description_color']) && $stockholm_options['gf_description_color'] !== '') {
			$gf_description_style[] = 'color: '.esc_attr($stockholm_options['gf_description_color']).'!important';
		}
		
		if(is_array($gf_description_style) && count($gf_description_style)) { ?>
			.gform_wrapper .description,.gform_wrapper .gfield_description,.gform_wrapper .gsection_description,.gform_wrapper .instruction,.gform_wrapper .ginput_complex label{
			<?php echo implode(';', $gf_description_style); ?>
			}
		<?php } ?>
		
		<?php
		$gf_button_style = array();
		$gf_button_hover_style = array();
		
		if(isset($stockholm_options['gf_button_text_color']) && !empty($stockholm_options['gf_button_text_color'])) {
			$gf_button_style[] = 'color: '.esc_attr($stockholm_options['gf_button_text_color']);
		}
		
		if(isset($stockholm_options['gf_button_background_color']) && !empty($stockholm_options['gf_button_background_color'])) {
			$gf_button_style[] = 'background-color: '.esc_attr($stockholm_options['gf_button_background_color']);
		}
		
		if(isset($stockholm_options['gf_button_border_width']) && $stockholm_options['gf_button_border_width'] !== '') {
			$gf_button_style[] = 'border-width: '.intval($stockholm_options['gf_button_border_width']).'px';
		}
		
		if(isset($stockholm_options['gf_button_border_color']) && !empty($stockholm_options['gf_button_border_color'])) {
			$gf_button_style[] = 'border-color: '.esc_attr($stockholm_options['gf_button_border_color']);
		}
		
		if(is_array($gf_button_style) && count($gf_button_style)) { ?>
			.gform_wrapper input[type=button],.gform_wrapper input[type=submit]{
			<?php echo implode(';', $gf_button_style); ?>
			}
		<?php }
		
		if(isset($stockholm_options['gf_button_focus_text_color']) && !empty($stockholm_options['gf_button_focus_text_color'])) {
			$gf_button_hover_style[] = 'color: '.esc_attr($stockholm_options['gf_button_focus_text_color']);
		}
		
		if(isset($stockholm_options['gf_button_focus_background_color']) && !empty($stockholm_options['gf_button_focus_background_color'])) {
			$gf_button_hover_style[] = 'background-color: '.esc_attr($stockholm_options['gf_button_focus_background_color']);
		}
		
		if(isset($stockholm_options['gf_button_focus_border_color']) && !empty($stockholm_options['gf_button_focus_border_color'])) {
			$gf_button_hover_style[] = 'border-color: '.esc_attr($stockholm_options['gf_button_focus_border_color']);
		}
		
		if(is_array($gf_button_hover_style) && count($gf_button_hover_style)) { ?>
			.gform_wrapper input[type=button]:hover,.gform_wrapper input[type=submit]:hover{
			<?php echo implode(';', $gf_button_hover_style); ?>
			}
		<?php } ?>
		
		<?php
		$gf_select_style = array();
		
		if(isset($stockholm_options['gf_select_text_color']) && !empty($stockholm_options['gf_select_text_color'])) {
			$gf_select_style[] = 'color: '.esc_attr($stockholm_options['gf_select_text_color']);
		}
		
		if(isset($stockholm_options['gf_select_background_color']) && !empty($stockholm_options['gf_select_background_color'])) {
			$gf_select_style[] = 'background-color: '.esc_attr($stockholm_options['gf_select_background_color']);
		}
		
		if(isset($stockholm_options['gf_select_border_color']) && !empty($stockholm_options['gf_select_border_color'])) {
			$gf_select_style[] = 'border-color: '.esc_attr($stockholm_options['gf_select_border_color']);
		}
		
		if(is_array($gf_select_style) && count($gf_select_style)) { ?>
			.gform_wrapper select{
			<?php echo implode(';', $gf_select_style); ?>
			}
		<?php } ?>
		
		<?php
		$gf_input_style = array();
		$gf_input_hover_style = array();
		
		if(isset($stockholm_options['gf_input_text_color']) && !empty($stockholm_options['gf_input_text_color'])) {
			$gf_input_style[] = 'color: '.esc_attr($stockholm_options['gf_input_text_color']);
		}
		
		if(isset($stockholm_options['gf_input_background_color']) && !empty($stockholm_options['gf_input_background_color'])) {
			$gf_input_style[] = 'background-color: '.esc_attr($stockholm_options['gf_input_background_color']);
		}
		
		if(isset($stockholm_options['gf_input_border_width']) && $stockholm_options['gf_input_border_width'] !== '') {
			$gf_input_style[] = 'border-width: '.intval($stockholm_options['gf_input_border_width']).'px';
		}
		
		if(isset($stockholm_options['gf_input_border_color']) && !empty($stockholm_options['gf_input_border_color'])) {
			$gf_input_style[] = 'border-color: '.esc_attr($stockholm_options['gf_input_border_color']);
		}
		
		if(is_array($gf_input_style) && count($gf_input_style)) { ?>
			.gform_wrapper input[type=text],.gform_wrapper input[type=url],.gform_wrapper input[type=email],.gform_wrapper input[type=tel],.gform_wrapper input[type=number],.gform_wrapper input[type=password],.gform_wrapper textarea{
			<?php echo implode(';', $gf_input_style); ?>
			}
		<?php }
		
		if(isset($stockholm_options['gf_input_focus_text_color']) && !empty($stockholm_options['gf_input_focus_text_color'])) {
			$gf_input_hover_style[] = 'color: '.esc_attr($stockholm_options['gf_input_focus_text_color']);
		}
		
		if(isset($stockholm_options['gf_input_focus_background_color']) && !empty($stockholm_options['gf_input_focus_background_color'])) {
			$gf_input_hover_style[] = 'background-color: '.esc_attr($stockholm_options['gf_input_focus_background_color']);
		}
		
		if(isset($stockholm_options['gf_input_focus_border_color']) && !empty($stockholm_options['gf_input_focus_border_color'])) {
			$gf_input_hover_style[] = 'border-color: '.esc_attr($stockholm_options['gf_input_focus_border_color']);
		}
		
		if(is_array($gf_input_hover_style) && count($gf_input_hover_style)) { ?>
			.gform_wrapper input[type=text]:focus,.gform_wrapper input[type=url]:focus,.gform_wrapper input[type=email]:focus,.gform_wrapper input[type=tel]:focus,.gform_wrapper input[type=number]:focus,.gform_wrapper input[type=password]:focus{
			<?php echo implode(';', $gf_input_hover_style); ?>
			}
		<?php } ?>
		
		<?php if(isset($stockholm_options['gf_input_focus_text_color']) && !empty($stockholm_options['gf_input_focus_text_color'])){ ?>
			.gform_wrapper input[type=text]:focus::-webkit-input-placeholder,
			.gform_wrapper input[type=text]:focus::-webkit-input-placeholder,
			.gform_wrapper input[type=text]:focus::-webkit-input-placeholder,
			.gform_wrapper input[type=text]:focus::-webkit-input-placeholder,
			.gform_wrapper input[type=text]:focus::-webkit-input-placeholder,
			.gform_wrapper input[type=text]:focus::-webkit-input-placeholder,
			.gform_wrapper textarea:focus::-webkit-input-placeholder{
			color: <?php echo esc_attr($stockholm_options['gf_input_focus_text_color']); ?>;
			}
			
			.gform_wrapper input[type=text]:focus:-moz-placeholder,
			.gform_wrapper input[type=text]:focus:-moz-placeholder,
			.gform_wrapper input[type=text]:focus:-moz-placeholder,
			.gform_wrapper input[type=text]:focus:-moz-placeholder,
			.gform_wrapper input[type=text]:focus:-moz-placeholder,
			.gform_wrapper input[type=text]:focus:-moz-placeholder,
			.gform_wrapper textarea:focus:-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['gf_input_focus_text_color']); ?>;
			}
			
			.gform_wrapper input[type=text]:focus::-moz-placeholder,
			.gform_wrapper input[type=text]:focus::-moz-placeholder,
			.gform_wrapper input[type=text]:focus::-moz-placeholder,
			.gform_wrapper input[type=text]:focus::-moz-placeholder,
			.gform_wrapper input[type=text]:focus::-moz-placeholder,
			.gform_wrapper input[type=text]:focus::-moz-placeholder,
			.gform_wrapper textarea:focus::-moz-placeholder{
			color: <?php echo esc_attr($stockholm_options['gf_input_focus_text_color']); ?>;
			}
			
			.gform_wrapper input[type=text]:focus:-ms-input-placeholder,
			.gform_wrapper input[type=text]:focus:-ms-input-placeholder,
			.gform_wrapper input[type=text]:focus:-ms-input-placeholder,
			.gform_wrapper input[type=text]:focus:-ms-input-placeholder,
			.gform_wrapper input[type=text]:focus:-ms-input-placeholder,
			.gform_wrapper input[type=text]:focus:-ms-input-placeholder,
			.gform_wrapper textarea:focus:-ms-input-placeholder{
			color: <?php echo esc_attr($stockholm_options['gf_input_focus_text_color']); ?>;
			}
		<?php } ?>
		
		<?php if(stockholm_qode_vc_grid_elements_enabled()) {
			
			$vc_grid_read_more_styles = array();
			$vc_grid_read_more_hover_styles = array();
			
			if(isset($stockholm_options['vc_grid_button_title_color']) && $stockholm_options['vc_grid_button_title_color'] !== '') {
				$vc_grid_read_more_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_button_title_color'] ).' !important';
			}
			
			if(isset($stockholm_options['vc_grid_button_title_fontsize']) && $stockholm_options['vc_grid_button_title_fontsize'] !== '') {
				$vc_grid_read_more_styles[] = 'font-size: '.intval($stockholm_options['vc_grid_button_title_fontsize']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_button_title_lineheight']) && $stockholm_options['vc_grid_button_title_lineheight'] !== '') {
				$vc_grid_read_more_styles[] = 'line-height: '.intval($stockholm_options['vc_grid_button_title_lineheight']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_button_title_letter_spacing']) && $stockholm_options['vc_grid_button_title_letter_spacing'] !== '') {
				$vc_grid_read_more_styles[] = 'letter-spacing: '.intval($stockholm_options['vc_grid_button_title_letter_spacing']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_button_title_fontstyle']) && $stockholm_options['vc_grid_button_title_fontstyle'] !== '') {
				$vc_grid_read_more_styles[] = 'font-style: '.esc_attr($stockholm_options['vc_grid_button_title_fontstyle']);
			}
			
			if(isset($stockholm_options['vc_grid_button_title_fontweight']) && $stockholm_options['vc_grid_button_title_fontweight'] !== '') {
				$vc_grid_read_more_styles[] = 'font-weight: '.esc_attr($stockholm_options['vc_grid_button_title_fontweight']);
			}
			
			if(isset($stockholm_options['vc_grid_button_title_google_fonts']) && $stockholm_options['vc_grid_button_title_google_fonts'] !== '-1') {
				$vc_grid_read_more_styles[] = 'font-family: ' . str_replace('+', ' ', $stockholm_options['vc_grid_button_title_google_fonts']) . ', sans-serif';
			}
			
			if(isset($stockholm_options['vc_grid_button_backgroundcolor']) && $stockholm_options['vc_grid_button_backgroundcolor'] !== '') {
				$vc_grid_read_more_styles[] = 'background-color: ' .$stockholm_options['vc_grid_button_backgroundcolor'];
			}
			
			if(isset($stockholm_options['vc_grid_button_border_color']) && $stockholm_options['vc_grid_button_border_color'] !== '') {
				$vc_grid_read_more_styles[] = 'border-color: ' .$stockholm_options['vc_grid_button_border_color'];
			}
			
			if(isset($stockholm_options['vc_grid_button_border_width']) && $stockholm_options['vc_grid_button_border_width'] !== '') {
				$vc_grid_read_more_styles[] = 'border-width: ' .$stockholm_options['vc_grid_button_border_width'].'px';
			}
			
			if(isset($stockholm_options['vc_grid_button_border_radius']) && $stockholm_options['vc_grid_button_border_radius'] !== '') {
				$vc_grid_read_more_styles[] = 'border-radius: ' .$stockholm_options['vc_grid_button_border_radius'].'px';
			}
			
			if(isset($stockholm_options['vc_grid_button_title_hovercolor']) && $stockholm_options['vc_grid_button_title_hovercolor'] !== '') {
				$vc_grid_read_more_hover_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_button_title_hovercolor'] ).' !important';
			}
			
			if(isset($stockholm_options['vc_grid_button_backgroundcolor_hover']) && $stockholm_options['vc_grid_button_backgroundcolor_hover'] !== '') {
				$vc_grid_read_more_hover_styles[] = 'background-color: '.esc_attr($stockholm_options['vc_grid_button_backgroundcolor_hover']);
			}
			
			if(isset($stockholm_options['vc_grid_button_border_hover_color']) && $stockholm_options['vc_grid_button_border_hover_color'] !== '') {
				$vc_grid_read_more_hover_styles[] = 'border-color: '.esc_attr($stockholm_options['vc_grid_button_border_hover_color']);
			}
			
			
			if(is_array($vc_grid_read_more_styles) && count($vc_grid_read_more_styles)) { ?>
				.vc_grid-container .vc_row.vc_grid .vc_grid-item .vc_btn {
				<?php echo esc_attr(implode(';', $vc_grid_read_more_styles)); ?>
				}
			<?php }
			
			if(is_array($vc_grid_read_more_hover_styles) && count($vc_grid_read_more_hover_styles)) { ?>
				.vc_grid-container .vc_row.vc_grid .vc_grid-item .vc_btn:hover {
				<?php echo esc_attr(implode(';', $vc_grid_read_more_hover_styles)); ?>
				}
			<?php }
			
			$vc_grid_load_more_styles = array();
			$vc_grid_load_more_hover_styles = array();
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_color']) && $stockholm_options['vc_grid_load_more_button_title_color'] !== '') {
				$vc_grid_load_more_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_load_more_button_title_color'] ).' !important';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_google_fonts']) && $stockholm_options['vc_grid_load_more_button_title_google_fonts'] !== '-1') {
				$vc_grid_load_more_styles[] = 'font-family: '. str_replace('+', ' ', $stockholm_options['vc_grid_load_more_button_title_google_fonts']) .', sans-serif';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_fontsize']) && $stockholm_options['vc_grid_load_more_button_title_fontsize'] !== '') {
				$vc_grid_load_more_styles[] = 'font-size: '.intval( $stockholm_options['vc_grid_load_more_button_title_fontsize']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_lineheight']) && $stockholm_options['vc_grid_load_more_button_title_lineheight'] !== '') {
				$vc_grid_load_more_styles[] = 'line-height: '.intval( $stockholm_options['vc_grid_load_more_button_title_lineheight']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_fontstyle']) && $stockholm_options['vc_grid_load_more_button_title_fontstyle'] !== '') {
				$vc_grid_load_more_styles[] = 'font-style: '.esc_attr( $stockholm_options['vc_grid_load_more_button_title_fontstyle']);
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_fontweight']) && $stockholm_options['vc_grid_load_more_button_title_fontweight'] !== '') {
				$vc_grid_load_more_styles[] = 'font-weight: '.esc_attr( $stockholm_options['vc_grid_load_more_button_title_fontweight']);
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_letter_spacing']) && $stockholm_options['vc_grid_load_more_button_title_letter_spacing'] !== '') {
				$vc_grid_load_more_styles[] = 'letter-spacing: '.intval( $stockholm_options['vc_grid_load_more_button_title_letter_spacing']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_backgroundcolor']) && $stockholm_options['vc_grid_load_more_button_backgroundcolor'] !== '') {
				$vc_grid_load_more_styles[] = 'background-color: '.esc_attr( $stockholm_options['vc_grid_load_more_button_backgroundcolor']);
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_border_color']) && $stockholm_options['vc_grid_load_more_button_border_color'] !== '') {
				$vc_grid_load_more_styles[] = 'border-color: '.esc_attr( $stockholm_options['vc_grid_load_more_button_border_color']);
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_border_width']) && $stockholm_options['vc_grid_load_more_button_border_width'] !== '') {
				$vc_grid_load_more_styles[] = 'border-width: '.intval( $stockholm_options['vc_grid_load_more_button_border_width']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_border_radius']) && $stockholm_options['vc_grid_load_more_button_border_radius'] !== '') {
				$vc_grid_load_more_styles[] = 'border-radius: '.intval( $stockholm_options['vc_grid_load_more_button_border_radius']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_title_hovercolor']) && $stockholm_options['vc_grid_load_more_button_title_hovercolor'] !== '') {
				$vc_grid_load_more_hover_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_load_more_button_title_hovercolor'] ).' !important';
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_backgroundcolor_hover']) && $stockholm_options['vc_grid_load_more_button_backgroundcolor_hover'] !== '') {
				$vc_grid_load_more_hover_styles[] = 'background-color: '.esc_attr( $stockholm_options['vc_grid_load_more_button_backgroundcolor_hover']);
			}
			
			if(isset($stockholm_options['vc_grid_load_more_button_border_hover_color']) && $stockholm_options['vc_grid_load_more_button_border_hover_color'] !== '') {
				$vc_grid_load_more_hover_styles[] = 'border-color: '.esc_attr( $stockholm_options['vc_grid_load_more_button_border_hover_color']);
			}
			
			
			if(is_array($vc_grid_load_more_styles) && count($vc_grid_load_more_styles)) { ?>
				.vc_grid-container .vc_row.vc_grid .vc_pageable-load-more-btn .vc_btn {
				<?php echo esc_attr(implode(';', $vc_grid_load_more_styles)); ?>
				}
			<?php }
			
			if(is_array($vc_grid_load_more_hover_styles) && count($vc_grid_load_more_hover_styles)) { ?>
				.vc_grid-container .vc_row.vc_grid .vc_pageable-load-more-btn .vc_btn:hover {
				<?php echo esc_attr(implode(';', $vc_grid_load_more_hover_styles)); ?>
				}
			<?php }
			
			$vc_grid_pagination_styles = array();
			$vc_grid_pagination_hover_styles = array();
			
			if(isset($stockholm_options['vc_grid_pagination_color']) && $stockholm_options['vc_grid_pagination_color'] !== '') {
				$vc_grid_pagination_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_pagination_color'] . ' !important');
			}
			
			if(isset($stockholm_options['vc_grid_pagination_background_color']) && $stockholm_options['vc_grid_pagination_background_color'] !== '') {
				$vc_grid_pagination_styles[] = 'background-color: '.esc_attr($stockholm_options['vc_grid_pagination_background_color'] . ' !important');
			}
			
			if(isset($stockholm_options['vc_grid_pagination_border_color']) && $stockholm_options['vc_grid_pagination_border_color'] !== '') {
				$vc_grid_pagination_styles[] = 'border-color: '.esc_attr($stockholm_options['vc_grid_pagination_border_color'] . ' !important');
			}
			
			if(isset($stockholm_options['vc_grid_pagination_hover_color']) && $stockholm_options['vc_grid_pagination_hover_color'] !== '') {
				$vc_grid_pagination_hover_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_pagination_hover_color'] . ' !important');
			}
			
			if(isset($stockholm_options['vc_grid_pagination_background_hover_color']) && $stockholm_options['vc_grid_pagination_background_hover_color'] !== '') {
				$vc_grid_pagination_hover_styles[] = 'background-color: '.esc_attr($stockholm_options['vc_grid_pagination_background_hover_color'] . ' !important');
			}
			
			if(isset($stockholm_options['vc_grid_pagination_border_hover_color']) && $stockholm_options['vc_grid_pagination_border_hover_color'] !== '') {
				$vc_grid_pagination_hover_styles[] = 'border-color: '.esc_attr($stockholm_options['vc_grid_pagination_border_hover_color'] . ' !important');
			}
			
			if(is_array($vc_grid_pagination_styles) && count($vc_grid_pagination_styles)) { ?>
				.vc_grid-container .vc_grid-pagination .vc_grid-pagination-list > li > a,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-square_dots .vc_grid-owl-dot span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-radio_dots .vc_grid-owl-dot span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-point_dots .vc_grid-owl-dot span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-fill_square_dots .vc_grid-owl-dot span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-round_fill_square_dots .vc_grid-owl-dot span {
				<?php echo esc_attr(implode(';', $vc_grid_pagination_styles)); ?>
				}
			<?php }
			
			if(is_array($vc_grid_pagination_hover_styles) && count($vc_grid_pagination_hover_styles)) { ?>
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-square_dots .vc_grid-owl-dot span:hover,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-square_dots .vc_grid-owl-dot.active span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-radio_dots .vc_grid-owl-dot span:hover,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-radio_dots .vc_grid-owl-dot.active span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-point_dots .vc_grid-owl-dot span:hover,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-point_dots .vc_grid-owl-dot.active span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-fill_square_dots .vc_grid-owl-dot span:hover,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-fill_square_dots .vc_grid-owl-dot.active span,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-round_fill_square_dots .vc_grid-owl-dot span:hover,
				.vc_grid-container .vc_grid.vc_grid-owl-theme .vc_grid-owl-dots.vc_grid-round_fill_square_dots .vc_grid-owl-dot.active span,
				.vc_grid-container .vc_grid-pagination .vc_grid-pagination-list > li > a:hover,
				.vc_grid-container .vc_grid-pagination .vc_grid-pagination-list > li.vc_grid-active > a {
				<?php echo esc_attr(implode(';', $vc_grid_pagination_hover_styles)); ?>
				}
			<?php }
			
			$vc_grid_filter_styles = array();
			$vc_grid_filter_hover_styles = array();
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_color']) && $stockholm_options['vc_grid_portfolio_filter_color'] !== '') {
				$vc_grid_filter_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_portfolio_filter_color']);
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_hovercolor']) && $stockholm_options['vc_grid_portfolio_filter_hovercolor'] !== '') {
				$vc_grid_filter_hover_styles[] = 'color: '.esc_attr($stockholm_options['vc_grid_portfolio_filter_hovercolor']);
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_font_size']) && $stockholm_options['vc_grid_portfolio_filter_font_size'] !== '') {
				$vc_grid_filter_styles[] = 'font-size: '.intval($stockholm_options['vc_grid_portfolio_filter_font_size']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_line_height']) && $stockholm_options['vc_grid_portfolio_filter_line_height'] !== '') {
				$vc_grid_filter_styles[] = 'line-height: '.intval($stockholm_options['vc_grid_portfolio_filter_line_height']).'px';
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_text_transform']) && $stockholm_options['vc_grid_portfolio_filter_text_transform'] !== '') {
				$vc_grid_filter_styles[] = 'text-transform: '.esc_attr($stockholm_options['vc_grid_portfolio_filter_text_transform']);
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_font_family']) && $stockholm_options['vc_grid_portfolio_filter_font_family'] !== '-1') {
				$vc_grid_filter_styles[] = 'font-family: ' . str_replace('+', ' ', $stockholm_options['vc_grid_portfolio_filter_font_family']) . ', sans-serif';
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_font_style']) && $stockholm_options['vc_grid_portfolio_filter_font_style'] !== '') {
				$vc_grid_filter_styles[] = 'font-style: '.esc_attr($stockholm_options['vc_grid_portfolio_filter_font_style']);
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_font_weight']) && $stockholm_options['vc_grid_portfolio_filter_font_weight'] !== '') {
				$vc_grid_filter_styles[] = 'font-weight: '.esc_attr($stockholm_options['vc_grid_portfolio_filter_font_weight']);
			}
			
			if(isset($stockholm_options['vc_grid_portfolio_filter_letter_spacing']) && $stockholm_options['vc_grid_portfolio_filter_letter_spacing'] !== '') {
				$vc_grid_filter_styles[] = 'letter-spacing: '.intval($stockholm_options['vc_grid_portfolio_filter_letter_spacing']).'px';
			}
			
			if(is_array($vc_grid_filter_styles) && count($vc_grid_filter_styles)) { ?>
				.vc_grid-container .vc_grid-filter > .vc_grid-filter-item span {
				<?php echo esc_attr(implode(';', $vc_grid_filter_styles)); ?>
				}
			<?php }
			
			if(is_array($vc_grid_filter_hover_styles) && count($vc_grid_filter_hover_styles)) { ?>
				.vc_grid-container .vc_grid-filter > .vc_grid-filter-item:hover span,
				.vc_grid-container .vc_grid-filter > .vc_grid-filter-item.vc_active span {
				<?php echo esc_attr(implode(';', $vc_grid_filter_hover_styles)); ?>
				}
			<?php } ?>
			
			<?php
			if(isset($stockholm_options['vc_grid_portfolio_filter_margin_bottom']) && $stockholm_options['vc_grid_portfolio_filter_margin_bottom'] !== '') { ?>
				.vc_grid-container .vc_grid-filter {
				margin-bottom: <?php echo intval($stockholm_options['vc_grid_portfolio_filter_margin_bottom']); ?>px;
				}
			<?php } ?>
			
			<?php if (!empty($stockholm_options['vc_grid_arrows_color'])) { ?>
				.vc_grid-container .vc_grid .vc_grid-owl-nav .vc_grid-owl-prev,
				.vc_grid-container .vc_grid .vc_grid-owl-nav .vc_grid-owl-next {
				color: <?php echo esc_attr($stockholm_options['vc_grid_arrows_color']); ?> !important;
				}
			<?php } ?>
		
		<?php } ?>
		
		<?php if(!empty($stockholm_options['header_bottom_lang_items_padding'])) { ?>
			.header_bottom li.menu-item-language > a,
			.header_bottom .wpml-ls-legacy-list-horizontal .wpml-ls-item a  {
			padding-left: <?php echo intval($stockholm_options['header_bottom_lang_items_padding']); ?>px;
			padding-right: <?php echo intval($stockholm_options['header_bottom_lang_items_padding']); ?>px;
			}
		<?php } ?>
		
		<?php
		//Contact Form 7 Custom Styles - start
		
		$cf7_custom_style_1_elements_styles = '';
		$cf7_custom_style_1_color_placeholder = '';
		$cf7_custom_style_1_for_animation_style = '';
		$cf7_custom_style_1_for_animation_holder_style = '';
		$cf7_custom_style_1_animation_line_style = '';
		if (isset($stockholm_options['cf7_custom_style_1_element_background_color']) && $stockholm_options['cf7_custom_style_1_element_background_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_element_background_transparency']) && $stockholm_options['cf7_custom_style_1_element_background_transparency'] !== '') {
				$cf7_custom_style_1_element_background_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_element_background_color']);
				$cf7_custom_style_1_elements_styles .= 'background-color: rgba(' . esc_attr($cf7_custom_style_1_element_background_color[0]) . ',' . esc_attr($cf7_custom_style_1_element_background_color[1]) . ',' . esc_attr($cf7_custom_style_1_element_background_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_element_background_transparency']) . ');';
			} else {
				$cf7_custom_style_1_elements_styles .= 'background-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_background_color']) . ';';
			}
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_border_color']) && $stockholm_options['cf7_custom_style_1_element_border_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_border_transparency']) && $stockholm_options['cf7_custom_style_1_border_transparency'] !== '') {
				$cf7_custom_style_1_element_border_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_element_border_color']);
				$cf7_custom_style_1_elements_styles .= 'border-color: rgba(' . esc_attr($cf7_custom_style_1_element_border_color[0]) . ',' . esc_attr($cf7_custom_style_1_element_border_color[1]) . ',' . esc_attr($cf7_custom_style_1_element_border_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_border_transparency']) . ');';
			} else {
				$cf7_custom_style_1_elements_styles .= 'border-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_border_color']) . ';';
			}
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_border_width']) && $stockholm_options['cf7_custom_style_1_element_border_width'] !== '') {
			$cf7_custom_style_1_animation_line_style .= 'height: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_border_width']) . 'px;';
			$cf7_custom_style_1_elements_styles .= 'border-width: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_border_width']) . 'px;';
			$cf7_custom_style_1_elements_styles .= 'border-style:solid;';
			$cf7_custom_style_1_elements_styles .= 'transform: translateZ(0);';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_border_radius']) && $stockholm_options['cf7_custom_style_1_element_border_radius'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'border-radius: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_border_radius']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_border_bottom']) && $stockholm_options['cf7_custom_style_1_element_border_bottom'] == 'yes') {
			$cf7_custom_style_1_elements_styles .= 'border-left: none; border-right:none; border-top: none;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_font_color']) && $stockholm_options['cf7_custom_style_1_element_font_color'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_color']) . ';';
			$cf7_custom_style_1_for_animation_style .= 'color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_color']) . ';';
			$cf7_custom_style_1_color_placeholder .= 'color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_color']) . ';';
			$cf7_custom_style_1_color_placeholder .= 'opacity:1;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_font_family']) && $stockholm_options['cf7_custom_style_1_element_font_family'] !== '-1') {
			$cf7_custom_style_1_elements_styles .= 'font-family: ' . str_replace('+', ' ', $stockholm_options['cf7_custom_style_1_element_font_family']) . ';';
			$cf7_custom_style_1_for_animation_style .= 'font-family: ' . str_replace('+', ' ', $stockholm_options['cf7_custom_style_1_element_font_family']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_font_size']) && $stockholm_options['cf7_custom_style_1_element_font_size'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'font-size: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_size']) . 'px;';
			$cf7_custom_style_1_for_animation_style .= 'font-size: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_size']) . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_element_line_height']) && $stockholm_options['cf7_custom_style_1_element_line_height'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'line-height: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_line_height']) . 'px;';
			$cf7_custom_style_1_for_animation_style .= 'line-height: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_line_height']) . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_element_font_style']) && $stockholm_options['cf7_custom_style_1_element_font_style'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'font-style: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_style']) . ';';
			$cf7_custom_style_1_for_animation_style .= 'font-style: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_style']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_font_weight']) && $stockholm_options['cf7_custom_style_1_element_font_weight'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'font-weight: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_weight']) . ';';
			$cf7_custom_style_1_for_animation_style .= 'font-weight: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_weight']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_letter_spacing']) && $stockholm_options['cf7_custom_style_1_element_letter_spacing'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'letter-spacing: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_letter_spacing']) . 'px;';
			$cf7_custom_style_1_for_animation_style .= 'letter-spacing: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_letter_spacing']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_text_transform']) && $stockholm_options['cf7_custom_style_1_element_text_transform'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'text-transform: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_text_transform']) . ';';
			$cf7_custom_style_1_for_animation_style .= 'text-transform: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_text_transform']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_padding_top']) && $stockholm_options['cf7_custom_style_1_element_padding_top'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'padding-top: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_padding_top']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_padding_right']) && $stockholm_options['cf7_custom_style_1_element_padding_right'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'padding-right: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_padding_right']) . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_element_padding_bottom']) && $stockholm_options['cf7_custom_style_1_element_padding_bottom'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'padding-bottom: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_padding_bottom']) . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_element_padding_left']) && $stockholm_options['cf7_custom_style_1_element_padding_left'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'padding-left: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_padding_left']) . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_element_margin_top']) && $stockholm_options['cf7_custom_style_1_element_margin_top'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'margin-top: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_margin_top']) . 'px;';
			$cf7_custom_style_1_for_animation_holder_style .= 'margin-top: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_margin_top']) . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_element_margin_bottom']) && $stockholm_options['cf7_custom_style_1_element_margin_bottom'] !== '') {
			$cf7_custom_style_1_elements_styles .= 'margin-bottom: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_margin_bottom']) . 'px;';
			$cf7_custom_style_1_for_animation_holder_style .= 'margin-bottom: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_margin_bottom']) . 'px;';
			?>.cf7_custom_style_1 span.wpcf7-not-valid-tip{
			top: -<?php echo esc_attr($stockholm_options['cf7_custom_style_1_element_margin_bottom']) . 'px;'; ?>;
			}<?php
		}
		
		$cf7_custom_style_1_elements_focus_styles = '';
		$cf7_custom_style_1_focus_color_placeholder = '';
		$cf7_custom_style_2_for_animation_style = '';
		$cf7_custom_style_1_animation_line_focus_style = '';
		
		if (isset($stockholm_options['cf7_custom_style_1_element_font_focus_color']) && $stockholm_options['cf7_custom_style_1_element_font_focus_color'] !== '') {
			$cf7_custom_style_1_elements_focus_styles .= 'color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_focus_color']) . ';';
			$cf7_custom_style_1_focus_color_placeholder .= 'color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_font_focus_color']) . ' !important;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_focus_background_color']) && $stockholm_options['cf7_custom_style_1_element_focus_background_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_element_focus_background_transparency']) && $stockholm_options['cf7_custom_style_1_element_focus_background_transparency'] !== '') {
				$cf7_custom_style_1_element_focus_background_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_element_focus_background_color']);
				$cf7_custom_style_1_elements_focus_styles .= 'background-color: rgba(' . esc_attr($cf7_custom_style_1_element_focus_background_color[0]) . ',' . esc_attr($cf7_custom_style_1_element_focus_background_color[1]) . ',' . esc_attr($cf7_custom_style_1_element_focus_background_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_element_focus_background_transparency']) . ');';
			} else {
				$cf7_custom_style_1_elements_focus_styles .= 'background-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_focus_background_color']) . ';';
			}
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_element_focus_border_color']) && $stockholm_options['cf7_custom_style_1_element_focus_border_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_focus_border_transparency']) && $stockholm_options['cf7_custom_style_1_focus_border_transparency'] !== '') {
				$cf7_custom_style_1_element_focus_border_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_element_focus_border_color']);
				$cf7_custom_style_1_elements_focus_styles .= 'border-color: rgba(' . esc_attr($cf7_custom_style_1_element_focus_border_color[0]) . ',' . esc_attr($cf7_custom_style_1_element_focus_border_color[1]) . ',' . esc_attr($cf7_custom_style_1_element_focus_border_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_focus_border_transparency']) . ');';
				$cf7_custom_style_1_animation_line_focus_style .= 'border-color: rgba(' . esc_attr($cf7_custom_style_1_element_focus_border_color[0]) . ',' . esc_attr($cf7_custom_style_1_element_focus_border_color[1]) . ',' . esc_attr($cf7_custom_style_1_element_focus_border_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_focus_border_transparency']) . ');';
				
			} else {
				$cf7_custom_style_1_elements_focus_styles .= 'border-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_focus_border_color']) . ';';
				$cf7_custom_style_1_animation_line_focus_style .= 'border-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_element_focus_border_color']) . ';';
			}
		}
		
		
		$cf7_custom_style_1_button_styles = '';
		if (isset($stockholm_options['cf7_custom_style_1_button_background_color']) && $stockholm_options['cf7_custom_style_1_button_background_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_button_background_transparency']) && $stockholm_options['cf7_custom_style_1_button_background_transparency'] !== '') {
				$cf7_custom_style_1_button_background_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_button_background_color']);
				$cf7_custom_style_1_button_styles .= 'background-color: rgba(' . esc_attr($cf7_custom_style_1_button_background_color[0]) . ',' . esc_attr($cf7_custom_style_1_button_background_color[1]) . ',' . esc_attr($cf7_custom_style_1_button_background_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_button_background_transparency']) . ');';
			} else {
				$cf7_custom_style_1_button_styles .= 'background-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_background_color']) . ';';
			}
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_border_color']) && $stockholm_options['cf7_custom_style_1_button_border_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_button_border_transparency']) && $stockholm_options['cf7_custom_style_1_button_border_transparency'] !== '') {
				$cf7_custom_style_1_button_border_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_button_border_color']);
				$cf7_custom_style_1_button_styles .= 'border-color: rgba(' . esc_attr($cf7_custom_style_1_button_border_color[0]) . ',' . esc_attr($cf7_custom_style_1_button_border_color[1]) . ',' . esc_attr($cf7_custom_style_1_button_border_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_button_border_transparency']) . ');';
				
			} else {
				$cf7_custom_style_1_button_styles .= 'border-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_border_color']) . ';';
			}
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_border_width']) && $stockholm_options['cf7_custom_style_1_button_border_width'] !== '') {
			$cf7_custom_style_1_button_styles .= 'border-width: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_border_width']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_border_radius']) && $stockholm_options['cf7_custom_style_1_button_border_radius'] !== '') {
			$cf7_custom_style_1_button_styles .= 'border-radius: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_border_radius']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_font_color']) && $stockholm_options['cf7_custom_style_1_button_font_color'] !== '') {
			$cf7_custom_style_1_button_styles .= 'color: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_font_color']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_font_family']) && $stockholm_options['cf7_custom_style_1_button_font_family'] !== '-1') {
			$cf7_custom_style_1_button_styles .= 'font-family: ' . str_replace('+', ' ', $stockholm_options['cf7_custom_style_1_button_font_family']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_font_size']) && $stockholm_options['cf7_custom_style_1_button_font_size'] !== '') {
			$cf7_custom_style_1_button_styles .= 'font-size: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_font_size']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_font_style']) && $stockholm_options['cf7_custom_style_1_button_font_style'] !== '') {
			$cf7_custom_style_1_button_styles .= 'font-style: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_font_style']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_font_weight']) && $stockholm_options['cf7_custom_style_1_button_font_weight'] !== '') {
			$cf7_custom_style_1_button_styles .= 'font-weight: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_font_weight']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_letter_spacing']) && $stockholm_options['cf7_custom_style_1_button_letter_spacing'] !== '') {
			$cf7_custom_style_1_button_styles .= 'letter-spacing: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_letter_spacing']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_text_transform']) && $stockholm_options['cf7_custom_style_1_button_text_transform'] !== '') {
			$cf7_custom_style_1_button_styles .= 'text-transform: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_text_transform']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_height']) && $stockholm_options['cf7_custom_style_1_button_height'] !== '') {
			$cf7_custom_style_1_button_styles .= 'height: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_height']) . 'px;';
			$cf7_custom_style_1_button_styles .= 'line-height: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_height']) . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_padding']) && $stockholm_options['cf7_custom_style_1_button_padding'] !== '') {
			$cf7_custom_style_1_button_styles .= 'padding-left: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_padding']) . 'px;';
			$cf7_custom_style_1_button_styles .= 'padding-right: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_padding']) . 'px;';
		}
		
		
		if (isset($stockholm_options['cf7_custom_style_1_button_margin']) && $stockholm_options['cf7_custom_style_1_button_margin'] !== '') {
			$cf7_custom_style_1_button_styles .= 'margin-top: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_margin']) . 'px;';
		}
		
		$cf7_custom_style_1_button_hover_styles = '';
		
		if (isset($stockholm_options['cf7_custom_style_1_button_font_hover_color']) && $stockholm_options['cf7_custom_style_1_button_font_hover_color'] !== '') {
			$cf7_custom_style_1_button_hover_styles .= 'color: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_font_hover_color']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_hover_background_color']) && $stockholm_options['cf7_custom_style_1_button_hover_background_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_button_hover_background_transparency']) && $stockholm_options['cf7_custom_style_1_button_hover_background_transparency'] !== '') {
				$cf7_custom_style_1_button_hover_background_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_button_hover_background_color']);
				$cf7_custom_style_1_button_hover_styles .= 'background-color: rgba(' . esc_attr($cf7_custom_style_1_button_hover_background_color[0]) . ',' . esc_attr($cf7_custom_style_1_button_hover_background_color[1]) . ',' . esc_attr($cf7_custom_style_1_button_hover_background_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_button_hover_background_transparency']) . ');';
			} else {
				$cf7_custom_style_1_button_hover_styles .= 'background-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_hover_background_color']) . ';';
			}
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_button_hover_border_color']) && $stockholm_options['cf7_custom_style_1_button_hover_border_color'] !== '') {
			if (isset($stockholm_options['cf7_custom_style_1_button_hover_border_transparency']) && $stockholm_options['cf7_custom_style_1_button_hover_border_transparency'] !== '') {
				$cf7_custom_style_1_button_hover_border_color = stockholm_qode_hex2rgb($stockholm_options['cf7_custom_style_1_button_hover_border_color']);
				$cf7_custom_style_1_button_hover_styles .= 'border-color: rgba(' . esc_attr($cf7_custom_style_1_button_hover_border_color[0]) . ',' . esc_attr($cf7_custom_style_1_button_hover_border_color[1]) . ',' . esc_attr($cf7_custom_style_1_button_hover_border_color[2]) . ',' . esc_attr($stockholm_options['cf7_custom_style_1_button_hover_border_transparency']) . ');';
				
			} else {
				$cf7_custom_style_1_button_hover_styles .= 'border-color: ' . esc_attr($stockholm_options['cf7_custom_style_1_button_hover_border_color']) . ';';
			}
		}
		
		$cf7_custom_style_1_labels_styles = "";
		
		if (isset($stockholm_options['cf7_custom_style_1_label_font_color']) && $stockholm_options['cf7_custom_style_1_label_font_color'] !== '') {
			$cf7_custom_style_1_labels_styles .= 'color: ' . $stockholm_options['cf7_custom_style_1_label_font_color'] . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_label_font_family']) && $stockholm_options['cf7_custom_style_1_label_font_family'] !== '-1') {
			$cf7_custom_style_1_labels_styles .= 'font-family: ' . str_replace('+', ' ', $stockholm_options['cf7_custom_style_1_label_font_family']) . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_label_font_size']) && $stockholm_options['cf7_custom_style_1_label_font_size'] !== '') {
			$cf7_custom_style_1_labels_styles .= 'font-size: ' . $stockholm_options['cf7_custom_style_1_label_font_size'] . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_label_line_height']) && $stockholm_options['cf7_custom_style_1_label_line_height'] !== '') {
			$cf7_custom_style_1_labels_styles .= 'line-height: ' . $stockholm_options['cf7_custom_style_1_label_line_height'] . 'px;';
		}
		if (isset($stockholm_options['cf7_custom_style_1_label_font_style']) && $stockholm_options['cf7_custom_style_1_label_font_style'] !== '') {
			$cf7_custom_style_1_labels_styles .= 'font-style: ' . $stockholm_options['cf7_custom_style_1_label_font_style'] . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_label_font_weight']) && $stockholm_options['cf7_custom_style_1_label_font_weight'] !== '') {
			$cf7_custom_style_1_labels_styles .= 'font-weight: ' . $stockholm_options['cf7_custom_style_1_label_font_weight'] . ';';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_label_letter_spacing']) && $stockholm_options['cf7_custom_style_1_label_letter_spacing'] !== '') {
			$cf7_custom_style_1_labels_styles .= 'letter-spacing: ' . $stockholm_options['cf7_custom_style_1_label_letter_spacing'] . 'px;';
		}
		
		if (isset($stockholm_options['cf7_custom_style_1_label_text_transform']) && $stockholm_options['cf7_custom_style_1_label_text_transform'] !== '') {
			$cf7_custom_style_1_labels_styles .= 'text-transform: ' . $stockholm_options['cf7_custom_style_1_label_text_transform'] . ';';
		}
		
		$cf7_custom_style_1_validation_messages_style = "";
		if(isset($stockholm_options['cf7_custom_style_1_error_validation_messages_color']) && $stockholm_options['cf7_custom_style_1_error_validation_messages_color'] !== '') {
			$cf7_custom_style_1_validation_messages_style .= 'color: '.esc_attr($stockholm_options['cf7_custom_style_1_error_validation_messages_color']).';';
		}
		
		if(isset($stockholm_options['cf7_custom_style_1_element_textarea_height']) && $stockholm_options['cf7_custom_style_1_element_textarea_height'] !== '') {?>
			.cf7_custom_style_1 textarea.wpcf7-form-control.wpcf7-textarea,
			.contact_form.cf7_custom_style_1 textarea{
			height: <?php echo intval($stockholm_options['cf7_custom_style_1_element_textarea_height']); ?>px;
			}
		<?php }
		
		if($cf7_custom_style_1_button_styles !== ""){ ?>
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-submit,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-submit:not([disabled]),
			.contact_form.cf7_custom_style_1 .qbutton{
			<?php echo esc_attr($cf7_custom_style_1_button_styles); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_button_hover_styles !== ""){ ?>
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-submit:hover,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-submit:not([disabled]):hover,
			.contact_form.cf7_custom_style_1 .qbutton:hover{
			<?php echo esc_attr($cf7_custom_style_1_button_hover_styles); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_elements_styles !== ""){ ?>
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-text,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-number,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-date,
			.cf7_custom_style_1 textarea.wpcf7-form-control.wpcf7-textarea,
			.cf7_custom_style_1 select.wpcf7-form-control.wpcf7-select,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-quiz,
			.contact_form.cf7_custom_style_1 input[type='text'],
			.contact_form.cf7_custom_style_1 textarea{
			<?php echo esc_attr($cf7_custom_style_1_elements_styles); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_elements_focus_styles !== ""){ ?>
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-text:focus,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-number:focus,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-date:focus,
			.cf7_custom_style_1 textarea.wpcf7-form-control.wpcf7-textarea:focus,
			.cf7_custom_style_1 select.wpcf7-form-control.wpcf7-select:focus,
			.cf7_custom_style_1 input.wpcf7-form-control.wpcf7-quiz:focus,
			.contact_form.cf7_custom_style_1 input[type='text']:focus,
			.contact_form.cf7_custom_style_1 textarea:focus{
			<?php echo esc_attr($cf7_custom_style_1_elements_focus_styles); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_labels_styles !== ""){ ?>
			.cf7_custom_style_1 p,
			.cf7_custom_style_1 .animate_input_holder .animate_input_text:after {
			<?php echo esc_attr($cf7_custom_style_1_labels_styles); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_for_animation_style !== ""){ ?>
			.cf7_custom_style_1 .animate_input_holder .animate_input_text {
			<?php echo esc_attr($cf7_custom_style_1_for_animation_style); ?>
			}
		<?php } ?>
		
		
		
		<?php if($cf7_custom_style_1_for_animation_holder_style !== ""){ ?>
			.cf7_custom_style_1 .animate_input_holder{
			<?php echo esc_attr($cf7_custom_style_1_for_animation_holder_style); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_validation_messages_style !== ""){ ?>
			.cf7_custom_style_1 span.wpcf7-not-valid-tip,
			.contact_form.cf7_custom_style_1 .contact-error{
			<?php echo esc_attr($cf7_custom_style_1_validation_messages_style); ?>
			}
		<?php } ?>
		<?php if($cf7_custom_style_1_color_placeholder !== ""){ ?>
			.cf7_custom_style_1 ::-webkit-input-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']::-webkit-input-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']:focus::-webkit-input-placeholder,
			.contact_form.cf7_custom_style_1 textarea:focus::-webkit-input-placeholder,
			.contact_form.cf7_custom_style_1 textarea::-webkit-input-placeholder{
			<?php echo esc_attr($cf7_custom_style_1_color_placeholder); ?>
			}
		<?php } ?>
		<?php if($cf7_custom_style_1_color_placeholder !== ""){ ?>
			.cf7_custom_style_1 :-moz-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']:-moz-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']:focus:-moz-placeholder,
			.contact_form.cf7_custom_style_1 textarea:focus:-moz-placeholder,
			.contact_form.cf7_custom_style_1 textarea:-moz-placeholder{
			<?php echo esc_attr($cf7_custom_style_1_color_placeholder); ?>
			}
		<?php } ?>
		<?php if($cf7_custom_style_1_color_placeholder !== ""){ ?>
			.cf7_custom_style_1 ::-moz-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']::-moz-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']:focus::-moz-placeholder,
			.contact_form.cf7_custom_style_1 textarea::-moz-placeholder,
			.contact_form.cf7_custom_style_1 textarea:focus::-moz-placeholder{
			<?php echo esc_attr($cf7_custom_style_1_color_placeholder); ?>
			}
		<?php } ?>
		<?php if($cf7_custom_style_1_color_placeholder !== ""){ ?>
			.cf7_custom_style_1 :-ms-input-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']:-ms-input-placeholder,
			.contact_form.cf7_custom_style_1 input[type='text']:focus:-ms-input-placeholder,
			.contact_form.cf7_custom_style_1 textarea:-ms-input-placeholder,
			.contact_form.cf7_custom_style_1 textarea:focus:-ms-input-placeholder{
			<?php echo esc_attr($cf7_custom_style_1_color_placeholder); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_animation_line_style !== ""){ ?>
			input.wpcf7-form-control~.qode-focus-border{
			<?php echo esc_attr($cf7_custom_style_1_animation_line_style); ?>
			}
		<?php } ?>
		
		<?php if($cf7_custom_style_1_animation_line_focus_style !== ""){ ?>
			input.wpcf7-form-control~.qode-focus-border{
			<?php echo esc_attr($cf7_custom_style_1_animation_line_focus_style); ?>
			}
		<?php } ?>
		
		<?php
	}
	
	add_action( 'stockholm_qode_action_style_dynamic', 'stockholm_qode_add_style_dynamic' );
}

if ( ! function_exists( 'stockholm_qode_add_style_dynamic_responsive' ) ) {
	function stockholm_qode_add_style_dynamic_responsive() {
		$stockholm_options = stockholm_qode_return_global_options();
		?>
		
		@media only screen and (max-width: 1000px){
		<?php if ( ! empty( $stockholm_options['header_background_color'] ) ) { ?>
			.header_bottom {
			background-color: <?php echo esc_attr( $stockholm_options['header_background_color'] ); ?>;
			}
		<?php } ?>
		<?php if ( ! empty( $stockholm_options['mobile_background_color'] ) ) { ?>
			.header_bottom,
			nav.mobile_menu{
			background-color: <?php echo esc_attr( $stockholm_options['mobile_background_color'] ); ?> !important;
			}
		<?php } ?>
		<?php if ( ! empty( $stockholm_options['vertical_mobile_background_color'] ) ) { ?>
			.header_bottom,
			nav.mobile_menu{
			background-color: <?php echo esc_attr( $stockholm_options['vertical_mobile_background_color'] ); ?> !important;
			}
		<?php } ?>
		}
		@media only screen and (min-width: 480px) and (max-width: 768px){
		
		<?php if ( isset( $stockholm_options['parallax_minheight'] ) && ! empty( $stockholm_options['parallax_minheight'] ) ) { ?>
			section.parallax_section_holder{
			height: auto !important;
			min-height: <?php echo intval( $stockholm_options['parallax_minheight'] ); ?>px;
			}
		<?php } ?>
		
		<?php if ( isset( $stockholm_options['header_background_color_mobile'] ) && ! empty( $stockholm_options['header_background_color_mobile'] ) ) { ?>
			header{
			background-color: <?php echo esc_attr( $stockholm_options['header_background_color_mobile'] ); ?> !important;
			background-image:none;
			}
		<?php } ?>
		}
		
		@media only screen and (max-width: 480px){
		
		<?php if ( isset( $stockholm_options['parallax_minheight'] ) && ! empty( $stockholm_options['parallax_minheight'] ) ) { ?>
			section.parallax_section_holder{
			height: auto !important;
			min-height: <?php echo intval( $stockholm_options['parallax_minheight'] ); ?>px;
			}
		<?php } ?>
		
		<?php if ( isset( $stockholm_options['header_background_color_mobile'] ) && ! empty( $stockholm_options['header_background_color_mobile'] ) ) { ?>
			header{
			background-color: <?php echo esc_attr( $stockholm_options['header_background_color_mobile'] ); ?> !important;
			background-image:none;
			}
		<?php } ?>
		}
		
		<?php
	}
	
	add_action( 'stockholm_qode_action_style_dynamic_responsive', 'stockholm_qode_add_style_dynamic_responsive' );
}

if ( ! function_exists( 'stockholm_qode_add_default_dynamic' ) ) {
	function stockholm_qode_add_default_dynamic() {
		$stockholm_options = stockholm_qode_return_global_options();
		?>
		
		function ajaxSubmitCommentForm(){
		"use strict";
		
		var options = {
		success: function(){
		$j("#commentform textarea").val("");
		$j("#commentform .success p").text("<?php esc_html_e('Comment has been sent!','stockholm'); ?>");
		}
		};
		
		$j('#commentform').submit(function() {
		$j(this).find('input[type="submit"]').next('.success').remove();
		$j(this).find('input[type="submit"]').after('<div class="success"><p></p></div>');
		$j(this).ajaxSubmit(options);
		return false;
		});
		}
		var header_height = 100;
		var min_header_height_scroll = 57;
		var min_header_height_fixed_hidden = 50;
		var min_header_height_sticky = 60;
		var scroll_amount_for_sticky = 85;
		var content_line_height = 60;
		var header_bottom_border_weight = 1;
		var scroll_amount_for_fixed_hiding = 200;
		var paspartu_width_init = 0.02;
		var search_header_height = 50;
		
		<?php if(isset($stockholm_options['header_height'])){
			if (!empty($stockholm_options['header_height'])) { ?>
				header_height = <?php echo intval($stockholm_options['header_height']); ?>;
			<?php } } ?>
		<?php if(isset($stockholm_options['header_height_scroll'])){
			if (!empty($stockholm_options['header_height_scroll'])) { ?>
				min_header_height_scroll = <?php echo intval($stockholm_options['header_height_scroll']); ?>;
			<?php } } ?>
		<?php if(isset($stockholm_options['header_height_sticky'])){
			if (!empty($stockholm_options['header_height_sticky'])) { ?>
				min_header_height_sticky = <?php echo intval($stockholm_options['header_height_sticky']); ?>;
			<?php } } ?>
		<?php if(isset($stockholm_options['scroll_amount_for_sticky'])){
			if (!empty($stockholm_options['scroll_amount_for_sticky'])) { ?>
				scroll_amount_for_sticky = <?php echo intval($stockholm_options['scroll_amount_for_sticky']); ?>;
			<?php } } ?>
		<?php if(isset($stockholm_options['content_menu_lineheight'])){
			if($stockholm_options['content_menu_lineheight'] != ""){ ?>
				content_line_height = <?php echo intval($stockholm_options['content_menu_lineheight']); ?>
			<?php } } ?>
		<?php if(isset($stockholm_options['scroll_amount_for_fixed_hiding'])){
			if (!empty($stockholm_options['scroll_amount_for_fixed_hiding'])) { ?>
				scroll_amount_for_fixed_hiding = <?php echo intval($stockholm_options['scroll_amount_for_fixed_hiding']); ?>;
			<?php } } ?>
		var logo_height = 130; // stockholm logo height
		var logo_width = 280; // stockholm logo width
		<?php if ( isset( $stockholm_options['logo_image'] ) && ! empty( $stockholm_options['logo_image'] ) ) {
			$image_dimension = stockholm_qode_get_image_dimensions( $stockholm_options['logo_image'] );
			
			if ( ! empty( $image_dimension ) ) { ?>
				logo_height  = <?php echo intval( $image_dimension['height'] ); ?>;
				logo_width = <?php echo intval( $image_dimension['width'] ); ?>;
			<?php }
		} ?>
		header_top_height = <?php echo intval( stockholm_qode_return_top_header_height() ); ?>;
		
		<?php if(isset($stockholm_options['paspartu_width']) && $stockholm_options['paspartu_width'] !== ""){ ?>
			paspartu_width_init = <?php echo esc_attr($stockholm_options['paspartu_width'])/100; ?>;
		<?php } ?>
		
		<?php if(isset($stockholm_options['search_text_line_height']) && $stockholm_options['search_text_line_height'] !== ""){ ?>
			search_header_height = <?php echo intval($stockholm_options['search_text_line_height']) + 15 + 15 ; ?>; //15 is margin top and margin bottom
		<?php } ?>
		
		var loading_text;
		loading_text = '<?php esc_attr_e('Loading new posts...', 'stockholm'); ?>';
		var finished_text;
		finished_text = '<?php esc_attr_e('No more posts', 'stockholm'); ?>';
		<?php
		if($stockholm_options['enable_google_map'] != ""){
			?>
			
			var piechartcolor;
			piechartcolor	= "#e6ae48";
			<?php
			if(isset($stockholm_options['first_color']) && !empty($stockholm_options['first_color'])){ ?>
				piechartcolor = "<?php echo esc_attr($stockholm_options['first_color']); ?>";
			<?php } ?>
			
			var geocoder;
			var map;
			
			function initialize() {
			"use strict";
			// Create an array of styles.
			<?php
			$google_maps_color = "#393939";
			if(isset($stockholm_options['google_maps_color'])){
				if (!empty($stockholm_options['google_maps_color']))
					$google_maps_color = $stockholm_options['google_maps_color'];
			}
			$google_maps_saturation = "-100";
			if(isset($stockholm_options['google_maps_saturation'])){
				if (!empty($stockholm_options['google_maps_saturation']))
					$google_maps_saturation = $stockholm_options['google_maps_saturation'];
			}
			$google_maps_lightness = "-60";
			if(isset($stockholm_options['google_maps_lightness'])){
				if (!empty($stockholm_options['google_maps_lightness']))
					$google_maps_lightness = $stockholm_options['google_maps_lightness'];
			}
			?>
			var mapStyles = [
			{
			stylers: [
			{hue: "<?php echo esc_attr($google_maps_color); ?>" },
			{saturation: "<?php echo esc_attr($google_maps_saturation); ?>"},
			{lightness: "<?php echo esc_attr($google_maps_lightness); ?>"},
			{gamma: 1}
			]
			}
			];
			var qodeMapType = new google.maps.StyledMapType(mapStyles,
			{name: "Qode Map"});
			
			geocoder = new google.maps.Geocoder();
			var latlng = new google.maps.LatLng(-34.397, 150.644);
			var myOptions = {
			<?php
			$google_maps_scroll_wheel = false;
			if(isset($stockholm_options['google_maps_scroll_wheel'])){
				if ($stockholm_options['google_maps_scroll_wheel'] == "yes")
					$google_maps_scroll_wheel = true;
			}
			$google_maps_zoom = 12;
			if(isset($stockholm_options['google_maps_zoom'])){
				if (intval($stockholm_options['google_maps_zoom']) > 0)
					$google_maps_zoom = intval($stockholm_options['google_maps_zoom']);
			}
			?>
			zoom: <?php echo esc_attr($google_maps_zoom); ?>,
			<?php if (!$google_maps_scroll_wheel) { ?>
				scrollwheel: false,
			<?php } ?>
			center: latlng,
			zoomControl: true,
			zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.RIGHT_CENTER
			},
			scaleControl: false,
			scaleControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
			},
			streetViewControl: false,
			streetViewControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
			},
			panControl: false,
			panControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
			},
			mapTypeControl: false,
			mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'qode_style'],
			style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
			position: google.maps.ControlPosition.LEFT_CENTER
			},
			<?php
			$google_maps_style = true;
			if ( isset( $stockholm_options['google_maps_style'] ) && $stockholm_options['google_maps_style'] == "no" ) {
				$google_maps_style = false;
			}
			?>
			<?php if ($google_maps_style) { ?>
				mapTypeId: 'qode_style'
			<?php } else { ?>
				mapTypeId: google.maps.MapTypeId.ROADMAP
			<?php } ?>
			};
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			<?php if ($google_maps_style) { ?>
				map.mapTypes.set('qode_style', qodeMapType);
			<?php } ?>
			}
			
			function codeAddress(data) {
			"use strict";
			
			if (data === '')
			return;
			
			var contentString = '<div id="content"><div id="siteNotice"></div><div id="bodyContent"><p>'+data+'</p></div></div>';
			var infowindow = new google.maps.InfoWindow({
			content: contentString
			});
			geocoder.geocode( { 'address': data}, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
			map: map,
			position: results[0].geometry.location,
			<?php if(isset($stockholm_options['google_maps_pin_image'])){ ?>
				icon:  '<?php echo esc_url($stockholm_options['google_maps_pin_image']); ?>',
			<?php } ?>
			title: data['store_title']
			});
			google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
			});
			}
			});
			}
			
			var $j = jQuery.noConflict();
			
			$j(document).ready(function() {
			"use strict";
			
			showContactMap();
			});
			<?php
		}
		?>
		
		function showContactMap() {
		"use strict";
		
		if($j("#map_canvas").length > 0 && typeof google === 'object'){
		initialize();
		codeAddress("<?php if(isset($stockholm_options['google_maps_address5'])) { echo esc_attr($stockholm_options['google_maps_address5']); } ?>");
		codeAddress("<?php if(isset($stockholm_options['google_maps_address4'])) { echo esc_attr($stockholm_options['google_maps_address4']); } ?>");
		codeAddress("<?php if(isset($stockholm_options['google_maps_address3'])) { echo esc_attr($stockholm_options['google_maps_address3']); } ?>");
		codeAddress("<?php if(isset($stockholm_options['google_maps_address2'])) { echo esc_attr($stockholm_options['google_maps_address2']); } ?>");
		codeAddress("<?php if(isset($stockholm_options['google_maps_address'])) { echo esc_attr($stockholm_options['google_maps_address']); } ?>");
		}
		}
		
		var no_ajax_pages = [];
		var qode_root = '<?php echo esc_url(home_url('/')); ?>';
		var theme_root = '<?php echo QODE_ROOT; ?>';
		<?php if(stockholm_qode_get_header_style_class() != ''){ ?>
			var header_style_admin = "<?php echo esc_attr(stockholm_qode_get_header_style_class()); ?>";
		<?php }else{ ?>
			var header_style_admin = "";
		<?php } ?>
		if(typeof no_ajax_obj !== 'undefined') {
		no_ajax_pages = no_ajax_obj.no_ajax_pages;
		}
		
		<?php
	}
	
	add_action( 'stockholm_qode_action_default_dynamic', 'stockholm_qode_add_default_dynamic' );
}