<?php
//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}
?>
<?php settings_errors(); ?>
<div class="xoo-container">
	<div class="xoo-main">

		<form method="POST" action="options.php" class="xoo-cp-form">
			<h3>Added to cart popup Settings.</h3>
			<?php settings_fields('xoo-cp-group'); ?>
			<?php do_settings_sections('xoo_cp'); ?>
			<?php submit_button(); ?>
		</form>

		<div class="rate-plugin">If you like the plugin , please show your support by rating <a href="" target="_blank">here.</a></div>
		<div class="plugin-support">
			Report Bugs/Issues <a href="" target="_blank">here.</a>,so that we can fix it :)
		</div>
	</div>

	<div class="xoo-sidebar">
		<?php require_once XOO_CP_PATH.'/admin/xoo-cp-sidebar.php'; ?>
	</div>
</div>