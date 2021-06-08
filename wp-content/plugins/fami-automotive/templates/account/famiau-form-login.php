<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $famiau;

$user_can_create_acc = $famiau['_famiau_sellers_can_create_acc'] == 'yes';
$class               = $user_can_create_acc ? 'has-register-form' : '';

?>

<div class="famiau-login-register-wrap <?php echo esc_attr( $class ); ?>">

    <div class="famiau-login-form-wrap">
        <h2><?php esc_html_e( 'Login', 'famiau' ); ?></h2>
        <form name="famiau_login" class="famiau-form famiau-form-login login" method="post">

            <p class="famiau-form-row">
                <label><?php esc_html_e( 'Username or email address', 'famiau' ); ?>&nbsp;<span
                            class="required">*</span></label>
                <input type="text" class="famiau-input input-text" name="username" autocomplete="username"
                       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
            </p>
            <p class="famiau-form-row">
                <label><?php esc_html_e( 'Password', 'famiau' ); ?>&nbsp;<span class="required">*</span></label>
                <input class="famiau-input input-text" type="password" name="password" autocomplete="current-password"/>
            </p>

            <p class="form-row">
				<?php wp_nonce_field( 'famiau-login', 'famiau-login-nonce' ); ?>
                <button type="submit" class="famiau-button button" name="login"
                        value="<?php esc_attr_e( 'Log in', 'famiau' ); ?>"><?php esc_html_e( 'Log in', 'famiau' ); ?></button>
                <label class="famiau-form__label famiau-form__label-for-checkbox inline">
                    <input class="famiau-form__input famiau-form__input-checkbox" name="rememberme" type="checkbox"
                           value="forever"/>
                    <span><?php esc_html_e( 'Remember me', 'famiau' ); ?></span>
                </label>
            </p>
            <p class="famiau-lost-password lost_password">
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'famiau' ); ?></a>
            </p>

        </form>
    </div>
	
	<?php if ( $user_can_create_acc ) { ?>
        <div class="famiau-register-form-wrap">
            <h2><?php esc_html_e( 'Register', 'famiau' ); ?></h2>

            <form name="famiau_register" method="post" class="famiau-form famiau-form-register register">

                <p class="famiau-form-row">
                    <label><?php esc_html_e( 'Username', 'famiau' ); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="text" class="famiau-input input-text" name="famiau_username" autocomplete="username"
                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                </p>

                <p class="famiau-form-row">
                    <label><?php esc_html_e( 'Email address', 'famiau' ); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="email" class="famiau-input input-text" name="famiau_email"
                           autocomplete="email"
                           value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                </p>

                <p class="famiau-form-row">
                    <label for="famiau-password"><?php esc_html_e( 'Password', 'famiau' ); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="password" class="famiau-input input-text" name="famiau_password"/>
                </p>
                <p class="famiau-form-row">
                    <label><?php esc_html_e( 'Confirm Password', 'famiau' ); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="password" class="famiau-input input-text" name="famiau_cf_password"/>
                </p>

                <p class="famiau-FormRow form-row">
					<?php wp_nonce_field( 'famiau-register', 'famiau-register-nonce' ); ?>
                    <button type="submit" class="famiau-Button button" name="register"
                            value="<?php esc_attr_e( 'Register', 'famiau' ); ?>"><?php esc_html_e( 'Register', 'famiau' ); ?></button>
                </p>

            </form>

        </div>
	<?php } ?>

</div>

