<?php

namespace Stockholm\Shortcodes\PortfolioList;

use Stockholm\Shortcodes\Lib\ShortcodeInterface;

class PortfolioList implements ShortcodeInterface {
	private $base;
	
	public function __construct() {
		$this->base = 'portfolio_list';
		
		add_action( 'stockholm_qode_action_vc_map', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			"name"                      => esc_html__( "Portfolio List", 'stockholm-core' ),
			"base"                      => "portfolio_list",
			"category"                  => esc_html__( 'by SELECT', 'stockholm-core' ),
			"icon"                      => "icon-wpb-portfolio extended-custom-icon-qode",
			"allowed_container_element" => 'vc_row',
			"params"                    => array(
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Type", 'stockholm-core' ),
					"param_name"  => "type",
					"value"       => array(
						esc_html__( "Standard", 'stockholm-core' )          => "standard",
						esc_html__( "Standard No Space", 'stockholm-core' ) => "standard_no_space",
						esc_html__( "Gallery Text", 'stockholm-core' )      => "hover_text",
						esc_html__( "Gallery No Space", 'stockholm-core' )  => "hover_text_no_space",
						esc_html__( "Masonry", 'stockholm-core' )           => "masonry",
						esc_html__( "Pinterest", 'stockholm-core' )         => "masonry_with_space",
						esc_html__( "Justified Gallery", 'stockholm-core' ) => "justified_gallery"
					),
					'save_always' => true
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Space Between Masonry", 'stockholm-core' ),
					"param_name"  => "masonry_space",
					"value"       => array(
						esc_html__( "No", 'stockholm-core' )  => "no",
						esc_html__( "Yes", 'stockholm-core' ) => "yes"
					),
					'save_always' => true,
					"dependency"  => array( 'element' => "type", 'value' => array( 'masonry' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Space Between Pinterest", 'stockholm-core' ),
					"param_name"  => "pinterest_space",
					"value"       => array(
						esc_html__( "No", 'stockholm-core' )  => "no",
						esc_html__( "Yes", 'stockholm-core' ) => "yes"
					),
					'save_always' => true,
					"dependency"  => array( 'element' => "type", 'value' => array( 'masonry_with_space' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Loading Type", 'stockholm-core' ),
					"param_name"  => "portfolio_loading_type",
					"value"       => array(
						esc_html__( "Default", 'stockholm-core' )            => "",
						esc_html__( "Appear From Bottom", 'stockholm-core' ) => "appear_from_bottom"
					),
					'save_always' => true,
					"dependency"  => array( 'element' => "type", 'value' => array( 'masonry_with_space', 'masonry' ) )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Parallax Item Speed", 'stockholm-core' ),
					"param_name"  => "parallax_item_speed",
					"value"       => "",
					"description" => esc_html__( 'This option only takes effect on portfolio items on which Set Masonry Item in Parallax is set to Yes default value is 0.3', 'stockholm-core' ),
					"dependency"  => array( 'element' => "masonry_space", 'value' => array( 'yes' ) )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Parallax Item Offset", 'stockholm-core' ),
					"param_name"  => "parallax_item_offset",
					"value"       => "",
					"description" => esc_html__( 'This option only takes effect on portfolio items on which Set Masonry Item in Parallax is set to Yes default value is 0', 'stockholm-core' ),
					"dependency"  => array( 'element' => "masonry_space", 'value' => array( 'yes' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Hover Type", 'stockholm-core' ),
					"param_name"  => "hover_type",
					"value"       => array(
						esc_html__( "Default", 'stockholm-core' )               => "default_hover",
						esc_html__( "Standard", 'stockholm-core' )              => "standard_hover",
						esc_html__( "Elegant Without Icons", 'stockholm-core' ) => "elegant_hover",
						esc_html__( "Move from Left", 'stockholm-core' )        => "move_from_left",
						esc_html__( "Overlapping Title", 'stockholm-core' )     => "overlapping_title_hover"
					),
					'save_always' => true,
					"dependency"  => array( 'element' => "type", 'value'   => array( 'hover_text', 'hover_text_no_space', 'masonry' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Pinterest Hover Type", 'stockholm-core' ),
					"param_name"  => "pinterest_hover_type",
					"value"       => array(
						esc_html__( "Default", 'stockholm-core' )       => "",
						esc_html__( "Info on Hover", 'stockholm-core' ) => "info_on_hover"
					),
					'save_always' => true,
					"dependency"  => array( 'element' => "type", 'value' => array( 'masonry_with_space' ) )
				),
				array(
					"type"       => "colorpicker",
					"heading"    => esc_html__( "Box Background Color", 'stockholm-core' ),
					"param_name" => "box_background_color",
					"value"      => "",
					"dependency" => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'masonry_with_space' ) )
				),
				array(
					"type"       => "dropdown",
					"heading"    => esc_html__( "Box Border", 'stockholm-core' ),
					"param_name" => "box_border",
					"value"      => array(
						esc_html__( "Default", 'stockholm-core' ) => "",
						esc_html__( "No", 'stockholm-core' )      => "no",
						esc_html__( "Yes", 'stockholm-core' )     => "yes"
					),
					"dependency" => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'masonry_with_space' ) )
				),
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Box Border Width (px)", 'stockholm-core' ),
					"param_name" => "box_border_width",
					"value"      => "",
					"dependency" => array( 'element' => "box_border", 'value' => array( 'yes' ) )
				),
				array(
					"type"       => "colorpicker",
					"heading"    => esc_html__( "Box Border Color", 'stockholm-core' ),
					"param_name" => "box_border_color",
					"value"      => "",
					"dependency" => array( 'element' => "box_border", 'value' => array( 'yes' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Columns", 'stockholm-core' ),
					"param_name"  => "columns",
					"value"       => array(
						""  => "",
						"2" => "2",
						"3" => "3",
						"4" => "4",
						"5" => "5",
						"6" => "6"
					),
					'save_always' => true,
					"dependency"  => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'hover_text', 'hover_text_no_space', 'masonry_with_space' ) )
				),
				array(
					"type"       => "dropdown",
					"heading"    => esc_html__( "Image size", 'stockholm-core' ),
					"param_name" => "image_size",
					"value"      => array(
						esc_html__( "Default", 'stockholm-core' )       => "",
						esc_html__( "Original Size", 'stockholm-core' ) => "full",
						esc_html__( "Square", 'stockholm-core' )        => "square",
						esc_html__( "Landscape", 'stockholm-core' )     => "landscape",
						esc_html__( "Portrait", 'stockholm-core' )      => "portrait"
					),
					"dependency" => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'hover_text', 'hover_text_no_space' ) )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Row Height (px)", 'stockholm-core' ),
					"param_name"  => "row_height",
					"value"       => "200",
					"save_always" => true,
					"description" => esc_html__( "Targeted row height, which may vary depending on the proportions of the images.", 'stockholm-core' ),
					"dependency"  => array( 'element' => "type", 'value' => array( 'justified_gallery' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Last Row Behavior", 'stockholm-core' ),
					"param_name"  => "justify_last_row",
					"value"       => array(
						esc_html__( "Align left", 'stockholm-core' )      => "nojustify",
						esc_html__( "Align right", 'stockholm-core' )     => "right",
						esc_html__( "Align centrally", 'stockholm-core' ) => "center",
						esc_html__( "Justify", 'stockholm-core' )         => "justify",
						esc_html__( "Hide", 'stockholm-core' )            => "hide"
					),
					"description" => esc_html__( "Defines whether to justify the last row, align it in a certain way, or hide it.", 'stockholm-core' ),
					"dependency"  => array( 'element' => "type", 'value' => array( 'justified_gallery' ) )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Justify Threshold (0-1)", 'stockholm-core' ),
					"param_name"  => "justify_threshold",
					"value"       => "0.75",
					"description" => esc_html__( "If the last row takes up more than this part of available width, it will be justified despite the defined alignment. Enter 1 to never justify the last row.", 'stockholm-core' ),
					"dependency"  => array( 'element' => "justify_last_row", 'value'   => array( 'nojustify', 'right', 'center' ) )
				),
				array(
					"type"       => "dropdown",
					"heading"    => esc_html__( "Order By", 'stockholm-core' ),
					"param_name" => "order_by",
					"value"      => array_flip( stockholm_qode_get_query_order_by_array( true ) )
				),
				array(
					"type"       => "dropdown",
					"heading"    => esc_html__( "Order", 'stockholm-core' ),
					"param_name" => "order",
					"value"      => array_flip( stockholm_qode_get_query_order_array( true ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Filter", 'stockholm-core' ),
					"param_name"  => "filter",
					"value"       => array(
						""                               => "",
						esc_html__( "Yes", 'stockholm-core' ) => "yes",
						esc_html__( "No", 'stockholm-core' )  => "no"
					),
					"description" => esc_html__( "Default value is No", 'stockholm-core' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Filter Position", 'stockholm-core' ),
					"param_name"  => "filter_position",
					"value"       => array(
						esc_html__( "Top", 'stockholm-core' )  => "top",
						esc_html__( "Left", 'stockholm-core' ) => "left"
					),
					"description" => esc_html__( "Default value is Top", 'stockholm-core' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Filter Order By", 'stockholm-core' ),
					"param_name"  => "filter_order_by",
					"value"       => array(
						esc_html__( "Name", 'stockholm-core' )  => "name",
						esc_html__( "Count", 'stockholm-core' ) => "count",
						esc_html__( "Id", 'stockholm-core' )    => "id",
						esc_html__( "Slug", 'stockholm-core' )  => "slug"
					),
					'save_always' => true,
					"description" => esc_html__( "Default value is Name", 'stockholm-core' ),
					"dependency"  => array( 'element' => "filter", 'value' => array( 'yes' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Disable Filter Title", 'stockholm-core' ),
					"param_name"  => "disable_filter_title",
					"value"       => array(
						""                               => "",
						esc_html__( "Yes", 'stockholm-core' ) => "yes",
						esc_html__( "No", 'stockholm-core' )  => "no"
					),
					"description" => esc_html__( "Default value is No", 'stockholm-core' ),
					"dependency"  => array( 'element' => "filter", 'value' => array( 'yes' ) )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Filter Title Text", 'stockholm-core' ),
					"param_name"  => "filter_title_text",
					"value"       => "",
					"description" => esc_html__( "Enter custom filter title text", 'stockholm-core' ),
					"dependency"  => array( 'element' => "disable_filter_title", 'value' => array( '', 'no' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Filter Align", 'stockholm-core' ),
					"param_name"  => "filter_align",
					"value"       => array(
						esc_html__( "Left", 'stockholm-core' )   => "left_align",
						esc_html__( "Center", 'stockholm-core' ) => "center_align",
						esc_html__( "Right", 'stockholm-core' )  => "right_align"
					),
					'save_always' => true,
					"dependency"  => array( 'element' => "filter", 'value' => array( 'yes' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Disable Portfolio Link", 'stockholm-core' ),
					"param_name"  => "disable_link",
					"value"       => array(
						""                               => "",
						esc_html__( "Yes", 'stockholm-core' ) => "yes",
						esc_html__( "No", 'stockholm-core' )  => "no"
					),
					"description" => esc_html__( "Default value is No", 'stockholm-core' ),
					"dependency"  => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'hover_text', 'hover_text_no_space', 'masonry', 'masonry_with_space' ) )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Show Lightbox", 'stockholm-core' ),
					"param_name"  => "lightbox",
					"value"       => array(
						""                               => "",
						esc_html__( "Yes", 'stockholm-core' ) => "yes",
						esc_html__( "No", 'stockholm-core' )  => "no"
					),
					"description" => esc_html__( "Default value is Yes", 'stockholm-core' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Show Like", 'stockholm-core' ),
					"param_name"  => "show_like",
					"value"       => array(
						""                               => "",
						esc_html__( "Yes", 'stockholm-core' ) => "yes",
						esc_html__( "No", 'stockholm-core' )  => "no"
					),
					"description" => esc_html__( "Default value is Yes", 'stockholm-core' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => esc_html__( "Show Load More", 'stockholm-core' ),
					"param_name"  => "show_load_more",
					"value"       => array(
						""                               => "",
						esc_html__( "Yes", 'stockholm-core' ) => "yes",
						esc_html__( "No", 'stockholm-core' )  => "no"
					),
					"description" => esc_html__( "Default value is Yes", 'stockholm-core' ),
					"dependency"  => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'hover_text', 'hover_text_no_space', 'masonry_with_space', 'justified_gallery' ) )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Number", 'stockholm-core' ),
					"param_name"  => "number",
					"value"       => "-1",
					"description" => esc_html__( "Number of portolios on page (-1 is all)", 'stockholm-core' ),
					"dependency"  => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'hover_text', 'hover_text_no_space', 'masonry', 'masonry_with_space', 'justified_gallery' ) )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Category", 'stockholm-core' ),
					"param_name"  => "category",
					"value"       => "",
					"description" => esc_html__( "Category Slug (leave empty for all)", 'stockholm-core' )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Selected Projects", 'stockholm-core' ),
					"param_name"  => "selected_projects",
					"value"       => "",
					"description" => esc_html__( "Selected Projects (leave empty for all, delimit by comma)", 'stockholm-core' )
				),
				array(
					"type"       => "dropdown",
					"heading"    => esc_html__( "Title Tag", 'stockholm-core' ),
					"param_name" => "title_tag",
					"value"      => array_flip( stockholm_qode_get_title_tag( true ) ),
					"dependency" => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'hover_text', 'hover_text_no_space', 'masonry', 'masonry_with_space' ) )
				),
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Title Custom Font Size (px)", 'stockholm-core' ),
					"param_name" => "title_font_size",
					"value"      => "",
					"dependency" => array( 'element' => "type", 'value'   => array( 'standard', 'standard_no_space', 'hover_text', 'hover_text_no_space', 'masonry', 'masonry_with_space' ) )
				),
				array(
					"type"       => "dropdown",
					"heading"    => esc_html__( "Text align", 'stockholm-core' ),
					"param_name" => "text_align",
					"value"      => array(
						""                                  => "",
						esc_html__( "Left", 'stockholm-core' )   => "left",
						esc_html__( "Center", 'stockholm-core' ) => "center",
						esc_html__( "Right", 'stockholm-core' )  => "right"
					),
					"dependency" => array( 'element' => 'type', 'value'   => array( 'standard', 'standard_no_space', 'masonry_with_space' ) )
				)
			)
		) );
	}
	
	public function render( $atts, $content = null ) {
		$args = array(
			"type"                   => "standard",
			"masonry_space"          => "no",
			"pinterest_space"        => "no",
			"hover_type"             => "default_hover",
			"pinterest_hover_type"   => "",
			"portfolio_loading_type" => "",
			"parallax_item_speed"    => "0.3",
			"parallax_item_offset"   => "0",
			"box_border"             => "",
			"box_background_color"   => "",
			"box_border_color"       => "",
			"box_border_width"       => "",
			"columns"                => "3",
			"image_size"             => "",
			"order_by"               => "date",
			"order"                  => "ASC",
			"number"                 => "-1",
			"filter"                 => "no",
			"filter_position"        => "top",
			"filter_order_by"        => "name",
			"disable_filter_title"   => "no",
			"filter_title_text"      => "",
			"filter_align"           => "left_align",
			"disable_link"           => "no",
			"lightbox"               => "yes",
			"show_like"              => "yes",
			"category"               => "",
			"selected_projects"      => "",
			"show_load_more"         => "yes",
			"title_tag"              => "h4",
			"title_font_size"        => "",
			"text_align"             => "",
			"row_height"             => "",
			"justify_last_row"       => "nojustify",
			"justify_threshold"      => 0.75
		);
		$params = shortcode_atts( $args, $atts );
		
		$params['thisObject'] = $this;
		
		$params['portfolio_qode_like'] = stockholm_qode_options()->getOptionValue( 'portfolio_qode_like' );
		
		$params['portfolio_list_hide_category'] = false;
		if ( stockholm_qode_options()->getOptionValue( 'portfolio_list_hide_category' ) == "yes" ) {
			$params['portfolio_list_hide_category'] = true;
		}
		
		if ( stockholm_qode_options()->getOptionValue( 'portfolio_filter_disable_separator' ) == "yes" ) {
			$params['portfolio_filter_class'] = "without_separator";
		} else {
			$params['portfolio_filter_class'] = "";
		}
		
		if ( $params['filter_title_text'] !== '' ) {
			$params['filter_title'] = $params['filter_title_text'];
		} else {
			$params['filter_title'] = esc_html__( 'Sort Portfolio:', 'stockholm-core' );
		}
		
		$params['_type_class']                         = '';
		$params['_portfolio_space_class']              = '';
		$params['_portfolio_masonry_with_space_class'] = '';
		$params['_portfolio_masonry_class']            = '';
		$params['_loading_class']                      = '';
		
		if ( $params['type'] == "hover_text" ) {
			$params['_type_class']            = " hover_text";
			$params['_portfolio_space_class'] = "portfolio_with_space portfolio_with_hover_text";
		} elseif ( $params['type'] == "standard" || $params['type'] == "masonry_with_space" ) {
			$params['_type_class']            = " standard";
			$params['_portfolio_space_class'] = "portfolio_with_space portfolio_standard";
			if ( $params['type'] == "masonry_with_space" ) {
				$params['_portfolio_masonry_with_space_class'] = ' masonry_with_space';
			}
			if ( $params['pinterest_space'] == "yes" && $params['type'] == "masonry_with_space" ) {
				$params['_portfolio_masonry_with_space_class'] = 'masonry_with_space pinterest_space';
			}
		} elseif ( $params['type'] == "standard_no_space" ) {
			$params['_type_class']            = " standard_no_space";
			$params['_portfolio_space_class'] = "portfolio_no_space portfolio_standard";
		} elseif ( $params['type'] == "hover_text_no_space" ) {
			$params['_type_class']            = " hover_text no_space";
			$params['_portfolio_space_class'] = "portfolio_no_space portfolio_with_hover_text";
		} elseif ( $params['type'] == "justified_gallery" ) {
			$params['_type_class']            = " justified_gallery";
			$params['_portfolio_space_class'] = "portfolio_no_space";
		}
		
		$params['filter_position_class'] = $this->getFilterPositionClass( $params );
		
		//get proper image size
		switch ( $params['image_size'] ) {
			case 'landscape':
				$params['thumb_size_class'] = 'portfolio_landscape_image';
				break;
			case 'portrait':
				$params['thumb_size_class'] = 'portfolio_portrait_image';
				break;
			case 'square':
				$params['thumb_size_class'] = 'portfolio_square_image';
				break;
			case 'full':
				$params['thumb_size_class'] = 'portfolio_full_image';
				break;
			default:
				$params['thumb_size_class'] = 'portfolio_default_image';
				break;
		}
		
		if ( $params['type'] == 'justified_gallery' ) {
			$params['hover_type'] = " justified_gallery_hover ";
		}
		
		if ( $params['portfolio_loading_type'] != "" ) {
			$params['_loading_class'] = $params['portfolio_loading_type'];
		}
		
		if ( $params['type'] == 'masonry_with_space' && $params['pinterest_hover_type'] == "info_on_hover" ) {
			$params['hover_type'] = " pinterest_info_on_hover ";
		}
		
		if ( $params['masonry_space'] == 'yes' ) {
			$params['_portfolio_masonry_class'] .= 'masonry_extended';
		}
		
		$params['portfolio_box_style']         = "";
		$params['portfolio_description_class'] = "";
		
		if ( $params['box_border'] == "yes" || $params['box_background_color'] != "" ) {
			
			$params['portfolio_box_style'] .= "style=";
			if ( $params['box_border'] == "yes" ) {
				$params['portfolio_box_style'] .= "border-style:solid;";
				if ( $params['box_border_color'] != "" ) {
					$params['portfolio_box_style'] .= "border-color:" . $params['box_border_color'] . ";";
				}
				if ( $params['box_border_width'] != "" ) {
					$params['portfolio_box_style'] .= "border-width:" . $params['box_border_width'] . "px;";
				} else {
					$params['portfolio_box_style'] .= "border-width: 1px;";
				}
			}
			if ( $params['box_background_color'] != "" ) {
				$params['portfolio_box_style'] .= "background-color:" . $params['box_background_color'] . ";";
			}
			$params['portfolio_box_style'] .= "'";
			
			$params['portfolio_description_class'] .= 'with_padding';
			
			$params['_portfolio_space_class'] = ' with_description_background';
			
		}
		
		if ( $params['text_align'] !== '' ) {
			$params['portfolio_description_class'] .= ' text_align_' . $params['text_align'];
		}
		
		$query_array             = $this->getQueryArray( $params );
		$query_results           = new \WP_Query( $query_array );
		$params['query_results'] = $query_results;
		
		$params['slug_list_'] = "pretty_photo_gallery";
		$params['title']      = get_the_title();
		
		return stockholm_qode_get_shortcode_template_part( 'templates/portfolio-holder', 'portfolio-list', $params['type'], $params );
	}
	
	public function getQueryArray( $params ) {
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		if ( $params['category'] == "" ) {
			$args = array(
				'post_status'    => 'publish',
				'post_type'      => 'portfolio_page',
				'orderby'        => $params['order_by'],
				'order'          => $params['order'],
				'posts_per_page' => $params['number'],
				'paged'          => $paged
			);
		} else {
			$args = array(
				'post_status'        => 'publish',
				'post_type'          => 'portfolio_page',
				'portfolio_category' => $params['category'],
				'orderby'            => $params['order_by'],
				'order'              => $params['order'],
				'posts_per_page'     => $params['number'],
				'paged'              => $paged
			);
		}
		$project_ids = null;
		if ( $params['selected_projects'] != "" ) {
			$project_ids      = explode( ",", $params['selected_projects'] );
			$args['post__in'] = $project_ids;
		}
		
		return $args;
	}
	
	public function getMasonryItemClasses( $params ) {
		$classes = array();
		
		$terms = wp_get_post_terms( get_the_ID(), 'portfolio_category' );
		foreach ( $terms as $term ) {
			$classes[] = "portfolio_category_" . $term->term_id;
		}
		
		$masonry_parallax = get_post_meta( get_the_ID(), "qode_portfolio_masonry_parallax", true );
		if ( $masonry_parallax == "yes" ) {
			$classes[] = "parallax_item";
		}
		
		$masonry_size = get_post_meta( get_the_ID(), "qode_portfolio_type_masonry_style", true );
		$classes[]    = $masonry_size;
		
		return $classes;
	}
	
	public function getItemClasses( $params ) {
		$classes = array();
		
		$terms = wp_get_post_terms( get_the_ID(), 'portfolio_category' );
		foreach ( $terms as $term ) {
			$classes[] = "portfolio_category_" . $term->term_id;
		}
		
		return $classes;
	}
	
	public function getFilterPositionClass( $params ) {
		$classes         = array();
		$filter_position = $params['filter_position'];
		
		switch ( $filter_position ) {
			case 'top':
				$classes[] = 'qode-filter-position-top';
				break;
			case 'left':
				$classes[] = 'qode-filter-position-left';
				break;
			default:
				$classes[] = 'qode-filter-position-top';
				break;
		}
		
		return $classes;
	}
}