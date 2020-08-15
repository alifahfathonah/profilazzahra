<?php
require_once 'recaptchalib.php';
if ( file_exists( '../../../../wp-blog-header.php' ) ) {
	require( '../../../../wp-blog-header.php' );
} else {
	require( '../../../../../wp-blog-header.php' );
}

$stockholm_options = stockholm_qode_return_global_options();

$publickey  = $stockholm_options['recaptcha_public_key'];
$privatekey = $stockholm_options['recaptcha_private_key'];

if ( $publickey == "" ) {
	$publickey = "6Ld5VOASAAAAABUGCt9ZaNuw3IF-BjUFLujP6C8L";
}
if ( $privatekey == "" ) {
	$privatekey = "6Ld5VOASAAAAAKQdKVcxZ321VM6lkhBsoT6lXe9Z";
}

$use_captcha = $stockholm_options['use_recaptcha'];
$resp        = $use_captcha !== 'no' ? recaptcha_check_answer( $privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"] ) : false;

if ( isset( $_POST ) && ! empty( $_POST ) && ( ( $resp && $resp->is_valid ) || $use_captcha == "no" ) ) {?>success<?php
	$email_to   = isset( $stockholm_options['receive_mail'] ) && ! empty( $stockholm_options['receive_mail'] ) ? sanitize_email( $stockholm_options['receive_mail'] ) : sanitize_email( get_option( 'admin_email' ) );
	$email_from = isset( $stockholm_options['email_from'] ) && ! empty( $stockholm_options['email_from'] ) ? sanitize_email( $stockholm_options['email_from'] ) : $email_to;
	$subject    = sanitize_text_field( $stockholm_options['email_subject'] );
	$name       = sanitize_text_field( $_POST["fname"] );
	$lastname   = sanitize_text_field( $_POST["lname"] );
	$website    = esc_url( $_POST["website"] );
	
	$text = "Name: " . $name . " " . $lastname . "\n";
	$text .= "Email: " . sanitize_email( $_POST["email"] ) . "\n";
	if ( ! empty( $website ) ) {
		$text .= "WebSite: " . $website . "\n";
	}
	$text .= "Message: " . sanitize_textarea_field( $_POST["message"] );
	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/plain; charset=utf-8" . "\r\n";
	$headers .= "From: '".$name." ".$lastname."' <".$email_from."> " . "\r\n";
	$headers .= "Reply-To: '" . sanitize_email( $_POST["email"] ) . "'" . "\r\n";
	$result = wp_mail( $email_to, $subject, $text, $headers );
	if ( ! $result ) {
		global $phpmailer;
		var_dump( $phpmailer->ErrorInfo );
	}
	
} else {
	die ( sprintf( esc_html__( "The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: %s)", "stockholm-core" ), $resp->error ) );
}