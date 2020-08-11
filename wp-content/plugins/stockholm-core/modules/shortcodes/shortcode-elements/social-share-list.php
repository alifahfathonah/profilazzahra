<?php
/* Social Share List shortcode */

if (!function_exists('social_share_list')) {
	function social_share_list($atts, $content = null) {
		$args = array(
			"list_type"    => "circle"
		);
		extract(shortcode_atts($args, $atts));
		
		$global_options = stockholm_qode_return_global_options();
		
		if(isset($global_options['twitter_via']) && !empty($global_options['twitter_via'])) {
			$twitter_via = " via " . $global_options['twitter_via'] . " ";
		} else {
			$twitter_via = 	"";
		}
		$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
		$html = "";
		if ( stockholm_qode_is_social_share_enabled() ) {
			$post_type = get_post_type();

			if(isset($global_options["post_types_names_$post_type"])) {
				if($global_options["post_types_names_$post_type"] == $post_type) {
					$html .= '<div class="social_share_list_holder ' . $list_type . '">';
					$html .= '<ul>';

					if(isset($global_options['enable_facebook_share']) &&  $global_options['enable_facebook_share'] == "yes") {
						$html .= '<li class="facebook_share">';
						if(wp_is_mobile()) {
							$html .= '<a title="'.esc_html__('Share on Facebook', 'stockholm-core').'" href="javascript:void(0)" onclick="window.open(\'http://m.facebook.com/sharer.php?u=' . get_permalink();						}
						else {
							$html .= '<a title="'.esc_html__('Share on Facebook', 'stockholm-core').'" href="javascript:void(0)" onclick="window.open(\'http://www.facebook.com/sharer/sharer.php?u=' . get_permalink();
						}
//						$html .= '&amp;p[summary]=' . urlencode(esc_attr(strip_tags(get_the_excerpt())));
						$html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');">';
						if(!empty($global_options['facebook_icon'])) {
							$html .= '<img src="' . $global_options["facebook_icon"] . '" alt="' . esc_attr__( 'Facebook image', 'stockholm-core' ) . '" />';
						} else {
							if($list_type == 'circle') {
								$html .= '<i class="social_facebook_circle"></i>';
							}
							else {
								$html .= '<i class="social_facebook"></i>';
							}
						}
						$html .= "</a>";
						$html .= "</li>";
					}

					if($global_options['enable_twitter_share'] == "yes") {
						$html .= '<li class="twitter_share">';

							$html .= '<a href="#" title="'.esc_attr__("Share on Twitter", 'stockholm-core').'" onclick="popUp=window.open(\'https://twitter.com/intent/tweet?text=' . urlencode( stockholm_qode_the_excerpt_max_charlength( mb_strlen(get_permalink())) . $twitter_via ) . ' ' . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';

						if(!empty($global_options['twitter_icon'])) {
							$html .= '<img src="' . $global_options["twitter_icon"] . '" alt="' . esc_attr__( 'Twitter image', 'stockholm-core' ) . '" />';
						} else {
							if($list_type == 'circle') {
								$html .= '<i class="social_twitter_circle"></i>';
							}
							else {
								$html .= '<i class="social_twitter"></i>';
							}
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if($global_options['enable_google_plus'] == "yes") {
						$html .= '<li  class="google_share">';
						$html .= '<a href="#" title="'.esc_attr__("Share on Google+","stockholm-core").'" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['google_plus_icon'])) {
							$html .= '<img src="' . $global_options['google_plus_icon'] . '" alt="' . esc_attr__( 'Google image', 'stockholm-core' ) . '" />';
						} else {
							if($list_type == 'circle') {
								$html .= '<i class="social_googleplus_circle"></i>';
							}
							else {
								$html .= '<i class="social_googleplus"></i>';
							}
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_linkedin']) && $global_options['enable_linkedin'] == "yes") {
						$html .= '<li  class="linkedin_share">';
						$html .= '<a href="#" title="'.esc_attr__("Share on LinkedIn","stockholm-core").'" onclick="popUp=window.open(\'https://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['linkedin_icon'])) {
							$html .= '<img src="' . $global_options['linkedin_icon'] . '" alt="' . esc_attr__( 'Linkedin image', 'stockholm-core' ) . '" />';
						} else {
							if($list_type == 'circle') {
								$html .= '<i class="social_linkedin_circle"></i>';
							}
							else {
								$html .= '<i class="social_linkedin"></i>';
							}
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_tumblr']) && $global_options['enable_tumblr'] == "yes") {
						$html .= '<li  class="tumblr_share">';
						$html .= '<a href="#" title="'.esc_attr__("Share on Tumblr","stockholm-core").'" onclick="popUp=window.open(\'https://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['tumblr_icon'])) {
							$html .= '<img src="' . $global_options['tumblr_icon'] . '" alt="' . esc_attr__( 'Tumblr image', 'stockholm-core' ) . '" />';
						} else {
							if($list_type == 'circle') {
								$html .= '<i class="social_tumblr_circle"></i>';
							}
							else {
								$html .= '<i class="social_tumblr"></i>';
							}
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_pinterest']) && $global_options['enable_pinterest'] == "yes") {
						$html .= '<li  class="pinterest_share">';
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						$html .= '<a href="#" title="'.esc_attr__("Share on Pinterest","stockholm-core").'" onclick="popUp=window.open(\'https://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()). '&amp;description=' . esc_attr(strip_tags(get_the_title())) .'&amp;media='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['pinterest_icon'])) {
							$html .= '<img src="' . $global_options['pinterest_icon'] . '" alt="' . esc_attr__( 'Pinterest image', 'stockholm-core' ) . '" />';
						} else {
							if($list_type == 'circle') {
								$html .= '<i class="social_pinterest_circle"></i>';
							}
							else {
								$html .= '<i class="social_pinterest"></i>';
							}
						}

						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_vk']) && $global_options['enable_vk'] == "yes") {
						$html .= '<li  class="vk_share">';
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						$html .= '<a href="#" title="'.esc_attr__("Share on VK","stockholm-core").'" onclick="popUp=window.open(\'https://vkontakte.ru/share.php?url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) .'&amp;description=' . urlencode(get_the_excerpt()) .'&amp;image='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['vk_icon'])) {
							$html .= '<img src="' . $global_options['vk_icon'] . '" alt="' . esc_attr__( 'VK image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<i class="fa fa-vk"></i>';
						}

						$html .= "</a>";
						$html .= "</li>";
					}

					$html .= '</ul>'; //close ul
					$html .= '</div>'; //close div.social_share_list_holder
				}
			}
		}
		return $html;
	}

	add_shortcode('social_share_list', 'social_share_list');
}