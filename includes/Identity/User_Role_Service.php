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
        if ( ! $user instanceof \WP_User ) {
            return;
        }

        $entry_purpose = get_field( 'bcc_entry_purpose', 'user_' . $user_id );
        $primary_role  = get_field( 'bcc_primary_contributor_role', 'user_' . $user_id );

        if ( empty( $entry_purpose ) ) {
            return;
        }

        /**
         * Explorer → Community
         */
        if ( $entry_purpose === 'Enter as an Explorer' ) {
            $user->set_role( 'bcc_community' );
            return;
        }

        /**
         * Contributor → single primary role
         */
        if ( $entry_purpose === 'Enter as a Contributor' && ! empty( $primary_role ) ) {

            $map = [
                'As a Builder'      => 'bcc_builder',
                'As a Validator'    => 'bcc_validator',
                'As an NFT Creator' => 'bcc_creator',
            ];

            if ( isset( $map[ $primary_role ] ) ) {
                $user->set_role( $map[ $primary_role ] );
            }
        }
    }
}
