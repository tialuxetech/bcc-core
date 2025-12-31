<?php
defined( 'ABSPATH' ) || exit;

use PeepSoProfileShortcode;

$profile = PeepSoProfileShortcode::get_instance();

$profile_user_id = $profile->get_view_user_id();
$viewer_user_id  = get_current_user_id();

$profile_user = get_user_by( 'id', $profile_user_id );

if ( ! $profile_user instanceof WP_User ) {
    echo '<p>' . esc_html__( 'User not found.', 'bcc-core' ) . '</p>';
    return;
}

$is_builder = in_array( 'bcc_builder', (array) $profile_user->roles, true );
?>

<div class="bcc-contribute bcc-contribute-builder">

<?php if ( $is_builder ) : ?>

    <h2><?php esc_html_e( 'Builder Contribution', 'bcc-core' ); ?></h2>

    <p>
        <?php esc_html_e(
            'This member contributes to the ecosystem by building tools, features, and infrastructure.',
            'bcc-core'
        ); ?>
    </p>

    <?php
    /**
     * Hook: bcc_builder_profile_content
     *
     * Future Builder dashboards, stats, tools will attach here.
     */
    do_action( 'bcc_builder_profile_content', $profile_user_id, $viewer_user_id );
    ?>

<?php else : ?>

    <h2><?php esc_html_e( 'Builder Access Required', 'bcc-core' ); ?></h2>

    <p>
        <?php esc_html_e(
            'This user is not registered as a Builder. Builder tools and contributions are restricted to approved members.',
            'bcc-core'
        ); ?>
    </p>

<?php endif; ?>

</div>
