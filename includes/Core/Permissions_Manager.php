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

        add_action( 'bcc_core_activate', [ self::class, 'sync_capabilities' ] );
        add_action( 'plugins_loaded', [ self::class, 'maybe_sync_capabilities' ] );
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
     * Sync capabilities to roles
     */
    public static function sync_capabilities(): void {

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

        // Remove deprecated capabilities
        if ( ! empty( self::$config['deprecated_capabilities'] ) ) {
            foreach ( wp_roles()->roles as $role_key => $data ) {

                $role = get_role( $role_key );
                if ( ! $role ) {
                    continue;
                }

                foreach ( self::$config['deprecated_capabilities'] as $cap ) {
                    $role->remove_cap( $cap );
                }
            }
        }

        update_option( 'bcc_core_cap_version', BCC_CORE_CAP_VERSION );
    }

    /**
     * Sync only when capability version changes
     */
    public static function maybe_sync_capabilities(): void {

        $stored = get_option( 'bcc_core_cap_version' );

        if ( $stored === BCC_CORE_CAP_VERSION ) {
            return;
        }

        self::sync_capabilities();
    }
}