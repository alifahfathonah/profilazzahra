<?php

$args = array(
    "columns"         => "four_columns"
);

$html = "";

extract(shortcode_atts($args, $atts));

$html = '<div class="qode_pricing_tables clearfix '.esc_attr($columns).'">';

$html .= do_shortcode($content);

$html .= '</div>';

echo stockholm_qode_get_module_part($html);