<?php
namespace BCC\Integrations\PeepSo;

defined( 'ABSPATH' ) || exit;

final class PeepSo_Integration {

    public static function init(): void {

        // Bail early if PeepSo is not active
        if ( ! class_exists( 'PeepSo' ) ) {
            return;
        }

        // PeepSo has its own lifecycle
        add_action( 'peepso_init', [ self::class, 'boot' ] );
    }

    public static function boot(): void {
        Profile_Tabs::init();
    }
}
