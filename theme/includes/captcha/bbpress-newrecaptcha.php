<?php

/*
Plugin Name: bbPress No CAPTCHA reCAPTCHA
Plugin URI: http://studiohyperset.com
Description: Adds Google’s No CAPTCHA reCAPTCHA to bbPress forms.
Author: Studio Hyperset
Author URI: http://studiohyperset.com
Version: 1.0
Contributors: studiohyperset, cantuaria, oqm4
Text Domain: bpress-no-captcha-recaptcha
*/


//Invokes the class if not invoked yet.
if( !class_exists('ReCaptcha') ) {
	require_once( dirname( __FILE__ ) . '/recaptchalib.php');
}


//Function to display default captcha structure
function bbp_newrecaptcha_get_form ($siteKey) {

	return '<div class="g-recaptcha" data-sitekey="'. $siteKey .'"></div><script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=en"></script>';

}


//Function to show recaptcha on topics
function bbp_newrecaptcha_insert_form() {

	$bbp_newrecaptcha_options = get_option('bbp_newrecaptcha');

	if( is_user_logged_in() && ! isset( $bbp_newrecaptcha_options['show_to_logged_in'] ) )
		return;
	$siteKey = trim( $bbp_newrecaptcha_options['site_key'] );
	echo bbp_newrecaptcha_get_form($siteKey);

}
add_action('bp_after_messages_compose_content', 'bbp_newrecaptcha_insert_form');
add_action('bbp_theme_before_topic_form_submit_wrapper', 'bbp_newrecaptcha_insert_form');


//Function to verify captcha
function bbp_newrecaptcha_verify_result( $reply_id ) {

	$bbp_newrecaptcha_options = get_option('bbp_newrecaptcha');

	if( is_user_logged_in() && !isset( $bbp_newrecaptcha_options['show_to_logged_in'] ) )
		return;

	$secret = trim( $bbp_newrecaptcha_options['secret_key'] );
	$resp = null;
	$reCaptcha = new ReCaptcha($secret);

	if ($_POST["g-recaptcha-response"]) {
		$resp = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
	}

	if ( ! ($resp != null && $resp->success) ) {
		bbp_add_error( 'bbp_reply_duplicate', __( '<strong>ERROR</strong>: reCAPTCHA Failure. Please try again.', 'bbpress-newrecaptcha' ) );
		if ($reply_id->recipients != null && $reply_id->thread_id == null)
		  unset($reply_id->recipients);
	}

}
add_action('messages_message_before_save', 'bbp_newrecaptcha_verify_result');
add_action('bbp_new_topic_pre_extras', 'bbp_newrecaptcha_verify_result');



/*******************************************
* Settings Page
*******************************************/

function bbp_newrecaptcha_page() {
	$bbpc_options = get_option('bbp_newrecaptcha');

	?>
	<div class="wrap">

		<h2><?php _e( 'bbPress No CAPTCHA reCAPTCHA Settings', 'bbpress-newrecaptcha' ); ?></h2>

		<?php
		if ( ! isset( $_REQUEST['updated'] ) )
			$_REQUEST['updated'] = false;
		?>

		<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved', 'bbpress-newrecaptcha' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">

			<?php settings_fields( 'bbp_newrecaptcha_group' ); ?>

			<h4><?php _e( 'reCaptcha Keys', 'bbpress-newrecaptcha' ); ?></h4>
			<p>
				<label for="bbp_newrecaptcha[site_key]"><?php _e( 'reCaptcha Site Key', 'bbpress-newrecaptcha' ); ?></label><br/>
				<input id="bbp_newrecaptcha[site_key]" style="width: 300px;" name="bbp_newrecaptcha[site_key]" type="text" value="<?php echo esc_attr( $bbpc_options['site_key'] ); ?>" />
				<p class="description"><?php _e( 'This your own personal reCaptcha Site key. Go to <a target="_blank" href="https://www.google.com/recaptcha/admin#list">your account</a>, then click on your domain (or add a new one) to find your site key.', 'bbpress-newrecaptcha' ); ?></p>
			</p>
			<p>
				<label for="bbp_newrecaptcha[secret_key]"><?php _e( 'reCaptcha Secret Key', 'bbpress-newrecaptcha' ); ?></label><br/>
				<input id="bbp_newrecaptcha[secret_key]" style="width: 300px;" name="bbp_newrecaptcha[secret_key]" type="text" value="<?php echo esc_attr( $bbpc_options['secret_key'] ); ?>" />
				<p class="description"><?php _e( 'This your own personal reCaptcha Secret key. Go to <a target="_blank" href="https://www.google.com/recaptcha/admin#list">your account</a>, then click on your domain (or add a new one) to find your secret key.', 'bbpress-newrecaptcha' ); ?></p>
			</p>
			<p>
				<input id="bbp_newrecaptcha[show_to_logged_in]" name="bbp_newrecaptcha[show_to_logged_in]" type="checkbox" value="1" <?php checked( true, isset( $bbpc_options['show_to_logged_in'] ) ); ?>/>
				<label for="bbp_newrecaptcha[show_to_logged_in]"><?php _e( 'Show to logged-in users?' ); ?></label><br/>
				<p class="description"><?php _e( 'Should logged in users see the reCaptcha form?', 'bbpress-newrecaptcha' ); ?></p>
			</p>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save', 'bbpress-newrecaptcha' ); ?>" />
			</p>

		</form>
	</div>

	<?php
}

// register the plugin settings
function bbp_newrecaptcha_settings() {

	// create whitelist of options
	register_setting( 'bbp_newrecaptcha_group', 'bbp_newrecaptcha' );

}
add_action( 'admin_init', 'bbp_newrecaptcha_settings' );


function bbp_newrecaptcha_menu() {

	// add settings page
	add_submenu_page('options-general.php', __( 'bbPress No CAPTCHA reCAPTCHA Settings', 'bbpress-recaptcha' ), __( 'bbPress No CAPTCHA reCAPTCHA', 'bbpress-recaptcha' ), 'manage_options', 'bbpress-newrecaptcha-settings', 'bbp_newrecaptcha_page');
}
add_action('admin_menu', 'bbp_newrecaptcha_menu');
