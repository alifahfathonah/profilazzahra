<?php
namespace StockholmQodeRestaurant\CPT\RestaurantMenu;

use StockholmQodeRestaurant\Lib;

/**
 * Class RestaurantMenuRegister
 * @package StockholmQodeRestaurant\CPT\RestaurantMenu
 */

class RestaurantMenuRegister implements Lib\PostTypeInterface {
	/**
	 * @var string
	 */
	private $base;
	/**
	 * @var string
	 */
	private $taxBase;

	public function __construct() {
		$this->base    = 'qode-restaurant-menu';
		$this->taxBase = 'qode-restaurant-menu-category';
	}

	/**
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	public function register() {
		$this->registerPostType();
		$this->registerTax();
	}

	/**
	 * Regsiters custom post type with WordPress
	 */
	private function registerPostType() {

		$menuPosition = 5;
		$menuIcon     = 'dashicons-list-view';

		register_post_type($this->base,
			array(
				'labels'        => array(
					'name'          => esc_html__('Select Restaurant Menu', 'select-restaurant'),
					'menu_name'     => esc_html__('Select Restaurant Menu', 'select-restaurant'),
					'all_items'     => esc_html__('Restaurant Menu Items', 'select-restaurant'),
					'add_new'       => esc_html__('Add New Restaurant Menu Item', 'select-restaurant'),
					'singular_name' => esc_html__('Restaurant Menu Item', 'select-restaurant'),
					'add_item'      => esc_html__('New Restaurant Menu Item', 'select-restaurant'),
					'add_new_item'  => esc_html__('Add New Restaurant Menu Item', 'select-restaurant'),
					'edit_item'     => esc_html__('Edit Restaurant Menu Item', 'select-restaurant')
				),
				'public'        => false,
				'show_in_menu'  => true,
				'menu_position' => $menuPosition,
				'show_ui'       => true,
				'has_archive'   => false,
				'hierarchical'  => false,
				'supports'      => array('title', 'thumbnail'),
				'menu_icon'     => $menuIcon
			)
		);
	}

	/**
	 * Registers custom taxonomy with WordPress
	 */
	private function registerTax() {
		$labels = array(
			'name'              => esc_html__('Restaurant Menu Category', 'select-restaurant'),
			'singular_name'     => esc_html__('Restaurant Menu Category', 'select-restaurant'),
			'search_items'      => esc_html__('Search Restaurant Menu Categories', 'select-restaurant'),
			'all_items'         => esc_html__('All Restaurant Menu Categories', 'select-restaurant'),
			'parent_item'       => esc_html__('Parent Restaurant Menu Category', 'select-restaurant'),
			'parent_item_colon' => esc_html__('Parent Restaurant Menu Category:', 'select-restaurant'),
			'edit_item'         => esc_html__('Edit Restaurant Menu Category', 'select-restaurant'),
			'update_item'       => esc_html__('Update Restaurant Menu Category', 'select-restaurant'),
			'add_new_item'      => esc_html__('Add New Restaurant Menu Category', 'select-restaurant'),
			'new_item_name'     => esc_html__('New Restaurant Menu Category Name', 'select-restaurant'),
			'menu_name'         => esc_html__('Restaurant Menu Categories', 'select-restaurant'),
		);

		register_taxonomy($this->taxBase, array($this->base), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
		));
	}

}