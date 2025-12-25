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

// Define constants
define( 'BCC_CORE_VERSION', '0.1.0' );
define( 'BCC_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'BCC_CORE_URL', plugin_dir_url( __FILE__ ) );

// Load core loader
require_once BCC_CORE_PATH . 'includes/Core/Loader.php';

// Initialize plugin
add_action( 'plugins_loaded', function () {
    \BCC\Core\Loader::init();
});
