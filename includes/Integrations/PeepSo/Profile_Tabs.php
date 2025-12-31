<?php
namespace BCC\Integrations\PeepSo;

defined( 'ABSPATH' ) || exit;

use PeepSoProfileShortcode;

final class Profile_Tabs {

    /**
     * Contributor lanes
     */
    private static array $lanes = [
        'builder'   => [
            'label' => 'Builder',
            'icon'  => 'pso-i-hammer',
            'role'  => 'bcc_builder',
        ],
        'validator' => [
            'label' => 'Validator',
            'icon'  => 'pso-i-check-circle',
            'role'  => 'bcc_validator',
        ],
        'creator'   => [
            'label' => 'Creator',
            'icon'  => 'pso-i-brush',
            'role'  => 'bcc_creator',
        ],
    ];

    public static function init(): void {

        // Register main profile menu
        add_filter( 'peepso_navigation_profile', [ self::class, 'register_contribute_menu' ] );

        // Register segment renderers
        foreach ( self::$lanes as $slug => $lane ) {
            add_action(
                'peepso_profile_segment_contribute-' . $slug,
                [ self::class, 'render_lane' ]
            );
        }
    }

    /**
     * Register "Contribute" profile menu + submenus
     */
    public static function register_contribute_menu( array $links ): array {

        $links['contribute'] = [
            'label' => __( 'Contribute', 'bcc-core' ),
            'icon'  => 'pso-i-users',
            'sub'   => [],
        ];

        foreach ( self::$lanes as $slug => $lane ) {
            $links['contribute']['sub'][ 'contribute-' . $slug ] = [
                'label' => $lane['label'],
                'href'  => 'contribute-' . $slug,
                'icon'  => $lane['icon'],
            ];
        }

        return $links;
    }

    /**
     * Render a contribution lane
     */
    public static function render_lane(): void {

        $view_user_id = PeepSoProfileShortcode::get_instance()->get_view_user_id();
        $segment      = PeepSoProfileShortcode::get_instance()->get_segment();

        $lane_slug = str_replace( 'contribute-', '', $segment );

        if ( empty( self::$lanes[ $lane_slug ] ) ) {
            return;
        }

        $lane = self::$lanes[ $lane_slug ];

        $template = BCC_CORE_PATH . 'includes/Integrations/PeepSo/Templates/profile/contribute-' . $lane_slug . '.php';

        if ( file_exists( $template ) ) {
            include $template;
        } else {
            echo '<p>' . esc_html__( 'This section is under construction.', 'bcc-core' ) . '</p>';
        }
    }
}
