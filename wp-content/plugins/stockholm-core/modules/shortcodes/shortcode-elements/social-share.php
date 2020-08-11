<?php
/* Social Share shortcode */

if (!function_exists('social_share')) {
	function social_share($atts, $content = null) {
		$global_options = stockholm_qode_return_global_options();
		
		if(isset($global_options['twitter_via']) && !empty($global_options['twitter_via'])) {
			$twitter_via = " via " . $global_options['twitter_via'] . " ";
		} else {
			$twitter_via = 	"";
		}
		if(isset($_SERVER["https"])) {
			$count_char = 23;
		} else{
			$count_char = 22;
		}
		$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
		$html = "";
		if( stockholm_qode_is_social_share_enabled() ) {
			$post_type = get_post_type();
			if(isset($global_options["post_types_names_$post_type"])) {
				if($global_options["post_types_names_$post_type"] == $post_type) {
					if($post_type == "portfolio_page") {
						$html .= '<div class="portfolio_share">';
					} elseif($post_type == "post") {
						$html .= '<div class="blog_share">';
					} elseif($post_type == "page") {
						$html .= '<div class="page_share">';
					}
					$html .= '<div class="social_share_holder">';
					$html .= '<a href="javascript:void(0)" target="_self"><i class="social_share social_share_icon"></i>';
					$html .= '<span class="social_share_title">'.  esc_html__('Share','stockholm-core') .'</span>';
					$html .= '</a>';
					$html .= '<div class="social_share_dropdown"><ul>';
					if(isset($global_options['enable_facebook_share']) &&  $global_options['enable_facebook_share'] == "yes") {
						$html .= '<li class="facebook_share">';
						if(wp_is_mobile()) {
							$html .= '<a title="'.esc_html__('Share on Facebook', 'stockholm-core').'" href="javascript:void(0)" onclick="window.open(\'http://m.facebook.com/sharer.php?u=' . get_permalink();
						}
						else {
							$html .= '<a title="'.esc_html__('Share on Facebook', 'stockholm-core').'" href="javascript:void(0)" onclick="window.open(\'http://www.facebook.com/sharer/sharer.php?u=' . get_permalink();
						}
//						$html .= '&amp;p[summary]=' . urlencode(esc_attr(strip_tags(get_the_excerpt())));
						$html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');">';
						if(!empty($global_options['facebook_icon'])) {
							$html .= '<img src="' . $global_options["facebook_icon"] . '" alt="' . esc_attr__( 'Facebook image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<span class="social_network_icon social_facebook_circle"></span>';
						}
						$html .= "<span class='share_text'>" . esc_html__("Facebook","stockholm-core") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}

					if($global_options['enable_twitter_share'] == "yes") {
						$html .= '<li class="twitter_share">';

						$html .= '<a href="#" title="'.esc_attr__("Share on Twitter", 'stockholm-core').'" onclick="popUp=window.open(\'https://twitter.com/intent/tweet?text=' . urlencode( stockholm_qode_the_excerpt_max_charlength( mb_strlen(get_permalink())) . $twitter_via ) . ' ' . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';

						if(!empty($global_options['twitter_icon'])) {
							$html .= '<img src="' . $global_options["twitter_icon"] . '" alt="' . esc_attr__( 'Twitter image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<span class="social_network_icon social_twitter_circle"></span>';
						}
						$html .= "<span class='share_text'>" . esc_html__("Twitter", 'stockholm-core') . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if($global_options['enable_google_plus'] == "yes") {
						$html .= '<li  class="google_share">';
						$html .= '<a href="#" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['google_plus_icon'])) {
							$html .= '<img src="' . $global_options['google_plus_icon'] . '" alt="' . esc_attr__( 'Google image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<span class="social_network_icon social_googleplus_circle"></span>';
						}
						$html .= "<span class='share_text'>" . esc_html__("Google+","stockholm-core") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_linkedin']) && $global_options['enable_linkedin'] == "yes") {
						$html .= '<li  class="linkedin_share">';
						$html .= '<a href="#" onclick="popUp=window.open(\'https://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['linkedin_icon'])) {
							$html .= '<img src="' . $global_options['linkedin_icon'] . '" alt="' . esc_attr__( 'Linkedin image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<span class="social_network_icon social_linkedin_circle"></span>';
						}
						$html .= "<span class='share_text'>" . esc_html__("LinkedIn","stockholm-core") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_tumblr']) && $global_options['enable_tumblr'] == "yes") {
						$html .= '<li  class="tumblr_share">';
						$html .= '<a href="#" onclick="popUp=window.open(\'https://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['tumblr_icon'])) {
							$html .= '<img src="' . $global_options['tumblr_icon'] . '" alt="' . esc_attr__( 'Thumbler image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<span class="social_network_icon social_tumblr_circle"></span>';
						}
						$html .= "<span class='share_text'>" . esc_html__("Tumblr","stockholm-core") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_pinterest']) && $global_options['enable_pinterest'] == "yes") {
						$html .= '<li  class="pinterest_share">';
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						$html .= '<a href="#" onclick="popUp=window.open(\'https://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()). '&amp;description=' . esc_attr(strip_tags(get_the_title())) .'&amp;media='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['pinterest_icon'])) {
							$html .= '<img src="' . $global_options['pinterest_icon'] . '" alt="' . esc_attr__( 'Pinterest image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<span class="social_network_icon social_pinterest_circle"></span>';
						}
						$html .= "<span class='share_text'>" . esc_html__("Pinterest","stockholm-core") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					if(isset($global_options['enable_vk']) && $global_options['enable_vk'] == "yes") {
						$html .= '<li  class="vk_share">';
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						$html .= '<a href="#" onclick="popUp=window.open(\'https://vkontakte.ru/share.php?url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) .'&amp;description=' . urlencode(get_the_excerpt()) .'&amp;image='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
						if(!empty($global_options['vk_icon'])) {
							$html .= '<img src="' . $global_options['vk_icon'] . '" alt="' . esc_attr__( 'VK image', 'stockholm-core' ) . '" />';
						} else {
							$html .= '<i class="fa fa-vk"></i>';
						}
						$html .= "<span class='share_text'>" . esc_html__("VK","stockholm-core") . "</span>";
						$html .= "</a>";
						$html .= "</li>";
					}
					$html .= "</ul></div>";
					$html .= "</div>";

					if($post_type == "portfolio_page" || $post_type == "post" || $post_type == "page") {
						$html .= '</div>';
					}
				}
			}
		}
		return $html;
	}
	add_shortcode('social_share', 'social_share');
}
