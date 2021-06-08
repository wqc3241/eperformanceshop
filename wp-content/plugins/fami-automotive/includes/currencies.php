<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get Base Currency Code.
 *
 * @return string
 */
function famiau_get_currency() {
	return apply_filters( 'famiau_currency', famiau_get_option( 'famiau_currency', 'USD' ) );
}

/**
 * Get full list of currency codes.
 *
 * @return array
 */
function famiau_get_currencies() {
	static $currencies;
	
	if ( ! isset( $currencies ) ) {
		$currencies = array_unique(
			apply_filters(
				'famiau_currencies',
				array(
					'AED' => __( 'United Arab Emirates dirham', 'famiau' ),
					'AFN' => __( 'Afghan afghani', 'famiau' ),
					'ALL' => __( 'Albanian lek', 'famiau' ),
					'AMD' => __( 'Armenian dram', 'famiau' ),
					'ANG' => __( 'Netherlands Antillean guilder', 'famiau' ),
					'AOA' => __( 'Angolan kwanza', 'famiau' ),
					'ARS' => __( 'Argentine peso', 'famiau' ),
					'AUD' => __( 'Australian dollar', 'famiau' ),
					'AWG' => __( 'Aruban florin', 'famiau' ),
					'AZN' => __( 'Azerbaijani manat', 'famiau' ),
					'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'famiau' ),
					'BBD' => __( 'Barbadian dollar', 'famiau' ),
					'BDT' => __( 'Bangladeshi taka', 'famiau' ),
					'BGN' => __( 'Bulgarian lev', 'famiau' ),
					'BHD' => __( 'Bahraini dinar', 'famiau' ),
					'BIF' => __( 'Burundian franc', 'famiau' ),
					'BMD' => __( 'Bermudian dollar', 'famiau' ),
					'BND' => __( 'Brunei dollar', 'famiau' ),
					'BOB' => __( 'Bolivian boliviano', 'famiau' ),
					'BRL' => __( 'Brazilian real', 'famiau' ),
					'BSD' => __( 'Bahamian dollar', 'famiau' ),
					'BTC' => __( 'Bitcoin', 'famiau' ),
					'BTN' => __( 'Bhutanese ngultrum', 'famiau' ),
					'BWP' => __( 'Botswana pula', 'famiau' ),
					'BYR' => __( 'Belarusian ruble (old)', 'famiau' ),
					'BYN' => __( 'Belarusian ruble', 'famiau' ),
					'BZD' => __( 'Belize dollar', 'famiau' ),
					'CAD' => __( 'Canadian dollar', 'famiau' ),
					'CDF' => __( 'Congolese franc', 'famiau' ),
					'CHF' => __( 'Swiss franc', 'famiau' ),
					'CLP' => __( 'Chilean peso', 'famiau' ),
					'CNY' => __( 'Chinese yuan', 'famiau' ),
					'COP' => __( 'Colombian peso', 'famiau' ),
					'CRC' => __( 'Costa Rican col&oacute;n', 'famiau' ),
					'CUC' => __( 'Cuban convertible peso', 'famiau' ),
					'CUP' => __( 'Cuban peso', 'famiau' ),
					'CVE' => __( 'Cape Verdean escudo', 'famiau' ),
					'CZK' => __( 'Czech koruna', 'famiau' ),
					'DJF' => __( 'Djiboutian franc', 'famiau' ),
					'DKK' => __( 'Danish krone', 'famiau' ),
					'DOP' => __( 'Dominican peso', 'famiau' ),
					'DZD' => __( 'Algerian dinar', 'famiau' ),
					'EGP' => __( 'Egyptian pound', 'famiau' ),
					'ERN' => __( 'Eritrean nakfa', 'famiau' ),
					'ETB' => __( 'Ethiopian birr', 'famiau' ),
					'EUR' => __( 'Euro', 'famiau' ),
					'FJD' => __( 'Fijian dollar', 'famiau' ),
					'FKP' => __( 'Falkland Islands pound', 'famiau' ),
					'GBP' => __( 'Pound sterling', 'famiau' ),
					'GEL' => __( 'Georgian lari', 'famiau' ),
					'GGP' => __( 'Guernsey pound', 'famiau' ),
					'GHS' => __( 'Ghana cedi', 'famiau' ),
					'GIP' => __( 'Gibraltar pound', 'famiau' ),
					'GMD' => __( 'Gambian dalasi', 'famiau' ),
					'GNF' => __( 'Guinean franc', 'famiau' ),
					'GTQ' => __( 'Guatemalan quetzal', 'famiau' ),
					'GYD' => __( 'Guyanese dollar', 'famiau' ),
					'HKD' => __( 'Hong Kong dollar', 'famiau' ),
					'HNL' => __( 'Honduran lempira', 'famiau' ),
					'HRK' => __( 'Croatian kuna', 'famiau' ),
					'HTG' => __( 'Haitian gourde', 'famiau' ),
					'HUF' => __( 'Hungarian forint', 'famiau' ),
					'IDR' => __( 'Indonesian rupiah', 'famiau' ),
					'ILS' => __( 'Israeli new shekel', 'famiau' ),
					'IMP' => __( 'Manx pound', 'famiau' ),
					'INR' => __( 'Indian rupee', 'famiau' ),
					'IQD' => __( 'Iraqi dinar', 'famiau' ),
					'IRR' => __( 'Iranian rial', 'famiau' ),
					'IRT' => __( 'Iranian toman', 'famiau' ),
					'ISK' => __( 'Icelandic kr&oacute;na', 'famiau' ),
					'JEP' => __( 'Jersey pound', 'famiau' ),
					'JMD' => __( 'Jamaican dollar', 'famiau' ),
					'JOD' => __( 'Jordanian dinar', 'famiau' ),
					'JPY' => __( 'Japanese yen', 'famiau' ),
					'KES' => __( 'Kenyan shilling', 'famiau' ),
					'KGS' => __( 'Kyrgyzstani som', 'famiau' ),
					'KHR' => __( 'Cambodian riel', 'famiau' ),
					'KMF' => __( 'Comorian franc', 'famiau' ),
					'KPW' => __( 'North Korean won', 'famiau' ),
					'KRW' => __( 'South Korean won', 'famiau' ),
					'KWD' => __( 'Kuwaiti dinar', 'famiau' ),
					'KYD' => __( 'Cayman Islands dollar', 'famiau' ),
					'KZT' => __( 'Kazakhstani tenge', 'famiau' ),
					'LAK' => __( 'Lao kip', 'famiau' ),
					'LBP' => __( 'Lebanese pound', 'famiau' ),
					'LKR' => __( 'Sri Lankan rupee', 'famiau' ),
					'LRD' => __( 'Liberian dollar', 'famiau' ),
					'LSL' => __( 'Lesotho loti', 'famiau' ),
					'LYD' => __( 'Libyan dinar', 'famiau' ),
					'MAD' => __( 'Moroccan dirham', 'famiau' ),
					'MDL' => __( 'Moldovan leu', 'famiau' ),
					'MGA' => __( 'Malagasy ariary', 'famiau' ),
					'MKD' => __( 'Macedonian denar', 'famiau' ),
					'MMK' => __( 'Burmese kyat', 'famiau' ),
					'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'famiau' ),
					'MOP' => __( 'Macanese pataca', 'famiau' ),
					'MRO' => __( 'Mauritanian ouguiya', 'famiau' ),
					'MUR' => __( 'Mauritian rupee', 'famiau' ),
					'MVR' => __( 'Maldivian rufiyaa', 'famiau' ),
					'MWK' => __( 'Malawian kwacha', 'famiau' ),
					'MXN' => __( 'Mexican peso', 'famiau' ),
					'MYR' => __( 'Malaysian ringgit', 'famiau' ),
					'MZN' => __( 'Mozambican metical', 'famiau' ),
					'NAD' => __( 'Namibian dollar', 'famiau' ),
					'NGN' => __( 'Nigerian naira', 'famiau' ),
					'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'famiau' ),
					'NOK' => __( 'Norwegian krone', 'famiau' ),
					'NPR' => __( 'Nepalese rupee', 'famiau' ),
					'NZD' => __( 'New Zealand dollar', 'famiau' ),
					'OMR' => __( 'Omani rial', 'famiau' ),
					'PAB' => __( 'Panamanian balboa', 'famiau' ),
					'PEN' => __( 'Peruvian nuevo sol', 'famiau' ),
					'PGK' => __( 'Papua New Guinean kina', 'famiau' ),
					'PHP' => __( 'Philippine peso', 'famiau' ),
					'PKR' => __( 'Pakistani rupee', 'famiau' ),
					'PLN' => __( 'Polish z&#x142;oty', 'famiau' ),
					'PRB' => __( 'Transnistrian ruble', 'famiau' ),
					'PYG' => __( 'Paraguayan guaran&iacute;', 'famiau' ),
					'QAR' => __( 'Qatari riyal', 'famiau' ),
					'RON' => __( 'Romanian leu', 'famiau' ),
					'RSD' => __( 'Serbian dinar', 'famiau' ),
					'RUB' => __( 'Russian ruble', 'famiau' ),
					'RWF' => __( 'Rwandan franc', 'famiau' ),
					'SAR' => __( 'Saudi riyal', 'famiau' ),
					'SBD' => __( 'Solomon Islands dollar', 'famiau' ),
					'SCR' => __( 'Seychellois rupee', 'famiau' ),
					'SDG' => __( 'Sudanese pound', 'famiau' ),
					'SEK' => __( 'Swedish krona', 'famiau' ),
					'SGD' => __( 'Singapore dollar', 'famiau' ),
					'SHP' => __( 'Saint Helena pound', 'famiau' ),
					'SLL' => __( 'Sierra Leonean leone', 'famiau' ),
					'SOS' => __( 'Somali shilling', 'famiau' ),
					'SRD' => __( 'Surinamese dollar', 'famiau' ),
					'SSP' => __( 'South Sudanese pound', 'famiau' ),
					'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'famiau' ),
					'SYP' => __( 'Syrian pound', 'famiau' ),
					'SZL' => __( 'Swazi lilangeni', 'famiau' ),
					'THB' => __( 'Thai baht', 'famiau' ),
					'TJS' => __( 'Tajikistani somoni', 'famiau' ),
					'TMT' => __( 'Turkmenistan manat', 'famiau' ),
					'TND' => __( 'Tunisian dinar', 'famiau' ),
					'TOP' => __( 'Tongan pa&#x2bb;anga', 'famiau' ),
					'TRY' => __( 'Turkish lira', 'famiau' ),
					'TTD' => __( 'Trinidad and Tobago dollar', 'famiau' ),
					'TWD' => __( 'New Taiwan dollar', 'famiau' ),
					'TZS' => __( 'Tanzanian shilling', 'famiau' ),
					'UAH' => __( 'Ukrainian hryvnia', 'famiau' ),
					'UGX' => __( 'Ugandan shilling', 'famiau' ),
					'USD' => __( 'United States (US) dollar', 'famiau' ),
					'UYU' => __( 'Uruguayan peso', 'famiau' ),
					'UZS' => __( 'Uzbekistani som', 'famiau' ),
					'VEF' => __( 'Venezuelan bol&iacute;var', 'famiau' ),
					'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'famiau' ),
					'VUV' => __( 'Vanuatu vatu', 'famiau' ),
					'WST' => __( 'Samoan t&#x101;l&#x101;', 'famiau' ),
					'XAF' => __( 'Central African CFA franc', 'famiau' ),
					'XCD' => __( 'East Caribbean dollar', 'famiau' ),
					'XOF' => __( 'West African CFA franc', 'famiau' ),
					'XPF' => __( 'CFP franc', 'famiau' ),
					'YER' => __( 'Yemeni rial', 'famiau' ),
					'ZAR' => __( 'South African rand', 'famiau' ),
					'ZMW' => __( 'Zambian kwacha', 'famiau' ),
				)
			)
		);
	}
	
	return $currencies;
}


