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

define( 'DB_HOST', '178.33.162.91' );



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

define( 'AUTH_KEY',         'YgGAn>h0];%%Zsx%`1vLal~Wb%`NY4hob.Q$Pww_DIc2*P.Xd%9va-]kemD y P8' );

define( 'SECURE_AUTH_KEY',  'r?r@X^4/c1ObJ1#CnGGttC{|+^/BTASA#.CYm5yO|mJ|U-%k:#=KB.7ZdQw})b!-' );

define( 'LOGGED_IN_KEY',    '[Y{+3tj8nR,kxE@>:k6S)2[nIzG@#QTnubNh3a:v2=8@{(*~PTXPT.I61[[W WH7' );

define( 'NONCE_KEY',        'FYilq8mQm0hp+h7Y;GWP|K{x^}n7wy4jw_2>)D]i_#sMcM~!`THa0}!`#79XjS3S' );

define( 'AUTH_SALT',        '9b,&w+.ipC9/:%3@f~fYViItT,k^0(X*Dd]e_F6?L?%{em!Lhx%6hMy ubjThs};' );

define( 'SECURE_AUTH_SALT', 'c_w$0zeBwOdy@Rg(qfWdHL[wv<,EOi>G3)MxJ *5^XCkn[PIQ%DGkfi_4Eg+Hhsk' );

define( 'LOGGED_IN_SALT',   '3SY-|C=vxz<-84R/. 0WQ8Q?o~MHb$$x5>T/V-L--V<l^qvLob^<]cCutFBix$uM' );

define( 'NONCE_SALT',       'izR?8h(gYOGEX)Y@SaIL&;o(3#:T3uK~vfB[RPCjU&}_7wLTb`P,U;3htF1`7+;s' );

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



// define('WP_ALLOW_REPAIR', true);