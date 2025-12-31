<?php
namespace BCC\Identity;

defined('ABSPATH') || exit;

final class Role_Assignment_Listener {

    public static function init(): void {

        /**
         * Fires AFTER ACF saves user meta.
         */
        add_action('acf/save_post', [self::class, 'handle_acf_user_save'], 20);
    }

    /**
     * Assign role after ACF user fields are saved
     */
    public static function handle_acf_user_save($post_id): void {

        // Only target users
        if (strpos($post_id, 'user_') !== 0) {
            return;
        }

        $user_id = (int) str_replace('user_', '', $post_id);

        if ($user_id <= 0) {
            return;
        }

        User_Role_Service::assign_primary_role($user_id);
    }
}