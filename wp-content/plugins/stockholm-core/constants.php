<?php

define( 'STOCKHOLM_CORE_VERSION', '1.2.1' );
define( 'STOCKHOLM_CORE_ABS_PATH', dirname( __FILE__ ) );
define( 'STOCKHOLM_CORE_REL_PATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'STOCKHOLM_CORE_URL_PATH', plugin_dir_url( __FILE__ ) );
define( 'STOCKHOLM_CORE_MODULES_PATH', STOCKHOLM_CORE_ABS_PATH . '/modules' );
define( 'STOCKHOLM_CORE_MODULES_URL_PATH', STOCKHOLM_CORE_URL_PATH . 'modules' );
define( 'STOCKHOLM_CORE_SHORTCODES_ROOT_DIR', STOCKHOLM_CORE_MODULES_PATH . '/shortcodes/shortcode-elements' );
define( 'STOCKHOLM_CORE_OPTIONS_NAME', 'qode_options_stockholm' );