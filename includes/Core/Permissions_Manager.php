<?php
namespace BCC\Core;

defined( 'ABSPATH' ) || exit;

final class Permissions_Manager {

    protected static array $config = [];

    /**
     * Initialize permissions system
     */
    public static function init(): void {
        self::load_config();

        add_action( 'bcc_core_activate', [ self::class, 'assign_capabilities' ] );
        add_action( 'bcc_core_sync_permissions', [ self::class, 'assign_capabilities' ] );
    }

    /**
     * Load permissions configuration
     */
    protected static function load_config(): void {
        self::$config = require BCC_CORE_PATH . 'config/permissions.php';

        self::$config = apply_filters(
            'bcc_permissions_config',
            self::$config
        );
    }

    /**
     * Assign capabilities to roles
     */
    public static function assign_capabilities(): void {

        if ( empty( self::$config['role_map'] ) ) {
            return;
        }

        foreach ( self::$config['role_map'] as $role_key => $caps ) {

            $role = get_role( $role_key );
            if ( ! $role ) {
                continue;
            }

            foreach ( $caps as $cap ) {
                $role->add_cap( $cap );
            }
        }
    }
}
