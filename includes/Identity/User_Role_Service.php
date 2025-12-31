<?php
namespace BCC\Identity;

use WP_User;

defined('ABSPATH') || exit;

final class User_Role_Service {

    /**
     * Assign the primary BCC role to a user
     */
    public static function assign_primary_role(int $user_id): void {

        if (!function_exists('get_field')) {
            return;
        }

        $user = get_user_by('id', $user_id);
        if (!$user instanceof WP_User) {
            return;
        }

        // -----------------------------------------
        // Read ACF identity fields
        // -----------------------------------------
        $entry_purpose = get_field('bcc_entry_purpose', 'user_' . $user_id);
        $primary_role  = get_field('bcc_primary_contributor_role', 'user_' . $user_id);

        if (empty($primary_role)) {
            return;
        }

        // -----------------------------------------
        // Map to canonical BCC role
        // -----------------------------------------
        $role_map = [
            'builder'   => 'bcc_builder',
            'validator' => 'bcc_validator',
            'creator'  => 'bcc_creator',
        ];

        if (!isset($role_map[$primary_role])) {
            return;
        }

        $target_role = $role_map[$primary_role];

        // -----------------------------------------
        // Enforce single primary role
        // -----------------------------------------
        foreach ($user->roles as $role) {
            if (str_starts_with($role, 'bcc_') && $role !== $target_role) {
                $user->remove_role($role);
            }
        }

        if (!$user->has_role($target_role)) {
            $user->add_role($target_role);
        }

        // -----------------------------------------
        // Remove default subscriber role
        // -----------------------------------------
        if ($user->has_role('subscriber')) {
            $user->remove_role('subscriber');
        }
    }
}