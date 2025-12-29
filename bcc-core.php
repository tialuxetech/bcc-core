<?php
/**
 * Plugin Name: BCC Core
 * Description: Core foundation plugin for the Blue Collar Crypto ecosystem.
 * Version: 0.1.0
 * Author: Blue Collar Crypto
 * Author URI: https://bluecollarcrypto.io
 * Text Domain: bcc-core
 */

defined( 'ABSPATH' ) || exit;

define( 'BCC_CORE_VERSION', '0.1.0' );
define( 'BCC_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'BCC_CORE_URL', plugin_dir_url( __FILE__ ) );

// Core files
require_once BCC_CORE_PATH . 'includes/Core/Loader.php';
require_once BCC_CORE_PATH . 'includes/Identity/Role_Manager.php';

add_action( 'plugins_loaded', function () {
    \BCC\Core\Loader::init();
});

// Plugin activation
register_activation_hook( __FILE__, function () {
    do_action( 'bcc_core_activate' );
});
