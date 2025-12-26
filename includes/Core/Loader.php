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
        self::register_modules();
        self::boot_modules();
    }

    /**
     * Register core modules
     */
    protected static function register_modules(): void {

        self::$modules = apply_filters( 'bcc_core_modules', [
            \BCC\Identity\Role_Manager::class,
            // Future:
            // \BCC\Onboarding\Flow_Controller::class,
            // \BCC\Validation\Validation_Queue::class,
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
