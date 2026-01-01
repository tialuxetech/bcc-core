<?php
namespace BCC\Identity;

defined('ABSPATH') || exit;

final class Role_Assignment_Listener {

    public static function init(): void {

        // Initial cleanup on user creation
        add_action( 'user_register', [ self::class, 'handle_user_register' ], 20 );

        // Final role resolution after ACF saves
        add_action( 'acf/save_post', [ self::class, 'handle_acf_user_save' ], 20 );
    }

    /**
     * Remove default WP role early
     */
    public static function handle_user_register( int $user_id ): void {

        $user = get_user_by( 'id', $user_id );
        if ( ! $user ) {
            return;
        }

        if ( $user->has_role( 'subscriber' ) ) {
            $user->remove_role( 'subscriber' );
        }
    }

    /**
     * Assign BCC roles after ACF save
     */
    public static function handle_acf_user_save( $post_id ): void {

        if ( ! is_string( $post_id ) || strpos( $post_id, 'user_' ) !== 0 ) {
            return;
        }

        $user_id = (int) str_replace( 'user_', '', $post_id );
        if ( $user_id <= 0 ) {
            return;
        }

        User_Role_Service::assign_primary_role( $user_id );
    }
}