<?php
/**
 * Account page for Fami Automotive (Will not use)
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

    <div class="famiau-content-wrap">

        <div class="famiau-products-header">
            <div class="famiau-content-inner famiau-container">
                <h1 class="famiau-page-title page-title"><?php esc_html_e( 'Automotive Account', 'famiau' ); ?></h1>
            </div>
        </div>
        
        <div class="famiau-content-inner famiau-container container">
			<?php
			
			famiau_wc_get_template_part( 'account/famiau-account', 'content' );
			
			?>
        </div>
    </div>

<?php


do_action( 'famiau_after_main_content' );

do_action( 'famiau_sidebar' );

get_footer( 'famiau' );