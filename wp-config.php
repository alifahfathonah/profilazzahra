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
define('FS_METHOD','direct');
define( 'DB_NAME', 'db_profil_azzahra' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         ';rZlS)WC@rE9@yBO;md0Z:Oh2 X=cT^Y|84tuquYeHA=g~PH3O=e/ri1a9=;Pg|3' );
define( 'SECURE_AUTH_KEY',  'm8s~q+k_3<s;mg5jaxVEk$rO`Cenu970w$DOM}f<i(GS=>SkLCqPT144CG >Uk=Q' );
define( 'LOGGED_IN_KEY',    'K7/Q#LBB}k5i(#{C7@g,C81qnbk!ih;nq]p@%x0Y-4a)}w1Dhum!`1`5Ug~X5az&' );
define( 'NONCE_KEY',        '7dcpn)HqccNyn8~1:rz}s87GI~s*sN.AQe38VlawiFg @wE@bISGZy|kw}Pae,JM' );
define( 'AUTH_SALT',        'Q%Qaz+0s>C}aST#s~Mb4BV/H%MFSI3Cjr8E#Fy>H8X.JWWN5czd y/F5cAlGYzt8' );
define( 'SECURE_AUTH_SALT', 'iHv<#G)U6/D[bwfCr/q/en_a0KHn &nAh(A:~PxDf.zXv6xt.d_d0kxO{*4a`vJ(' );
define( 'LOGGED_IN_SALT',   ';PWn8sgEN?CuB}_J#{Eueq4NakL8@yH`4kI%y+cI5~nL!yLGG^5E[Evh(A!s,i5q' );
define( 'NONCE_SALT',       'R*Ehw.RUk-L{h1v?a]iJ4Dz7ey u?Q+GLa]aoX92!l. &=s+q260&wLM{nY?7PEF' );

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
