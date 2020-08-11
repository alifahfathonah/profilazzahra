<?php

if ( ! function_exists( 'stockholm_core_is_installed' ) ) {
	/**
	 * Function that checks if forward module installed
	 *
	 * @param $name string - module name
	 *
	 * @return bool
	 */
	function stockholm_core_is_installed( $name ) {
		
		switch ( $name ) {
			case 'theme';
				return defined( 'STOCKHOLM_QODE' );
				break;
			case 'woocommerce';
				return function_exists( 'is_woocommerce' );
				break;
			case 'visual-composer';
				return class_exists( 'WPBakeryVisualComposerAbstract' );
				break;
			case 'gutenberg-editor';
				return function_exists( 'register_block_type' );
				break;
            case 'revolution-slider';
                return class_exists('RevSliderFront');
                break;
            case 'layer-slider';
                return defined('LS_PLUGIN_VERSION');
                break;
			default:
				return false;
		}
	}
}

if(!function_exists('stockholm_core_get_module_template_part')) {

    function stockholm_core_get_module_template_part($template, $module, $slug = '', $params = array()) {
        $template_path = STOCKHOLM_CORE_MODULES_PATH . '/' .$module;

        return stockholm_core_return_template_part($template_path.'/'.$template, $slug, $params);

    }
}

if(!function_exists('stockholm_core_return_template_part')) {
    /**
     * Loads template part with parameters. If file with slug parameter added exists it will load that file, else it will load file without slug added.
     * Child theme friendly function
     *
     * @param string $template name of the template to load without extension
     * @param string $slug
     * @param array $params array of parameters to pass to template
     *
     * @return html
     * @see stockholm_qode_get_template_part()
     */
    function stockholm_core_return_template_part($template, $slug = '', $params = array()) {
        if(is_array($params) && count($params)) {
            extract($params);
        }
        $html          = '';
        $templates = array();

        if($template !== '') {
            if($slug !== '') {
                $templates[] = "{$template}-{$slug}.php";
            }

            $templates[] = $template.'.php';
        }

        $located = stockholm_qode_find_template_path($templates, true);

        if($located) {
            ob_start();
            include($located);
            $html = ob_get_clean();
        }

        return $html;
    }
}