<?php
/**
 * This template file used for load custom registration form html content.
 *
 * @package wp
 */

if ( ! is_user_logged_in() ) {

	?>

<div class="wp_custom_reg_form_sec">
	<div class="wp_custom_reg_form_container">
		<div class="wp_custom_reg_form_form">
			<div class="mb-3 form-group row wp_custom_reg_form_row">
				<label class="col-sm-2 form-label" for="wp_custom_username"><?php echo esc_html( __( 'Username', 'wp' ) ); ?></label>
				<div class="col-sm-10">    
					<input type="text" class="form-control" name="wp_custom_username" id="wp_custom_username" placeholder="<?php echo esc_html( __( 'Enter username', 'wp' ) ); ?>">
					<small class="wp_custom_reg_form_help_text form-text text-muted"></small>
					<div class="wp_custom_reg_form_error" style="display:none;"><?php echo esc_html( __( 'Please enter a username.', 'wp' ) ); ?></div>
				</div>
			</div>
			<div class="mb-3 form-group row wp_custom_reg_form_row">
				<label class="col-sm-2 form-label" for="wp_custom_email"><?php echo esc_html( __( 'Email', 'wp' ) ); ?></label>
				<div class="col-sm-10">    
					<input type="text" class="form-control" name="wp_custom_email" id="wp_custom_email" placeholder="<?php echo esc_html( __( 'Enter email address', 'wp' ) ); ?>">
					<small class="wp_custom_reg_form_help_text form-text text-muted"></small>
					<div class="wp_custom_reg_form_error" style="display:none;"><?php echo esc_html( __( 'Please enter a valid email address.', 'wp' ) ); ?></div>
				</div>
			</div>
			<div class="mb-3 form-group row wp_custom_reg_form_row">
				<label class="col-sm-2 form-label" for="wp_custom_password"><?php echo esc_html( __( 'Password', 'wp' ) ); ?></label>
				<div class="col-sm-10">    
					<input type="password" class="form-control" name="wp_custom_password" id="wp_custom_password" placeholder="<?php echo esc_html( __( 'Enter password', 'wp' ) ); ?>">
					<small class="wp_custom_reg_form_help_text form-text text-muted"></small>
					<div class="wp_custom_reg_form_error" style="display:none;"><?php echo esc_html( __( 'Please enter a valid email address.', 'wp' ) ); ?></div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group row wp_custom_reg_form_row">
					<div class="col-sm-2"></div>
					<div class="col-sm-10">
						<button type="submit" class="wp_custom_reg_form_submit btn btn-primary" data-nonce="<?php echo esc_html( wp_create_nonce( 'wp-custom-reg-form-data-save' ) ); ?>" data-action="wp_custom_reg_form_data_save"><?php echo esc_html( __( 'Register', 'wp' ) ); ?></button>
						<span class="wp_custom_reg_form_wait_msg"><?php echo esc_html( __( 'Please wait...', 'wp' ) ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php } else {
	echo esc_html( __( 'User is already loggedin.', 'wp' ) );
}

?>
