<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="famiau-my-listing-wrap">
    <h3><?php esc_html_e( 'My Listing', 'famiau' ); ?></h3>
    <div class="famiau-my-listing-content">

        <div class="famiau-all-my-listings">
	        <?php famiau_get_template_part( 'account/famiau-my-listings', 'table' ); ?>
        </div>
        <button class="famiau-button famiau-toogle-add-new-listing-btn"><?php esc_html_e( 'Add New Listing', 'famiau' ); ?></button>
		<?php famiau_get_template_part( 'listing-form/famiau-listing', 'form' ); ?>
    </div>
</div>
