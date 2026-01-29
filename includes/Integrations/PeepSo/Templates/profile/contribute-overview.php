<?php
defined('ABSPATH') || exit;

$current_user = wp_get_current_user();

/**
 * Helper: check if user has a capability
 */
$can = function(string $cap): bool {
    return current_user_can($cap);
};

/**
 * Human-readable active roles (optional, still useful)
 */
global $wp_roles;
$readable_roles = [];
foreach ((array)$current_user->roles as $role_key) {
    if (isset($wp_roles->roles[$role_key])) {
        $readable_roles[] = $wp_roles->roles[$role_key]['name'];
    }
}

/**
 * Profile URLs
 */
use BCC\Integrations\PeepSo\Helpers\Profile_Helper;

$contribute_base = Profile_Helper::get_contribute_base_url();
if (!$contribute_base) {
    return;
}

$build_url    = $contribute_base . 'build';
$validate_url = $contribute_base . 'validate';
$create_url   = $contribute_base . 'create';
?>

<div class="ps-profile__about-fields ps-js-profile-list bcc-contribute-overview">

    <!-- CONTRIBUTOR STATUS -->
    <div class="ps-card ps-card--profile">
        <div class="ps-card__body">
            <h3 class="ps-card__title"><?php esc_html_e('Contributor Overview', 'bcc-core'); ?></h3>

            <p class="ps-text">
                <?php esc_html_e(
                    'This is your contribution hub. From here, you can manage your activities within the BCC ecosystem.',
                    'bcc-core'
                ); ?>
            </p>

            <p class="ps-text ps-text--small">
                <strong><?php esc_html_e('Your active roles:', 'bcc-core'); ?></strong>
                <?php echo esc_html(implode(', ', $readable_roles)); ?>
            </p>
        </div>
    </div>

    <!-- ROLE-BASED ACTIONS (now capability-based) -->
    <div class="ps-card ps-card--profile">
        <div class="ps-card__body">
            <h4 class="ps-card__title"><?php esc_html_e('Available Actions', 'bcc-core'); ?></h4>

            <div class="bcc-contribute-actions" style="display: flex; flex-wrap: wrap; gap: 16px;">

                <?php if ($can('bcc_can_build')): ?>
                    <div class="ps-widget" style="flex: 1 1 calc(33.333% - 16px); box-sizing: border-box;">
                        <p class="ps-text">
                            <strong><?php esc_html_e('Builder', 'bcc-core'); ?></strong><br>
                            <?php esc_html_e('Submit new builds and manage your contributions.', 'bcc-core'); ?>
                        </p>
                        <a href="<?php echo esc_url($build_url); ?>" class="ps-btn ps-btn--primary ps-btn--sm">
                            <?php esc_html_e('Go to Build', 'bcc-core'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($can('bcc_can_validate')): ?>
                    <div class="ps-widget" style="flex: 1 1 calc(33.333% - 16px); box-sizing: border-box;">
                        <p class="ps-text">
                            <strong><?php esc_html_e('Validator', 'bcc-core'); ?></strong><br>
                            <?php esc_html_e('Review and validate community submissions.', 'bcc-core'); ?>
                        </p>
                        <a href="<?php echo esc_url($validate_url); ?>" class="ps-btn ps-btn--primary ps-btn--sm">
                            <?php esc_html_e('Go to Validate', 'bcc-core'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($can('bcc_can_create')): ?>
                    <div class="ps-widget" style="flex: 1 1 calc(33.333% - 16px); box-sizing: border-box;">
                        <p class="ps-text">
                            <strong><?php esc_html_e('NFT Creator', 'bcc-core'); ?></strong><br>
                            <?php esc_html_e('Create and manage NFT assets.', 'bcc-core'); ?>
                        </p>
                        <a href="<?php echo esc_url($create_url); ?>" class="ps-btn ps-btn--primary ps-btn--sm">
                            <?php esc_html_e('Go to Create', 'bcc-core'); ?>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>


    <!-- GUIDANCE -->
    <div class="ps-card ps-card--profile">
        <div class="ps-card__body">
            <h4 class="ps-card__title"><?php esc_html_e('Need help getting started?', 'bcc-core'); ?></h4>
            <p class="ps-text">
                <?php esc_html_e(
                    'Each role has specific responsibilities. Use the sections above to begin contributing or continue where you left off.',
                    'bcc-core'
                ); ?>
            </p>
        </div>
    </div>

</div>