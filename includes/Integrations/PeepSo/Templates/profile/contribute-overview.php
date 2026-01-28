<?php
defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_roles   = (array) $current_user->roles;

global $wp_roles;

/**
 * Helper: check if user has a BCC role
 */
$has_role = function (string $role) use ($user_roles): bool {
    return in_array($role, $user_roles, true);
};

/**
 * Human-readable role names
 */
$readable_roles = [];
foreach ($user_roles as $role_key) {
    if (isset($wp_roles->roles[$role_key])) {
        $readable_roles[] = $wp_roles->roles[$role_key]['name'];
    }
}

/**
 * Profile URLs
 */
$profile_user_id = PeepSoProfileShortcode::get_instance()->get_view_user_id();

$peepso_user = PeepSoUser::get_instance($profile_user_id);

if (!$peepso_user) {
    return;
}

$profile_url = $peepso_user->get_profileurl();

$build_url    = trailingslashit($profile_url) . 'contribute/build';
$validate_url = trailingslashit($profile_url) . 'contribute/validate';
$create_url   = trailingslashit($profile_url) . 'contribute/create';
?>

<div class="ps-page ps-page-body">

    <!-- CONTRIBUTOR STATUS -->
    <div class="ps-card ps-card--profile">
        <div class="ps-card__body">

            <h3 class="ps-card__title">
                <?php esc_html_e('Contributor Overview', 'bcc-core'); ?>
            </h3>

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

    <!-- ROLE-BASED ACTIONS -->
    <div class="ps-card ps-card--profile">
        <div class="ps-card__body">

            <h4 class="ps-card__title">
                <?php esc_html_e('Available Actions', 'bcc-core'); ?>
            </h4>

            <?php if ($has_role('bcc_builder')) : ?>
                <div class="ps-widget">
                    <p class="ps-text">
                        <strong><?php esc_html_e('Builder', 'bcc-core'); ?></strong><br>
                        <?php esc_html_e('Submit new builds and manage your contributions.', 'bcc-core'); ?>
                    </p>
                    <a href="<?php echo esc_url($build_url); ?>" class="ps-btn ps-btn--primary ps-btn--sm">
                        <?php esc_html_e('Go to Build', 'bcc-core'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($has_role('bcc_validator')) : ?>
                <div class="ps-widget">
                    <p class="ps-text">
                        <strong><?php esc_html_e('Validator', 'bcc-core'); ?></strong><br>
                        <?php esc_html_e('Review and validate community submissions.', 'bcc-core'); ?>
                    </p>
                    <a href="<?php echo esc_url($validate_url); ?>" class="ps-btn ps-btn--primary ps-btn--sm">
                        <?php esc_html_e('Go to Validate', 'bcc-core'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($has_role('bcc_creator')) : ?>
                <div class="ps-widget">
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

    <!-- GUIDANCE -->
    <div class="ps-card ps-card--profile">
        <div class="ps-card__body">

            <h4 class="ps-card__title">
                <?php esc_html_e('Need help getting started?', 'bcc-core'); ?>
            </h4>

            <p class="ps-text">
                <?php esc_html_e(
                    'Each role has specific responsibilities. Use the sections above to begin contributing or continue where you left off.',
                    'bcc-core'
                ); ?>
            </p>

        </div>
    </div>

</div>
