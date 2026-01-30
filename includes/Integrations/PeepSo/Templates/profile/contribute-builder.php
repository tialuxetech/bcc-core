<?php
defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;

/**
 * Capability / Role Helpers
 */
$can_build        = current_user_can('bcc_build_project');
$is_builder       = in_array('bcc_builder', (array) $current_user->roles, true);
$intent_to_build  = get_user_meta($user_id, 'bcc_user_intends_to_build', true);

/**
 * Handle intent action
 */
if (
    isset($_POST['bcc_intent_to_build']) &&
    isset($_POST['bcc_intent_to_build_nonce']) &&
    wp_verify_nonce($_POST['bcc_intent_to_build_nonce'], 'bcc_intent_to_build_action')
) {
    update_user_meta($user_id, 'bcc_user_intends_to_build', true);

    wp_safe_redirect( esc_url_raw( wp_unslash($_SERVER['REQUEST_URI']) ) );
    exit;
}
?>

<div class="ps-profile__about-fields ps-js-profile-list bcc-builder-tab">

<?php if (!$is_builder && !$intent_to_build): ?>

    <!-- STATE 1: Not a Builder Yet -->
    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e("You're not a Builder yet", 'bcc-core'); ?></h3>
            <p><?php esc_html_e(
                'You haven’t contributed any builds yet. Would you like to start contributing as a Builder?',
                'bcc-core'
            ); ?></p>

            <form method="post">
                <?php wp_nonce_field('bcc_intent_to_build_action', 'bcc_intent_to_build_nonce'); ?>
                <button type="submit" name="bcc_intent_to_build" class="ps-btn ps-btn--primary">
                    <?php esc_html_e('Yes, I want to contribute as a Builder', 'bcc-core'); ?>
                </button>
            </form>
        </div>
    </div>

<?php elseif (!$is_builder && $intent_to_build): ?>

    <!-- STATE 2: Intent Declared – Complete Setup -->
    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e('Complete Your Builder Profile', 'bcc-core'); ?></h3>
            <p><?php esc_html_e(
                'Please complete your builder profile to unlock builder tools and start contributing projects.',
                'bcc-core'
            ); ?></p>

        <div class="bcc-acf-form bcc-acf-form--builder">
            <?php
            if ( function_exists('acf_form') ) {
                acf_form([
                    'post_id'       => 'user_' . $user_id,
                    'field_groups'  => ['group_697bf25663dcd'],
                    'submit_value'  => __('Save Builder Info', 'bcc-core'),
                    'return'        => esc_url_raw( wp_unslash($_SERVER['REQUEST_URI']) ),
                ]);
            }
            ?>
        </div>    
            
        </div>
    </div>

<?php elseif ($is_builder && $can_build): ?>

    <!-- STATE 3: Active Builder -->
    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e('Builder Dashboard', 'bcc-core'); ?></h3>
            <p><?php esc_html_e(
                'Manage your builds, submit new projects, and track your contributions.',
                'bcc-core'
            ); ?></p>

            <!-- Builder tools will live here -->
        </div>
    </div>

<?php endif; ?>

</div>