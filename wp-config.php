<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'muj_eshop' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '#2JGLPYR2Qq' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '9^u@_*Z+lhni9S6a@z cghpQ8BUT39?5b@Q>8|+|2=n{jex((0n<):NLW1i8iG|=' );
define( 'SECURE_AUTH_KEY',  '>;m=+FBJo6c~ILU=|Nbi5-xNzhitB0^Llt@zwuk6~Mo/89?9]&Or@MH2(H*Y8RXM' );
define( 'LOGGED_IN_KEY',    'pT90FCegRu&:n?-w3>6^^FU8.WL:Px0#xt}b=ydGMvmxZwK7HttP9i%]]RvvX+N?' );
define( 'NONCE_KEY',        '|HKhbqC25)4:BDG3vW#jrfqY;Vn4X2R#?k:6vGAK}7}$oH9BWDX|{>_g_}cJ[S0j' );
define( 'AUTH_SALT',        '3ck]ctnfl<-w7VZr{DXqg6{k?&jPK#X0K VBo&nT8xQb~Hq:RDKyXYFmU!Ol2ytJ' );
define( 'SECURE_AUTH_SALT', '<ZY$u$GPcDSh]}Dm0M*JW@]AxRNxXe.K#F/<x}HtD{Cq&2O/Y<fsAd;N_1(iD{-l' );
define( 'LOGGED_IN_SALT',   '6IjP O1nJiCoKM3Hds /rz<~1]9a{/tc[P(dvTmRyf|`0s=3~8J1JQe664QdI2u.' );
define( 'NONCE_SALT',       'i @,?oD_)n~.=u(2KBv-G&r!}J2y4l}i>-#%E43~Xw:h8a(KRX~)Ds0$* @5b<3v' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
