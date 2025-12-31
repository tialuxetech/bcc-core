<?php
namespace BCC\Identity;

defined('ABSPATH') || exit;

final class Role_Assignment_Listener {

    public static function init(): void {

        /**
         * Fires AFTER ACF saves user meta
         */
        add_action( 'acf/save_post', [ self::class, 'handle_acf_user_save' ], 20 );
    }

    /**
     * Assign role after ACF user fields are saved
     *
     * @param mixed $post_id
     */
    public static function handle_acf_user_save( $post_id ): void {

        // We only care about user saves
        if ( ! is_string( $post_id ) || strpos( $post_id, 'user_' ) !== 0 ) {
            return;
        }

        $user_id = (int) str_replace( 'user_', '', $post_id );

        if ( $user_id <= 0 ) {
            return;
        }

        // Delegate role logic to the service
        \BCC\Identity\User_Role_Service::assign_primary_role( $user_id );
    }
}