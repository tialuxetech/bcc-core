<?php
namespace BCC\Integrations\PeepSo\Helpers;

defined('ABSPATH') || exit;

final class Profile_Helper {

    public static function get_profile_base_url(): ?string {

        if (
            ! class_exists('PeepSoProfileShortcode') ||
            ! class_exists('PeepSoUser')
        ) {
            return null;
        }

        $profile_user_id = \PeepSoProfileShortcode::get_instance()->get_view_user_id();

        if ( ! $profile_user_id ) {
            return null;
        }

        $peepso_user = \PeepSoUser::get_instance($profile_user_id);

        if ( ! $peepso_user ) {
            return null;
        }

        return trailingslashit($peepso_user->get_profileurl());
    }

    public static function get_contribute_base_url(): ?string {
        $profile_url = self::get_profile_base_url();
        return $profile_url ? trailingslashit($profile_url . 'contribute') : null;
    }
}