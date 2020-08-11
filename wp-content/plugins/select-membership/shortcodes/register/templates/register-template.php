<div class="qode-social-register-holder">
	<form method="post" class="qode-register-form">
		<fieldset>
			<div>
				<input type="text" name="user_register_name" id="user_register_name" placeholder="<?php esc_attr_e( 'User Name', 'select-membership' ) ?>" value="" required pattern=".{3,}" title="<?php esc_attr_e( 'Three or more characters', 'select-membership' ); ?>"/>
			</div>
			<div>
				<input type="email" name="user_register_email" id="user_register_email" placeholder="<?php esc_attr_e( 'Email', 'select-membership' ) ?>" value="" required />
			</div>
            <div>
                <input type="password" name="user_register_password" id="user_register_password" placeholder="<?php esc_attr_e('Password','select-membership') ?>" value="" required />
            </div>
            <div>
                <input type="password" name="user_register_confirm_password" iid="user_register_confirm_password" placeholder="<?php esc_attr_e('Repeat Password','select-membership') ?>" value="" required />
            </div>
			<div class="qode-register-button-holder">
				<?php
                echo '<button class="qbutton" type="submit">' . esc_html__( 'REGISTER', 'select-membership' ) . '</button>';

				wp_nonce_field( 'qode-ajax-register-nonce', 'qode-register-security' ); ?>
			</div>
		</fieldset>
	</form>
	<?php do_action( 'stockholm_qode_action_membership_login_ajax_response' ); ?>
</div>