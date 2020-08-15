<?php
if(!function_exists('stockholm_core_demos_list')) {

	function stockholm_core_demos_list() {

		$demos = array(
			'stockholm1' => array(
				'title' => esc_html__('Stockholm 1 - Anders', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm2' => array(
				'title' => esc_html__('Stockholm 2 - Bjorn', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm3' => array(
				'title' => esc_html__('Stockholm 3 - Claes', 'stockholm-core'),
				'rev-sliders' => array(),
                'layer-sliders' => array()
			),
			'stockholm4' => array(
				'title' => esc_html__('Stockholm 4 - Daniel', 'stockholm-core'),
				'rev-sliders' => array(),
                'layer-sliders' => array(
                    'sliders' => array('LayerSlider_Export_Stockholm4.zip'),
                    'pairs' => array( 24 => 1),
                    'slider_in_content' => true
                )
			),
			'stockholm5' => array(
				'title' => esc_html__('Stockholm 5 - Erland', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm6' => array(
				'title' => esc_html__('Stockholm 6 - Fredrik', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm7' => array(
				'title' => esc_html__('Stockholm 7 - Gustav', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm8' => array(
				'title' => esc_html__('Stockholm 8 - Hugo', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array(),
				'required-plugins' => array('js_composer', 'revslider', 'contact-form-7', 'woocommerce', 'LayerSlider', 'qode-instagram-widget', 'qode-twitter-feed')
			),
			'stockholm9' => array(
				'title' => esc_html__('Stockholm 9 - Ingmar', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm10' => array(
				'title' => esc_html__('Stockholm 10 - Jonas', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm11' => array(
				'title' => esc_html__('Stockholm 11 - Kaleb', 'stockholm-core'),
				'rev-sliders' => array(),
                'layer-sliders' => array(
                    'sliders' => array('LayerSlider_Export_Stockholm11.zip'),
                    'pairs' => array( 24 => 1),
                    'slider_in_content' => true
                )
			),
			'stockholm12' => array(
				'title' => esc_html__('Stockholm 12 - Lars', 'stockholm-core'),
				'rev-sliders' => array(),
                'layer-sliders' => array(
                    'sliders' => array('LayerSlider_Export_Stockholm12.zip'),
                    'pairs' => array( 26 => 1),
                    'slider_in_content' => true
                )
			),
			'stockholm13' => array(
				'title' => esc_html__('Stockholm 13 - Magnus', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm14' => array(
				'title' => esc_html__('Stockholm 14 - Noel', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm15' => array(
				'title' => esc_html__('Stockholm 15 - Oskar', 'stockholm-core'),
				'rev-sliders' => array(),
                'layer-sliders' => array(
                    'sliders' => array('LayerSlider_Export_Stockholm15.zip'),
                    'pairs' => array( 24 => 1),
                    'slider_in_content' => true
                )
			),
			'stockholm16' => array(
				'title' => esc_html__('Stockholm 16 - Petter', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm17' => array(
				'title' => esc_html__('Stockholm 17 - Roland', 'stockholm-core'),
				'rev-sliders' => array(),
                'layer-sliders' => array(
                    'sliders' => array('LayerSlider_Export_Stockholm17.zip'),
                    'pairs' => array( 26 => 1),
                    'slider_in_content' => true
                )
			),
			'stockholm18' => array(
				'title' => esc_html__('Stockholm 18 - Sigfrid', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm19' => array(
				'title' => esc_html__('Stockholm 19 - Tomas', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
			'stockholm20' => array(
				'title' => esc_html__('Stockholm 20 - Viggo', 'stockholm-core'),
				'rev-sliders' => array(),
				'layer-sliders' => array()
			),
            'stockholmnew1' => array(
                'title' => esc_html__('New Demo 1 - Kelda', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew2' => array(
                'title' => esc_html__('New Demo 2 - Frida', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew3' => array(
                'title' => esc_html__('New Demo 3 - Jette', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew4' => array(
                'title' => esc_html__('New Demo 4 - Metta', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew5' => array(
                'title' => esc_html__('New Demo 5 - Göta', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew6' => array(
                'title' => esc_html__('New Demo 6 - Eva', 'stockholm-core'),
                'rev-sliders' => array('blog-slider.zip'),
                'layer-sliders' => array()
            ),
            'stockholmnew7' => array(
                'title' => esc_html__('New Demo 7 - Tilde', 'stockholm-core'),
                'rev-sliders' => array('blog-pinterest-slider.zip'),
                'layer-sliders' => array()
            ),
            'stockholmnew8' => array(
                'title' => esc_html__('New Demo 8 - Ebba', 'stockholm-core'),
                'rev-sliders' => array('pinterest-slider.zip'),
                'layer-sliders' => array()
            ),
            'stockholmnew9' => array(
                'title' => esc_html__('New Demo 9 - Rona', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew10' => array(
                'title' => esc_html__('New Demo 10 - Tove', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew11' => array(
                'title' => esc_html__('New Demo 11 - Solveig', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew12' => array(
                'title' => esc_html__('New Demo 12 - Hilde', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew13' => array(
                'title' => esc_html__('New Demo 13 - Kajsa', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew14' => array(
                'title' => esc_html__('New Demo 14 - Elke', 'stockholm-core'),
                'rev-sliders' => array('shop-slider.zip'),
                'layer-sliders' => array()
            ),
            'stockholmnew15' => array(
                'title' => esc_html__('New Demo 15 - Anke', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew16' => array(
                'title' => esc_html__('New Demo 16 - Dagmar', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew17' => array(
                'title' => esc_html__('New Demo 17 - Tala', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew18' => array(
                'title' => esc_html__('New Demo 18 - Oda', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew19' => array(
                'title' => esc_html__('New Demo 19 - Ylva', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew20' => array(
                'title' => esc_html__('New Demo 20 - Gala', 'stockholm-core'),
                'rev-sliders' => array(),
                'layer-sliders' => array()
            ),
            'stockholmnew21' => array(
                'title' => esc_html__('New Demo 21 - Olaf', 'stockholm-core'),
                'rev-sliders' => array('slider-1.zip'),
                'layer-sliders' => array()
            ),
            'stockholmnew22' => array(
                'title' => esc_html__('New Demo 22 - Freya', 'stockholm-core'),
                'rev-sliders' => array('slider-1.zip'),
                'layer-sliders' => array()
            ),
            'stockholmnew23' => array(
                'title' => esc_html__('New Demo 23 - Gunnar', 'stockholm-core'),
                'rev-sliders' => array('slider-1.zip'),
                'layer-sliders' => array()
            )
		);

		return $demos;
	}
}