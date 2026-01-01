<?php
namespace BCC\Identity;

defined('ABSPATH') || exit;

error_log('[BCC][BOOT] Role_Assignment_Listener file loaded');

final class Role_Assignment_Listener {

    public static function init(): void {

        error_log('[BCC][Role_Assignment_Listener] init() called');

        add_action( 'acf/save_post', [ self::class, 'handle_acf_user_save' ], 20 );
    }

    public static function handle_acf_user_save( $post_id ): void {

        error_log('[BCC][Role_Assignment_Listener] acf/save_post fired. post_id: ' . print_r($post_id, true));

        // Only user saves
        if ( ! is_string( $post_id ) || strpos( $post_id, 'user_' ) !== 0 ) {
            error_log('[BCC][Role_Assignment_Listener] Not a user save. Exiting.');
            return;
        }

        $user_id = (int) str_replace( 'user_', '', $post_id );

        if ( $user_id <= 0 ) {
            error_log('[BCC][Role_Assignment_Listener] Invalid user ID.');
            return;
        }

        error_log('[BCC][Role_Assignment_Listener] Valid user detected. user_id: ' . $user_id);

        \BCC\Identity\User_Role_Service::assign_primary_role( $user_id );
    }
}
