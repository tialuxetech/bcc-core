<?php
namespace BCC\Integrations\PeepSo;

defined('ABSPATH') || exit;

use PeepSoProfileShortcode;

final class Profile_Tabs {

    /**
     * Contributor lanes
     */
    private static array $lanes = [
        'builder' => [
            'label' => 'Builder',
            'icon'  => 'pso-i-hammer',
            'role'  => 'bcc_builder',
        ],
        'validator' => [
            'label' => 'Validator',
            'icon'  => 'pso-i-check-circle',
            'role'  => 'bcc_validator',
        ],
        'creator' => [
            'label' => 'Creator',
            'icon'  => 'pso-i-brush',
            'role'  => 'bcc_creator',
        ],
    ];

    /**
     * Boot integration
     */
    public static function init(): void {

        // Profile cover navigation (supports nested menus)
        add_filter('peepso_navigation_profile', [self::class, 'register_contribute_menu']);

        // Dropdown + sidebar require flattened menus
        add_filter('peepso_navigation_profile_dropdown', [self::class, 'flatten_contribute_menu']);
        add_filter('peepso_navigation_profile_sidebar', [self::class, 'flatten_contribute_menu']);

        // SINGLE profile segment
        add_action(
            'peepso_profile_segment_contribute',
            [self::class, 'render_contribute']
        );
    }

    /**
     * Register "Contribute" menu with submenus (profile cover only)
     */
    public static function register_contribute_menu(array $links): array {

        $links['contribute'] = [
            'label' => __('Contribute', 'bcc-core'),
            'icon'  => 'pso-i-users',
            'sub'   => [],
        ];

        foreach (self::$lanes as $slug => $lane) {
            $links['contribute']['sub']['contribute-' . $slug] = [
                'label' => $lane['label'],
                'href'  => 'contribute-' . $slug,
                'icon'  => $lane['icon'],
            ];
        }

        return $links;
    }

    /**
     * Flatten Contribute submenus for dropdown + sidebar
     */
    public static function flatten_contribute_menu(array $links): array {

        if (!isset($links['contribute']['sub'])) {
            return $links;
        }

        foreach ($links['contribute']['sub'] as $key => $item) {
            $links[$key] = [
                'label' => __('Contribute: ', 'bcc-core') . $item['label'],
                'href'  => $item['href'],
                'icon'  => $item['icon'],
            ];
        }

        unset($links['contribute']);

        return $links;
    }

    /**
     * Render Contribute profile page (with internal sub-tabs)
     */
    public static function render_contribute(): void {

        $profile = PeepSoProfileShortcode::get_instance();
        $segment = $profile->get_segment();

        // Determine active tab
        $tab = str_replace('contribute-', '', $segment);

        if (!isset(self::$lanes[$tab])) {
            $tab = 'builder'; // default tab
        }

        // Make data available to template
        $lanes = self::$lanes;
        $active_tab = $tab;

        $template = BCC_CORE_PATH . 'includes/Integrations/PeepSo/Templates/profile/contribute.php';

        if (file_exists($template)) {
            include $template;
        } else {
            echo '<p>' . esc_html__('Contribute page not available.', 'bcc-core') . '</p>';
        }
    }
}