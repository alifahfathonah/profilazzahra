<?php
namespace Jet_Menu\Endpoints;


class Get_Menu_Items extends Base {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'get-menu-items';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		return array(
			'menu_id' => array(
				'default'    => '',
				'required'   => false,
			),
		);
	}

	/**
	 * [callback description]
	 * @param  [type]   $request [description]
	 * @return function          [description]
	 */
	public function callback( $request ) {

		$args = $request->get_params();

		$menu_id = ! empty( $args['menu_id'] ) ? $args['menu_id'] : false;

		$menu_data = jet_menu_public_manager()->generate_menu_raw_data( $menu_id );

		return rest_ensure_response( [
			'data' => $menu_data,
		] );
	}

}
