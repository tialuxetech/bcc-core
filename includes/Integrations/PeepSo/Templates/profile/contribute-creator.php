<?php
defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;

$can_create       = current_user_can('bcc_create_nft');
$is_creator       = in_array('bcc_creator', (array) $current_user->roles, true);
$intent_to_create = get_user_meta($user_id, 'bcc_user_intends_to_create', true);

if (
    isset($_POST['bcc_intent_to_create']) &&
    wp_verify_nonce($_POST['bcc_intent_to_create_nonce'], 'bcc_intent_to_create_action')
) {
    update_user_meta($user_id, 'bcc_user_intends_to_create', true);
    wp_safe_redirect( esc_url_raw( wp_unslash($_SERVER['REQUEST_URI']) ) );
    exit;
}
?>

<div class="ps-profile__about-fields ps-js-profile-list bcc-creator-tab">

<?php if (!$is_creator && !$intent_to_create): ?>

    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e("You're not a Creator yet", 'bcc-core'); ?></h3>
            <p><?php esc_html_e(
                'Creators design and publish NFTs that power identity and reputation.',
                'bcc-core'
            ); ?></p>

            <form method="post">
                <?php wp_nonce_field('bcc_intent_to_create_action', 'bcc_intent_to_create_nonce'); ?>
                <button type="submit" name="bcc_intent_to_create" class="ps-btn ps-btn--primary">
                    <?php esc_html_e('I want to contribute as a Creator', 'bcc-core'); ?>
                </button>
            </form>
        </div>
    </div>

<?php elseif (!$is_creator && $intent_to_create): ?>

    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e('Complete Your Creator Setup', 'bcc-core'); ?></h3>

        <div class="bcc-acf-form bcc-acf-form--builder">
            <?php
            if ( function_exists('acf_form') ) {
                acf_form([
                    'post_id'      => 'user_' . $user_id,
                    'field_groups' => ['group_6911e394534c4'],
                    'submit_value' => __('Save Creator Info', 'bcc-core'),
                    'return'       => esc_url_raw( wp_unslash($_SERVER['REQUEST_URI']) ),
                ]);
            }
            ?>
        </div>    
            
        </div>
    </div>

<?php elseif ($is_creator && $can_create): ?>

    <div class="ps-card">
        <div class="ps-card__body">
            <h3><?php esc_html_e('Creator Dashboard', 'bcc-core'); ?></h3>
        </div>
    </div>

<?php endif; ?>

</div>
