<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
	return; 	
}

class Xoo_CP_Public{

	protected static $instance = null;

	public function __construct(){
		add_action('wp_enqueue_scripts',array($this,'enqueue_scripts'));
		add_action('plugins_loaded',array($this,'load_txt_domain'),99);
		add_action('wp_footer',array($this,'get_popup_markup'));
	}

	//Get class instance
	public static function get_instance(){
		if(self::$instance === null){
			self::$instance = new self();
		}	
		return self::$instance; 
	}


	//Inline styles from cart popup settings
	public static function get_inline_styles(){
		global $xoo_cp_sy_pw_value,$xoo_cp_sy_imgw_value,$xoo_cp_sy_btnbg_value,$xoo_cp_sy_btnc_value,$xoo_cp_sy_btns_value,$xoo_cp_sy_btnbr_value,$xoo_cp_sy_tbc_value,$xoo_cp_sy_tbs_value,$xoo_cp_gl_ibtne_value,$xoo_cp_gl_vcbtne_value,$xoo_cp_gl_chbtne_value,$xoo_cp_ad_rl_no_value,$xoo_cp_ad_rl_pm_value,$xoo_cp_ad_rl_pts_value,$xoo_cp_ad_bk_bs_value,$xoo_cp_ad_bk_bc_value,$xoo_cp_ad_bk_bbgc_value,$xoo_cp_ad_bk_icc_value,$xoo_cp_ad_bk_icbg_value,$xoo_cp_ad_ti_hbg_value,$xoo_cp_ad_ti_hc_value,$xoo_cp_ad_ti_tw_value,$xoo_cp_ad_ti_ta_value,$xoo_cp_ad_sy_cbg_value,$xoo_cp_ad_sy_cc_value,$xoo_cp_ad_sy_ctc_value,$xoo_cp_ad_sy_cbimg_value,$xoo_cp_ad_gl_ctc_value,$xoo_cp_ad_gl_ctbg_value,$xoo_cp_ad_gl_ctfs_value,$xoo_cp_ad_gl_ctbs_value,$xoo_cp_ad_gl_ctbc_value,$xoo_cp_ad_sy_rpc_value,$xoo_cp_gl_qtyen_value,$xoo_cp_gl_spinen_value;

	$style = '';
	if(!$xoo_cp_gl_ibtne_value){
		$style .= 'span.xcp-chng{
			display: none;
		}';
	}

