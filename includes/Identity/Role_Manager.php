<?php
namespace BCC\Identity;

defined( 'ABSPATH' ) || exit;

final class Role_Manager {

    /**
     * Canonical BCC roles
     */
    private static array $roles = [
        'bcc_community' => [
            'label' => 'Community Member',
        ],
        'bcc_validator' => [
            'label' => 'Validator',
        ],
        'bcc_builder' => [
            'label' => 'Builder',
        ],
        'bcc_creator' => [
            'label' => 'NFT Creator',
        ],
    ];

    /**
     * Boot Role Manager
     */
    public static function init(): void {
        add_action( 'init', [ self::class, 'register_roles' ] );
    }

    /**
     * Register roles in WordPress
     */
    public static function register_roles(): void {
        foreach ( self::$roles as $role_key => $role ) {
            if ( ! get_role( $role_key ) ) {
                add_role(
                    $role_key,
                    $role['label'],
                    [] // Capabilities assigned later
                );
            }
        }
    }

    /**
     * Return all BCC roles
     */
    public static function get_roles(): array {
        return array_keys( self::$roles );
    }

    /**
     * Check if user has any BCC role
     */
    public static function user_has_bcc_role( int $user_id ): bool {
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return false;
        }

        foreach ( self::get_roles() as $role ) {
            if ( in_array( $role, (array) $user->roles, true ) ) {
                return true;
            }
        }

        return false;
    }
}