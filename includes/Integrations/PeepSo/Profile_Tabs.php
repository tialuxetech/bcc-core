<?php
namespace BCC\Integrations\PeepSo;

use BCC\Core\Permissions_Manager;

defined('ABSPATH') || exit;

final class Profile_Tabs {

    /**
     * Boot integration
     */
    public static function init(): void {

        // Register ONE profile menu
        add_filter('peepso_navigation_profile', [self::class, 'register_contribute_menu']);

        // Control profile menu order
        add_filter(
            'peepso_filter_navigation_profile_order',
            [self::class, 'order_profile_tabs'],
            20
        );

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

        $current_user = wp_get_current_user();

        // Only show menu if user has capability to view contributions
        if (current_user_can('bcc_view_profile') && current_user_can('bcc_view_contribution_status')) {
            $links['contribute'] = [
                'label' => __('Contribute', 'bcc-core'),
                'href'  => 'contribute',
                'icon'  => 'pso-i-rectangle-list',
            ];
        }

        return $links;
    }

    /**
     * Place Contribute after About tab
     */
    public static function order_profile_tabs(array $order): array {
        $order = array_values(array_diff($order, ['contribute']));

        $new_order = [];

        foreach ($order as $key) {
            $new_order[] = $key;

            if ($key === 'about') {
                $new_order[] = 'contribute';
            }
        }

        return $new_order;
    }

    /**
     * Render Contribute profile page
     */
    public static function render_contribute(): void {

        $current_user = wp_get_current_user();

        // Redirect if user cannot view contributions
        if (!current_user_can('bcc_view_profile') || !current_user_can('bcc_view_contribution_status')) {
            wp_safe_redirect(site_url('/become-a-contributor'));
            exit;
        }

        $template = BCC_CORE_PATH . 'includes/Integrations/PeepSo/Templates/profile/contribute.php';

        if (file_exists($template)) {
            include $template;
        } else {
            echo '<p>' . esc_html__('Contribute page not available.', 'bcc-core') . '</p>';
        }
    }
}