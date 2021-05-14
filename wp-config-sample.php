<?php
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
define( 'DB_NAME', 'laclave_bodegas_personales' );

/** MySQL database username */
define( 'DB_USER', 'laclave_roberto' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Evalcal65rob' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'Mh-J#cssJmO+z)9vMDd/s!Nz%GsFb|4!Uz}5Ypu/HnSU_#0/P(TCDLRR.<zX~l5o');
define('SECURE_AUTH_KEY',  'GSFD7@L4g[+?ad7l:G=30m>8x?],!j|kv8K)LXoH*G+yZ}`bx :0184!K>7V`/q=');
define('LOGGED_IN_KEY',    '+1OqDJD*.Wf+w-O;D~IXbB]T.Y4`w_mS&4vkbU-eC2+7w+x~~SAJlw W`#*/*=Dv');
define('NONCE_KEY',        '($p):kW:-3U!^2BQ>EV}f1$ScA*$.EePDp(G2p?z=$Ge/ W?r[}oGi4{r|CA>~Sz');
define('AUTH_SALT',        '#{PH-mxJt@>~`3<v;5Sa8__yk0x+Vn7dq_@FffCVMfc;}_7yQyyds4AM(P4_YF0/');
define('SECURE_AUTH_SALT', 'j+m>cI6JtgRSAc_l/GK]}L#Vk6|Ngnm:0|-89e&g{#s ]#+l=XWI-,A(4XJs?a@C');
define('LOGGED_IN_SALT',   '2CJ~F<}6I%NDa1*v8-;uu3R5J7*y@46$=oa5H74?_uKs,$N4QV>F]|oVDzMQyX+B');
define('NONCE_SALT',       'b*W0Uvt~;-8=Du07r:Iwvop,Y$sn0zy29~S3*D-D[:Wc+97.hj0{N9S::h0H:|H`');
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
