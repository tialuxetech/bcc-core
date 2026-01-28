<?php
defined('ABSPATH') || exit;

use PeepSoProfileShortcode;
use BCC\Integrations\PeepSo\Helpers\Profile_Helper;

// --------------------------------------------------
// PROFILE CONTEXT
// --------------------------------------------------
$profile = PeepSoProfileShortcode::get_instance();
$profile_user_id = $profile->get_view_user_id();

if (!$profile_user_id) {
    echo '<p>' . esc_html__('Invalid profile context.', 'bcc-core') . '</p>';
    return;
}

$profile_user = get_user_by('id', $profile_user_id);

if (!$profile_user instanceof WP_User) {
    echo '<p>' . esc_html__('User not found.', 'bcc-core') . '</p>';
    return;
}

// --------------------------------------------------
// BASE URL (via helper — authoritative)
// --------------------------------------------------
$contribute_base = Profile_Helper::get_contribute_base_url();

if (!$contribute_base) {
    echo '<p>' . esc_html__('Unable to load contribute section.', 'bcc-core') . '</p>';
    return;
}

// --------------------------------------------------
// INTERNAL ROUTING
// --------------------------------------------------
$request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts       = explode('/', $request_uri);

$section = 'dashboard';

foreach ($parts as $index => $part) {
    if ($part === 'contribute' && isset($parts[$index + 1])) {
        $section = sanitize_key($parts[$index + 1]);
        break;
    }
}

// Allowed internal sections
$sections = [
    'dashboard' => [
        'label' => __('Overview', 'bcc-core'),
        'icon'  => 'pso-i-rectangle-list',
        'file'  => 'contribute-overview.php',
    ],
    'build' => [
        'label' => __('Build', 'bcc-core'),
        'icon'  => 'pso-i-user-pen',
        'file'  => 'contribute-builder.php',
    ],
    'validate' => [
        'label' => __('Validate', 'bcc-core'),
        'icon'  => 'pso-i-check-circle',
        'file'  => 'contribute-validator.php',
    ],
    'create' => [
        'label' => __('Create', 'bcc-core'),
        'icon'  => 'pso-i-pen-field',
        'file'  => 'contribute-creator.php',
    ],
];

// Fallback
if (!isset($sections[$section])) {
    $section = 'dashboard';
}
?>

<div class="peepso">
    <div class="ps-page ps-page--profile ps-page--profile-about">

        <?php PeepSoTemplate::exec_template('general', 'navbar'); ?>

        <div class="ps-profile ps-profile--edit ps-profile--about">

            <?php
            PeepSoTemplate::exec_template(
                'profile',
                'focus',
                ['current' => 'contribute']
            );
            ?>

            <div class="ps-profile__edit">

                <!-- INTERNAL SUBMENU -->
                <div class="ps-tabs ps-tabs--center ps-profile__edit-tabs">
                    <?php foreach ($sections as $slug => $data): ?>
                        <div class="ps-tabs__item <?php echo $slug === $section ? 'ps-tabs__item--active' : ''; ?>">
                            <a href="<?php echo esc_url(
                                $slug === 'dashboard'
                                    ? $contribute_base
                                    : $contribute_base . $slug
                            ); ?>">
                                <i class="<?php echo esc_attr($data['icon']); ?>"></i>
                                <?php echo esc_html($data['label']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ACTIVE SECTION CONTENT -->
                <div class="ps-profile__edit-tab ps-profile__edit-tab--about">
                    <div class="ps-profile__content">

                        <?php
                        $section_file = __DIR__ . '/' . $sections[$section]['file'];

                        if (file_exists($section_file)) {
                            include $section_file;
                        } else {
                            echo '<p>' . esc_html__('Section under construction.', 'bcc-core') . '</p>';
                        }
                        ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>