/**
 * Get Currency symbol.
 *
 * @param string $currency Currency. (default: '').
 *
 * @return string
 */
function famiau_get_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		$currency = famiau_get_currency();
	}
	
	$symbols         = apply_filters(
		'famiau_currency_symbols', array(
			                         'AED' => '&#x62f;.&#x625;',
			                         'AFN' => '&#x60b;',
			                         'ALL' => 'L',
			                         'AMD' => 'AMD',
			                         'ANG' => '&fnof;',
			                         'AOA' => 'Kz',
			                         'ARS' => '&#36;',
			                         'AUD' => '&#36;',
			                         'AWG' => 'Afl.',
			                         'AZN' => 'AZN',
			                         'BAM' => 'KM',
			                         'BBD' => '&#36;',
			                         'BDT' => '&#2547;&nbsp;',
			                         'BGN' => '&#1083;&#1074;.',
			                         'BHD' => '.&#x62f;.&#x628;',
			                         'BIF' => 'Fr',
			                         'BMD' => '&#36;',
			                         'BND' => '&#36;',
			                         'BOB' => 'Bs.',
			                         'BRL' => '&#82;&#36;',
			                         'BSD' => '&#36;',
			                         'BTC' => '&#3647;',
			                         'BTN' => 'Nu.',
			                         'BWP' => 'P',
			                         'BYR' => 'Br',
			                         'BYN' => 'Br',
			                         'BZD' => '&#36;',
			                         'CAD' => '&#36;',
			                         'CDF' => 'Fr',
			                         'CHF' => '&#67;&#72;&#70;',
			                         'CLP' => '&#36;',
			                         'CNY' => '&yen;',
			                         'COP' => '&#36;',
			                         'CRC' => '&#x20a1;',
			                         'CUC' => '&#36;',
			                         'CUP' => '&#36;',
			                         'CVE' => '&#36;',
			                         'CZK' => '&#75;&#269;',
			                         'DJF' => 'Fr',
			                         'DKK' => 'DKK',
			                         'DOP' => 'RD&#36;',
			                         'DZD' => '&#x62f;.&#x62c;',
			                         'EGP' => 'EGP',
			                         'ERN' => 'Nfk',
			                         'ETB' => 'Br',
			                         'EUR' => '&euro;',
			                         'FJD' => '&#36;',
			                         'FKP' => '&pound;',
			                         'GBP' => '&pound;',
			                         'GEL' => '&#x20be;',
			                         'GGP' => '&pound;',
			                         'GHS' => '&#x20b5;',
			                         'GIP' => '&pound;',
			                         'GMD' => 'D',
			                         'GNF' => 'Fr',
			                         'GTQ' => 'Q',
			                         'GYD' => '&#36;',
			                         'HKD' => '&#36;',
			                         'HNL' => 'L',
			                         'HRK' => 'Kn',
			                         'HTG' => 'G',
			                         'HUF' => '&#70;&#116;',
			                         'IDR' => 'Rp',
			                         'ILS' => '&#8362;',
			                         'IMP' => '&pound;',
			                         'INR' => '&#8377;',
			                         'IQD' => '&#x639;.&#x62f;',
			                         'IRR' => '&#xfdfc;',
			                         'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			                         'ISK' => 'kr.',
			                         'JEP' => '&pound;',
			                         'JMD' => '&#36;',
			                         'JOD' => '&#x62f;.&#x627;',
			                         'JPY' => '&yen;',
			                         'KES' => 'KSh',
			                         'KGS' => '&#x441;&#x43e;&#x43c;',
			                         'KHR' => '&#x17db;',
			                         'KMF' => 'Fr',
			                         'KPW' => '&#x20a9;',
			                         'KRW' => '&#8361;',
			                         'KWD' => '&#x62f;.&#x643;',
			                         'KYD' => '&#36;',
			                         'KZT' => 'KZT',
			                         'LAK' => '&#8365;',
			                         'LBP' => '&#x644;.&#x644;',
			                         'LKR' => '&#xdbb;&#xdd4;',
			                         'LRD' => '&#36;',
			                         'LSL' => 'L',
			                         'LYD' => '&#x644;.&#x62f;',
			                         'MAD' => '&#x62f;.&#x645;.',
			                         'MDL' => 'MDL',
			                         'MGA' => 'Ar',
			                         'MKD' => '&#x434;&#x435;&#x43d;',
			                         'MMK' => 'Ks',
			                         'MNT' => '&#x20ae;',
			                         'MOP' => 'P',
			                         'MRO' => 'UM',
			                         'MUR' => '&#x20a8;',
			                         'MVR' => '.&#x783;',
			                         'MWK' => 'MK',
			                         'MXN' => '&#36;',
			                         'MYR' => '&#82;&#77;',
			                         'MZN' => 'MT',
			                         'NAD' => '&#36;',
			                         'NGN' => '&#8358;',
			                         'NIO' => 'C&#36;',
			                         'NOK' => '&#107;&#114;',
			                         'NPR' => '&#8360;',
			                         'NZD' => '&#36;',
			                         'OMR' => '&#x631;.&#x639;.',
			                         'PAB' => 'B/.',
			                         'PEN' => 'S/.',
			                         'PGK' => 'K',
			                         'PHP' => '&#8369;',
			                         'PKR' => '&#8360;',
			                         'PLN' => '&#122;&#322;',
			                         'PRB' => '&#x440;.',
			                         'PYG' => '&#8370;',
			                         'QAR' => '&#x631;.&#x642;',
			                         'RMB' => '&yen;',
			                         'RON' => 'lei',
			                         'RSD' => '&#x434;&#x438;&#x43d;.',
			                         'RUB' => '&#8381;',
			                         'RWF' => 'Fr',
			                         'SAR' => '&#x631;.&#x633;',
			                         'SBD' => '&#36;',
			                         'SCR' => '&#x20a8;',
			                         'SDG' => '&#x62c;.&#x633;.',
			                         'SEK' => '&#107;&#114;',
			                         'SGD' => '&#36;',
			                         'SHP' => '&pound;',
			                         'SLL' => 'Le',
			                         'SOS' => 'Sh',
			                         'SRD' => '&#36;',
			                         'SSP' => '&pound;',
			                         'STD' => 'Db',
			                         'SYP' => '&#x644;.&#x633;',
			                         'SZL' => 'L',
			                         'THB' => '&#3647;',
			                         'TJS' => '&#x405;&#x41c;',
			                         'TMT' => 'm',
			                         'TND' => '&#x62f;.&#x62a;',
			                         'TOP' => 'T&#36;',
			                         'TRY' => '&#8378;',
			                         'TTD' => '&#36;',
			                         'TWD' => '&#78;&#84;&#36;',
			                         'TZS' => 'Sh',
			                         'UAH' => '&#8372;',
			                         'UGX' => 'UGX',
			                         'USD' => '&#36;',
			                         'UYU' => '&#36;',
			                         'UZS' => 'UZS',
			                         'VEF' => 'Bs F',
			                         'VND' => '&#8363;',
			                         'VUV' => 'Vt',
			                         'WST' => 'T',
			                         'XAF' => 'CFA',
			                         'XCD' => '&#36;',
			                         'XOF' => 'CFA',
			                         'XPF' => 'Fr',
			                         'YER' => '&#xfdfc;',
			                         'ZAR' => '&#82;',
			                         'ZMW' => 'ZK',
		                         )
	);
	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';
	
	return apply_filters( 'famiau_currency_symbol', $currency_symbol, $currency );
}

