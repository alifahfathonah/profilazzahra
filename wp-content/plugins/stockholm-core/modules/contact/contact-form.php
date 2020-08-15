<?php

if(!function_exists('stockholm_core_contact_form_part')) {

	function stockholm_core_contact_form_part() {

		if (stockholm_qode_options()->getOptionValue('enable_contact_form') == "yes") {
			$custom_style = "";
			if (stockholm_qode_options()->getOptionValue('use_custom_style') == 'yes') {
				$custom_style = 'cf7_custom_style_1';
			}
			?>
			<div class="contact_form qode-contact-form-contact-template <?php echo esc_attr($custom_style) ?>">
				<?php if (stockholm_qode_options()->getOptionValue('contact_heading_above') !== "") { ?>
					<h5> <?php echo esc_html(stockholm_qode_options()->getOptionValue('contact_heading_above')); ?> </h5>
				<?php } ?>
				<form id="contact-form" method="post" action="">
					<?php wp_nonce_field('stockholm_core_contact_page_nonce', 'stockholm_core_contact_page_nonce'); ?>
					<div class="two_columns_50_50 clearfix">
						<div class="column1">
							<div class="column_inner">
								<input type="text" class="requiredField" name="fname" id="fname" value="" placeholder="<?php esc_attr_e('First Name *', 'stockholm-core'); ?>"/>
							</div>
						</div>
						<div class="column2">
							<div class="column_inner">
								<input type="text" class="requiredField" name="lname" id="lname" value="" placeholder="<?php esc_attr_e('Last Name *', 'stockholm-core'); ?>"/>
							</div>
						</div>
					</div>
					<?php if (stockholm_qode_options()->getOptionValue('hide_contact_form_website') == "yes") { ?>
						<input type="text" class="requiredField email" name="email" id="email" value="" placeholder="<?php esc_attr_e('Email *', 'stockholm-core'); ?>"/>
						<input type="hidden" name="website" id="website" value=""/>
					<?php } else { ?>
						<div class="two_columns_50_50 clearfix">
							<div class="column1">
								<div class="column_inner">
									<input type="text" class="requiredField email" name="email" id="email" value="" placeholder="<?php esc_attr_e('Email *', 'stockholm-core'); ?>"/>
								</div>
							</div>
							<div class="column2">
								<div class="column_inner">
									<input type="text" name="website" id="website" value="" placeholder="<?php esc_attr_e('Website', 'stockholm-core'); ?>"/>
								</div>
							</div>
						</div>
					<?php } ?>

					<textarea name="message" id="message" rows="10" placeholder="<?php esc_attr_e('Message', 'stockholm-core'); ?>"></textarea>

					<?php if(stockholm_qode_options()->getOptionValue('use_recaptcha') && stockholm_qode_options()->getOptionValue('recaptcha_public_key') != '') { ?>
						<div id="qode-captcha-element-holder" data-sitekey="<?php echo stockholm_qode_options()->getOptionValue('recaptcha_public_key'); ?>"></div>
					<?php } ?>
					<span class="submit_button_contact">
						<input class="qbutton contact_form_button" type="submit" value="<?php esc_attr_e('Contact Us', 'stockholm-core'); ?>"/>
					</span>
				</form>
			</div>
		<?php }
	}

	add_action('stockholm_qode_action_contact_page_above_map', 'stockholm_core_contact_form_part');
}