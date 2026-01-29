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
        self::maybe_sync_capabilities();
    }

    /**
     * Load permissions configuration
     */
    protected static function load_config(): void {
        self::$config = require BCC_CORE_PATH . 'config/permissions.php';
        self::$config = apply_filters( 'bcc_permissions_config', self::$config );
    }

    // Sync all capabilities for all roles
    public static function sync_capabilities(): void {
        if ( empty( self::$config['role_map'] ) ) {
            return;
        }

        foreach ( self::$config['role_map'] as $role_key => $allowed_caps ) {

            $role = get_role( $role_key );
            if ( ! $role ) {
                continue;
            }

            // Normalize caps
            $allowed_caps = array_fill_keys( $allowed_caps, true );

            // Remove any bcc_* caps not in the allowed list
            foreach ( $role->capabilities as $cap => $granted ) {
                if ( str_starts_with( $cap, 'bcc_' ) && ! isset( $allowed_caps[ $cap ] ) ) {
                    $role->remove_cap( $cap );
                }
            }

            // Add missing caps
            foreach ( $allowed_caps as $cap => $_ ) {
                if ( ! $role->has_cap( $cap ) ) {
                    $role->add_cap( $cap );
                }
            }
        }

        // Update version to mark sync complete
        update_option( 'bcc_core_cap_version', BCC_CORE_CAP_VERSION );
    }

    /**
     * Only sync if capability version changed
     */
    public static function maybe_sync_capabilities(): void {
        $stored = get_option( 'bcc_core_cap_version' );

        if ( $stored !== BCC_CORE_CAP_VERSION ) {
            self::sync_capabilities();
        }
    }
}