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

// --------------------------------------------------
// PLUGIN CONSTANTS
// --------------------------------------------------
define( 'BCC_CORE_VERSION', '0.1.0' );
define( 'BCC_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'BCC_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'BCC_CORE_CAP_VERSION', '1.0.4' );


// --------------------------------------------------
// CORE FILE INCLUDES (EXPLICIT & RELIABLE)
// --------------------------------------------------

// Core
require_once BCC_CORE_PATH . 'includes/Core/Loader.php';
require_once BCC_CORE_PATH . 'includes/Core/Permissions_Manager.php';
require_once BCC_CORE_PATH . 'includes/Core/Redirect_Manager.php';

// Identity
require_once BCC_CORE_PATH . 'includes/Identity/Role_Manager.php';
require_once BCC_CORE_PATH . 'includes/Identity/User_Role_Service.php';
require_once BCC_CORE_PATH . 'includes/Identity/Role_Assignment_Listener.php';

// Integrations
require_once BCC_CORE_PATH . 'includes/Integrations/PeepSo/PeepSo_Integration.php';
require_once BCC_CORE_PATH . 'includes/Integrations/PeepSo/Profile_Tabs.php';

// --------------------------------------------------
// BOOTSTRAP CORE AFTER PLUGINS LOAD
// --------------------------------------------------
add_action( 'plugins_loaded', function () {
    \BCC\Core\Loader::init();
});

// --------------------------------------------------
// PLUGIN ACTIVATION HOOK
// --------------------------------------------------
register_activation_hook( __FILE__, function () {
    do_action( 'bcc_core_activate' );
});