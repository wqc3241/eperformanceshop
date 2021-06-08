<?php
/**
 * Fami Automotive Account navigation
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $current_user;

if ( ! in_array( 'famiau_user', $current_user->roles ) ) {
	return;
}

do_action( 'famiau_before_account_navigation' );
?>

<nav class="famiau-account-navigation">
    <ul class="famiau-acc-tabs-nav famiau-tabs-nav">
        <li class="famiau-account-nav-link active">
            <a class="famiau-dashboard" href="#famiau-dashboard"><?php esc_attr_e( 'Dashboard', 'famiau' ); ?></a>
        </li>
        <li class="famiau-account-nav-link">
            <a class="famiau-my-listings"
               href="#famiau-my-listings"><?php esc_attr_e( 'My Listings', 'famiau' ); ?></a>
        </li>
        <li class="famiau-account-nav-link">
            <a class="famiau-personal-info"
               href="#famiau-personal-info"><?php esc_attr_e( 'Personal Information', 'famiau' ); ?></a>
        </li>
        <li class="famiau-account-nav-link">
            <a class="famiau-change-pw" href="#famiau-edit-account"><?php esc_attr_e( 'Account Details', 'famiau' ); ?></a>
        </li>
        <li class="famiau-account-nav-link">
            <a class="famiau-logout"
               href="<?php echo famiau_logout_url(); ?>"><?php esc_attr_e( 'Logout', 'famiau' ); ?></a>
        </li>
    </ul>
</nav>

<?php do_action( 'famiau_before_account_navigation' ); ?>
