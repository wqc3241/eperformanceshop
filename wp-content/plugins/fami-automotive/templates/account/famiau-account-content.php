<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;

if ( ! in_array( 'famiau_user', $current_user->roles ) ) {
	return;
}

do_action( 'famiau_account_navigation' ); ?>

<div class="famiau-account-content">
	<?php
	if ( is_user_logged_in() ) {
		if ( current_user_can( 'famiau_user' ) ) {
			?>
            <div id="famiau-dashboard" class="famiau-acc-tab-content active">
				<?php
				echo '<p>' . sprintf( __( 'Hello %s (not %s? %s)', 'famiau' ), '<strong>' . $current_user->display_name . '</strong>', '<strong>' . $current_user->display_name . '</strong>', '<a href="' . famiau_logout_url() . '">' . esc_html__( 'Log out', 'famiau' ) . '</a>' ) . '</p>';
				echo '<p>' . sprintf( __( 'From your account dashboard you can view/manage your %s, edit %s.', 'famiau' ), '<a class="famiau-view-tab" href="#famiau-my-listings">' . esc_html__( 'listings', 'famiau' ) . '</a>', '<a href="#famiau-edit-account">' . esc_html__( 'your password and account details', 'famiau' ) . '</a>' ) . '</p>';
				?>
            </div>
            <div id="famiau-my-listings" class="famiau-acc-tab-content">
				<?php
				famiau_get_template_part( 'account/famiau-my-listings', '' );
				?>
            </div>
            <div id="famiau-personal-info" class="famiau-acc-tab-content">
            
            </div>
            <div id="famiau-edit-account" class="famiau-acc-tab-content">
            
            </div>
			<?php
		} else {
			?>
            <div class="famiau-message-wrap"><?php echo sprintf( '<p class="famiau-message famiau-message-info">%s</p>', esc_html__( 'Your account is limited to this page, please sign in with the appropriate account or sign up for a new one.', 'famiau' ) ); ?></div>
			<?php
		}
	} else {
		
	}
	?>
</div>
