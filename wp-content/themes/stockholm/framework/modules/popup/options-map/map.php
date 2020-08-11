<?php

if ( ! function_exists('stockholm_qode_popup_options_map') ) {
    function stockholm_qode_popup_options_map() {

        $cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );

        $contact_forms = array();
        if ( $cf7 ) {
            foreach ( $cf7 as $cform ) {
                $contact_forms[ $cform->ID ] = $cform->post_title;
            }
        } else {
            $contact_forms[0] =  esc_html__( 'No contact forms found', 'stockholm' ) ;
        }
	
	    stockholm_qode_add_admin_page(
            array(
                'slug' => '_popup_page',
                'title' => esc_html__('Pop-up', 'stockholm' ),
                'icon' => 'fa fa-pencil-square-o'
            )
        );

        $popup_panel = stockholm_qode_add_admin_panel(
            array(
                'title' => esc_html__('Pop-up', 'stockholm' ),
                'name' => 'popup',
                'page' => '_popup_page'
            )
        );
	
	    stockholm_qode_add_admin_field(
            array(
                'parent'		=> $popup_panel,
                'type'			=> 'yesno',
                'name'			=> 'enable_popup',
                'default_value'	=> 'no',
                'label'			=> esc_html__('Enable Pop-up', 'stockholm' ),
                'description'	=> '',
                'args'			=> array(
                    'dependence' => true,
                    'dependence_hide_on_yes' => '',
                    'dependence_show_on_yes' => '#qodef_enable_popup_container'
                )
            )
        );

        $enable_popup_container = stockholm_qode_add_admin_container(
            array(
                'parent'			=> $popup_panel,
                'name'				=> 'enable_popup_container',
                'hidden_property'	=> 'enable_popup',
                'hidden_value'		=> 'no',
            )
        );
	
	    stockholm_qode_add_admin_field(array(
            'parent' => $enable_popup_container,
            'type' => 'text',
            'name' => 'popup_title',
            'default_value' => '',
            'label' => esc_html__('Title','stockholm' ),
            'description' => esc_html__('Enter title pop-up window', 'stockholm' )
        ));
	
	    stockholm_qode_add_admin_field(array(
            'parent' => $enable_popup_container,
            'type' => 'text',
            'name' => 'popup_subtitle',
            'default_value' => '',
            'label' => esc_html__('Subtitle', 'stockholm' ),
            'description' => esc_html__('Enter subtitle pop-up window', 'stockholm' )
        ));
	
	    stockholm_qode_add_admin_field(
            array(
                'name'          => 'popup_contact_form',
                'type'          => 'select',
                'default_value' => '',
                'label'         => esc_html__('Select Contact Form', 'stockholm' ),
                'description'   => esc_html__('Choose contact form to display in popup window', 'stockholm' ),
                'parent'        => $enable_popup_container,
                'options'       => $contact_forms
            )
        );
	
	    stockholm_qode_add_admin_field(
            array(
                'name'          => 'popup_contact_form_style',
                'type'          => 'select',
                'default_value' => '',
                'label'         => esc_html__('Contact Form Style', 'stockholm' ),
                'description'   => esc_html__('Choose style defined in Contact Form 7 option tab', 'stockholm' ),
                'parent'        => $enable_popup_container,
                'options'       => array(
                    'default' => 'Default',
                    'cf7_custom_style_1' => esc_html__('Custom Style 1', 'stockholm' )
                )
            )
        );
    }

    add_action('stockholm_qode_action_options_map', 'stockholm_qode_popup_options_map', 165);
}