function famiau_get_price_format_without_html( $price = 0 ) {
	$decimal_separator  = famiau_get_price_decimal_separator();
	$thousand_separator = famiau_get_price_thousand_separator();
	$decimals           = famiau_get_price_decimals();
	$price_format       = famiau_price_format();
	$unformatted_price  = $price;
	$negative           = $price < 0;
	$price              = apply_filters( 'raw_famiau_price', floatval( $negative ? $price * - 1 : $price ) );
	$price              = apply_filters( 'formatted_famiau_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );
	if ( $decimals <= 0 ) {
		$price = famiau_trim_zeros( $price );
	}
	
	$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, famiau_get_currency_symbol(), $price );
	$formatted_price = apply_filters( 'famiau_get_price_format_without_html', $formatted_price, $price, $unformatted_price );
	
	return $formatted_price;
}

function famiau_get_price_html( $price = 0 ) {
	$decimal_separator  = famiau_get_price_decimal_separator();
	$thousand_separator = famiau_get_price_thousand_separator();
	$decimals           = famiau_get_price_decimals();
	$price_format       = famiau_price_format();
	$unformatted_price  = $price;
	$negative           = $price < 0;
	$price              = apply_filters( 'raw_famiau_price', floatval( $negative ? $price * - 1 : $price ) );
	$price              = apply_filters( 'formatted_famiau_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );
	
	if ( $decimals <= 0 ) {
		$price = famiau_trim_zeros( $price );
	}
	
	$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="famiau-currency-symbol">' . famiau_get_currency_symbol() . '</span>', $price );
	$price_html      = '<span class="famiau-price-amount amount">' . $formatted_price . '</span>';
	$price_html      = apply_filters( 'famiau_price_html', $price_html, $price, $unformatted_price );
	
	return $price_html;
}

/**
 * @param string $selected
 * @param string $class
 * @param string $name
 * @param string $id
 * @param bool   $echo
 *
 * @return string
 */
function famiau_currency_pos_select_html( $selected = 'left', $class = '', $name = '', $id = '', $echo = true ) {
	$currency_pos_args = array(
		'left'        => esc_html__( 'Left', 'famiau' ),
		'right'       => esc_html__( 'Right', 'famiau' ),
		'left_space'  => esc_html__( 'Left with space', 'famiau' ),
		'right_space' => esc_html__( 'Right with space', 'famiau' ),
	);
	
	$class .= ' famiau-currency-pos-select';
	
	$html = famiau_select_html( $currency_pos_args, $selected, $class, $name, $id, false );
	
	if ( $echo ) {
		echo $html;
	}
	
	return $html;
}

/**
 * Trim trailing zeros off prices.
 *
 * @param string|float|int $price Price.
 *
 * @return string
 */
function famiau_trim_zeros( $price ) {
	return preg_replace( '/' . preg_quote( wc_get_price_decimal_separator(), '/' ) . '0++$/', '', $price );
}

/**
 * Get the price format depending on the currency position.
 *
 * @return string
 */
function famiau_price_format() {
	$currency_pos = famiau_get_option( '_famiau_currency_pos', 'left' );
	$format       = '%1$s%2$s';
	
	switch ( $currency_pos ) {
		case 'left':
			$format = '%1$s%2$s';
			break;
		case 'right':
			$format = '%2$s%1$s';
			break;
		case 'left_space':
			$format = '%1$s&nbsp;%2$s';
			break;
		case 'right_space':
			$format = '%2$s&nbsp;%1$s';
			break;
	}
	
	return apply_filters( 'famiau_price_format', $format, $currency_pos );
}

/**
 * Return the thousand separator for prices.
 *
 * @return string
 */
function famiau_get_price_thousand_separator() {
	return stripslashes( apply_filters( 'famiau_get_price_thousand_separator', famiau_get_option( '_famiau_thousand_separator', ',' ) ) );
}

/**
 * Return the decimal separator for prices.
 *
 * @return string
 */
function famiau_get_price_decimal_separator() {
	$separator = apply_filters( 'famiau_get_price_decimal_separator', famiau_get_option( '_famiau_decimal_separator', '.' ) );
	
	return $separator ? stripslashes( $separator ) : '.';
}

/**
 * Return the number of decimals after the decimal point.
 *
 * @return int
 */
function famiau_get_price_decimals() {
	return absint( apply_filters( 'famiau_get_price_decimals', famiau_get_option( '_famiau_num_of_decimals', 2 ) ) );
}