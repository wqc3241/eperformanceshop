<?php
/**
 ========================
      ADMIN SETTINGS
 ========================
 */

//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}

// Enqueue Scripts & Stylesheet
function xoo_cp_admin_enqueue($hook){
	if('toplevel_page_xoo_cp' != $hook){
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style('xoo-cp-admin-css',plugins_url('/assets/css/xoo-cp-admin-css.css',__FILE__),null,'1.2');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('xoo-cp-admin-js',plugins_url('/assets/js/xoo-cp-admin-js.js',__FILE__),array('jquery','wp-color-picker'),'1.2',true);
}
add_action('admin_enqueue_scripts','xoo_cp_admin_enqueue');

//Settings page
function xoo_cp_menu_settings(){
	add_menu_page( 'Added to cart popup', 'Added to cart popup', 'manage_options', 'xoo_cp', 'xoo_cp_settings_cb', 'dashicons-cart', 61 );
	add_action('admin_init','xoo_cp_settings');
}
add_action('admin_menu','xoo_cp_menu_settings');

//Settings callback function
function xoo_cp_settings_cb(){
	include plugin_dir_path(__FILE__).'xoo-cp-settings.php';
}

//Custom settings
function xoo_cp_settings(){

	/*****************************/
	/** BASIC REGISTER SETTINGS **/
	/*****************************/

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-atcem'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-fullcart'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-ibtne'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-qtyen'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-vcbtne'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-chbtne'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-spinen'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-splk'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-pw'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-imgw'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btnc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btnbg'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btns'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btnbr'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-tbs'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-tbc'
 	);

 	/*********************************/
	/** Advanced REGISTER SETTINGS **/
	/*******************************/

	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-en'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-enm'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-enatc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-tl'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-no'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-ty'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-pm'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-rl-pts'
 	);

 	//Basket

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-en'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-ict'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-bdr'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-bs'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-bc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-bbgc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-icc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-bk-icbg'
 	);

 	//Product item

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-ti-hbg'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-ti-hc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-ti-tw'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-ti-ta'
 	);

 	//General 

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-gl-ct'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-gl-ctc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-gl-ctbg'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-gl-ctfs'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-gl-ctbs'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-gl-ctbc'
 	);

 	//Style Options

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-sy-cbimg'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-sy-cbg'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-sy-ctc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-sy-cc'
 	);


 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-sy-rpc'
 	);


 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-ad-sy-sbt'
 	);


	/***************/
	/** SECTIONS **/
	/*************/

	//Begin Basic
 	add_settings_section(
		'xoo-cp-main',
		'',
		'xoo_cp_main_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-gl',
		'',
		'xoo_cp_gl_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-sy',
		'',
		'xoo_cp_sy_cb',
		'xoo_cp'
	);

	//End Basic - Begin Advanced
	add_settings_section(
		'xoo-cp-adv',
		'',
		'xoo_cp_adv_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-ad-rl',
		'',
		'xoo_cp_ad_rl_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-ad-bk',
		'',
		'xoo_cp_ad_bk_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-ad-ti',
		'',
		'xoo_cp_ad_ti_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-ad-gl',
		'',
		'xoo_cp_ad_gl_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-ad-sy',
		'',
		'xoo_cp_ad_sy_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-adv-end',
		'',
		'xoo_cp_adv_end_cb',
		'xoo_cp'
	);


	/*****************************/
	/** BASIC  SETTINGS FIELD   **/
	/*****************************/

	add_settings_field(
		'xoo-cp-gl-atcem',
		'Enable on Mobile',
		'xoo_cp_gl_atcem_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-fullcart',
		'Full Cart',
		'xoo_cp_gl_fullcart_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-ibtne',
		'+/- Qty Button',
		'xoo_cp_gl_ibtne_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-qtyen',
		'Update Quantity',
		'xoo_cp_gl_qtyen_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-vcbtne',
		'View Cart Button',
		'xoo_cp_gl_vcbtne_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-chbtne',
		'Checkout Button',
		'xoo_cp_gl_chbtne_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-spinen',
		'Show spinner icon',
		'xoo_cp_gl_spinen_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-splk',
		'Shop Link',
		'xoo_cp_gl_splk_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);


	add_settings_field(
		'xoo-cp-sy-pw',
		'PopUp Width',
		'xoo_cp_sy_pw_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);


	add_settings_field(
		'xoo-cp-sy-imgw',
		'Image Width',
		'xoo_cp_sy_imgw_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btnbg',
		'Button Background Color',
		'xoo_cp_sy_btnbg_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btnc',
		'Button Text Color',
		'xoo_cp_sy_btnc_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btns',
		'Button Font Size',
		'xoo_cp_sy_btns_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btnbr',
		'Button Border Radius',
		'xoo_cp_sy_btnbr_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-tbs',
		'Item Border Size',
		'xoo_cp_sy_tbs_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-tbc',
		'Item Border Color',
		'xoo_cp_sy_tbc_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	/*****************************/
	/** ADVANCED SETTINGS FIELD **/
	/*****************************/

	add_settings_field(
		'xoo-cp-ad-rl-en',
		'Enable Suggested Products',
		'xoo_cp_ad_rl_en_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);

	add_settings_field(
		'xoo-cp-ad-rl-enm',
		'Suggested Products on mobile',
		'xoo_cp_ad_rl_enm_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);

	add_settings_field(
		'xoo-cp-ad-rl-enatc',
		'Add to Cart',
		'xoo_cp_ad_rl_enatc_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);

	add_settings_field(
		'xoo-cp-ad-rl-tl',
		'Suggested products title',
		'xoo_cp_ad_rl_tl_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);

	add_settings_field(
		'xoo-cp-ad-rl-no',
		'Number of suggested products',
		'xoo_cp_ad_rl_no_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);

	add_settings_field(
		'xoo-cp-ad-rl-ty',
		'Suggested products type',
		'xoo_cp_ad_rl_ty_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);


	add_settings_field(
		'xoo-cp-ad-rl-pm',
		'Suggested products Margin',
		'xoo_cp_ad_rl_pm_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);

	add_settings_field(
		'xoo-cp-ad-rl-pts',
		'Products Font Size',
		'xoo_cp_ad_rl_pts_cb',
		'xoo_cp',
		'xoo-cp-ad-rl'
	);


	//Basket

	add_settings_field(
		'xoo-cp-ad-bk-en',
		'Show Basket',
		'xoo_cp_ad_bk_en_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);

	add_settings_field(
		'xoo-cp-ad-bk-ict',
		'Basket Icon',
		'xoo_cp_ad_bk_ict_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);


	add_settings_field(
		'xoo-cp-ad-bk-bdr',
		'Draggable Basket',
		'xoo_cp_ad_bk_bdr_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);


	add_settings_field(
		'xoo-cp-ad-bk-bs',
		'Basket Size',
		'xoo_cp_ad_bk_bs_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);

	add_settings_field(
		'xoo-cp-ad-bk-bc',
		'Basket Color',
		'xoo_cp_ad_bk_bc_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);

	add_settings_field(
		'xoo-cp-ad-bk-bbgc',
		'Basket Background Color',
		'xoo_cp_ad_bk_bbgc_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);


	add_settings_field(
		'xoo-cp-ad-bk-icc',
		'Basket Count Color',
		'xoo_cp_ad_bk_icc_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);

	add_settings_field(
		'xoo-cp-ad-bk-icbg',
		'Basket Count BG Color',
		'xoo_cp_ad_bk_icbg_cb',
		'xoo_cp',
		'xoo-cp-ad-bk'
	);

	//P* Product item options

	add_settings_field(
		'xoo-cp-ad-ti-hbg',
		'Table Head BG Color',
		'xoo_cp_ad_ti_hbg_cb',
		'xoo_cp',
		'xoo-cp-ad-ti'
	);

	add_settings_field(
		'xoo-cp-ad-ti-hc',
		'Table Head Color',
		'xoo_cp_ad_ti_hc_cb',
		'xoo_cp',
		'xoo-cp-ad-ti'
	);

	add_settings_field(
		'xoo-cp-ad-ti-tw',
		'Product Title Width',
		'xoo_cp_ad_ti_tw_cb',
		'xoo_cp',
		'xoo-cp-ad-ti'
	);

	add_settings_field(
		'xoo-cp-ad-ti-ta',
		'Product Title Align',
		'xoo_cp_ad_ti_ta_cb',
		'xoo_cp',
		'xoo-cp-ad-ti'
	);

	//P* General Options

	add_settings_field(
		'xoo-cp-ad-gl-ct',
		'Your Cart Text',
		'xoo_cp_ad_gl_ct_cb',
		'xoo_cp',
		'xoo-cp-ad-gl'
	);

	add_settings_field(
		'xoo-cp-ad-gl-ctc',
		'Title Text Color',
		'xoo_cp_ad_gl_ctc_cb',
		'xoo_cp',
		'xoo-cp-ad-gl'
	);

	add_settings_field(
		'xoo-cp-ad-gl-ctbg',
		'Title Background Color',
		'xoo_cp_ad_gl_ctbg_cb',
		'xoo_cp',
		'xoo-cp-ad-gl'
	);

	add_settings_field(
		'xoo-cp-ad-gl-ctfs',
		'Title Font Size',
		'xoo_cp_ad_gl_ctfs_cb',
		'xoo_cp',
		'xoo-cp-ad-gl'
	);

	add_settings_field(
		'xoo-cp-ad-gl-ctbs',
		'Title Border Size',
		'xoo_cp_ad_gl_ctbs_cb',
		'xoo_cp',
		'xoo-cp-ad-gl'
	);

	add_settings_field(
		'xoo-cp-ad-gl-ctbc',
		'Title Border Color',
		'xoo_cp_ad_gl_ctbc_cb',
		'xoo_cp',
		'xoo-cp-ad-gl'
	);



	//P* Other Style Options

	add_settings_field(
		'xoo-cp-ad-sy-cbimg',
		'Container Background Image',
		'xoo_cp_ad_sy_cbimg_cb',
		'xoo_cp',
		'xoo-cp-ad-sy'
	);

	add_settings_field(
		'xoo-cp-ad-sy-cbg',
		'Container Background Color',
		'xoo_cp_ad_sy_cbg_cb',
		'xoo_cp',
		'xoo-cp-ad-sy'
	);

	add_settings_field(
		'xoo-cp-ad-sy-ctc',
		'Container Text Color',
		'xoo_cp_ad_sy_ctc_cb',
		'xoo_cp',
		'xoo-cp-ad-sy'
	);

	add_settings_field(
		'xoo-cp-ad-sy-cc',
		'Container Close Button',
		'xoo_cp_ad_sy_cc_cb',
		'xoo_cp',
		'xoo-cp-ad-sy'
	);


	add_settings_field(
		'xoo-cp-ad-sy-rpc',
		'Remove Product Button Color',
		'xoo_cp_ad_sy_rpc_cb',
		'xoo_cp',
		'xoo-cp-ad-sy'
	);

	add_settings_field(
		'xoo-cp-ad-sy-sbt',
		'Scrollbar Theme',
		'xoo_cp_ad_sy_sbt_cb',
		'xoo_cp',
		'xoo-cp-ad-sy'
	);

}

/***** Custom Settings Callback *****/

//Main - General Settings callback
function xoo_cp_main_cb(){
	?>

<?php 	/** Settings Tab **/ ?>
	<div class="xoo-tabs">
		<ul>
			<li class="tab-1 active-tab">Main</li>
			<li class="tab-2">Advanced</li>
		</ul>
	</div>

<?php 	/** Settings Tab **/ ?>

	<?php
	$tab = '<div class="main-settings settings-tab settings-tab-active" tab-class ="tab-1">';  //Begin Main settings
	echo $tab;
}


function xoo_cp_gl_cb(){
	echo '<h2>General Options</h2>';
}

function xoo_cp_sy_cb(){
	echo '<h2>Style Options</h2>';
}

function xoo_cp_adv_cb(){
	echo '</div>';
	echo '<div class="advanced-settings settings-tab" tab-class="tab-2">';
}

function xoo_cp_ad_rl_cb(){
	echo '<h2>Suggested Products</h2>';
}

function xoo_cp_ad_bk_cb(){
	echo '<h2>Cart Basket</h2>';
}

function xoo_cp_ad_ti_cb(){
	echo '<h2>Table Items</h2>';
}


function xoo_cp_ad_gl_cb(){
	echo '<h2>General Options</h2>';
}

function xoo_cp_ad_sy_cb(){
	echo '<h2>Other Style Options</h2>';
}

function xoo_cp_adv_end_cb(){
	echo '</div>';
}


/*****************************/
/** BASIC SETTINGS CALLBACK **/
/*****************************/


//Enable on Mobile Devices
$xoo_cp_gl_atcem_value = sanitize_text_field(get_option('xoo-cp-gl-atcem','true'));
function xoo_cp_gl_atcem_cb(){
	global $xoo_cp_gl_atcem_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-atcem" id="xoo-cp-gl-atcem" value="true"'.checked('true',$xoo_cp_gl_atcem_value,false).'>';
	$html .= '<label for="xoo-cp-gl-atcem">Enable on mobile devices.</label>';
	echo $html;
}

$xoo_cp_gl_fullcart_value = sanitize_text_field(get_option('xoo-cp-gl-fullcart','true'));
function xoo_cp_gl_fullcart_cb(){
	global $xoo_cp_gl_fullcart_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-fullcart" id="xoo-cp-gl-fullcart" value="true"'.checked('true',$xoo_cp_gl_fullcart_value,false).'>';
	$html .= '<label for="xoo-cp-gl-fullcart">Show all cart items.</label>';
	echo $html;
}

//Enable +/- button
$xoo_cp_gl_ibtne_value = sanitize_text_field(get_option('xoo-cp-gl-ibtne','true'));
function xoo_cp_gl_ibtne_cb(){
	global $xoo_cp_gl_ibtne_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-ibtne" id="xoo-cp-gl-ibtne" value="true"'.checked('true',$xoo_cp_gl_ibtne_value,false).'>';
	$html .= '<label for="xoo-cp-gl-ibtne"> Enable Increase/Decrease Quantity buttons.</label>';
	echo $html;
}

//Allow Quantity Update
$xoo_cp_gl_qtyen_value = sanitize_text_field(get_option('xoo-cp-gl-qtyen','true'));
function xoo_cp_gl_qtyen_cb(){
	global $xoo_cp_gl_qtyen_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-qtyen" id="xoo-cp-gl-qtyen" value="true"'.checked('true',$xoo_cp_gl_qtyen_value,false).'>';
	$html .= '<label for="xoo-cp-gl-qtyen">Allow users to update quantity from popup.</label>';
	echo $html;
}


//View Cart button
$xoo_cp_gl_vcbtne_value = sanitize_text_field(get_option('xoo-cp-gl-vcbtne','true'));
function xoo_cp_gl_vcbtne_cb(){
	global $xoo_cp_gl_vcbtne_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-vcbtne" id="xoo-cp-gl-vcbtne" value="true"'.checked('true',$xoo_cp_gl_vcbtne_value,false).'>';
	$html .= '<label for="xoo-cp-gl-vcbtne">Enable View Cart button.</label>';
	echo $html;
}

//Checkout button
$xoo_cp_gl_chbtne_value = sanitize_text_field(get_option('xoo-cp-gl-chbtne','true'));
function xoo_cp_gl_chbtne_cb(){
	global $xoo_cp_gl_chbtne_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-chbtne" id="xoo-cp-gl-chbtne" value="true"'.checked('true',$xoo_cp_gl_chbtne_value,false).'>';
	$html .= '<label for="xoo-cp-gl-chbtne">Enable Checkout button.</label>';
	echo $html;
}


//Enable spin icon
$xoo_cp_gl_spinen_value = sanitize_text_field(get_option('xoo-cp-gl-spinen','true'));
function xoo_cp_gl_spinen_cb(){
	global $xoo_cp_gl_spinen_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-spinen" id="xoo-cp-gl-spinen" value="true"'.checked('true',$xoo_cp_gl_spinen_value,false).'>';
	$html .= '<label for="xoo-cp-gl-spinen">Show spinner/Check icon on add to cart.</label>';
	echo $html;
}


//Enable spin icon
$xoo_cp_gl_splk_value = sanitize_text_field(get_option('xoo-cp-gl-splk',''));
function xoo_cp_gl_splk_cb(){
	global $xoo_cp_gl_splk_value;
	$html  = '<input type="text" name="xoo-cp-gl-splk" id="xoo-cp-gl-splk" value="'.$xoo_cp_gl_splk_value.'">';
	$html .= '<label for="xoo-cp-gl-splk">Shop page link when cart is empty.</label>';
	echo $html;
}


//Style Options Callback

//Popup Width
$xoo_cp_sy_pw_value = sanitize_text_field(get_option('xoo-cp-sy-pw',650));
function xoo_cp_sy_pw_cb(){
	global $xoo_cp_sy_pw_value;
	$html  = '<input type="number" name="xoo-cp-sy-pw" id="xoo-cp-sy-pw" value="'.$xoo_cp_sy_pw_value.'">';
	$html .= '<label for="xoo-cp-sy-pw">Value in pixels (Default: 650).</label>';
	echo $html;
}


//Image Width
$xoo_cp_sy_imgw_value = sanitize_text_field(get_option('xoo-cp-sy-imgw','20'));
function xoo_cp_sy_imgw_cb(){
	global $xoo_cp_sy_imgw_value;
	$html  = '<input type="number" name="xoo-cp-sy-imgw" id="xoo-cp-sy-imgw" value="'.$xoo_cp_sy_imgw_value.'">';
	$html .= '<label for="xoo-cp-sy-imgw">Value in percentage (Default: 20).</label>';
	echo $html;
}

//Button Background Color
$xoo_cp_sy_btnbg_value = sanitize_text_field(get_option('xoo-cp-sy-btnbg','#a46497'));
function xoo_cp_sy_btnbg_cb(){
	global $xoo_cp_sy_btnbg_value;
	$html  = '<input type="text" name="xoo-cp-sy-btnbg" id="xoo-cp-sy-btnbg" class="color-field" value="'.$xoo_cp_sy_btnbg_value.'"';
	echo $html;
}

//Button text Color
$xoo_cp_sy_btnc_value = sanitize_text_field(get_option('xoo-cp-sy-btnc','#ffffff'));
function xoo_cp_sy_btnc_cb(){
	global $xoo_cp_sy_btnc_value;
	$html  = '<input type="text" name="xoo-cp-sy-btnc" id="xoo-cp-sy-btnc" class="color-field" value="'.$xoo_cp_sy_btnc_value.'"';
	echo $html;
}

//Button Font Size
$xoo_cp_sy_btns_value = sanitize_text_field(get_option('xoo-cp-sy-btns','14'));
function xoo_cp_sy_btns_cb(){
	global $xoo_cp_sy_btns_value;
	$html  = '<input type="number" name="xoo-cp-sy-btns" id="xoo-cp-sy-btns" value="'.$xoo_cp_sy_btns_value.'">';
	$html .= '<label for="xoo-cp-sy-btns">Size in px (Default 14).</label>';
	echo $html;
}

//Button Border Radius
$xoo_cp_sy_btnbr_value = sanitize_text_field(get_option('xoo-cp-sy-btnbr','14'));
function xoo_cp_sy_btnbr_cb(){
	global $xoo_cp_sy_btnbr_value;
	$html  = '<input type="number" name="xoo-cp-sy-btnbr" id="xoo-cp-sy-btnbr" value="'.$xoo_cp_sy_btnbr_value.'">';
	$html .= '<label for="xoo-cp-sy-btnbr">Size in px (Default 5).</label>';
	echo $html;
}


//Tr Border Size
$xoo_cp_sy_tbs_value = sanitize_text_field(get_option('xoo-cp-sy-tbs','1'));
function xoo_cp_sy_tbs_cb(){
	global $xoo_cp_sy_tbs_value;
	$html  = '<input type="number" name="xoo-cp-sy-tbs" id="xoo-cp-sy-tbs" value="'.$xoo_cp_sy_tbs_value.'">';
	$html .= '<label for="xoo-cp-sy-tbs">Size in px (Default 1).</label>';
	echo $html;
}


//Table Border Color
$xoo_cp_sy_tbc_value = sanitize_text_field(get_option('xoo-cp-sy-tbc','#ebe9eb'));
function xoo_cp_sy_tbc_cb(){
	global $xoo_cp_sy_tbc_value;
	$html  = '<input type="text" class="color-field" name="xoo-cp-sy-tbc" id="xoo-cp-sy-tbc" value="'.$xoo_cp_sy_tbc_value.'">';
	echo $html;
}

/********************************/
/** ADVANCED SETTINGS CALLBACK **/
/********************************/

//Enable Suggested Products
$xoo_cp_ad_rl_en_value = sanitize_text_field(get_option('xoo-cp-ad-rl-en','true'));
function xoo_cp_ad_rl_en_cb(){
	global $xoo_cp_ad_rl_en_value;
	$html  = '<input type="checkbox" name="xoo-cp-ad-rl-en" id="xoo-cp-ad-rl-en" value="true"'.checked('true',$xoo_cp_ad_rl_en_value,false).'>';
	$html .= '<label for="xoo-cp-ad-rl-en">Enable suggested products.</label>';
	echo $html;
}

//Enable Suggested Products on mobile
$xoo_cp_ad_rl_enm_value = sanitize_text_field(get_option('xoo-cp-ad-rl-enm','false'));
function xoo_cp_ad_rl_enm_cb(){
	global $xoo_cp_ad_rl_enm_value;
	$html  = '<input type="checkbox" name="xoo-cp-ad-rl-enm" id="xoo-cp-ad-rl-enm" value="true"'.checked('true',$xoo_cp_ad_rl_enm_value,false).'>';
	$html .= '<label for="xoo-cp-ad-rl-enm">Enable suggested products on mobile.</label>';
	echo $html;
}

//Enable add to cart button
$xoo_cp_ad_rl_enatc_value = sanitize_text_field(get_option('xoo-cp-ad-rl-enatc','true'));
function xoo_cp_ad_rl_enatc_cb(){
	global $xoo_cp_ad_rl_enatc_value;
	$html  = '<input type="checkbox" name="xoo-cp-ad-rl-enatc" id="xoo-cp-ad-rl-enatc" value="true"'.checked('true',$xoo_cp_ad_rl_enatc_value,false).'>';
	$html .= '<label for="xoo-cp-ad-rl-enatc">Enable Add to cart button for suggested products.</label>';
	echo $html;
}

//Suggested Products Title
$xoo_cp_ad_rl_tl_value = sanitize_text_field(get_option('xoo-cp-ad-rl-tl','Products you may like'));
function xoo_cp_ad_rl_tl_cb(){
	global $xoo_cp_ad_rl_tl_value;
	$html  = '<input type="text" name="xoo-cp-ad-rl-tl" id="xoo-cp-ad-rl-tl" value="'.$xoo_cp_ad_rl_tl_value.'">';
	$html .= '<label for="xoo-cp-ad-rl-tl">Suggested Products Title.</label>';
	echo $html;
}

//Number of suggested products
$xoo_cp_ad_rl_no_value = sanitize_text_field(get_option('xoo-cp-ad-rl-no',5));
function xoo_cp_ad_rl_no_cb(){
	global $xoo_cp_ad_rl_no_value;
	$html  = '<input type="number" name="xoo-cp-ad-rl-no" id="xoo-cp-ad-rl-no" value="'.$xoo_cp_ad_rl_no_value.'">';
	$html .= '<label for="xoo-cp-ad-rl-no">Suggested Products Number.</label>';
	echo $html;
}

//Type of suggested Products
$xoo_cp_ad_rl_ty_value = sanitize_text_field(get_option('xoo-cp-ad-rl-ty','related'));
function xoo_cp_ad_rl_ty_cb(){
	global $xoo_cp_ad_rl_ty_value;
	?>
	<select name="xoo-cp-ad-rl-ty">
		<option value="related" <?php selected('related',$xoo_cp_ad_rl_ty_value); ?>>Related Products</option>
		<option value="cross-sells" <?php selected('cross-sells',$xoo_cp_ad_rl_ty_value); ?>>Cross-sells</option>
		<option value="up-sells" <?php selected('up-sells',$xoo_cp_ad_rl_ty_value); ?>>Up-sells</option>
	</select>
	<?php
}


//Suggested product margin
$xoo_cp_ad_rl_pm_value = sanitize_text_field(get_option('xoo-cp-ad-rl-pm',2));
function xoo_cp_ad_rl_pm_cb(){
	global $xoo_cp_ad_rl_pm_value;
	$html  = '<input type="number" name="xoo-cp-ad-rl-pm" id="xoo-cp-ad-rl-pm" value="'.$xoo_cp_ad_rl_pm_value.'">';
	$html .= '<label for="xoo-cp-ad-rl-pm">Margin between products (Value in Percentage , Default: 2)</label>';
	echo $html;
}

//Font Size
$xoo_cp_ad_rl_pts_value = sanitize_text_field(get_option('xoo-cp-ad-rl-pts',13));
function xoo_cp_ad_rl_pts_cb(){
	global $xoo_cp_ad_rl_pts_value;
	$html  = '<input type="number" name="xoo-cp-ad-rl-pts" id="xoo-cp-ad-rl-pts" value="'.$xoo_cp_ad_rl_pts_value.'">';
	$html .= '<label for="xoo-cp-ad-rl-pts">Font Size (Value in px , Default : 13).</label>';
	echo $html;
}


//****** CART BASKET ******//

//Enable Basket
$xoo_cp_ad_bk_en_value = sanitize_text_field(get_option('xoo-cp-ad-bk-en','true'));
function xoo_cp_ad_bk_en_cb(){
	global $xoo_cp_ad_bk_en_value;
	$html  = '<input type="checkbox" name="xoo-cp-ad-bk-en" id="xoo-cp-ad-bk-en" value="true"'.checked('true',$xoo_cp_ad_bk_en_value,false).'>';
	$html .= '<label>Show basket icon</label>';
	echo $html;
}

//Basket Icon type
$xoo_cp_ad_bk_ict_value = sanitize_text_field(get_option('xoo-cp-ad-bk-ict','xoo-cp-icon-basket1'));
function xoo_cp_ad_bk_ict_cb(){
	global $xoo_cp_ad_bk_ict_value;
	?>
	<select name="xoo-cp-ad-bk-ict" id="xoo-cp-ad-bk-ict">
		<option value="xoo-cp-icon-basket1" <?php selected($xoo_cp_ad_bk_ict_value,'xoo-cp-icon-basket1'); ?>>&#xe903; Basket Icon 1</option>
		<option value="xoo-cp-icon-basket2" <?php selected($xoo_cp_ad_bk_ict_value,'xoo-cp-icon-basket2'); ?>>&#xe904; Basket Icon 2</option>
		<option value="xoo-cp-icon-basket3" <?php selected($xoo_cp_ad_bk_ict_value,'xoo-cp-icon-basket3'); ?>>&#xe905; Basket Icon 3</option>
		<option value="xoo-cp-icon-basket4" <?php selected($xoo_cp_ad_bk_ict_value,'xoo-cp-icon-basket4'); ?>>&#xe901; Basket Icon 4</option>
		<option value="xoo-cp-icon-basket5" <?php selected($xoo_cp_ad_bk_ict_value,'xoo-cp-icon-basket5'); ?>>&#xe900; Basket Icon 5</option>
		<option value="xoo-cp-icon-basket6" <?php selected($xoo_cp_ad_bk_ict_value,'xoo-cp-icon-basket6'); ?>>&#xe902; Basket Icon 6</option>
	</select>
	<?php
}

//Basket draggable
$xoo_cp_ad_bk_bdr_value = sanitize_text_field(get_option('xoo-cp-ad-bk-bdr','true'));
function xoo_cp_ad_bk_bdr_cb(){
	global $xoo_cp_ad_bk_bdr_value;
	$html  = '<input type="checkbox" name="xoo-cp-ad-bk-bdr" id="xoo-cp-ad-bk-bdr" value="true"'.checked('true',$xoo_cp_ad_bk_bdr_value,false).'>';
	$html .= '<label for="xoo-cp-ad-bk-bdr">Drag Basket anywhere on the screen.</label>';
	echo $html;
}

//Basket size
$xoo_cp_ad_bk_bs_value = sanitize_text_field(get_option('xoo-cp-ad-bk-bs',40));
function xoo_cp_ad_bk_bs_cb(){
	global $xoo_cp_ad_bk_bs_value;
	$html  = '<input type="number" name="xoo-cp-ad-bk-bs" id="xoo-cp-ad-bk-bs" value="'.$xoo_cp_ad_bk_bs_value.'">';
	$html .= '<label for="xoo-cp-ad-bk-bs">Value in px. (Default : 40)</label>';
	echo $html;
}

//Basket Color
$xoo_cp_ad_bk_bc_value = sanitize_text_field(get_option('xoo-cp-ad-bk-bc','#444444'));
function xoo_cp_ad_bk_bc_cb(){
	global $xoo_cp_ad_bk_bc_value;
	$html  = '<input type="text" name="xoo-cp-ad-bk-bc" id="xoo-cp-ad-bk-bc" value="'.$xoo_cp_ad_bk_bc_value.'" class="color-field">';
	echo $html;
}

//Basket Background Color
$xoo_cp_ad_bk_bbgc_value = sanitize_text_field(get_option('xoo-cp-ad-bk-bbgc','#ffffff'));
function xoo_cp_ad_bk_bbgc_cb(){
	global $xoo_cp_ad_bk_bbgc_value;
	$html  = '<input type="text" name="xoo-cp-ad-bk-bbgc" id="xoo-cp-ad-bk-bbgc" value="'.$xoo_cp_ad_bk_bbgc_value.'" class="color-field">';
	echo $html;
}


//Basket Count Color
$xoo_cp_ad_bk_icc_value = sanitize_text_field(get_option('xoo-cp-ad-bk-icc','#ffffff'));
function xoo_cp_ad_bk_icc_cb(){
	global $xoo_cp_ad_bk_icc_value;
	$html  = '<input type="text" name="xoo-cp-ad-bk-icc" id="xoo-cp-ad-bk-icc" value="'.$xoo_cp_ad_bk_icc_value.'" class="color-field">';
	echo $html;
}

//Basket Count background Color
$xoo_cp_ad_bk_icbg_value = sanitize_text_field(get_option('xoo-cp-ad-bk-icbg','#cc0086'));
function xoo_cp_ad_bk_icbg_cb(){
	global $xoo_cp_ad_bk_icbg_value;
	$html  = '<input type="text" name="xoo-cp-ad-bk-icbg" id="xoo-cp-ad-bk-icbg" value="'.$xoo_cp_ad_bk_icbg_value.'" class="color-field">';
	echo $html;
}

/** Table Items Options **/

//Table Head Background Color
$xoo_cp_ad_ti_hbg_value = sanitize_text_field(get_option('xoo-cp-ad-ti-hbg','#eeeeee'));
function xoo_cp_ad_ti_hbg_cb(){
	global $xoo_cp_ad_ti_hbg_value;
	$html  = '<input type="text" name="xoo-cp-ad-ti-hbg" id="xoo-cp-ad-ti-hbg" value="'.$xoo_cp_ad_ti_hbg_value.'" class="color-field">';
	echo $html;
}

//Table Head text Color
$xoo_cp_ad_ti_hc_value = sanitize_text_field(get_option('xoo-cp-ad-ti-hc','#000000'));
function xoo_cp_ad_ti_hc_cb(){
	global $xoo_cp_ad_ti_hc_value;
	$html  = '<input type="text" name="xoo-cp-ad-ti-hc" id="xoo-cp-ad-ti-hc" value="'.$xoo_cp_ad_ti_hc_value.'" class="color-field">';
	echo $html;
}

//Item title Width
$xoo_cp_ad_ti_tw_value = sanitize_text_field(get_option('xoo-cp-ad-ti-tw',40));
function xoo_cp_ad_ti_tw_cb(){
	global $xoo_cp_ad_ti_tw_value;
	$html  = '<input type="number" name="xoo-cp-ad-ti-tw" id="xoo-cp-ad-ti-tw" value="'.$xoo_cp_ad_ti_tw_value.'">';
	$html .= '<label for="xoo-cp-ad-ti-tw">Item Title Column width.(Value in percentage - Default: 40)</label>';
	echo $html;
}

//Item title align
$xoo_cp_ad_ti_ta_value = sanitize_text_field(get_option('xoo-cp-ad-ti-ta','left'));
function xoo_cp_ad_ti_ta_cb(){
	global $xoo_cp_ad_ti_ta_value;
	?>
	<select name="xoo-cp-ad-ti-ta">
		<option value="left" <?php selected('left',$xoo_cp_ad_ti_ta_value); ?>>Left</option>
		<option value="center" <?php selected('center',$xoo_cp_ad_ti_ta_value); ?>>Center</option>
		<option value="right" <?php selected('right',$xoo_cp_ad_ti_ta_value); ?>>Right</option>
	</select>
	<?php
}


/*** General Options ***/

//Your cart Text
$xoo_cp_ad_gl_ct_value = sanitize_text_field(get_option('xoo-cp-ad-gl-ct','Your Cart'));
function xoo_cp_ad_gl_ct_cb(){
	global $xoo_cp_ad_gl_ct_value;
	$html  = '<input type="text" name="xoo-cp-ad-gl-ct" id="xoo-cp-ad-gl-ct" value="'.$xoo_cp_ad_gl_ct_value.'">';
	echo $html;
}

//Cart Text Color
$xoo_cp_ad_gl_ctc_value = sanitize_text_field(get_option('xoo-cp-ad-gl-ctc','#000000'));
function xoo_cp_ad_gl_ctc_cb(){
	global $xoo_cp_ad_gl_ctc_value;
	$html  = '<input type="text" name="xoo-cp-ad-gl-ctc" id="xoo-cp-ad-gl-ctc" value="'.$xoo_cp_ad_gl_ctc_value.'" class="color-field">';
	echo $html;
}

//Cart Text Background
$xoo_cp_ad_gl_ctbg_value = sanitize_text_field(get_option('xoo-cp-ad-gl-ctbg'));
function xoo_cp_ad_gl_ctbg_cb(){
	global $xoo_cp_ad_gl_ctbg_value;
	$html  = '<input type="text" name="xoo-cp-ad-gl-ctbg" id="xoo-cp-ad-gl-ctbg" value="'.$xoo_cp_ad_gl_ctbg_value.'" class="color-field">';
	echo $html;
}

//Cart Text Font Size
$xoo_cp_ad_gl_ctfs_value = sanitize_text_field(get_option('xoo-cp-ad-gl-ctfs',16));
function xoo_cp_ad_gl_ctfs_cb(){
	global $xoo_cp_ad_gl_ctfs_value;
	$html  = '<input type="number" name="xoo-cp-ad-gl-ctfs" id="xoo-cp-ad-gl-ctfs" value="'.$xoo_cp_ad_gl_ctfs_value.'" >';
	$html .= '<label for="xoo-cp-ad-gl-ctfs">Value in px. (Default : 16)</label>';
	echo $html;
}

//Cart Text Border size
$xoo_cp_ad_gl_ctbs_value = sanitize_text_field(get_option('xoo-cp-ad-gl-ctbs',2));
function xoo_cp_ad_gl_ctbs_cb(){
	global $xoo_cp_ad_gl_ctbs_value;
	$html  = '<input type="number" name="xoo-cp-ad-gl-ctbs" id="xoo-cp-ad-gl-ctbs" value="'.$xoo_cp_ad_gl_ctbs_value.'" >';
	$html .= '<label for="xoo-cp-ad-gl-ctbs">Value in px. (Default : 2)</label>';
	echo $html;
}

//Cart Text Border color
$xoo_cp_ad_gl_ctbc_value = sanitize_text_field(get_option('xoo-cp-ad-gl-ctbc','#000000'));
function xoo_cp_ad_gl_ctbc_cb(){
	global $xoo_cp_ad_gl_ctbc_value;
	$html  = '<input type="text" name="xoo-cp-ad-gl-ctbc" id="xoo-cp-ad-gl-ctbc" value="'.$xoo_cp_ad_gl_ctbc_value.'" class="color-field">';
	echo $html;
}


/*** Style Options ***/

//Container background Image
$xoo_cp_ad_sy_cbimg_value = esc_attr(get_option('xoo-cp-ad-sy-cbimg'));
function xoo_cp_ad_sy_cbimg_cb(){
	global $xoo_cp_ad_sy_cbimg_value;
	$html = '<input type="button" id="xmedia-btn" class="button xoo-prbtn" value="Select">';
	$html .= '<input type="hidden" name="xoo-cp-ad-sy-cbimg" id ="xoo-cp-ad-sy-cbimg" value="'.$xoo_cp_ad_sy_cbimg_value.'">';
	$html .= '<button class="xoo-remove-media button">X Remove</button>';
	$html .= '<span class="xoo-media-name"></span>';
	$html .= '<p class="description">Supported format: JPEG,PNG </p>';
	echo $html;	
}

//Container Background Color
$xoo_cp_ad_sy_cbg_value = sanitize_text_field(get_option('xoo-cp-ad-sy-cbg','#ffffff'));
function xoo_cp_ad_sy_cbg_cb(){
	global $xoo_cp_ad_sy_cbg_value;
	$html  = '<input type="text" name="xoo-cp-ad-sy-cbg" id="xoo-cp-ad-sy-cbg" value="'.$xoo_cp_ad_sy_cbg_value.'" class="color-field">';
	echo $html;
}

//Container Text Color
$xoo_cp_ad_sy_ctc_value = sanitize_text_field(get_option('xoo-cp-ad-sy-ctc','#000000'));
function xoo_cp_ad_sy_ctc_cb(){
	global $xoo_cp_ad_sy_ctc_value;
	$html  = '<input type="text" name="xoo-cp-ad-sy-ctc" id="xoo-cp-ad-sy-ctc" value="'.$xoo_cp_ad_sy_ctc_value.'" class="color-field">';
	echo $html;
}

//Container Close Button
$xoo_cp_ad_sy_cc_value = sanitize_text_field(get_option('xoo-cp-ad-sy-cc','#000000'));
function xoo_cp_ad_sy_cc_cb(){
	global $xoo_cp_ad_sy_cc_value;
	$html  = '<input type="text" name="xoo-cp-ad-sy-cc" id="xoo-cp-ad-sy-cc" value="'.$xoo_cp_ad_sy_cc_value.'" class="color-field">';
	echo $html;
}



//Remove Product color
$xoo_cp_ad_sy_rpc_value = sanitize_text_field(get_option('xoo-cp-ad-sy-rpc','#ea0a0a'));
function xoo_cp_ad_sy_rpc_cb(){
	global $xoo_cp_ad_sy_rpc_value;
	$html  = '<input type="text" name="xoo-cp-ad-sy-rpc" id="xoo-cp-ad-sy-rpc" value="'.$xoo_cp_ad_sy_rpc_value.'" class="color-field">';
	echo $html;
}


//Scroll Bar Theme
$xoo_cp_ad_sy_sbt_value = sanitize_text_field(get_option('xoo-cp-ad-sy-sbt','dark'));
function xoo_cp_ad_sy_sbt_cb(){
	global $xoo_cp_ad_sy_sbt_value;
	?>
	<select name="xoo-cp-ad-sy-sbt">
		<option value="dark" <?php selected('dark',$xoo_cp_ad_sy_sbt_value); ?>>Dark</option>
		<option value="light" <?php selected('light',$xoo_cp_ad_sy_sbt_value); ?>>Light</option>
		<option value="rounded" <?php selected('rounded',$xoo_cp_ad_sy_sbt_value); ?>>Rounded light</option>
		<option value="rounded-dark" <?php selected('rounded-dark',$xoo_cp_ad_sy_sbt_value); ?>>Rounded dark</option>
	</select>
	<?php
}
?>