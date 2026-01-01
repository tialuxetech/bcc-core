<?php
namespace BCC\Identity;

use WP_User;

defined('ABSPATH') || exit;

final class User_Role_Service {

    public static function assign_primary_role( int $user_id ): void {

        error_log('[BCC][User_Role_Service] assign_primary_role() started for user_id: ' . $user_id);

        if ( ! function_exists( 'get_field' ) ) {
            error_log('[BCC][User_Role_Service] ACF get_field() not available.');
            return;
        }

        $user = get_user_by( 'id', $user_id );

        if ( ! $user instanceof WP_User ) {
            error_log('[BCC][User_Role_Service] User object not found.');
            return;
        }

        error_log('[BCC][User_Role_Service] User loaded. Current roles: ' . implode(', ', $user->roles));

        // Read ACF fields
        $entry_purpose = get_field( 'bcc_entry_purpose', 'user_' . $user_id );
        $primary_role  = get_field( 'bcc_primary_contributor_role', 'user_' . $user_id );

        error_log('[BCC][User_Role_Service] entry_purpose: ' . print_r($entry_purpose, true));
        error_log('[BCC][User_Role_Service] primary_role: ' . print_r($primary_role, true));

        if ( empty( $entry_purpose ) ) {
            error_log('[BCC][User_Role_Service] entry_purpose empty. Exiting.');
            return;
        }

        // Remove default subscriber role
        if ( $user->has_role( 'subscriber' ) ) {
            $user->remove_role( 'subscriber' );
            error_log('[BCC][User_Role_Service] Removed subscriber role.');
        }

        /**
         * Explorer → Community
         */
        if ( $entry_purpose === 'Enter as an Explorer' ) {

            $user->add_role( 'bcc_community' );
            error_log('[BCC][User_Role_Service] Assigned bcc_community role.');

            return;
        }

        /**
         * Contributor → single primary role
         */
        if ( $entry_purpose === 'Enter as a Contributor' ) {

            if ( empty( $primary_role ) ) {
                error_log('[BCC][User_Role_Service] Contributor selected but primary role empty.');
                return;
            }

            $map = [
                'As a Builder'      => 'bcc_builder',
                'As a Validator'    => 'bcc_validator',
                'As an NFT Creator' => 'bcc_creator',
            ];

            if ( ! isset( $map[ $primary_role ] ) ) {
                error_log('[BCC][User_Role_Service] primary_role not in map.');
                return;
            }

            $target_role = $map[ $primary_role ];

            // Remove other BCC roles
            foreach ( $user->roles as $role ) {
                if ( str_starts_with( $role, 'bcc_' ) && $role !== $target_role ) {
                    $user->remove_role( $role );
                    error_log('[BCC][User_Role_Service] Removed role: ' . $role);
                }
            }

            if ( ! $user->has_role( $target_role ) ) {
                $user->add_role( $target_role );
                error_log('[BCC][User_Role_Service] Assigned role: ' . $target_role);
            }

            return;
        }

        error_log('[BCC][User_Role_Service] No matching entry purpose condition hit.');
    }
}
