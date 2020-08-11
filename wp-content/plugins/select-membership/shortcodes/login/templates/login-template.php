<div class="qode-social-login-holder">
    <div class="qode-social-login-holder-inner">
        <form method="post" class="qode-login-form">
            <?php
            $redirect = '';
            if ( isset( $_GET['redirect_uri'] ) ) {
                $redirect = $_GET['redirect_uri'];
            } ?>
            <fieldset>
                <div>
                    <input type="text" name="user_login_name" id="user_login_name" placeholder="<?php esc_attr_e( 'User Name', 'select-membership' ) ?>" value="" required pattern=".{3,}" title="<?php esc_attr_e( 'Three or more characters', 'select-membership' ); ?>"/>
                </div>
                <div>
                    <input type="password" name="user_login_password" id="user_login_password" placeholder="<?php esc_attr_e( 'Password', 'select-membership' ) ?>" value="" required/>
                </div>
                <div class="qode-lost-pass-remember-holder clearfix">
                    <span class="qode-login-remember">
                        <input name="rememberme" value="forever" id="rememberme" type="checkbox"/>
                        <label for="rememberme" class="qode-checbox-label"><?php esc_html_e( 'Remember me', 'select-membership' ) ?></label>
                    </span>
                </div>
                <input type="hidden" name="redirect" id="redirect" value="<?php echo esc_url( $redirect ); ?>">
                <div class="qode-login-button-holder">
                    <a href="<?php echo wp_lostpassword_url(); ?>" class="qode-login-action-btn" data-el="#qode-reset-pass-content" data-title="<?php esc_attr_e( 'Lost Password?', 'select-membership' ); ?>"><?php esc_html_e( 'Lost Your password?', 'select-membership' ); ?></a>
                    <?php
                        echo '<button class="qbutton" type="submit">' . esc_html__( 'LOGIN', 'select-membership' ) . '</button>';
                    ?>
                    <?php wp_nonce_field( 'qode-ajax-login-nonce', 'qode-login-security' ); ?>
                </div>
            </fieldset>
        </form>
    </div>
    <?php
    if(stockholm_qode_membership_theme_installed()) {
        //if social login enabled add social networks login
        $social_login_enabled = stockholm_qode_options()->getOptionValue('enable_social_login') == 'yes' ? true : false;
        if($social_login_enabled) { ?>
            <div class="qode-login-form-social-login">
                <div class="qode-login-social-title">
                    <?php esc_html_e('Connect with Social Networks', 'select-membership'); ?>
                </div>
                <div class="qode-login-social-networks">
                    <?php do_action('stockholm_qode_action_membership_social_network_login'); ?>
                </div>
            </div>
        <?php }
    }
    do_action( 'stockholm_qode_action_membership_login_ajax_response' );
    ?>
</div>