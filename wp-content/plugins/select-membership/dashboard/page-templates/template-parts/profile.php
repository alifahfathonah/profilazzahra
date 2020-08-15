<div class="qode-membership-dashboard-page">
	<div class="qode-membership-dashboard-page-content">
		<div class="qode-profile-image">
            <?php echo stockholm_qode_membership_kses_img( $profile_image ); ?>
        </div>
		<p>
			<span><?php esc_html_e( 'First Name', 'select-membership' ); ?>:</span>
			<?php echo esc_html( $first_name ); ?>
		</p>
		<p>
			<span><?php esc_html_e( 'Last Name', 'select-membership' ); ?>:</span>
			<?php echo esc_html( $last_name ); ?>
		</p>
		<p>
			<span><?php esc_html_e( 'Email', 'select-membership' ); ?>:</span>
			<?php echo sanitize_email( $email ); ?>
		</p>
		<p>
			<span><?php esc_html_e( 'Desription', 'select-membership' ); ?>:</span>
			<?php echo wp_kses_post( $description ); ?>
		</p>
		<p>
			<span><?php esc_html_e( 'Website', 'select-membership' ); ?>:</span>
			<a href="<?php echo esc_url( $website ); ?>" target="_blank"><?php echo esc_url( $website ); ?></a>
		</p>
	</div>
</div>
