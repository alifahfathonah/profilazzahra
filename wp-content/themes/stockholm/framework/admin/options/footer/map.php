<?php

if ( ! function_exists( 'stockholm_qode_footer_options_map' ) ) {
	function stockholm_qode_footer_options_map() {
		
		$footerPage = new StockholmQodeAdminPage(
			"3",
			esc_html__( "Footer", 'stockholm' )
		);
		stockholm_qode_framework()->qodeOptions->addAdminPage(
			"footer",
			$footerPage
		);
		
		$panel10 = new StockholmQodePanel(
			esc_html__( "Footer", 'stockholm' ),
			"footer_panel"
		);
		$footerPage->addChild(
			"panel10",
			$panel10
		);
		
		$uncovering_footer = new StockholmQodeField(
			"yesno",
			"uncovering_footer",
			"no",
			esc_html__( "Uncovering Footer", 'stockholm' ),
			esc_html__( "Enabling this option will make Footer gradually appear on scroll", 'stockholm' )
		);
		$panel10->addChild(
			"uncovering_footer",
			$uncovering_footer
		);
		
		$footer_in_grid = new StockholmQodeField(
			"yesno",
			"footer_in_grid",
			"yes",
			esc_html__( "Footer in Grid", 'stockholm' ),
			esc_html__( "Enabling this option will place Footer content in grid", 'stockholm' )
		);
		$panel10->addChild(
			"footer_in_grid",
			$footer_in_grid
		);
		
		$show_footer_top = new StockholmQodeField(
			"yesno",
			"show_footer_top",
			"yes",
			esc_html__( "Show Footer Top", 'stockholm' ),
			esc_html__( "Enabling this option will show Footer Top area", 'stockholm' ),
			array(),
			array(
				"dependence"             => true,
				"dependence_hide_on_yes" => "",
				"dependence_show_on_yes" => "#qodef_show_footer_top_container"
			)
		);
		$panel10->addChild(
			"show_footer_top",
			$show_footer_top
		);
		
		$show_footer_top_container = new StockholmQodeContainer(
			"show_footer_top_container",
			"show_footer_top",
			"no"
		);
		$panel10->addChild(
			"show_footer_top_container",
			$show_footer_top_container
		);
		
		$footer_top_columns = new StockholmQodeField(
			"select",
			"footer_top_columns",
			"4",
			esc_html__( "Footer Top Columns", 'stockholm' ),
			esc_html__( "Choose number of columns for Footer Top area", 'stockholm' ),
			array(
				"1" => esc_html__( "1", 'stockholm' ),
				"2" => esc_html__( "2", 'stockholm' ),
				"3" => esc_html__( "3", 'stockholm' ),
				"5" => esc_html__( "3(25%+25%+50%)", 'stockholm' ),
				"6" => esc_html__( "3(50%+25%+25%)", 'stockholm' ),
				"4" => esc_html__( "4", 'stockholm' )
			)
		);
		$show_footer_top_container->addChild(
			"footer_top_columns",
			$footer_top_columns
		);
		
		$footer_border_columns = new StockholmQodeField(
			"yesno",
			"footer_border_columns",
			"yes",
			esc_html__( "Border Between Columns", 'stockholm' ),
			esc_html__( "Disabling this option will remove border between footer columns.", 'stockholm' )
		);
		$show_footer_top_container->addChild(
			"footer_border_columns",
			$footer_border_columns
		);
		
		$group1 = new StockholmQodeGroup(
			esc_html__( "Footer Top Area Style", 'stockholm' ),
			esc_html__( "Configure style for Footer Top area", 'stockholm' )
		);
		$show_footer_top_container->addChild(
			"group1",
			$group1
		);
		
		$row1 = new StockholmQodeRow();
		$group1->addChild(
			"row1",
			$row1
		);
		$footer_top_background_color = new StockholmQodeField(
			"colorsimple",
			"footer_top_background_color",
			"",
			esc_html__( "Background Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_background_color",
			$footer_top_background_color
		);
		$footer_top_border_color = new StockholmQodeField(
			"colorsimple",
			"footer_top_border_color",
			"",
			esc_html__( "Top Border Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_border_color",
			$footer_top_border_color
		);
		$footer_top_border_in_grid = new StockholmQodeField(
			"yesnosimple",
			"footer_top_border_in_grid",
			"no",
			esc_html__( "Set Top Border In Grid", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_border_in_grid",
			$footer_top_border_in_grid
		);
		$footer_columns_border_color = new StockholmQodeField(
			"colorsimple",
			"footer_columns_border_color",
			"",
			esc_html__( "Columns Border Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_columns_border_color",
			$footer_columns_border_color
		);
		$row2 = new StockholmQodeRow();
		$group1->addChild(
			"row2",
			$row2
		);
		$footer_top_padding = new StockholmQodeField(
			"textsimple",
			"footer_top_padding",
			"",
			esc_html__( "Top Padding(px)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_top_padding",
			$footer_top_padding
		);
		$footer_bottom_padding = new StockholmQodeField(
			"textsimple",
			"footer_bottom_padding",
			"",
			esc_html__( "Bottom Padding(px)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_bottom_padding",
			$footer_bottom_padding
		);
		$footer_left_padding = new StockholmQodeField(
			"textsimple",
			"footer_left_padding",
			"",
			esc_html__( "Left Padding(px or %)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_left_padding",
			$footer_left_padding
		);
		$footer_right_padding = new StockholmQodeField(
			"textsimple",
			"footer_right_padding",
			"",
			esc_html__( "Right Padding(px or %)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_right_padding",
			$footer_right_padding
		);
		
		$group2 = new StockholmQodeGroup(
			esc_html__( "Footer Top Title Style", 'stockholm' ),
			esc_html__( "Configure style for Footer Top title", 'stockholm' )
		);
		$show_footer_top_container->addChild(
			"group2",
			$group2
		);
		
		$row1 = new StockholmQodeRow();
		$group2->addChild(
			"row1",
			$row1
		);
		$footer_title_color = new StockholmQodeField(
			"colorsimple",
			"footer_title_color",
			"",
			esc_html__( "Text Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_title_color",
			$footer_title_color
		);
		
		$footer_title_font_size = new StockholmQodeField(
			"textsimple",
			"footer_title_font_size",
			"",
			esc_html__( "Font Size (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_title_font_size",
			$footer_title_font_size
		);
		
		$footer_title_line_height = new StockholmQodeField(
			"textsimple",
			"footer_title_line_height",
			"",
			esc_html__( "Line Height (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_title_line_height",
			$footer_title_line_height
		);
		
		$footer_title_text_transform = new StockholmQodeField(
			"selectblanksimple",
			"footer_title_text_transform",
			"",
			esc_html__( "Text Transform", 'stockholm' ),
			"",
			stockholm_qode_get_text_transform_array()
		);
		$row1->addChild(
			"footer_title_text_transform",
			$footer_title_text_transform
		);
		
		$row2 = new StockholmQodeRow( true );
		$group2->addChild(
			"row2",
			$row2
		);
		$footer_title_font_family = new StockholmQodeField(
			esc_html__( "Fontsimple", 'stockholm' ),
			"footer_title_font_family",
			"-1",
			esc_html__( "Font Family", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_title_font_family",
			$footer_title_font_family
		);
		
		$footer_title_font_style = new StockholmQodeField(
			"selectblanksimple",
			"footer_title_font_style",
			"",
			esc_html__( "Font Style", 'stockholm' ),
			"",
			stockholm_qode_get_font_style_array()
		);
		$row2->addChild(
			"footer_title_font_style",
			$footer_title_font_style
		);
		
		$footer_title_font_weight = new StockholmQodeField(
			"selectblanksimple",
			"footer_title_font_weight",
			"",
			esc_html__( "Font Weight", 'stockholm' ),
			"",
			stockholm_qode_get_font_weight_array()
		);
		$row2->addChild(
			"footer_title_font_weight",
			$footer_title_font_weight
		);
		
		$footer_title_letter_spacing = new StockholmQodeField(
			"textsimple",
			"footer_title_letter_spacing",
			"",
			esc_html__( "Letter Spacing (px)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_title_letter_spacing",
			$footer_title_letter_spacing
		);
		
		$group3 = new StockholmQodeGroup(
			esc_html__( "Footer Top Text Style", 'stockholm' ),
			esc_html__( "Configure style for Footer Top text", 'stockholm' )
		);
		$show_footer_top_container->addChild(
			"group3",
			$group3
		);
		
		$row1 = new StockholmQodeRow();
		$group3->addChild(
			"row1",
			$row1
		);
		$footer_top_text_color = new StockholmQodeField(
			"colorsimple",
			"footer_top_text_color",
			"",
			esc_html__( "Text Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_text_color",
			$footer_top_text_color
		);
		
		$footer_top_text_font_size = new StockholmQodeField(
			"textsimple",
			"footer_top_text_font_size",
			"",
			esc_html__( "Font Size (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_text_font_size",
			$footer_top_text_font_size
		);
		
		$footer_top_text_line_height = new StockholmQodeField(
			"textsimple",
			"footer_top_text_line_height",
			"",
			esc_html__( "Line Height (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_text_line_height",
			$footer_top_text_line_height
		);
		
		$footer_top_text_text_transform = new StockholmQodeField(
			"selectblanksimple",
			"footer_top_text_text_transform",
			"",
			esc_html__( "Text Transform", 'stockholm' ),
			"",
			stockholm_qode_get_text_transform_array()
		);
		$row1->addChild(
			"footer_top_text_text_transform",
			$footer_top_text_text_transform
		);
		
		$row2 = new StockholmQodeRow( true );
		$group3->addChild(
			"row2",
			$row2
		);
		$footer_top_text_font_family = new StockholmQodeField(
			esc_html__( "Fontsimple", 'stockholm' ),
			"footer_top_text_font_family",
			"-1",
			esc_html__( "Font Family", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_top_text_font_family",
			$footer_top_text_font_family
		);
		
		$footer_top_text_font_style = new StockholmQodeField(
			"selectblanksimple",
			"footer_top_text_font_style",
			"",
			esc_html__( "Font Style", 'stockholm' ),
			"",
			stockholm_qode_get_font_style_array()
		);
		$row2->addChild(
			"footer_top_text_font_style",
			$footer_top_text_font_style
		);
		
		$footer_top_text_font_weight = new StockholmQodeField(
			"selectblanksimple",
			"footer_top_text_font_weight",
			"",
			esc_html__( "Font Weight", 'stockholm' ),
			"",
			stockholm_qode_get_font_weight_array()
		);
		$row2->addChild(
			"footer_top_text_font_weight",
			$footer_top_text_font_weight
		);
		
		$footer_top_text_letter_spacing = new StockholmQodeField(
			"textsimple",
			"footer_top_text_letter_spacing",
			"",
			esc_html__( "Letter Spacing (px)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_top_text_letter_spacing",
			$footer_top_text_letter_spacing
		);
		
		$group4 = new StockholmQodeGroup(
			esc_html__( "Footer Top Link Style", 'stockholm' ),
			esc_html__( "Configure style for Footer Top link", 'stockholm' )
		);
		$show_footer_top_container->addChild(
			"group4",
			$group4
		);
		
		$row1 = new StockholmQodeRow();
		$group4->addChild(
			"row1",
			$row1
		);
		$footer_top_link_color = new StockholmQodeField(
			"colorsimple",
			"footer_top_link_color",
			"",
			esc_html__( "Text Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_link_color",
			$footer_top_link_color
		);
		
		$footer_top_link_font_size = new StockholmQodeField(
			"textsimple",
			"footer_top_link_font_size",
			"",
			esc_html__( "Font Size (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_link_font_size",
			$footer_top_link_font_size
		);
		
		$footer_top_link_line_height = new StockholmQodeField(
			"textsimple",
			"footer_top_link_line_height",
			"",
			esc_html__( "Line Height (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_top_link_line_height",
			$footer_top_link_line_height
		);
		
		$footer_top_link_text_transform = new StockholmQodeField(
			"selectblanksimple",
			"footer_top_link_text_transform",
			"",
			esc_html__( "Text Transform", 'stockholm' ),
			"",
			stockholm_qode_get_text_transform_array()
		);
		$row1->addChild(
			"footer_top_link_text_transform",
			$footer_top_link_text_transform
		);
		
		$row2 = new StockholmQodeRow( true );
		$group4->addChild(
			"row2",
			$row2
		);
		$footer_top_link_font_family = new StockholmQodeField(
			esc_html__( "Fontsimple", 'stockholm' ),
			"footer_top_link_font_family",
			"-1",
			esc_html__( "Font Family", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_top_link_font_family",
			$footer_top_link_font_family
		);
		
		$footer_top_link_font_style = new StockholmQodeField(
			"selectblanksimple",
			"footer_top_link_font_style",
			"",
			esc_html__( "Font Style", 'stockholm' ),
			"",
			stockholm_qode_get_font_style_array()
		);
		$row2->addChild(
			"footer_top_link_font_style",
			$footer_top_link_font_style
		);
		
		$footer_top_link_font_weight = new StockholmQodeField(
			"selectblanksimple",
			"footer_top_link_font_weight",
			"",
			esc_html__( "Font Weight", 'stockholm' ),
			"",
			stockholm_qode_get_font_weight_array()
		);
		$row2->addChild(
			"footer_top_link_font_weight",
			$footer_top_link_font_weight
		);
		
		$footer_top_link_letter_spacing = new StockholmQodeField(
			"textsimple",
			"footer_top_link_letter_spacing",
			"",
			esc_html__( "Letter Spacing (px)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_top_link_letter_spacing",
			$footer_top_link_letter_spacing
		);
		
		$row3 = new StockholmQodeRow( true );
		$group4->addChild(
			"row3",
			$row3
		);
		$footer_top_link_hover_color = new StockholmQodeField(
			"colorsimple",
			"footer_top_link_hover_color",
			"",
			esc_html__( "Text Hover Color", 'stockholm' ),
			""
		);
		$row3->addChild(
			"footer_top_link_hover_color",
			$footer_top_link_hover_color
		);
		
		$footer_text = new StockholmQodeField(
			"yesno",
			"footer_text",
			"yes",
			esc_html__( "Show Footer Bottom", 'stockholm' ),
			esc_html__( "Enabling this option will show Footer Bottom area", 'stockholm' ),
			array(),
			array(
				"dependence"             => true,
				"dependence_hide_on_yes" => "",
				"dependence_show_on_yes" => "#qodef_footer_text_container"
			)
		);
		$panel10->addChild(
			"footer_text",
			$footer_text
		);
		$footer_text_container = new StockholmQodeContainer(
			"footer_text_container",
			"footer_text",
			"no"
		);
		$panel10->addChild(
			"footer_text_container",
			$footer_text_container
		);
		
		$group5 = new StockholmQodeGroup(
			esc_html__( "Footer Bottom Area Style", 'stockholm' ),
			esc_html__( "Configure style for Footer Bottom area", 'stockholm' )
		);
		$footer_text_container->addChild(
			"group5",
			$group5
		);
		
		$row1 = new StockholmQodeRow();
		$group5->addChild(
			"row1",
			$row1
		);
		$footer_bottom_height = new StockholmQodeField(
			"textsimple",
			"footer_bottom_height",
			"",
			esc_html__( "Height (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_height",
			$footer_bottom_height
		);
		$footer_bottom_background_color = new StockholmQodeField(
			"colorsimple",
			"footer_bottom_background_color",
			"",
			esc_html__( "Background Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_background_color",
			$footer_bottom_background_color
		);
		$footer_bottom_border_color = new StockholmQodeField(
			"colorsimple",
			"footer_bottom_border_color",
			"",
			esc_html__( "Top Border Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_border_color",
			$footer_bottom_border_color
		);
		$footer_bottom_border_in_grid = new StockholmQodeField(
			"yesnosimple",
			"footer_bottom_border_in_grid",
			"no",
			esc_html__( "Set Top Border In Grid", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_border_in_grid",
			$footer_bottom_border_in_grid
		);
		
		$group6 = new StockholmQodeGroup(
			esc_html__( "Footer Bottom Text Style", 'stockholm' ),
			esc_html__( "Configure style for Footer Bottom text", 'stockholm' )
		);
		$footer_text_container->addChild(
			"group6",
			$group6
		);
		
		$row1 = new StockholmQodeRow();
		$group6->addChild(
			"row1",
			$row1
		);
		$footer_bottom_text_color = new StockholmQodeField(
			"colorsimple",
			"footer_bottom_text_color",
			"",
			esc_html__( "Text Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_text_color",
			$footer_bottom_text_color
		);
		
		$footer_bottom_text_font_size = new StockholmQodeField(
			"textsimple",
			"footer_bottom_text_font_size",
			"",
			esc_html__( "Font Size (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_text_font_size",
			$footer_bottom_text_font_size
		);
		
		$footer_bottom_text_line_height = new StockholmQodeField(
			"textsimple",
			"footer_bottom_text_line_height",
			"",
			esc_html__( "Line Height (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_text_line_height",
			$footer_bottom_text_line_height
		);
		
		$footer_bottom_text_text_transform = new StockholmQodeField(
			"selectblanksimple",
			"footer_bottom_text_text_transform",
			"",
			esc_html__( "Text Transform", 'stockholm' ),
			"",
			stockholm_qode_get_text_transform_array()
		);
		$row1->addChild(
			"footer_bottom_text_text_transform",
			$footer_bottom_text_text_transform
		);
		
		$row2 = new StockholmQodeRow( true );
		$group6->addChild(
			"row2",
			$row2
		);
		$footer_bottom_text_font_family = new StockholmQodeField(
			esc_html__( "Fontsimple", 'stockholm' ),
			"footer_bottom_text_font_family",
			"-1",
			esc_html__( "Font Family", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_bottom_text_font_family",
			$footer_bottom_text_font_family
		);
		
		$footer_bottom_text_font_style = new StockholmQodeField(
			"selectblanksimple",
			"footer_bottom_text_font_style",
			"",
			esc_html__( "Font Style", 'stockholm' ),
			"",
			stockholm_qode_get_font_style_array()
		);
		$row2->addChild(
			"footer_bottom_text_font_style",
			$footer_bottom_text_font_style
		);
		
		$footer_bottom_text_font_weight = new StockholmQodeField(
			"selectblanksimple",
			"footer_bottom_text_font_weight",
			"",
			esc_html__( "Font Weight", 'stockholm' ),
			"",
			stockholm_qode_get_font_weight_array()
		);
		$row2->addChild(
			"footer_bottom_text_font_weight",
			$footer_bottom_text_font_weight
		);
		
		$footer_bottom_text_letter_spacing = new StockholmQodeField(
			"textsimple",
			"footer_bottom_text_letter_spacing",
			"",
			esc_html__( "Letter Spacing (px)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_bottom_text_letter_spacing",
			$footer_bottom_text_letter_spacing
		);
		
		$group7 = new StockholmQodeGroup(
			esc_html__( "Footer Bottom Link Style", 'stockholm' ),
			esc_html__( "Configure style for Footer Bottom link", 'stockholm' )
		);
		$footer_text_container->addChild(
			"group7",
			$group7
		);
		
		$row1 = new StockholmQodeRow();
		$group7->addChild(
			"row1",
			$row1
		);
		$footer_bottom_link_color = new StockholmQodeField(
			"colorsimple",
			"footer_bottom_link_color",
			"",
			esc_html__( "Text Color", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_link_color",
			$footer_bottom_link_color
		);
		
		$footer_bottom_link_font_size = new StockholmQodeField(
			"textsimple",
			"footer_bottom_link_font_size",
			"",
			esc_html__( "Font Size (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_link_font_size",
			$footer_bottom_link_font_size
		);
		
		$footer_bottom_link_line_height = new StockholmQodeField(
			"textsimple",
			"footer_bottom_link_line_height",
			"",
			esc_html__( "Line Height (px)", 'stockholm' ),
			""
		);
		$row1->addChild(
			"footer_bottom_link_line_height",
			$footer_bottom_link_line_height
		);
		
		$footer_bottom_link_text_transform = new StockholmQodeField(
			"selectblanksimple",
			"footer_bottom_link_text_transform",
			"",
			esc_html__( "Text Transform", 'stockholm' ),
			"",
			stockholm_qode_get_text_transform_array()
		);
		$row1->addChild(
			"footer_bottom_link_text_transform",
			$footer_bottom_link_text_transform
		);
		
		$row2 = new StockholmQodeRow( true );
		$group7->addChild(
			"row2",
			$row2
		);
		$footer_bottom_link_font_family = new StockholmQodeField(
			esc_html__( "Fontsimple", 'stockholm' ),
			"footer_bottom_link_font_family",
			"-1",
			esc_html__( "Font Family", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_bottom_link_font_family",
			$footer_bottom_link_font_family
		);
		
		$footer_bottom_link_font_style = new StockholmQodeField(
			"selectblanksimple",
			"footer_bottom_link_font_style",
			"",
			esc_html__( "Font Style", 'stockholm' ),
			"",
			stockholm_qode_get_font_style_array()
		);
		$row2->addChild(
			"footer_bottom_link_font_style",
			$footer_bottom_link_font_style
		);
		
		$footer_bottom_link_font_weight = new StockholmQodeField(
			"selectblanksimple",
			"footer_bottom_link_font_weight",
			"",
			esc_html__( "Font Weight", 'stockholm' ),
			"",
			stockholm_qode_get_font_weight_array()
		);
		$row2->addChild(
			"footer_bottom_link_font_weight",
			$footer_bottom_link_font_weight
		);
		
		$footer_bottom_link_letter_spacing = new StockholmQodeField(
			"textsimple",
			"footer_bottom_link_letter_spacing",
			"",
			esc_html__( "Letter Spacing (px)", 'stockholm' ),
			""
		);
		$row2->addChild(
			"footer_bottom_link_letter_spacing",
			$footer_bottom_link_letter_spacing
		);
		
		$row3 = new StockholmQodeRow( true );
		$group7->addChild(
			"row3",
			$row3
		);
		$footer_bottom_link_hover_color = new StockholmQodeField(
			"colorsimple",
			"footer_bottom_link_hover_color",
			"",
			esc_html__( "Text Hover Color", 'stockholm' ),
			""
		);
		$row3->addChild(
			"footer_bottom_link_hover_color",
			$footer_bottom_link_hover_color
		);
	}
	
	add_action( 'stockholm_qode_action_options_map', 'stockholm_qode_footer_options_map', 40 );
}