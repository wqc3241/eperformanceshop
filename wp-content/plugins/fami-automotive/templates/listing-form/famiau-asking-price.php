<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $famiau;

$prices_desc = stripslashes( $famiau['_famiau_prices_desc'] );

?>

<div class="famiau-fields-group">
    <div data-key="_famiau_price" class="form-group">
		<?php if ( trim( $prices_desc ) != '' ) { ?>
            <div class="famiau-box-desc-wrap">
				<?php echo wpautop( do_shortcode( $prices_desc ) ); ?>
            </div>
		<?php } ?>
        <input type="number" class="famiau-field form-control"
               name="_famiau_price"
               id="_famiau_price"/>
    </div>
</div>
