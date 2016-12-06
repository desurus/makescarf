<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'makescarf');

/** MySQL hostname */
define('DB_HOST', 'mysql');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'L0u@{Z(LeKxt_?#nWs%}6Zea[r~n Zy:m<.|FJ~8ehC:0->ja@TLJ)5->ZpY;N+L');
define('SECURE_AUTH_KEY',  'mY 4h+*rYfJx]KS@`+Wn_kgiuE|6Xrf-vu#X{4=?6y.dP?4> |J:xD&;swu=znz^');
define('LOGGED_IN_KEY',    '}lq;m|_#MxDM.o{2lb:,| TIhY0kRglle9bX(n 2]P-H( 4^+JkX%sf7Je?]^W^&');
define('NONCE_KEY',        '!7=d)Z|Na-Y+qpfYh7&UlA-.>Tvm{+NAXo/{YF_==w(t#>=3dOYJ|cUF+I|31s5]');
define('AUTH_SALT',        ';8|mqm.c(M8#z+SqE3inyK8(>S&7IP81A#tz#<eI$ShGm ^>IM&RuFNvJ_*vl|XM');
define('SECURE_AUTH_SALT', 'ExGMLFQm}NUXY.7hM`LE.41s-a];1C~G/xL3t5[^(gn7zQ}DEwk.B/s`cy$ZP5nL');
define('LOGGED_IN_SALT',   'mMR;jDi rwdw7?&tK;gLkt{KjZX+ro|$=j:ONIc>xuOTK[rA+A:{SnRHQ4BO_@f^');
define('NONCE_SALT',       '&cx}Z+KP7bk*NVZ|jSV7[@72m!HIlxWY?_+k?R+V1hCyQ9Kd+TdZ?SomE]Fi4)%`');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'notwp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

// Enable Debug logging to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

// Disable display of errors and warnings 
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
