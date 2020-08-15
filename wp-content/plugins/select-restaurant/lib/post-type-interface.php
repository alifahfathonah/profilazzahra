<?php

namespace StockholmQodeRestaurant\Lib;

/**
 * interface PostTypeInterface
 * @package StockholmQodeRestaurant\Lib;
 */
interface PostTypeInterface {
	/**
	 * @return string
	 */
	public function getBase();
	
	/**
	 * Registers custom post type with WordPress
	 */
	public function register();
}