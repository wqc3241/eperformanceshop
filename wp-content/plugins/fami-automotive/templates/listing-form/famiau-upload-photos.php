<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $famiau;
$imgs_upload_desc = stripslashes( $famiau['_famiau_imgs_upload_desc'] );

$message_text_html = is_user_logged_in() ? '' : '<div class="famiau-warning-message-wrap"><p><a href="' . famiau_get_account_page_link() . '">' . esc_html__( 'You need to login to upload images', 'famiau' ) . '</a></p></div>';

?>

<?php if ( trim( $imgs_upload_desc ) != '' ) { ?>
    <div class="row">
        <div class="col-sm-12 col-md-3">
            <div class="famiau-box-desc-wrap">
				<?php echo wpautop( do_shortcode( $imgs_upload_desc ) ); ?>
				<?php echo $message_text_html; ?>
            </div>
        </div>
        <div class="col-sm-12 col-md-9">
            <div class="famiau-fields-group">
				<?php if ( shortcode_exists( 'famiau_upload_media' ) ) {
					echo do_shortcode( '[famiau_upload_media]' );
				}; ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="famiau-fields-group">
		<?php if ( shortcode_exists( 'famiau_upload_media' ) ) {
			echo do_shortcode( '[famiau_upload_media]' );
		}; ?>
    </div>
<?php } ?>
