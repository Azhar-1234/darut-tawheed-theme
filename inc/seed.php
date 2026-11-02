<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * On first activation, create sample notices, features, branches and a primary menu
 * using the text from the reference homepage, so the front page is not empty.
 */
add_action('after_switch_theme', function () {
    flush_rewrite_rules(false);
    if (get_option('dt_seeded')) {
        return;
    }
    // CPTs are registered on init; make sure they exist during the switch request.
    foreach (['notice', 'feature'] as $pt) {
        if (!post_type_exists($pt)) {
            return; // seed on the next admin load instead
        }
    }
    dt_seed_content();
});

add_action('admin_init', function () {
    if (!get_option('dt_seeded') && get_stylesheet() === 'darut-tawheed') {
        dt_seed_content();
    }
});

function dt_seed_content() {
    update_option('dt_seeded', 1);

    $notices = [
        ['2026-06-10', 'শিক্ষার্থী পরিবহনে সিসি ক্যামেরা স্থাপন সংক্রান্ত বিজ্ঞপ্তি'],
        ['2026-05-20', 'ঈদুল আযহা উপলক্ষে ছুটি ও প্রয়োজনীয় নির্দেশনা'],
        ['2026-04-08', 'শিক্ষার্থীদের স্বাস্থ্য সুরক্ষা ও সংক্রামক রোগ প্রতিরোধ সংক্রান্ত জরুরি বিজ্ঞপ্তি'],
        ['2026-03-05', 'পবিত্র রমাদান ও ঈদুল ফিতর ২০২৬ উপলক্ষে ছুটির বিজ্ঞপ্তি'],
        ['2026-01-21', 'বার্ষিক ক্রীড়া প্রতিযোগিতা ও পুরস্কার বিতরণী অনুষ্ঠান–২০২৬'],
        ['2026-01-03', 'বই বিতরণ ও নিয়মিত পাঠদান কার্যক্রম সম্পর্কে নোটিশ'],
        ['2025-11-19', 'শিক্ষার্থী অগ্রগতির মূল্যায়ন ও করণীয়'],
        ['2025-11-15', 'ভর্তি আবেদনের সময়সীমা বৃদ্ধি প্রসঙ্গে নোটিশ'],
        ['2025-11-10', 'বার্ষিক পরীক্ষা–২০২৫ সংক্রান্ত নোটিশ'],
        ['2025-11-06', 'Entrepreneurship & Food Sharing Program-2025'],
    ];
    foreach ($notices as [$date, $title]) {
        wp_insert_post(['post_type' => 'notice', 'post_status' => 'publish', 'post_title' => $title, 'post_date' => $date . ' 10:00:00', 'post_content' => '']);
    }

    $features = [
        ['সমন্বিত কারিকুলাম', 'এখানে জেনারেল শিক্ষা ও ইসলামী শিক্ষার সমন্বয় করে পাঠদান করা হয়। দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার লক্ষ্য হলো—এমন এক প্রজন্ম গড়ে…'],
        ['হিফযুল কুরআন', 'কুরআন ও সুন্নাহর নুসূস (মূলপাঠ) আত্মস্থ করা ছাড়া বিজ্ঞ আলেম হওয়া সম্ভব নয়। এজন্য হিফযুল কুরআনকে দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার…'],
        ['ভাষাগত দক্ষতা', 'যুগচ্যালেঞ্জ মোকাবিলা ও কার্যকর দাওয়াহ পরিচালনার জন্য ভাষাগত দক্ষতা অত্যন্ত গুরুত্বপূর্ণ। এই উপলব্ধি থেকেই মাদরাসাতুস…'],
        ['নিজস্ব বই দ্বারা পাঠদান', 'দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা–র অন্যতম বৈশিষ্ট্য হলো— এখানে Islamic Studies বিষয়টি পাঠদান করা হয় প্রতিষ্ঠানের নিজস্বভাবে প্রণীত…'],
        ['শিক্ষায় আধুনিক প্রযুক্তির ব্যবহার', 'দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা শিক্ষাব্যবস্থায় আধুনিক প্রযুক্তির সুচিন্তিত ও কার্যকর প্রয়োগ করে থাকে।এখানে পাঠদান পরিচালিত হয়…'],
        ['রুট পরিবর্তনের সুযোগ', 'দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার সিলেবাস এমনভাবে সাজানো হয়েছে, যাতে একজন শিক্ষার্থী মাধ্যমিক স্তরের শিক্ষা সম্পন্ন করার পাশাপাশি…'],
        ['আখলাক ও মূল্যবোধ', 'ইসলামের মৌলিক শিক্ষাসমূহের অন্যতম হলো অর্জিত জ্ঞান অনুযায়ী আমলি জীবন গড়ে তোলা, শিষ্টাচার ও মূল্যবোধ লালন করা।'],
        ['হালাকা ও নাসীহা প্রোগ্রাম', 'একজন শিক্ষার্থীর শিক্ষাগত যোগ্যতা এবং চারিত্রিক মানোন্নয়নে শিক্ষক-অভিভাবকের সম্মিলিত প্রচেষ্টা ব্যতীত সম্ভব নয়। তাই…'],
        ['অভিভাবকদের দীনি শিক্ষা কার্যক্রম', 'সন্তান মা-বাবার নিকট আল্লাহ তাআলার পক্ষ থেকে আমানত। শিশুর প্রথম পাঠশালা হচ্ছে মায়ের কোল। এখানে সে জীবনের অনেক কিছু…'],
    ];
    foreach ($features as $i => [$title, $excerpt]) {
        wp_insert_post(['post_type' => 'feature', 'post_status' => 'publish', 'post_title' => $title, 'post_excerpt' => $excerpt, 'post_content' => $excerpt, 'menu_order' => $i + 1]);
    }

    // Primary menu with custom links; edit under Appearance > Menus.
    $menu_id = wp_create_nav_menu('Primary');
    if (!is_wp_error($menu_id)) {
        $add = function ($title, $url, $parent = 0) use ($menu_id) {
            return wp_update_nav_menu_item($menu_id, 0, ['menu-item-title' => $title, 'menu-item-url' => $url, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $parent]);
        };
        $add('হোম', home_url('/'));
        $groups = [
            'আমাদের সম্পর্কে' => ['চেয়ারম্যানের বার্তা' => 'message-chairman', 'লক্ষ্য ও উদ্দেশ্য' => 'mission-vision'],
            'ক্যাম্পাস'       => ['বালিকা শাখা' => 'branch/balika', 'বালক শাখা' => 'branch/balok'],
            'একাডেমিক'       => ['কারিকুলাম রূপরেখা' => 'syllabus', 'একাডেমিক ক্যালেন্ডার' => 'academic-calendar', 'সম্মানিত শিক্ষকবৃন্দ' => 'all-teacher'],
            'ভর্তি'           => ['প্রসপেক্টাস' => 'prospectus'],
            'গ্যালারী'        => ['ছবি' => 'gallery', 'ভিডিও' => 'gallery-video'],
        ];
        foreach ($groups as $label => $items) {
            $parent = $add($label, '#');
            foreach ($items as $text => $slug) {
                $add($text, $slug[0] === '#' ? home_url('/') . $slug : home_url('/' . $slug . '/'), $parent);
            }
        }
        foreach (['যোগাযোগ' => 'contact', 'FAQ' => 'faq', 'নোটিশ' => 'notice'] as $text => $slug) {
            $add($text, home_url('/' . $slug . '/'));
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Static front page is not needed: front-page.php is used automatically.
}
