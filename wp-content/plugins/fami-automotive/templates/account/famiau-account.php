<?php
/**
 * Account page for Fami Automotive
 *
 * @package Fami Automotive
 */

defined( 'ABSPATH' ) || exit;

get_header( 'famiau' );

?>
    <div class="famiau-main-wrap">
        <div class="famiau-content-wrap">

            <div class="famiau-products-header">
                <div class="famiau-content-inner famiau-container">
                    <h1 class="famiau-page-title page-title"><?php esc_html_e( 'Automotive Account', 'famiau' ); ?></h1>
                </div>
            </div>

            <div class="famiau-content-inner famiau-container container">
				<?php
				
				if ( is_user_logged_in() ) {
					famiau_get_template_part( 'account/famiau-account', 'content' );
				} else {
					famiau_get_template_part( 'account/famiau-form-login' );
				}
				
				?>
            </div>
        </div>
    </div>
<?php
get_footer( 'famiau' );
