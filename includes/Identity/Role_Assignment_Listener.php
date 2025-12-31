<?php
namespace BCC\Identity;

defined('ABSPATH') || exit;

final class Role_Assignment_Listener {

    public static function init(): void {

        /**
         * Fires when a user is created.
         * This is intentionally conservative.
         */
        add_action('user_register', [self::class, 'handle_user_register'], 20);
    }

    /**
     * Handle new user registration
     */
    public static function handle_user_register(int $user_id): void {

        // Delay execution slightly to allow ACF to save user meta
        add_action('shutdown', function () use ($user_id) {
            User_Role_Service::assign_primary_role($user_id);
        });
    }
}