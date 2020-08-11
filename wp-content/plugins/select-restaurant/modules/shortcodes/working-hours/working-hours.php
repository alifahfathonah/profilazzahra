<?php
namespace StockholmQodeRestaurant\Shortcodes\WorkingHours;

use StockholmQodeRestaurant\Lib\ShortcodeInterface;

class WorkingHours implements ShortcodeInterface {
	private $base;

	public function __construct() {
		$this->base = 'qode_working_hours';

		add_action('vc_before_init', array($this, 'vcMap'));
	}

	public function getBase() {
		return $this->base;
	}

	public function vcMap() {
		vc_map(array(
			'name'                      => esc_html__('Working Hours', 'select-restaurant'),
			'base'                      => $this->base,
			'category'                  => 'by SELECT RESTAURANT',
			'icon'                      => 'icon-wpb-working-hours extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Items Title Tag', 'select-restaurant'),
					'param_name'  => 'items_title_tag',
					'value' => array_flip( stockholm_qode_get_title_tag( true ) )
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__('Color', 'select-restaurant'),
					'param_name'  => 'color'
				),
			)
		));
	}

	public function render($atts, $content = null) {
		$default_atts = array(
			'items_title_tag'   => 'h6',
			'color'				=> ''
		);

		$params = shortcode_atts($default_atts, $atts);

		$params['working_hours']  = $this->getWorkingHours();
		$params['holder_classes'] = $this->getHolderClasses($params);
		$params['holder_styles']  = $this->getHolderStyles($params);
		$params['item_styles']	  = $this->getItemStyles($params);

		return stockholm_qode_restaurant_get_template_part('modules/shortcodes/working-hours/templates/working-hours-template', '', $params, true);
	}

	private function getWorkingHours() {
		$workingHours = array();

		if(stockholm_qode_restaurant_theme_installed()) {
			//monday
			if(stockholm_qode_options()->getOptionValue('wh_monday_from') !== '') {
				$workingHours['monday']['label'] = esc_html__('Monday', 'select-restaurant');
				$workingHours['monday']['from']  = stockholm_qode_options()->getOptionValue('wh_monday_from');
			}

			if(stockholm_qode_options()->getOptionValue('wh_monday_to') !== '') {
				$workingHours['monday']['to'] = stockholm_qode_options()->getOptionValue('wh_monday_to');
			}

			if(stockholm_qode_options()->getOptionValue('wh_monday_closed') !== '') {
				$workingHours['monday']['closed'] = stockholm_qode_options()->getOptionValue('wh_monday_closed');
			}

			//tuesday
			if(stockholm_qode_options()->getOptionValue('wh_tuesday_from') !== '') {
				$workingHours['tuesday']['label'] = esc_html__('Tuesday', 'select-restaurant');
				$workingHours['tuesday']['from']  = stockholm_qode_options()->getOptionValue('wh_tuesday_from');
			}

			if(stockholm_qode_options()->getOptionValue('wh_tuesday_to') !== '') {
				$workingHours['tuesday']['to'] = stockholm_qode_options()->getOptionValue('wh_tuesday_to');
			}

			if(stockholm_qode_options()->getOptionValue('wh_tuesday_closed') !== '') {
				$workingHours['tuesday']['closed'] = stockholm_qode_options()->getOptionValue('wh_tuesday_closed');
			}

			//wednesday
			if(stockholm_qode_options()->getOptionValue('wh_wednesday_from') !== '') {
				$workingHours['wednesday']['label'] = esc_html__('Wednesday', 'select-restaurant');
				$workingHours['wednesday']['from']  = stockholm_qode_options()->getOptionValue('wh_wednesday_from');
			}

			if(stockholm_qode_options()->getOptionValue('wh_wednesday_to') !== '') {
				$workingHours['wednesday']['to'] = stockholm_qode_options()->getOptionValue('wh_wednesday_to');
			}

			if(stockholm_qode_options()->getOptionValue('wh_wednesday_closed') !== '') {
				$workingHours['wednesday']['closed'] = stockholm_qode_options()->getOptionValue('wh_wednesday_closed');
			}

			//thursday
			if(stockholm_qode_options()->getOptionValue('wh_thursday_from') !== '') {
				$workingHours['thursday']['label'] = esc_html__('Thursday', 'select-restaurant');
				$workingHours['thursday']['from']  = stockholm_qode_options()->getOptionValue('wh_thursday_from');
			}

			if(stockholm_qode_options()->getOptionValue('wh_thursday_to') !== '') {
				$workingHours['thursday']['to'] = stockholm_qode_options()->getOptionValue('wh_thursday_to');
			}

			if(stockholm_qode_options()->getOptionValue('wh_thursday_closed') !== '') {
				$workingHours['thursday']['closed'] = stockholm_qode_options()->getOptionValue('wh_thursday_closed');
			}

			//friday
			if(stockholm_qode_options()->getOptionValue('wh_friday_from') !== '') {
				$workingHours['friday']['label'] = esc_html__('Friday', 'select-restaurant');
				$workingHours['friday']['from']  = stockholm_qode_options()->getOptionValue('wh_friday_from');
			}

			if(stockholm_qode_options()->getOptionValue('wh_friday_to') !== '') {
				$workingHours['friday']['to'] = stockholm_qode_options()->getOptionValue('wh_friday_to');
			}

			if(stockholm_qode_options()->getOptionValue('wh_friday_closed') !== '') {
				$workingHours['friday']['closed'] = stockholm_qode_options()->getOptionValue('wh_friday_closed');
			}

			//saturday
			if(stockholm_qode_options()->getOptionValue('wh_saturday_from') !== '') {
				$workingHours['saturday']['label'] = esc_html__('Saturday', 'select-restaurant');
				$workingHours['saturday']['from']  = stockholm_qode_options()->getOptionValue('wh_saturday_from');
			}

			if(stockholm_qode_options()->getOptionValue('wh_saturday_to') !== '') {
				$workingHours['saturday']['to'] = stockholm_qode_options()->getOptionValue('wh_saturday_to');
			}

			if(stockholm_qode_options()->getOptionValue('wh_saturday_closed') !== '') {
				$workingHours['saturday']['closed'] = stockholm_qode_options()->getOptionValue('wh_saturday_closed');
			}

			//sunday
			if(stockholm_qode_options()->getOptionValue('wh_sunday_from') !== '') {
				$workingHours['sunday']['label'] = esc_html__('Sunday', 'select-restaurant');
				$workingHours['sunday']['from']  = stockholm_qode_options()->getOptionValue('wh_sunday_from');
			}

			if(stockholm_qode_options()->getOptionValue('wh_sunday_to') !== '') {
				$workingHours['sunday']['to'] = stockholm_qode_options()->getOptionValue('wh_sunday_to');
			}

			if(stockholm_qode_options()->getOptionValue('wh_sunday_closed') !== '') {
				$workingHours['sunday']['closed'] = stockholm_qode_options()->getOptionValue('wh_sunday_closed');
			}
		}

		return $workingHours;
	}

	private function getHolderClasses($params) {
		$classes = array('qode-working-hours-holder');

		if(isset($params['enable_frame']) && $params['enable_frame'] === 'yes') {
			$classes[] = 'qode-wh-with-frame';
		}

		if(isset($params['bg_image']) && $params['bg_image'] !== '') {
			$classes[] = 'qode-wh-with-bg-image';
		}

		return $classes;
	}

	private function getHolderStyles($params) {
		$styles = array();

		if(isset($params['bg_image']) && $params['bg_image'] !== '') {
			$bg_url = wp_get_attachment_url($params['bg_image']);

			if(!empty($bg_url)) {
				$styles[] = 'background-image: url('.$bg_url.')';
			}
		}

		return $styles;
	}
	private function getItemStyles($params) {
		$styles = array();

		if(isset($params['color']) && $params['color'] !== '') {
			$styles[] = 'color: '.$params['color'];
		}

		return $styles;
	}

}
