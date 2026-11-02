<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * One-time rename of the reference institution's name in content already saved
 * in the database (options, notices, features, branches).
 */
add_action('init', function () {
    if (get_option('dt_migrated_name') || !post_type_exists('notice')) {
        return;
    }
    update_option('dt_migrated_name', 1);

    $from = 'মাদরাসাতুস সুন্নাহ';
    $to   = 'দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা';

    $replace = function ($v) use (&$replace, $from, $to) {
        if (is_array($v)) {
            return array_map($replace, $v);
        }
        return is_string($v) ? str_replace($from, $to, $v) : $v;
    };

    $opts = get_option('dt_options');
    if (is_array($opts)) {
        update_option('dt_options', $replace($opts));
    }

    $posts = get_posts(['post_type' => ['notice', 'feature'], 'post_status' => 'any', 'numberposts' => -1]);
    foreach ($posts as $p) {
        wp_update_post(wp_slash([
            'ID'           => $p->ID,
            'post_title'   => $replace($p->post_title),
            'post_content' => $replace($p->post_content),
            'post_excerpt' => $replace($p->post_excerpt),
        ]));
    }
});

/** Create the Chairman message and Mission & vision pages (templates are picked by slug). */
add_action('init', function () {
    if (get_option('dt_pages_created')) {
        return;
    }
    update_option('dt_pages_created', 1);
    foreach (['message-chairman' => 'চেয়ারম্যানের বার্তা', 'mission-vision' => 'লক্ষ্য ও উদ্দেশ্য'] as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug]);
        }
    }
});

/** Create the academic pages (templates are picked by slug). */
add_action('init', function () {
    if (get_option('dt_pages_academic')) {
        return;
    }
    update_option('dt_pages_academic', 1);
    foreach (['syllabus' => 'কারিকুলাম রূপরেখা', 'academic-calendar' => 'একাডেমিক ক্যালেন্ডার', 'all-teacher' => 'সম্মানিত শিক্ষকবৃন্দ'] as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug]);
        }
    }
});

/** Update saved curriculum cards that still hold the earlier sample content. */
add_action('init', function () {
    if (get_option('dt_migrated_levels')) {
        return;
    }
    update_option('dt_migrated_levels', 1);
    $opts = get_option('dt_options');
    if (!is_array($opts)) {
        return;
    }
    if (isset($opts['syllabus_c1_body']) && strpos($opts['syllabus_c1_body'], 'প্লে (৩') !== false) {
        $opts['syllabus_c1_body'] = dt_default_syllabus(1);
    }
    if (isset($opts['syllabus_c3_body']) && strpos($opts['syllabus_c3_body'], 'দশম শ্রেণির পর') !== false) {
        $opts['syllabus_c3_body'] = dt_default_syllabus(3);
    }
    update_option('dt_options', $opts);
});

/** Turn the earlier three fixed curriculum cards into the repeatable card list. */
add_action('init', function () {
    if (get_option('dt_migrated_cards')) {
        return;
    }
    update_option('dt_migrated_cards', 1);
    $opts = get_option('dt_options');
    if (!is_array($opts) || isset($opts['syllabus_cards']) || !array_key_exists('syllabus_c1_body', $opts) && !array_key_exists('syllabus_c2_body', $opts) && !array_key_exists('syllabus_c3_body', $opts)) {
        return;
    }
    $defaults = dt_default_syllabus_cards();
    $cards = [];
    foreach ([1, 2, 3] as $n) {
        if ($n === 3 && array_key_exists('syllabus_show_root', $opts) && empty($opts['syllabus_show_root'])) {
            continue;
        }
        $title = $opts["syllabus_c{$n}_title"] ?? $defaults[$n - 1]['title'];
        $body  = $opts["syllabus_c{$n}_body"] ?? $defaults[$n - 1]['body'];
        if ($n === 2) {
            $body = preg_replace('#<li><strong>হিফযুল কুরআন:</strong>[^<]*</li>#u', '', $body);
        }
        if (trim(wp_strip_all_tags($body)) !== '') {
            $cards[] = ['title' => $title, 'body' => $body];
        }
    }
    $opts['syllabus_cards'] = $cards;
    foreach (['syllabus_c1_title', 'syllabus_c1_body', 'syllabus_c2_title', 'syllabus_c2_body', 'syllabus_c3_title', 'syllabus_c3_body', 'syllabus_show_root'] as $k) {
        unset($opts[$k]);
    }
    update_option('dt_options', $opts);
});

/** Create the Prospectus page. */
add_action('init', function () {
    if (get_option('dt_pages_prospectus')) {
        return;
    }
    update_option('dt_pages_prospectus', 1);
    if (!get_page_by_path('prospectus')) {
        wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'প্রসপেক্টাস', 'post_name' => 'prospectus']);
    }
});

/** Remove the admission procedure, outline and fee chart links from the saved menus. */
add_action('init', function () {
    if (get_option('dt_menu_admission_trimmed')) {
        return;
    }
    update_option('dt_menu_admission_trimmed', 1);
    $slugs = ['admission-procedure', 'admission-outline', 'admission_outline', 'fees'];
    foreach (get_terms(['taxonomy' => 'nav_menu', 'hide_empty' => false]) as $menu) {
        foreach ((array) wp_get_nav_menu_items($menu->term_id) as $item) {
            $path = trim((string) wp_parse_url($item->url, PHP_URL_PATH), '/');
            if (in_array(basename($path), $slugs, true)) {
                wp_delete_post($item->ID, true);
            }
        }
    }
});

