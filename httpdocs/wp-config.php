<?php
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_evn0b' );

/** MySQL database username */
define( 'DB_USER', 'wp_d4tb5' );

/** MySQL database password */
define( 'DB_PASSWORD', '39$h5mCeSH' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '*:%3D*!z88IAI-K8/2-b9j4@oA8n4K[3T4MRZAqMY6x:fdR;6rKT1LC9y0_;UK4H');
define('SECURE_AUTH_KEY', '+&p04#0/S|E3udkP9M&)FFwmuC]Rz1GSv1IlpvZ83z6f#Sn&vLy24Yqd+cZMJ~E0');
define('LOGGED_IN_KEY', 'Aq92(6~H+JbM:HOzADIS2)#51~B[EJO1qrI(3_975u|z01UN#81cc6QrxU3%SwgI');
define('NONCE_KEY', 'J33vjSg:!L[)FKVuWT_WF7gV!N#029ureN:VBjISghG:C4a32B488xm[a~5d&J4x');
define('AUTH_SALT', '21PLF~WNB8qd5hCs%E8#0vk0g79d+C[[NbY5#m|83X@fWyq67VUL3|0lE98g4+iW');
define('SECURE_AUTH_SALT', '6n*v+P:U+(g5UYkrZpbEuIa7t6sT!GP/Gj0Gja:hJ]N84k7+G*j];069r;w)q;/d');
define('LOGGED_IN_SALT', 'O*7gmV-@i7hLqz;@6tnq4m|Yk~-)%ON3ZWox)eIsGx4253oR#]i4+d&:82i4G;mK');
define('NONCE_SALT', '[Z*09912&Ix(2wCdU_d5#A12l)!6@5h!j9H!:EUGWB|H8b!GZt1B%4H9[q7x-_!t');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '9Oej1_';


define('WP_ALLOW_MULTISITE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
