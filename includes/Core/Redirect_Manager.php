<?php
namespace BCC\Core;

use WP_User;
use WP_Error;

defined( 'ABSPATH' ) || exit;

final class Redirect_Manager {

    public static function init(): void {
        add_filter( 'login_redirect', [ self::class, 'handle_login_redirect' ], 20, 3 );
    }

    public static function handle_login_redirect(
        string $redirect_to,
        string $requested_redirect_to,
        $user
    ): string {

        // Bail if login failed or invalid user
        if ( ! $user instanceof WP_User ) {
            return $redirect_to;
        }

        // Never interfere with admins
        if ( in_array( 'administrator', (array) $user->roles, true ) ) {
            return $redirect_to;
        }

        // Ensure PeepSo is available
        if ( ! class_exists( 'PeepSo' ) ) {
            return $redirect_to;
        }

        $username = $user->user_nicename;

        $map = [
            'bcc_builder'   => "profile/{$username}/contribute",
            'bcc_validator' => "profile/{$username}/contribute",
            'bcc_creator'   => "profile/{$username}/contribute",
            'bcc_community' => "profile/{$username}",
        ];

        foreach ( $map as $role => $path ) {
            if ( in_array( $role, (array) $user->roles, true ) ) {
                return home_url( $path );
            }
        }

        return $redirect_to;
    }
}
