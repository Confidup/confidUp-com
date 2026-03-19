<?php
/**
 * WordPress Configuration
 *
 * Sensitive values are loaded from environment variables.
 * Copy .env.example to .env and fill in your values.
 * Never commit .env to version control.
 */

// Load .env file if it exists and phpdotenv is available
if ( file_exists( __DIR__ . '/.env' ) && class_exists( 'Dotenv\Dotenv' ) ) {
	$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
	$dotenv->load();
}

// Helper: read from $_ENV, $_SERVER, or fallback
function confidup_env( string $key, $default = null ) {
	return $_ENV[ $key ] ?? $_SERVER[ $key ] ?? getenv( $key ) ?: $default;
}

// =====================
// Database
// =====================
define( 'DB_NAME',     confidup_env( 'DB_NAME',     'wordpress' ) );
define( 'DB_USER',     confidup_env( 'DB_USER',     'root' ) );
define( 'DB_PASSWORD', confidup_env( 'DB_PASSWORD', '' ) );
define( 'DB_HOST',     confidup_env( 'DB_HOST',     'localhost' ) );
define( 'DB_CHARSET',  'utf8mb4' );
define( 'DB_COLLATE',  '' );

// =====================
// Authentication Keys & Salts
// Generate fresh ones at: https://api.wordpress.org/secret-key/1.1/salt/
// =====================
define( 'AUTH_KEY',         confidup_env( 'AUTH_KEY',         'put your unique phrase here' ) );
define( 'SECURE_AUTH_KEY',  confidup_env( 'SECURE_AUTH_KEY',  'put your unique phrase here' ) );
define( 'LOGGED_IN_KEY',    confidup_env( 'LOGGED_IN_KEY',    'put your unique phrase here' ) );
define( 'NONCE_KEY',        confidup_env( 'NONCE_KEY',        'put your unique phrase here' ) );
define( 'AUTH_SALT',        confidup_env( 'AUTH_SALT',        'put your unique phrase here' ) );
define( 'SECURE_AUTH_SALT', confidup_env( 'SECURE_AUTH_SALT', 'put your unique phrase here' ) );
define( 'LOGGED_IN_SALT',   confidup_env( 'LOGGED_IN_SALT',   'put your unique phrase here' ) );
define( 'NONCE_SALT',       confidup_env( 'NONCE_SALT',       'put your unique phrase here' ) );

// =====================
// Table Prefix
// =====================
$table_prefix = confidup_env( 'DB_TABLE_PREFIX', 'wp_' );

// =====================
// Environment
// =====================
define( 'WP_ENVIRONMENT_TYPE', confidup_env( 'WP_ENVIRONMENT_TYPE', 'production' ) );
define( 'WP_DEBUG',            confidup_env( 'WP_DEBUG', false ) );
define( 'WP_DEBUG_LOG',        confidup_env( 'WP_DEBUG_LOG', false ) );
define( 'WP_DEBUG_DISPLAY',    false );

// =====================
// URLs
// =====================
define( 'WP_HOME',    confidup_env( 'WP_HOME',    'https://confidup.com' ) );
define( 'WP_SITEURL', confidup_env( 'WP_SITEURL', 'https://confidup.com' ) );

// =====================
// Performance & Security
// =====================
define( 'DISALLOW_FILE_EDIT',  true );
define( 'DISALLOW_FILE_MODS',  true );
define( 'FORCE_SSL_ADMIN',     true );
define( 'WP_POST_REVISIONS',   5 );
define( 'AUTOSAVE_INTERVAL',   120 );
define( 'EMPTY_TRASH_DAYS',    14 );

// =====================
// Bootstrap
// =====================
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
