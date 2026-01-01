<?php
namespace BCC\Integrations\PeepSo;

defined('ABSPATH') || exit;

final class Profile_Tabs {

    /**
     * Boot integration
     */
    public static function init(): void {

        // Register ONE profile menu
        add_filter('peepso_navigation_profile', [self::class, 'register_contribute_menu']);

        // Register ONE profile segment
        add_action(
            'peepso_profile_segment_contribute',
            [self::class, 'render_contribute']
        );
    }

    /**
     * Register "Contribute" profile tab
     */
    public static function register_contribute_menu(array $links): array {

        $links['contribute'] = [
            'label' => __('Contribute', 'bcc-core'),
            'href'  => 'contribute',
            'icon'  => 'pso-i-users',
        ];

        return $links;
    }

    /**
     * Render Contribute profile page (internal routing handled in template)
     */
    public static function render_contribute(): void {

        $template = BCC_CORE_PATH . 'includes/Integrations/PeepSo/Templates/profile/contribute.php';

        if (file_exists($template)) {
            include $template;
        } else {
            echo '<p>' . esc_html__('Contribute page not available.', 'bcc-core') . '</p>';
        }
    }
}