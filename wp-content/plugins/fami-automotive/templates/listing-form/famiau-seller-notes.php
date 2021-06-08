<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $famiau;
$seler_notes_suggestions = famiau_seler_notes_suggestions();
$seler_notes_suggestions = $seler_notes_suggestions['seller_notes_suggestions'];
$suggestion_items        = $famiau[ $seler_notes_suggestions['option_key'] ];
?>

<div class="famiau-fields-group">
    <div data-key="<?php echo esc_attr( $seler_notes_suggestions['meta_key'] ); ?>" class="form-group">
        <textarea name="<?php echo esc_attr( $seler_notes_suggestions['meta_key'] ); ?>"
                  id="<?php echo esc_attr( $seler_notes_suggestions['meta_key'] ); ?>"
                  class="famiau-field famiau-has-suggestion form-control"></textarea>
		<?php if ( ! empty( $suggestion_items ) ) { ?>
            <span class="famiau-suggestion-lbs famiau-lbs-group">
                <?php foreach ( $suggestion_items as $suggestion_item ) { ?>
                    <label data-suggest_for="<?php echo esc_attr( $seler_notes_suggestions['meta_key'] ); ?>"
                           data-suggest_val="<?php echo esc_attr( $suggestion_item ); ?>"
                           class="famiau-suggestion-lb"><?php echo esc_attr( $suggestion_item ); ?></label>
                <?php } ?>
            </span>
		<?php } ?>
    </div>
</div>
