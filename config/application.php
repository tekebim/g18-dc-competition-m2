<?php
/**
 * Your base production configuration goes in this file. Environment-specific
 * overrides go in their respective config/environments/{{WP_ENV}}.php file.
 *
 * A good default policy is to deviate from the production config as little as
 * possible. Try to define as much of your configuration in this file as you
 * can.
 */

use Roots\WPConfig\Config;

/** @var string Directory containing all of the site's files */
$root_dir = dirname(__DIR__);

/** @var string Document Root */
$webroot_dir = $root_dir . '/web';

/**
 * Expose global env() function from oscarotero/env
 */
Env::init();

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = Dotenv\Dotenv::createImmutable($root_dir);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['WP_HOME', 'WP_SITEURL']);
    if (!env('DATABASE_URL')) {
        $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
    }
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', env('WP_ENV') ?: 'production');

/**
 * URLs
 */
Config::define('WP_HOME', env('WP_HOME'));
Config::define('WP_SITEURL', env('WP_SITEURL'));

/**
 * Custom Content Directory
 */
Config::define('CONTENT_DIR', '/app');
Config::define('WP_CONTENT_DIR', $webroot_dir . Config::get('CONTENT_DIR'));
Config::define('WP_CONTENT_URL', Config::get('WP_HOME') . Config::get('CONTENT_DIR'));

/**
 * DB settings
 */
Config::define('DB_NAME', env('DB_NAME'));
Config::define('DB_USER', env('DB_USER'));
Config::define('DB_PASSWORD', env('DB_PASSWORD'));
Config::define('DB_HOST', env('DB_HOST') ?: 'localhost');
Config::define('DB_CHARSET', 'utf8mb4');
Config::define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

if (env('DATABASE_URL')) {
    $dsn = (object) parse_url(env('DATABASE_URL'));

    Config::define('DB_NAME', substr($dsn->path, 1));
    Config::define('DB_USER', $dsn->user);
    Config::define('DB_PASSWORD', isset($dsn->pass) ? $dsn->pass : null);
    Config::define('DB_HOST', isset($dsn->port) ? "{$dsn->host}:{$dsn->port}" : $dsn->host);
}

/**
 * Authentication Unique Keys and Salts
 */
Config::define('AUTH_KEY', env('AUTH_KEY'));
Config::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
Config::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
Config::define('NONCE_KEY', env('NONCE_KEY'));
Config::define('AUTH_SALT', env('AUTH_SALT'));
Config::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
Config::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
Config::define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Custom Settings
 */
Config::define('AUTOMATIC_UPDATER_DISABLED', true);
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
// Config::define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', true);
Config::define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?: 5);
Config::define('WPLANG', env('WPLANG') ?: 'fr-FR');
Config::define('WP_LOCALE', env('WP_LOCALE') ?: 'fr_FR');


/**
 * Project Settings
 */
Config::define('WP_ECODESIGN', env('WP_ECODESIGN') ?: false);
Config::define('ACF_PRO_KEY', env('ACF_PRO_KEY'));
Config::define('ACF_THEME_CODE_PRO_KEY', env('ACF_THEME_CODE_PRO_KEY'));
Config::define('WPML_USERID', env('WPML_USERID'));
Config::define('WPML_PRO_KEY', env('WPML_PRO_KEY'));
Config::define('OTGS_INSTALLER_SITE_KEY_WPML', env('OTGS_INSTALLER_SITE_KEY_WPML') ?: '');
Config::define('GOOGLE_ANALYTICS_TRACKING_ID', env('GOOGLE_ANALYTICS_TRACKING_ID') ?: '');
Config::define('EMOJI_JS', env('EMOJI_JS') ?: false);
Config::define('GUTEMBERG_CSS', env('GUTEMBERG_CSS') ?: false);
Config::define('IMG_MAX_SIZE', env('IMG_MAX_SIZE') ?: '3000');
Config::define('IMG_MAX_WIDTH', env('IMG_MAX_WIDTH') ?: '2732');
Config::define('IMG_MAX_HEIGHT', env('IMG_MAX_HEIGHT') ?: '2732');
Config::define('IMG_BREAKPOINTS', env('IMG_BREAKPOINTS') ?: '320 768 1140');
Config::define('YOAST_SEO_SHOW_EDITOR', env('YOAST_SEO_SHOW_EDITOR') ?: false);


/**
 * Contact Form Recaptcha
 */
Config::define('RECAPTCHA_SECRET_KEY', env('RECAPTCHA_SECRET_KEY') ?: '');
Config::define('RECAPTCHA_PUBLIC_KEY', env('RECAPTCHA_PUBLIC_KEY') ?: '');


/**
 * Gitlab constants
 */
Config::define('GITLAB_API_ENDPOINT_DOMAIN', env('GITLAB_API_ENDPOINT_DOMAIN') ?: '');
Config::define('GITLAB_API_ACCESS_TOKEN', env('GITLAB_API_ACCESS_TOKEN') ?: '');
Config::define('GITLAB_API_PROJECT_ID', env('GITLAB_API_PROJECT_ID') ?: '');


/**
 * StaticWebsiteBuilder constants
 */
Config::define('STATICWEBSITEBUILDER_STATIC_BUILDS_DIR', env('STATICWEBSITEBUILDER_STATIC_BUILDS_DIR') ?: '');
Config::define('STATICWEBSITEBUILDER_STATIC_CURRENT_DIR', env('STATICWEBSITEBUILDER_STATIC_CURRENT_DIR') ?: '');


/**
 * Debugging Settings
 */
Config::define('WP_DEBUG_DISPLAY', false);
Config::define('SCRIPT_DEBUG', false);
ini_set('display_errors', '0');

/**
 * GraphQL debugging
 */
Config::define('GRAPHQL_DEBUG', env('GRAPHQL_DEBUG') ?: false);

/**
 * ACF debugging
 */
Config::define('ACFE_DEV', WP_ENV === 'development' ? true : false);

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
    require_once $env_config;
}

Config::apply();

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}
