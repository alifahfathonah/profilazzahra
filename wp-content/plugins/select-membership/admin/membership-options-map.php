<?php
/**
 * Options map file
 */

if ( ! function_exists( 'stockholm_qode_membership_membership_options_map' ) ) {
	/**
	 * Map plugin options
     *
     * @param $page
	 */
	function stockholm_qode_membership_membership_options_map( $page ) {

		$panel_social_login = stockholm_qode_add_admin_panel( array(
			'page'  => $page,
			'name'  => 'panel_social_login',
			'title' => esc_html__('Enable Social Login', 'select-membership')
		) );

		stockholm_qode_add_admin_field( array(
			'type'          => 'yesno',
			'name'          => 'enable_social_login',
			'default_value' => 'no',
			'label'         => esc_html__('Enable Social Login', 'select-membership'),
			'description'   => esc_html__('Enabling this option will allow login from social networks of your choice', 'select-membership'),
			'args'          => array(
				'dependence'             => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#qodef_panel_enable_social_login'
			),
			'parent'        => $panel_social_login
		) );

		$panel_enable_social_login = stockholm_qode_add_admin_panel( array(
			'page'            => $page,
			'name'            => 'panel_enable_social_login',
			'title'           => esc_html__('Enable Login via', 'select-membership'),
			'hidden_property' => 'enable_social_login',
			'hidden_value'    => 'no'
		) );

		stockholm_qode_add_admin_field( array(
			'type'          => 'yesno',
			'name'          => 'enable_facebook_social_login',
			'default_value' => 'no',
			'label'         => esc_html__('Facebook', 'select-membership'),
			'description'   => esc_html__('Enabling this option will allow login via Facebook', 'select-membership'),
			'args'          => array(
				'dependence'             => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#qodef_enable_facebook_social_login_container'
			),
			'parent'        => $panel_enable_social_login
		) );

		$enable_facebook_social_login_container = stockholm_qode_add_admin_container( array(
			'name'            => 'enable_facebook_social_login_container',
			'hidden_property' => 'enable_facebook_social_login',
			'hidden_value'    => 'no',
			'parent'          => $panel_enable_social_login
		) );

		stockholm_qode_add_admin_field( array(
			'type'          => 'text',
			'name'          => 'enable_facebook_login_fbapp_id',
			'default_value' => '',
			'label'         => esc_html__('Facebook App ID', 'select-membership'),
			'description'   => esc_html__('Copy your application ID form created Facebook Application', 'select-membership'),
			'parent'        => $enable_facebook_social_login_container
		) );

		stockholm_qode_add_admin_field( array(
			'type'          => 'yesno',
			'name'          => 'enable_google_social_login',
			'default_value' => 'no',
			'label'         => esc_html__('Google+', 'select-membership'),
			'description'   => esc_html__('Enabling this option will allow login via Google+', 'select-membership'),
			'args'          => array(
				'dependence'             => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#qodef_enable_google_social_login_container'
			),
			'parent'        => $panel_enable_social_login
		) );

		$enable_google_social_login_container = stockholm_qode_add_admin_container( array(
			'name'            => 'enable_google_social_login_container',
			'hidden_property' => 'enable_google_social_login',
			'hidden_value'    => 'no',
			'parent'          => $panel_enable_social_login
		) );

		stockholm_qode_add_admin_field( array(
			'type'          => 'text',
			'name'          => 'enable_google_login_client_id',
			'default_value' => '',
			'label'         => esc_html__('Client ID', 'select-membership'),
			'description'   => esc_html__('Copy your Client ID form created Google Application', 'select-membership'),
			'parent'        => $enable_google_social_login_container
		) );
	}

	add_action( 'stockholm_qode_action_social_options', 'stockholm_qode_membership_membership_options_map' );
}