	if(!$xoo_cp_gl_vcbtne_value){
		$style .= 'a.xoo-cp-btn-vc{
			display: none;
		}';
	}

	if(!$xoo_cp_gl_chbtne_value){
		$style .= 'a.xoo-cp-btn-ch{
			display: none;
		}';
	}

	if($xoo_cp_gl_qtyen_value && $xoo_cp_gl_ibtne_value){
		$style .= 'td.xoo-cp-pqty{
		    min-width: 120px;
		}';
	}

	$sp_width = (100/$xoo_cp_ad_rl_no_value) - ($xoo_cp_ad_rl_pm_value);
	$sp_margin = $xoo_cp_ad_rl_pm_value/2;

	if($xoo_cp_ad_ti_ta_value == 'left'){
		$style .= '.xoo-cp-variations{
			float: left;
		}';
	}
	elseif($xoo_cp_ad_ti_ta_value == 'center'){
		$style .= '.xoo-cp-variations{
			margin: 0 auto;
		}';

	}
	elseif($xoo_cp_ad_ti_ta_value == 'right'){
		$style .= '.xoo-cp-variations{
			float: right;
		}';
	}


	if(!$xoo_cp_gl_spinen_value){
		$style .= '.xoo-cp-adding,.xoo-cp-added{display:none!important}';
	}


	$style .= "
		table.xoo-cp-cart tr.xoo-cp-ths{
			background-color: $xoo_cp_ad_ti_hbg_value;
		}
		tr.xoo-cp-ths th{
			color: $xoo_cp_ad_ti_hc_value;
		}
		.xoo-cp-container{
			max-width: {$xoo_cp_sy_pw_value}px;
			background-color: {$xoo_cp_ad_sy_cbg_value};
			background-image: url({$xoo_cp_ad_sy_cbimg_value});
		}
		.xoo-cp-container , li.xoo-cp-rel-sing h3 , li.xoo-cp-rel-sing .product_price , input.xoo-cp-qty , li.xoo-cp-rel-sing .amount , .xoo-cp-empct , .xoo-cp-ptitle a{
			color: {$xoo_cp_ad_sy_ctc_value}
		}
		.xcp-chng ,.xoo-cp-qtybox{
    		border-color: {$xoo_cp_ad_sy_ctc_value};
		}
		input.xoo-cp-qty{
			background-color: {$xoo_cp_ad_sy_cbg_value};
		}
		.xcp-btn{
			background-color: {$xoo_cp_sy_btnbg_value};
			color: {$xoo_cp_sy_btnc_value};
			font-size: {$xoo_cp_sy_btns_value}px;
			border-radius: {$xoo_cp_sy_btnbr_value}px;
			border: 1px solid {$xoo_cp_sy_btnbg_value};
		}
		.xcp-btn:hover{
			color: {$xoo_cp_sy_btnc_value};
		}
		td.xoo-cp-pimg{
			width: {$xoo_cp_sy_imgw_value}%;
		}
		table.xoo-cp-cart , table.xoo-cp-cart td{
			border: 0;
		}
		table.xoo-cp-cart tr{
			border-top: {$xoo_cp_sy_tbs_value}px solid;
			border-bottom: {$xoo_cp_sy_tbs_value}px solid;
			border-color: {$xoo_cp_sy_tbc_value};
		}
		.xoo-cp-rel-sing{
		    width: $sp_width%;
		    display: inline-block;
		    margin: 0 $sp_margin%;
		    float: left;
		    text-align: center;
		}
		.xoo-cp-rel-title , .xoo-cp-rel-price .amount , .xoo-cp-rel-sing a.add_to_cart_button{
			font-size: 13px;
		}

		.xoo-cp-basket{
			background-color: {$xoo_cp_ad_bk_bbgc_value};
		}
		.xcp-bk-icon{
   			font-size: {$xoo_cp_ad_bk_bs_value}px;
   			color: {$xoo_cp_ad_bk_bc_value};
		}
		.xcp-bk-count{
			color: {$xoo_cp_ad_bk_icc_value};
			background-color: {$xoo_cp_ad_bk_icbg_value};
		}

		span.xoo-cp-close{
			color: {$xoo_cp_ad_sy_cc_value};
		}

		.xoo-cp-hdtxt , span.xcp-rel-head{
			background-color: {$xoo_cp_ad_gl_ctbg_value};
			color: {$xoo_cp_ad_gl_ctc_value};
			font-size: {$xoo_cp_ad_gl_ctfs_value}px;
		}
		
		.xoo-cp-hdtxt{
			border-bottom: {$xoo_cp_ad_gl_ctbs_value}px solid {$xoo_cp_ad_gl_ctbc_value};
		}

		span.xcp-rel-head{
			border-bottom: {$xoo_cp_ad_gl_ctbs_value}px solid {$xoo_cp_ad_gl_ctbc_value};
			border-top: {$xoo_cp_ad_gl_ctbs_value}px solid {$xoo_cp_ad_gl_ctbc_value};
		}

		td.xoo-cp-remove .xoo-cp-remove-pd{
			color: $xoo_cp_ad_sy_rpc_value;
		}

		table.xoo-cp-cart td.xoo-cp-ptitle{
			width: $xoo_cp_ad_ti_tw_value%;
			text-align: $xoo_cp_ad_ti_ta_value;
		}";

		return $style;
	}


	//enqueue stylesheets & scripts
	public function enqueue_scripts(){
		global $xoo_cp_gl_resetbtn_value,$xoo_cp_ad_sy_sbt_value,$xoo_cp_ad_bk_bdr_value;

		wp_enqueue_style('xoo-cp-style',XOO_CP_URL.'/assets/css/xoo-cp-style.css',null,XOO_CP_VERSION);
		wp_enqueue_style('xoo-scrollbar-style',XOO_CP_URL.'/lib/scrollbar/jquery.mCustomScrollbar.min.css');
		
		wp_enqueue_script('xoo-cp-js',XOO_CP_URL.'/assets/js/xoo-cp-js.min.js',array('jquery'),XOO_CP_VERSION,true);
		wp_enqueue_script('xoo-scrollbar-js',XOO_CP_URL.'/lib/scrollbar/jquery.mCustomScrollbar.concat.min.js');

		wp_localize_script('xoo-cp-js','xoo_cp_localize',array(
			'adminurl'     		=> admin_url().'admin-ajax.php',
			'homeurl' 			=> get_bloginfo('url'),
			'wc_ajax_url' 		=> WC_AJAX::get_endpoint( "%%endpoint%%" ),
			'reset_cart'		=> $xoo_cp_gl_resetbtn_value,
			'sbtheme'			=> $xoo_cp_ad_sy_sbt_value,
			'drag_basket' 		=> $xoo_cp_ad_bk_bdr_value
		));

		wp_add_inline_style('xoo-cp-style',self::get_inline_styles());

	}


	//Load text domain
	public function load_txt_domain(){
		$domain = 'added-to-cart-popup-woocommerce';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR . '/'.$domain.'-' . $locale . '.mo' ); //wp-content languages
		load_plugin_textdomain( $domain, FALSE, basename(XOO_CP_PATH) . '/languages/' ); // Plugin Languages
	}


	//Get popup markup
	public function get_popup_markup(){
		if(is_cart() || is_checkout()){return;}
		wc_get_template('xoo-cp-popup-template.php','','',XOO_CP_PATH.'/templates/');
	}

}

?>