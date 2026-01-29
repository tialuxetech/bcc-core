<?php
defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;

/**
 * Capability / Role Helpers
 */
$can_build = current_user_can('bcc_build_project');
$is_builder = in_array('bcc_builder', $current_user->roles);
$intent_to_build = get_user_meta($user_id, 'bcc_user_intends_to_build', true);

// Handle the "Intent to become Builder" action
if ( isset($_POST['bcc_intent_to_build']) && wp_verify_nonce($_POST['bcc_intent_to_build_nonce'], 'bcc_intent_to_build_action') ) {
    update_user_meta($user_id, 'bcc_user_intends_to_build', true);
    // refresh the page to show the next state
    wp_safe_redirect( get_permalink() );
    exit;
}
?>

<div class="ps-profile__about-fields ps-js-profile-list bcc-builder-tab">

    <?php if (!$is_builder && !$intent_to_build): ?>
        <!-- State 1: Not a Builder Yet -->
        <div class="ps-card">
            <div class="ps-card__body">
                <h3><?php esc_html_e("You're not a Builder yet", 'bcc-core'); ?></h3>
                <p><?php esc_html_e('You haven’t contributed as a Builder yet. Would you like to start contributing?', 'bcc-core'); ?></p>

                <form method="post">
                    <?php wp_nonce_field('bcc_intent_to_build_action', 'bcc_intent_to_build_nonce'); ?>
                    <button type="submit" name="bcc_intent_to_build" class="ps-btn ps-btn--primary">
                        <?php esc_html_e('Yes, I want to contribute as a Builder', 'bcc-core'); ?>
                    </button>
                </form>
            </div>
        </div>

    <?php elseif (!$is_builder && $intent_to_build): ?>
        <!-- State 2: Intent to Become Builder / Incomplete Setup -->
        <div class="ps-card">
            <div class="ps-card__body">
                <h3><?php esc_html_e('Complete Your Builder Profile', 'bcc-core'); ?></h3>
                <p><?php esc_html_e('Please complete your builder profile to start contributing projects.', 'bcc-core'); ?></p>

                <!-- ACF Form Placeholder: replace "builder_form" with your ACF form key -->
                <?php
                if ( function_exists('acf_form') ) {
                    acf_form([
                        'post_id' => 'user_' . $user_id,
                        'field_groups' => ['group_6911e394534c4'], // Replace with your ACF field group key
                        'submit_value' => __('Save Builder Info', 'bcc-core'),
                        'return' => get_permalink(),
                    ]);
                }
                ?>
            </div>
        </div>

    <?php elseif ($is_builder && $can_build): ?>
        <!-- State 3: Active Builder / Full Dashboard -->
        <div class="ps-card">
            <div class="ps-card__body">
                <h3><?php esc_html_e('Your Builder Dashboard', 'bcc-core'); ?></h3>
                <p><?php esc_html_e('Access your tools, insights, and manage your projects.', 'bcc-core'); ?></p>

                <!-- Here you can load the full builder functionality, e.g., projects, stats, submission tools -->
            </div>
        </div>

    <?php endif; ?>

</div>