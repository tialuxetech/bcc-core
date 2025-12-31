<?php
namespace BCC\Core;

use WP_User;

defined( 'ABSPATH' ) || exit;

final class Redirect_Manager {

    /**
     * Bootstrap redirects
     */
    public static function init(): void {
        add_filter( 'login_redirect', [ self::class, 'handle_login_redirect' ], 20, 3 );
    }

    /**
     * Handle role-based login redirect
     *
     * @param string   $redirect_to
     * @param string   $requested_redirect_to
     * @param WP_User  $user
     * @return string
     */
    public static function handle_login_redirect(
        string $redirect_to,
        string $requested_redirect_to,
        WP_User $user
    ): string {

        // Safety: invalid user
        if ( ! $user instanceof WP_User ) {
            return $redirect_to;
        }

        // Never interfere with admins
        if ( in_array( 'administrator', (array) $user->roles, true ) ) {
            return $redirect_to;
        }

        // PeepSo must be active
        if ( ! class_exists( 'PeepSo' ) ) {
            return $redirect_to;
        }

        $username = $user->user_nicename;

        // Role → destination map
        $destinations = [
            'builder'   => "profile/{$username}/contribute/builder",
            'validator' => "profile/{$username}/contribute/validator",
            'creator'   => "profile/{$username}/contribute/creator",
        ];

        foreach ( $destinations as $role => $path ) {
            if ( in_array( $role, (array) $user->roles, true ) ) {
                return home_url( $path );
            }
        }

        // Fallback
        return $redirect_to;
    }
}