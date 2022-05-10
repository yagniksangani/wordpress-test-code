<?php
/**
 * Plugin Name: WP Custom Registration Form
 * Plugin URI: https://github.com/yagniksangani
 * Description: A plugin is used to add custom registration for on the frontend page.
 * Version: 1.0.0
 * Requires at least: 4.4
 * Requires PHP: 5.6.20
 * Author: Yagnik Sangani
 * Author URI: https://github.com/yagniksangani
 * Text Domain: wp
 * License: GPL v2 or later
 * Tested up to: 5.8.2
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * @package wp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_CUSTOM_REGISTRATION_FORM
 */
class WP_CUSTOM_REGISTRATION_FORM {

	/**
	 * Return instance of self.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * WP_CUSTOM_REGISTRATION_FORM constructor.
	 */
	public function __construct() {
		$this->hooks(); // register hooks to make things happen.
	}

	/**
	 * Add all the hook inside the this private method.
	 */
	public function hooks() {
		// Enqueue scripts to add into shortcode page.
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_custom_registration_form_shortcode_scripts' ) );

		// Add shortcode to add on frontend page.
		add_shortcode( 'wp_custom_registration_form', array( $this, 'wp_custom_registration_form_content' ) );

		// Action hook to register users.
		add_action( 'wp_ajax_nopriv_wp_custom_reg_form_data_save', array( $this, 'wp_custom_reg_form_data_save' ) );
	}

	/**
	 * Enqueue the scripts if the wp_custom_registration_form shortcode is being used.
	 */
	public function wp_custom_registration_form_shortcode_scripts() {
		global $post;

		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wp_custom_registration_form' ) ) {

			wp_enqueue_style( 'wp-custom-reg-form-css', plugin_dir_url( __FILE__ ) . 'assets/css/wp-custom-registration-form.css', array(), '1.0' );

			$translation_array = array(
				'site_url'   => site_url(),
				'admin_ajax' => admin_url( 'admin-ajax.php' ),
			);

			wp_register_script( 'wp-custom-reg-form-js', plugin_dir_url( __FILE__ ) . 'assets/js/wp-custom-registration-form.js', array( 'jquery', 'wp-util' ), '1.0', true );
			wp_localize_script( 'wp-custom-reg-form-js', 'wpcustomregform', $translation_array );
			wp_enqueue_script( 'wp-custom-reg-form-js' );

		}
	}

	/**
	 * Renders the HTML for custom registration form.
	 * Shortcode - wp_custom_registration_form.
	 */
	public function wp_custom_registration_form_content() {

		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'includes/templates/wp_custom_registration_form.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Register users using data of the custom registration form fields.
	 */
	public function wp_custom_reg_form_data_save() {
		// nonce check.
		check_ajax_referer( 'wp-custom-reg-form-data-save', 'nonce' );

		// get all fields data.
		$username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
		$email    = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING );
		$password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );

		// Register user using email address.
		$user_id = wp_create_user( $username, $password, $email );

		if ( is_wp_error( $user_id ) ) {
			$error_msg = $user_id->get_error_message();
			$error_msg = array( 'msg' => __( $error_msg, 'wp' ) );
			wp_send_json_error( $error_msg );
			wp_die();
		} else {
			$email_subject = __( 'Welcome to the demo site', 'wp' );

			// translators: %s email address.
			$email_body = sprintf( __( 'Hello %s, Thank you for registration with us. Thanks!', 'wp' ), $email );

			// Send the email...
			if ( ! empty( $email_subject ) && ! empty( $email_body ) ) {
				wp_mail(
					$email,
					$email_subject,
					$email_body,
					array( 'Content-Type: text/html; charset=UTF-8' )
				);
			}

			// Auto login after registration.
			$user = get_user_by( 'id', $user_id );
			wp_set_current_user( $user_id, $email );
			wp_set_auth_cookie( $user_id );
			do_action( 'wp_login', $email, $user );

			$message = __( 'Registration Successful.', 'wp' );
		}

		// Redirection page url to redirect user after registration.
		$redirection_url = home_url();

		$msg = array(
			'msg'             => $message,
			'redirection_url' => $redirection_url,
		);
		wp_send_json_success( $msg );

		exit;
	}

}

new WP_CUSTOM_REGISTRATION_FORM();