/** Create the gallery pages. */
add_action('init', function () {
    if (get_option('dt_pages_gallery')) {
        return;
    }
    update_option('dt_pages_gallery', 1);
    foreach (['gallery' => 'গ্যালারী', 'gallery-video' => 'ভিডিও গ্যালারী'] as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug]);
        }
    }
});

/** Remove the Career link from the saved menus. */
add_action('init', function () {
    if (get_option('dt_menu_career_removed')) {
        return;
    }
    update_option('dt_menu_career_removed', 1);
    foreach (get_terms(['taxonomy' => 'nav_menu', 'hide_empty' => false]) as $menu) {
        foreach ((array) wp_get_nav_menu_items($menu->term_id) as $item) {
            if (basename(trim((string) wp_parse_url($item->url, PHP_URL_PATH), '/')) === 'career') {
                wp_delete_post($item->ID, true);
            }
        }
    }
});

/** Create the Contact and FAQ pages. */
add_action('init', function () {
    if (get_option('dt_pages_contact_faq')) {
        return;
    }
    update_option('dt_pages_contact_faq', 1);
    foreach (['contact' => 'যোগাযোগ', 'faq' => 'প্রশ্নোত্তর (FAQ)'] as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug]);
        }
    }
});

/** Create the Branches page. */
add_action('init', function () {
    if (get_option('dt_pages_branch')) {
        return;
    }
    update_option('dt_pages_branch', 1);
    if (!get_page_by_path('branch')) {
        wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'শাখাসমূহ', 'post_name' => 'branch']);
    }
});

/** Point the "শাখাসমূহ" menu link at /branch/ and add each branch under "ক্যাম্পাস" in saved menus. */
add_action('init', function () {
    if (get_option('dt_menu_branches')) {
        return;
    }
    update_option('dt_menu_branches', 1);
    foreach (get_terms(['taxonomy' => 'nav_menu', 'hide_empty' => false]) as $menu) {
        $items = (array) wp_get_nav_menu_items($menu->term_id);
        $parent = 0;
        foreach ($items as $item) {
            if ($item->title === 'ক্যাম্পাস') {
                $parent = (int) $item->ID;
            }
        }
        if (!$parent) {
            continue;
        }
        $have = [];
        foreach ($items as $item) {
            if ((int) $item->menu_item_parent === $parent) {
                $have[] = $item;
            }
        }
        foreach ($have as $item) {
            if ($item->title === 'শাখাসমূহ') {
                wp_update_nav_menu_item($menu->term_id, $item->ID, ['menu-item-title' => 'শাখাসমূহ', 'menu-item-url' => home_url('/branch/'), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $parent]);
            }
        }
        $titles = wp_list_pluck($have, 'title');
        foreach (dt_branches() as $slug => $b) {
            if (!in_array($b['name'], $titles, true)) {
                wp_update_nav_menu_item($menu->term_id, 0, ['menu-item-title' => $b['name'], 'menu-item-url' => home_url('/branch/' . $slug . '/'), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $parent]);
            }
        }
    }
});

/** Remove the "শাখাসমূহ" link under "ক্যাম্পাস" from saved menus; the branches are listed directly. */
add_action('init', function () {
    if (get_option('dt_menu_branches_list_removed')) {
        return;
    }
    update_option('dt_menu_branches_list_removed', 1);
    foreach (get_terms(['taxonomy' => 'nav_menu', 'hide_empty' => false]) as $menu) {
        $items = (array) wp_get_nav_menu_items($menu->term_id);
        $parents = [];
        foreach ($items as $item) {
            if ($item->title === 'ক্যাম্পাস') {
                $parents[] = (int) $item->ID;
            }
        }
        foreach ($items as $item) {
            if ($item->title === 'শাখাসমূহ' && in_array((int) $item->menu_item_parent, $parents, true)) {
                wp_delete_post($item->ID, true);
            }
        }
    }
});

/** Rename the Dhaka/Cumilla branch menu links to the Feni boys' and girls' branches. */
add_action('init', function () {
    if (get_option('dt_menu_branches_feni')) {
        return;
    }
    update_option('dt_menu_branches_feni', 1);
    $map = ['dhaka' => ['বালিকা শাখা', 'balika'], 'cumilla' => ['বালক শাখা', 'balok']];
    foreach (get_terms(['taxonomy' => 'nav_menu', 'hide_empty' => false]) as $menu) {
        foreach ((array) wp_get_nav_menu_items($menu->term_id) as $item) {
            $path = trim((string) wp_parse_url($item->url, PHP_URL_PATH), '/');
            if (preg_match('#(^|/)branch/(dhaka|cumilla)$#', $path, $m)) {
                [$title, $slug] = $map[$m[2]];
                wp_update_nav_menu_item($menu->term_id, $item->ID, ['menu-item-title' => $title, 'menu-item-url' => home_url('/branch/' . $slug . '/'), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => (int) $item->menu_item_parent]);
            }
        }
    }
});

/** Create the online admission form page. */
add_action('init', function () {
    if (get_option('dt_pages_pre_admission')) {
        return;
    }
    update_option('dt_pages_pre_admission', 1);
    if (!get_page_by_path('pre-admission')) {
        wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'প্রি-এডমিশন', 'post_name' => 'pre-admission']);
    }
});
