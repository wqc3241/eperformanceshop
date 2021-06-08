<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $famiau;
$video_links_desc = stripslashes( $famiau['_famiau_video_links_desc'] );

?>

<div class="famiau-fields-group">
    <div data-key="_famiau_video_url" class="form-group">
		<?php if ( trim( $video_links_desc ) != '' ) { ?>
            <div class="famiau-box-desc-wrap">
				<?php echo wpautop( do_shortcode( $video_links_desc ) ); ?>
            </div>
		<?php } ?>
        <input type="text" class="famiau-field form-control"
               name="_famiau_video_url"
               id="_famiau_video_url"
               placeholder="<?php esc_html_e( 'Add Youtube video or Vimeo video url', 'famiau' ); ?>"/>
    </div>
</div>
