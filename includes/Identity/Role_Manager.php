<?php
namespace BCC\Identity;

defined( 'ABSPATH' ) || exit;

final class Role_Manager {

    /**
     * BCC role definitions
     */
    protected static array $roles = [
        'bcc_validator' => [
            'label' => 'Validator',
        ],
        'bcc_builder_validator' => [
            'label' => 'Builder & Validator',
        ],
        'bcc_community' => [
            'label' => 'Community Member',
        ],
        'bcc_nft_creator' => [
            'label' => 'NFT Creator',
        ],
    ];

    /**
     * Initialize Role Manager
     */
    public static function init(): void {
        add_action( 'init', [ self::class, 'register_roles' ] );
    }

    /**
     * Register BCC roles with WordPress
     */
    public static function register_roles(): void {
        foreach ( self::$roles as $role_key => $role ) {
            if ( ! get_role( $role_key ) ) {
                add_role(
                    $role_key,
                    $role['label'],
                    [] // Capabilities intentionally empty for now
                );
            }
        }
    }

    /**
     * Get all BCC roles
     */
    public static function get_roles(): array {
        return self::$roles;
    }

    /**
     * Check if user has a BCC role
     */
    public static function user_has_bcc_role( int $user_id ): bool {
        $user = get_user_by( 'id', $user_id );
        if ( ! $user ) {
            return false;
        }

        foreach ( array_keys( self::$roles ) as $role ) {
            if ( in_array( $role, (array) $user->roles, true ) ) {
                return true;
            }
        }

        return false;
    }
}
