<?php
namespace BCC\Core;

defined( 'ABSPATH' ) || exit;

final class Loader {

    /**
     * Registered modules
     *
     * @var array
     */
    protected static array $modules = [];

    /**
     * Initialize BCC Core
     */
    public static function init(): void {
        self::register_assets();
        self::register_modules();
        self::boot_modules();
    }

    /**
     * Register global assets
     */
    public static function register_assets(): void {
        add_action( 'wp_enqueue_scripts', [ self::class, 'enqueue_frontend_assets' ] );
    }

    /**
     * Enqueue frontend scripts
     */
    public static function enqueue_frontend_assets(): void {
        wp_enqueue_script(
            'bcc-auth-acf-fix',
            BCC_CORE_URL . 'assets/js/bcc-auth-acf-fix.js',
            [],
            BCC_CORE_VERSION,
            true
        );
    }


    /**
     * Register core modules
     */
    protected static function register_modules(): void {

        self::$modules = apply_filters( 'bcc_core_modules', [
            \BCC\Identity\Role_Manager::class,
            \BCC\Core\Permissions_Manager::class,
            \BCC\Core\Redirect_Manager::class,
            \BCC\Integrations\PeepSo\PeepSo_Integration::class,
            // Future modules classes can be added here:
        ]);
    }

    /**
     * Boot all registered modules
     */
    protected static function boot_modules(): void {
        foreach ( self::$modules as $module ) {
            if ( class_exists( $module ) && method_exists( $module, 'init' ) ) {
                $module::init();
            }
        }
    }
}
