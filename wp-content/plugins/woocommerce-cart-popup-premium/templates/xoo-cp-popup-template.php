<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
	return; 	
}

global $xoo_cp_ad_gl_ct_value,$xoo_cp_ad_bk_en_value,$xoo_cp_ad_bk_ict_value,$xoo_cp_gl_fullcart_value;

?>

<div class="xoo-cp-opac"></div>
<div class="xoo-cp-modal">
	<div class="xoo-cp-container">
		<div class="xoo-cp-outer">
			<div class="xoo-cp-cont-opac"></div>
			<span class="xoo-cp-preloader xoo-cp-icon-spinner"></span>
		</div>

		<span class="xoo-cp-close xoo-cp-icon-cross"></span>

		<?php if($xoo_cp_gl_fullcart_value): ?>
			<div class="xoo-cp-hdtxt"><?php echo $xoo_cp_ad_gl_ct_value; ?></div>
		<?php endif; ?>

		<div class="xoo-cp-atcn"></div>

		<div class="xoo-cp-container-scroll ">

			<?php do_action('xoo_cp_before_cart_content'); ?>

			<div class="xoo-cp-content"></div>

			<?php do_action('xoo_cp_after_cart_content'); ?>

			<div class="xoo-cp-rp-container"></div>

		</div>
	</div>
</div>


<?php if($xoo_cp_ad_bk_en_value): ?>
	<div class ="xoo-cp-basket">
		<span class="xcp-bk-count"><?php echo WC()->cart->get_cart_contents_count();  ?></span>
		<span class="xcp-bk-icon <?php echo $xoo_cp_ad_bk_ict_value; ?>"></span>
	</div>
<?php endif; ?>


<div class="xoo-cp-notice-box" style="display: none;">
	<div>
	  <span class="xoo-cp-notice"></span>
	</div>
</div>
