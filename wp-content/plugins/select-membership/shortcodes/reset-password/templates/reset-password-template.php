<div class="qode-social-reset-password-holder">
	<form action="<?php echo site_url( 'wp-login.php?action=lostpassword' ); ?>" method="post" id="qode-lost-password-form" class="qode-reset-pass-form">
		<div>
			<input type="text" name="user_reset_password_login" class="qode-input-field" id="user_reset_password_login" placeholder="<?php esc_attr_e( 'Enter username or email', 'select-membership' ) ?>" value="" size="20" required>
		</div>
		<?php do_action( 'lostpassword_form' ); ?>
		<div class="qode-reset-password-button-holder">
			<?php

            echo '<button class="qbutton" type="submit">' . esc_html__( 'NEW PASSWORD', 'select-membership' ) . '</button>';

			?>
		</div>
	</form>
	<?php do_action( 'stockholm_qode_action_membership_login_ajax_response' ); ?>
</div>