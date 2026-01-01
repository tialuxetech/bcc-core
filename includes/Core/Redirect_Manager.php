final class Redirect_Manager {

    public static function init(): void {
        add_filter( 'login_redirect', [ self::class, 'handle_login_redirect' ], 20, 3 );
    }

    public static function handle_login_redirect(
        string $redirect_to,
        string $requested_redirect_to,
        \WP_User $user
    ): string {

        if ( ! $user instanceof \WP_User ) {
            return $redirect_to;
        }

        if ( in_array( 'administrator', $user->roles, true ) ) {
            return $redirect_to;
        }

        if ( ! class_exists( 'PeepSo' ) ) {
            return $redirect_to;
        }

        $username = $user->user_nicename;

        $map = [
            'bcc_builder'   => "profile/{$username}/contribute/builder",
            'bcc_validator' => "profile/{$username}/contribute/validator",
            'bcc_creator'   => "profile/{$username}/contribute/creator",
            'bcc_community' => "profile/{$username}",
        ];

        foreach ( $map as $role => $path ) {
            if ( in_array( $role, $user->roles, true ) ) {
                return home_url( $path );
            }
        }

        return $redirect_to;
    }
}