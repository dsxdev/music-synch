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
define( 'DB_NAME', 'music-synch' );

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
define( 'AUTH_KEY',         '1O S8[_{D{6r{2v_);Zr^3i@b]D2qT>rI-J!U.;da]v`=*.EQO8sZALju#atxJta' );
define( 'SECURE_AUTH_KEY',  ',OC)s?SBOgz;76z~ka=fXiYK0Z.-~Z, AN?VG&J5JwXa13q%a`]-nC}Nns@X5aID' );
define( 'LOGGED_IN_KEY',    ';%OD?ZvgmeFmg[Y3~ yf)F>g=_M@beY%ap7v&h0!SIHv/5QyX/!L^f0+:Cdx-SPO' );
define( 'NONCE_KEY',        '5YcpY<AJOA:_OFe7clb)K^bxrn-FYaxwIjTeYTxggNhFK^M,B3I&ol;4.n*]gSI-' );
define( 'AUTH_SALT',        'l2}f=P8B;YU:$!dKz!N`r$kx-dF1_r-43z<R uwyL4B.w-nD&mlaS~.c]UXaJ/_W' );
define( 'SECURE_AUTH_SALT', '_5t(vIb8#6Bu0F36X1UnF-+gFfDe#>;MXs^rid/!8)Q4dBMb;~u/{.*3BA2~H`so' );
define( 'LOGGED_IN_SALT',   'MGbknvp_S`Y1Myxilzk3!BW{}ZLp#(>J8>FL3sO~R3-p-Fj(f1PQL$S!=30XeO[F' );
define( 'NONCE_SALT',       'F.s1=TBSBrOu@NgT8`|;}kVDr!|_Ej:FKQDU2Q@]!ya=V8rt3azl#nfi!B$RQEi@' );

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
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
