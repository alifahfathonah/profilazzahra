<?php
$full_path = __FILE__;
$path = explode('wp-content', $full_path);
require_once( $path[0] . '/wp-load.php' );
?>

<div id="qode_shortcode_form_wrapper">
    <form id="qode_shortcode_form" name="qode_shortcode_form" method="post" action="">
        <div class="input">
            <label>Size</label>
            <select name="size" id="size">
                <option value="">Default</option>
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
                <option value="big_large">Extra Large</option>
                <option value="big_large_full_width">Extra Large Full Width</option>
            </select>
        </div>
		 <div class="input">
            <label>Style</label>
            <select name="style" id="style">
                <option value="">Default</option>
                <option value="white">White</option>
            </select>
        </div>
        <div class="input">
            <label>Text</label>
            <input name="text" id="text" value="" />
        </div>
        
        <div class="input">
            <label>Icon Pack</label>
            <select name="icon_pack" id="icon_pack">
                <option value="">No Icon</option>
                <option value="font_awesome">Font Awesome</option>
                <option value="font_elegant">Font Elegant</option>
            </select>
        </div>
        
        <div class="input">
        <label>Font Awesome Icons</label>
            <select id="fa_icon" name="fa_icon">
                <option value=""></option>
                <?php
                $fa_icons = stockholm_qode_font_awesome_icon_array();
                foreach ($fa_icons as $key => $value) {
                ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($key); ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        
        <div class="input">
        <label>Elegant Icons</label>
            <select id="fe_icon" name="fe_icon">
                <option value=""></option>
                <?php
                $fe_icons = stockholm_qode_font_elegant_icon_array();
                foreach ($fe_icons as $key => $value) {
                ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($key); ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        
        <div class="input">
            <label>Icon Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="icon_color" id="icon_color" value="" maxlength="10" size="10" />
        </div>
        
        <div class="input">
            <label>Link</label>
            <input name="link" id="link" value="" size="55" />
        </div>
        
        <div class="input">
            <label>Target</label>
            <select name="target" id="target">
                <option value="_self">Self</option>
                <option value="_blank">Blank</option>
            </select>
        </div>
        <div class="input">
            <label>Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="color" id="color" value="" maxlength="12" size="12" />
        </div>
        <div class="input">
            <label>Hover Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="hover_color" id="hover_color" value="" maxlength="12" size="12" />
        </div>
        <div class="input">
            <label>Background Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="background_color" id="background_color" value="" maxlength="12" size="12" />
        </div>
        <div class="input">
            <label>Hover Background Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="hover_background_color" id="hover_background_color" value="" maxlength="12" size="12" />
        </div>
        <div class="input">
            <label>Border Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="border_color" id="border_color" value="" maxlength="12" size="12" />
        </div>
        <div class="input">
            <label>Hover Border Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="hover_border_color" id="hover_border_color" value="" maxlength="12" size="12" />
        </div>
        <div class="input">
            <label>Font Style</label>
            <select name="font_style" id="font_style">
                <option value=""></option>
                <option value="normal">Normal</option>
                <option value="italic">Italic</option>
            </select>
        </div>
        <div class="input">
            <label>Font Weight</label>
            <select name="font_weight" id="font_weight">
                <option class="" value="">Default</option>
            	<option value="100">Thin 100</option>
            	<option value="200">Extra-Light 200</option>
            	<option value="300">Light 300</option>
            	<option value="400">Regular 400</option>
            	<option value="500">Medium 500</option>
            	<option value="600">Semi-Bold 600</option>
            	<option value="700">Bold 700</option>
            	<option value="800">Extra-Bold 800</option>
            	<option value="900">Ultra-Bold 900</option>
            </select>
        </div>
        <div class="input">
            <label>Text Align</label>
            <select name="text_align" id="text_align">
                <option value=""></option>
                <option value="left">Left</option>
                <option value="right">Right</option>
                <option value="center">Center</option>
            </select>
        </div>
		 <div class="input">
            <label>Margin</label>
            <input name="margin" id="margin" value="" />
        </div>
        <div class="input">
            <input type="submit" name="Insert" id="qode_insert_shortcode_button" value="Submit" />
        </div>
    </form>
</div>
