<?php
if (!defined('ABSPATH')) {
    exit;
}

/** Build the JSON payload the React homepage renders from. */
function dt_home_payload() {
    $slides = [];
    foreach ((array) dt_opt('slides') as $row) {
        // Older saves stored rows with an "image" key; the gallery field stores plain IDs.
        $id = is_array($row) ? (int) ($row['image'] ?? 0) : (int) $row;
        $img = $id ? wp_get_attachment_image_src($id, 'full') : false;
        if ($img) {
            $slides[] = ['src' => $img[0], 'srcset' => wp_get_attachment_image_srcset($id, 'full') ?: '', 'w' => (int) $img[1], 'h' => (int) $img[2], 'alt' => (string) get_post_meta($id, '_wp_attachment_image_alt', true)];
        }
    }

    if (!$slides) {
        // Until slides are added in Theme Options, show the bundled banner.
        $slides[] = ['src' => get_template_directory_uri() . '/assets/img/default-slide.png', 'srcset' => '', 'w' => 960, 'h' => 421, 'alt' => get_bloginfo('name')];
    }

    $notices = [];
    $q = new WP_Query(['post_type' => 'notice', 'posts_per_page' => max(1, (int) dt_opt('notice_count')), 'no_found_rows' => true]);
    foreach ($q->posts as $p) {
        $notices[] = ['title' => get_the_title($p), 'url' => get_permalink($p), 'date' => dt_bn_date(get_post_time('U', false, $p)), 'iso' => get_the_date('c', $p)];
    }

    $features = [];
    $q = new WP_Query(['post_type' => 'feature', 'posts_per_page' => max(1, (int) dt_opt('features_count')), 'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'], 'no_found_rows' => true]);
    foreach ($q->posts as $i => $p) {
        $features[] = ['num' => dt_bn(sprintf('%02d', $i + 1)), 'title' => get_the_title($p), 'excerpt' => wp_trim_words(get_the_excerpt($p), 22, '…'), 'url' => get_permalink($p)];
    }

    $facilities = [];
    foreach ((array) dt_opt('facilities') as $f) {
        if (empty($f['title'])) {
            continue;
        }
        $facilities[] = [
            'title' => $f['title'],
            'text'  => $f['short_text'] ?? '',
            'image' => !empty($f['image']) ? (string) wp_get_attachment_image_url((int) $f['image'], 'thumbnail') : '',
            'icon'  => preg_replace('/[^a-z0-9\- ]/i', '', $f['icon'] ?? ''),
        ];
    }

    $branches = [];
    foreach (dt_branches() as $slug => $b) {
        $branches[] = ['title' => $b['name'], 'address' => $b['address'] ?? '', 'url' => home_url('/branch/' . $slug . '/'), 'contacts' => dt_branch_contacts($b)];
    }

    $sent = isset($_GET['dt_sent']) ? sanitize_key(wp_unslash($_GET['dt_sent'])) : '';

    return [
        'sections' => dt_home_sections(),
        'slides'   => $slides,
        'notices'  => ['title' => dt_opt('notices_title'), 'items' => $notices, 'allText' => dt_opt('notice_all_text'), 'allUrl' => get_post_type_archive_link('notice')],
        'about'    => ['bg' => dt_img(dt_opt('about_image'), 'large'), 'badge' => dt_opt('established_year'), 'title' => dt_opt('about_title'), 'html' => wp_kses_post(wpautop(dt_opt('about_text')))],
        'features' => ['title' => dt_opt('features_title'), 'moreText' => dt_opt('features_more'), 'items' => $features],
        'facilities' => ['title' => dt_opt('facilities_title'), 'items' => $facilities, 'moreText' => dt_opt('facilities_more_text'), 'moreUrl' => dt_opt('facilities_more_link') ?: home_url('/facility/')],
        'chairman' => ['title' => dt_opt('chairman_title'), 'photo' => dt_img(dt_opt('chairman_photo'), 'medium'), 'quote' => dt_opt('chairman_quote'), 'name' => dt_opt('chairman_name'), 'designation' => dt_opt('chairman_designation')],
        'contact'  => [
            'title' => dt_opt('contact_title'), 'phone' => dt_opt('phone'), 'phoneHref' => dt_tel(dt_opt('phone')), 'whatsapp' => dt_opt('whatsapp'), 'waHref' => 'https://wa.me/' . ltrim(dt_tel(dt_opt('whatsapp')), '+'), 'email' => dt_opt('email'), 'hours' => dt_opt('office_hours'),
            'address' => dt_opt('address'), 'mapLink' => dt_opt('map_link'), 'mapEmbed' => dt_kses_iframe(dt_opt('map_embed')),
            'mapSrc' => 'https://www.google.com/maps?output=embed&q=' . rawurlencode(dt_opt('map_query') ?: dt_opt('address')),
            'sent' => $sent, 'action' => admin_url('admin-post.php'), 'nonce' => wp_create_nonce('dt_feedback'),
        ],
        'branches' => ['title' => dt_opt('branches_title'), 'items' => $branches],
    ];
}
