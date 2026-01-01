<?php
defined('ABSPATH') || exit;

use PeepSoProfileShortcode;

// --------------------------------------------------
// PROFILE CONTEXT
// --------------------------------------------------
$profile = PeepSoProfileShortcode::get_instance();

$profile_user_id = $profile->get_view_user_id();
$viewer_user_id  = get_current_user_id();

$profile_user = get_user_by('id', $profile_user_id);

if (!$profile_user instanceof WP_User) {
    echo '<p>' . esc_html__('User not found.', 'bcc-core') . '</p>';
    return;
}

// Variables injected from Profile_Tabs::render_contribute()
// $lanes
// $active_tab
?>

<div class="peepso">
    <div class="ps-page ps-page--profile ps-page--profile-about">

        <?php PeepSoTemplate::exec_template('general', 'navbar'); ?>

        <div class="ps-profile ps-profile--edit ps-profile--about">

            <?php
            // Highlight the main "Contribute" tab in profile navigation
            PeepSoTemplate::exec_template(
                'profile',
                'focus',
                ['current' => 'contribute']
            );
            ?>

            <div class="ps-profile__edit">

                <!-- INTERNAL SUB-TABS (PeepSo-style) -->
                <div class="ps-profile__edit-tabs">
                    <?php foreach ($lanes as $slug => $lane): ?>
                        <a
                            href="<?php echo esc_attr('contribute-' . $slug); ?>"
                            class="ps-profile__edit-tab <?php echo $active_tab === $slug ? 'active' : ''; ?>"
                        >
                            <i class="<?php echo esc_attr($lane['icon']); ?>"></i>
                            <?php echo esc_html($lane['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- ACTIVE TAB CONTENT -->
                <div class="ps-profile__edit-tab ps-profile__edit-tab--about">
                    <div class="ps-profile__about">

                        <?php
                        $lane_template = __DIR__ . '/contribute-' . $active_tab . '.php';

                        if (file_exists($lane_template)) {
                            include $lane_template;
                        } else {
                            echo '<p>' . esc_html__('This section is under construction.', 'bcc-core') . '</p>';
                        }
                        ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>