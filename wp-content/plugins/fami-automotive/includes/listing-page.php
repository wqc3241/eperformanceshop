<?php
/**
 * Display the automotive listing (Will Not used)
 *
 * @package Fami Automotive
 */

// http://motors.stylemixthemes.com/classified/add-car/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'famiau' );

do_action( 'famiau_before_main_content' );

?>
    <header class="famiau-products-header">
        <div class="famiau-content-inner famiau-container">
			<?php if ( apply_filters( 'famiau_show_page_title', true ) ) : ?>
                <h1 class="famiau-products-header__title famiau-page-title page-title"><?php esc_html_e( 'Automotive Listing', 'famiau' ); ?></h1>
			<?php endif; ?>
        </div>
    </header>

    <div class="famiau-content-wrap">
        <div class="famiau-content-inner famiau-container">
			<?php
			
			/**
			 * famiau_display_listing   10
			 */
			do_action( 'famiau_listing' );
			
			?>
        </div>
    </div>

<?php


do_action( 'famiau_after_main_content' );

do_action( 'famiau_sidebar' );

get_footer( 'famiau' );