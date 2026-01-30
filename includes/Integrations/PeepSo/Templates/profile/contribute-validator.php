<?php
defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;

$can_validate       = current_user_can('bcc_validate_submission');
$is_validator       = in_array('bcc_validator', (array) $current_user->roles, true);
$intent_to_validate = get_user_meta($user_id, 'bcc_user_intends_to_validate', true);

if (
    isset($_POST['bcc_intent_to_validate']) &&
    wp_verify_nonce($_POST['bcc_intent_to_validate_nonce'], 'bcc_intent_to_validate_action')
) {
    update_user_meta($user_id, 'bcc_user_intends_to_validate', true);
    wp_safe_redirect( esc_url_raw( wp_unslash($_SERVER['REQUEST_URI']) ) );
    exit;
}
?>

<div class="ps-profile__about-fields ps-js-profile-list bcc-validator-tab">

<?php if (!$is_validator && !$intent_to_validate): ?>

    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e("You're not a Validator yet", 'bcc-core'); ?></h3>
            <p><?php esc_html_e(
                'Validators help maintain quality and trust across the ecosystem.',
                'bcc-core'
            ); ?></p>

            <form method="post">
                <?php wp_nonce_field('bcc_intent_to_validate_action', 'bcc_intent_to_validate_nonce'); ?>
                <button type="submit" name="bcc_intent_to_validate" class="ps-btn ps-btn--primary">
                    <?php esc_html_e('I want to contribute as a Validator', 'bcc-core'); ?>
                </button>
            </form>
        </div>
    </div>

<?php elseif (!$is_validator && $intent_to_validate): ?>

    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e('Complete Your Validator Setup', 'bcc-core'); ?></h3>


        <div class="bcc-acf-form bcc-acf-form--builder">
            <?php
            if ( function_exists('acf_form') ) {
                acf_form([
                    'post_id'      => 'user_' . $user_id,
                    'field_groups' => ['group_6911e394534c4'],
                    'submit_value' => __('Save Validator Info', 'bcc-core'),
                    'return'       => esc_url_raw( wp_unslash($_SERVER['REQUEST_URI']) ),
                ]);
            }
            ?>
        </div>
        
        </div>
    </div>

<?php elseif ($is_validator && $can_validate): ?>

    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e('Validator Dashboard', 'bcc-core'); ?></h3>
        </div>
    </div>

<?php endif; ?>

</div>
