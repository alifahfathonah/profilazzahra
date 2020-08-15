<?php
namespace Stockholm\Shortcodes\ContentSlide;

use Stockholm\Shortcodes\Lib\ShortcodeInterface;

class ContentSlide implements ShortcodeInterface {

    private $base;

    function __construct() {
        $this->base = 'qode_content_slide';
		add_action('stockholm_qode_action_vc_map', array($this, 'vcMap'));
    }

    public function getBase() {
        return $this->base;
    }

    public function vcMap() {
        vc_map(array(
                'name' => esc_html__('Content Slide','stockholm-core'),
                'base' => $this->base,
                'as_child' => array('only' => 'qode_elliptical_slider'),
				'as_parent' => array('except' => 'vc_tabs, vc_accordion, cover_boxes, portfolio_list, portfolio_slider, qode_carousel'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				'category' => esc_html__('by SELECT','stockholm-core'),
                'icon' => 'extended-custom-icon-qode icon-wpb-content-slide',
				'show_settings_on_create' => false,
				'js_view' => 'VcColumnView',
				'content_element'	=> true,
                'params' => array()
        ));
    }

    public function render($atts, $content = null) {

        $args = array(
            'image' => '',
            'elliptical_section_background_color' => ''
        );

        $params = shortcode_atts($args, $atts);

        $params['content'] = $content;


        $html = stockholm_qode_get_shortcode_template_part('templates/content-slide-template', 'content-slider', '', $params);

        return $html;
    }





}