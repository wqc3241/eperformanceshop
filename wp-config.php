<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'e-performance-shop' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'F@lv1jid2.z76.or9z' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '~qfuYOdA)R_ :HFjm t;dlF.22gOc3l!U<CL-|PqPX)(7dd;^1`nU(kLJ(;{%b}L' );
define( 'SECURE_AUTH_KEY',  '.E9GlV0Apj%=>p1UHs0r]2Nb^*t)BC>b^f[mFRA_%R?Io4zr*j.S>`BSzz&)vU r' );
define( 'LOGGED_IN_KEY',    'S#ikVM1uP>ip)s$Z)Gw&aZR0VQM /H#U<1%&}&>^s<vk/ ,nVpc|Mw^z5)Wx `U^' );
define( 'NONCE_KEY',        '1HYHwMbjl%?LB^NGj:EtuJQ:XAY`*4NwRTQEDGQ_WvWI[B##&aukP7xuJ%TDnnc*' );
define( 'AUTH_SALT',        'yjuEhO*&{?@.3 RiUnf):Zq%K+L}:1m0<I4rIlMFl.RuULnWOgE&c($7@!gaWR!?' );
define( 'SECURE_AUTH_SALT', 'x~7Cnw %]or+w4<nT)-O%~%)UykA4W1Q7O?V$i/{9[kwtIF)$amcL.IF gaWj_3=' );
define( 'LOGGED_IN_SALT',   'L,a?e0Yh%M|6]M A,b,dQW_??Ew[gNu|LR:foP9q ofPH!7MuEG(g*K4I3X%sBg=' );
define( 'NONCE_SALT',       'rpP&Y:%;SR,Q`=D&vW 8G#u?ni=7Cv6QH@>mQTkxayk,HE:+8Hpiono{/qZ_LWqU' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
