<?php
namespace BCC\Identity;

use WP_User;

defined('ABSPATH') || exit;

final class User_Role_Service {

    public static function assign_primary_role( int $user_id ): void {

        if ( ! function_exists( 'get_field' ) ) {
            return;
        }

        $user = get_user_by( 'id', $user_id );
        if ( ! $user instanceof WP_User ) {
            return;
        }

        /**
         * Read ACF values (LABELS, not slugs)
         */
        $entry_purpose = get_field( 'bcc_entry_purpose', 'user_' . $user_id );
        $primary_role  = get_field( 'bcc_primary_contributor_role', 'user_' . $user_id );

        if ( ! $entry_purpose ) {
            return;
        }

        /**
         * Reset roles (single source of truth)
         */
        $user->set_role( '' );

        /**
         * Explorer → Community
         */
        if ( $entry_purpose === 'Enter as an Explorer' ) {
            $user->add_role( 'bcc_community' );
            return;
        }

        /**
         * Contributor → One primary role
         */
        if ( $entry_purpose === 'Enter as a Contributor' ) {

            $role_map = [
                'As a Builder'      => 'bcc_builder',
                'As a Validator'    => 'bcc_validator',
                'As an NFT Creator' => 'bcc_creator',
            ];

            if ( isset( $role_map[ $primary_role ] ) ) {
                $user->add_role( $role_map[ $primary_role ] );
            }
        }
    }
}