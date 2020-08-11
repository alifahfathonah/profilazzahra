<div class="qode-login-register-holder">
	<div class="qode-login-register-content">
		<ul>
			<li><a href="#qode-login-content"><?php esc_html_e( 'Login', 'select-membership' ); ?></a></li>
			<li><a href="#qode-register-content"><?php esc_html_e( 'Register', 'select-membership' ); ?></a></li>
		</ul>
		<div class="qode-login-content-inner" id="qode-login-content">
			<div class="qode-wp-login-holder"><?php echo stockholm_qode_membership_execute_shortcode( 'qode_user_login', array() ); ?></div>
		</div>
		<div class="qode-register-content-inner" id="qode-register-content">
			<div class="qode-wp-register-holder"><?php echo stockholm_qode_membership_execute_shortcode( 'qode_user_register', array() ) ?></div>
		</div>
	</div>
</div>