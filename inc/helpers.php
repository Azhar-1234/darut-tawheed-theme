<?php
if (!defined('ABSPATH')) {
    exit;
}

/** Digits-only (plus leading +) form of a phone number, for tel:/wa.me links. */
function dt_tel($n) {
    return preg_replace('/[^0-9+]/', '', (string) $n);
}

/**
 * Contact rows [label, info, href, icon] for a branch. A branch without its own phone or email
 * shows the site-wide ones from Theme Options.
 */
function dt_branch_contacts($b) {
    $rows = [];
    foreach (preg_split('/\r\n|\r|\n/', (string) ($b['phones'] ?? '')) as $line) {
        [$num, $label] = array_map('trim', array_pad(explode('|', $line, 2), 2, ''));
        if ($num !== '') {
            $rows[] = [$label, $num];
        }
    }
    if (!$rows) {
        $rows[] = ['', dt_opt('phone')];
    }
    $rows[] = ['', trim((string) ($b['email'] ?? '')) ?: dt_opt('email')];
    $out = [];
    foreach ($rows as [$l, $i]) {
        if ($i === '' || $i === null) {
            continue;
        }
        $mail = is_email($i);
        $out[] = [$l, $i, $mail ? 'mailto:' . $i : 'tel:' . dt_tel($i), $mail ? 'fa-envelope' : 'fa-phone'];
    }
    return $out;
}

/** Class rows [name, age] from a branch's "class | age" lines. */
function dt_branch_classes($b) {
    $out = [];
    foreach (preg_split('/\r\n|\r|\n/', (string) ($b['classes'] ?? '')) as $line) {
        [$name, $age] = array_map('trim', array_pad(explode('|', $line, 2), 2, ''));
        if ($name !== '') {
            $out[] = [$name, $age];
        }
    }
    return $out;
}

/** Images for a branch's "slides" or "gallery": the selected media, or the bundled live-site photos. */
function dt_branch_images($b, $key) {
    $out = [];
    foreach ((array) ($b[$key] ?? []) as $id) {
        $id = is_array($id) ? (int) ($id['image'] ?? 0) : (int) $id;
        $full = $id ? wp_get_attachment_image_url($id, 'full') : '';
        if ($full) {
            $out[] = ['full' => $full, 'src' => wp_get_attachment_image_url($id, 'large') ?: $full, 'alt' => (string) get_post_meta($id, '_wp_attachment_image_alt', true)];
        }
    }
    if ($out) {
        return $out;
    }
    $files = $key === 'slides' ? ['banner.webp', 'event.webp'] : ['event.webp', 'p1.webp', 'p2.webp', 'p3.webp', 'p4.webp', 'p5.webp', 'p6.webp'];
    foreach ($files as $f) {
        $u = get_template_directory_uri() . '/assets/img/branch/feni/' . $f;
        $out[] = ['full' => $u, 'src' => $u, 'alt' => (string) ($b['name'] ?? '')];
    }
    return $out;
}

/** URL slug of a branch: the saved slug, or one derived from its position. */
function dt_branch_slug($b, $n) {
    return sanitize_title($b['slug'] ?? '') ?: 'branch-' . ($n + 1);
}

/** Branches with a name, keyed by slug. */
function dt_branches() {
    $out = [];
    foreach (array_values((array) dt_opt('branches')) as $n => $b) {
        if (!empty($b['name'])) {
            $out[dt_branch_slug($b, $n)] = $b;
        }
    }
    return $out;
}

/** Convert western digits to Bengali digits. */
function dt_bn($str) {
    return strtr((string) $str, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
}

/** Format a timestamp like "১০ জুন, ২০২৬". */
function dt_bn_date($ts) {
    $months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
    return dt_bn(wp_date('d', $ts)) . ' ' . $months[(int) wp_date('n', $ts) - 1] . ', ' . dt_bn(wp_date('Y', $ts));
}

/** Home page sections: slug => default label. Template: template-parts/home/{slug}.php */
function dt_home_section_labels() {
    return [
        'slider'        => 'Slider',
        'notice_ticker' => 'গুরুত্বপূর্ণ আপডেট (Notices)',
        'about'         => 'কেন দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা (About)',
        'chairman_video' => 'চেয়ারম্যানের ভিডিও বার্তা (Video)',
        'features'      => 'যে কারণে অনন্য (Features)',
        'facilities'    => 'সুযোগ-সুবিধা (Facilities)',
        'chairman'      => 'চেয়ারম্যানের বাণী',
        'contact'       => 'যোগাযোগ',
        'branches'      => 'শাখাসমূহ',
    ];
}

/** Enabled home sections in saved order. */
function dt_home_sections() {
    $saved = dt_opt('home_sections');
    $slugs = array_keys(dt_home_section_labels());
    $out = [];
    foreach ($slugs as $i => $slug) {
        $row = is_array($saved) && isset($saved[$slug]) ? $saved[$slug] : ['enabled' => 1, 'order' => $i + 1];
        if (!empty($row['enabled'])) {
            $out[$slug] = (int) ($row['order'] ?? $i + 1);
        }
    }
    asort($out);
    return array_keys($out);
}

/** Option getter: saved value wins, otherwise the default from the field definitions. */
function dt_opt($key) {
    static $saved = null, $defaults = null;
    if ($saved === null) {
        $saved = get_option('dt_options', []);
        $saved = is_array($saved) ? $saved : [];
        $defaults = dt_option_defaults();
    }
    // Saved values that still equal an earlier default fall through to the current default.
    static $legacy = [
        'fb_page_url' => ['https://www.facebook.com/madrasatussunnah.org'],
        'facebook_url' => ['https://www.facebook.com/madrasatussunnah.org'],
        'phone'       => ['+8809639151525'],
        'email'       => ['info@madrasatussunnah.org'],
        'address'     => ['দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা, সাতারকুল, বাড্ডা, ঢাকা।', 'মাদরাসাতুস সুন্নাহ, সাতারকুল, বাড্ডা, ঢাকা।'],
        'map_link'    => ['https://maps.app.goo.gl/fwWmibihFVsmPyZMA'],
        'established_year'     => ['প্রতিষ্ঠা : ২০২৩ সাল'],
        'chairman_name'        => ['শায়খ আহমাদুল্লাহ'],
        'chairman_designation' => ['চেয়ারম্যান, আস-সুন্নাহ ফাউন্ডেশন', 'দারুত তাওহীদ আল মাদানিয়্যাহ মাদ্রাসা', 'চেয়ারম্যান, দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা'],
        'chairman_name' => ['শায়খ আহমাদুল্লাহ'],
        'branches_title' => ['আমাদের শাখাসমূহ'],
        'branches'    => [[
            ['name' => 'প্রধান শাখা', 'address' => 'মাদরাসাতুস সুন্নাহ, প্লট-৫, রোড-৪, ব্লক-সি, সানভ্যালি আবাসন, স্বদেশ প্রোপার্টিজ, সাঁতারকুল, বাড্ডা, ঢাকা-১২১২', 'contact_label' => '', 'contact_info' => ''],
            ['name' => 'কুমিল্লা শাখা', 'address' => 'ছোটশালঘর (বাস স্ট্যান্ড), দেবিদ্বার, কুমিল্লা।', 'contact_label' => '', 'contact_info' => ''],
        ], [
            ['name' => 'বালিকা শাখা', 'address' => 'গ্রীনভিউ ১৩০, নাজির রোড-১, ফেনী টাউন, বাংলাদেশ', 'contact_label' => '', 'contact_info' => ''],
            ['name' => 'বালক শাখা', 'address' => 'ড্যাফোডিল ভিলা, সেলিনা পারভিন সড়ক (নাজির রোড), ফেনী টাউন, বাংলাদেশ', 'contact_label' => '', 'contact_info' => ''],
        ], [
            ['name' => 'প্রধান শাখা', 'slug' => 'dhaka', 'address' => 'মাদরাসাতুস সুন্নাহ, প্লট-৫, রোড-৪, ব্লক-সি, সানভ্যালি আবাসন, স্বদেশ প্রোপার্টিজ, সাঁতারকুল, বাড্ডা, ঢাকা-১২১২', 'phones' => '+8809639151525 | অফিস', 'email' => 'info@madrasatussunnah.org', 'classes' => "প্লে | ৩-৪\nনার্সারি | ৪-৫\nকেজি | ৫-৬\nপ্রথম শ্রেণি | ৬-৭\nদ্বিতীয় শ্রেণি | ৭-৮\nতৃতীয় শ্রেণি | ৮-৯\nচতুর্থ শ্রেণি | ৯-১০\nপঞ্চম শ্রেণি | ১০-১১\nষষ্ঠ শ্রেণি | ১১-১২\nসপ্তম শ্রেণি | ১২-১৩.৬"],
            ['name' => 'কুমিল্লা শাখা', 'slug' => 'cumilla', 'address' => 'ছোটশালঘর (বাস স্ট্যান্ড), দেবিদ্বার, কুমিল্লা।', 'phones' => "01409-979957 | অফিস\n01604-758887 | ইনচার্জ", 'email' => 'madrasatussunnahbd.cu@gmail.com', 'classes' => "নার্সারি | ৪-৫\nকেজি | ৫-৬\nপ্রথম শ্রেণি | ৬-৭\nদ্বিতীয় শ্রেণি | ৭-৮\nতৃতীয় শ্রেণি | ৮-৯\nচতুর্থ শ্রেণি | ৯-১০\nপঞ্চম শ্রেণি | ১০-১১"],
        ], [
            ['name' => 'প্রধান শাখা', 'slug' => 'dhaka', 'address' => 'মাদরাসাতুস সুন্নাহ, প্লট-৫, রোড-৪, ব্লক-সি, সানভ্যালি আবাসন, স্বদেশ প্রোপার্টিজ, সাঁতারকুল, বাড্ডা, ঢাকা-১২১২', 'phones' => '+8809639151525 | অফিস', 'email' => 'info@madrasatussunnah.org', 'classes' => "প্লে | ৩-৪\nনার্সারি | ৪-৫\nকেজি | ৫-৬\nপ্রথম শ্রেণি | ৬-৭\nদ্বিতীয় শ্রেণি | ৭-৮\nতৃতীয় শ্রেণি | ৮-৯\nচতুর্থ শ্রেণি | ৯-১০\nপঞ্চম শ্রেণি | ১০-১১\nষষ্ঠ শ্রেণি | ১১-১২\nসপ্তম শ্রেণি | ১২-১৩.৬", 'slides' => [], 'gallery' => [], 'map' => ''],
            ['name' => 'কুমিল্লা শাখা', 'slug' => 'cumilla', 'address' => 'ছোটশালঘর (বাস স্ট্যান্ড), দেবিদ্বার, কুমিল্লা।', 'phones' => "01409-979957 | অফিস\n01604-758887 | ইনচার্জ", 'email' => 'madrasatussunnahbd.cu@gmail.com', 'classes' => "নার্সারি | ৪-৫\nকেজি | ৫-৬\nপ্রথম শ্রেণি | ৬-৭\nদ্বিতীয় শ্রেণি | ৭-৮\nতৃতীয় শ্রেণি | ৮-৯\nচতুর্থ শ্রেণি | ৯-১০\nপঞ্চম শ্রেণি | ১০-১১", 'slides' => [], 'gallery' => [], 'map' => ''],
        ]],
    ];
    if (array_key_exists($key, $saved) && !(isset($legacy[$key]) && in_array($saved[$key], $legacy[$key], true))) {
        return $saved[$key];
    }
    return $defaults[$key] ?? '';
}

/** Image URL from an attachment ID option. */
function dt_img($id, $size = 'full') {
    return $id ? wp_get_attachment_image_url((int) $id, $size) : '';
}

/** Allow map iframes in the map_embed option. */
function dt_kses_iframe($html) {
    return wp_kses($html, ['iframe' => ['src' => true, 'width' => true, 'height' => true, 'style' => true, 'loading' => true, 'allowfullscreen' => true, 'referrerpolicy' => true, 'title' => true, 'frameborder' => true]]);
}

/** Fallback menu used until a menu is assigned to "Primary". */
function dt_fallback_menu() {
    $u = function ($p) { return esc_url(home_url('/' . $p)); };
    echo '<ul class="menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">হোম</a></li>';
    $groups = [
        'আমাদের সম্পর্কে' => ['message-chairman' => 'চেয়ারম্যানের বার্তা', 'mission-vision' => 'লক্ষ্য ও উদ্দেশ্য'],
        'ক্যাম্পাস'       => array_combine(array_map(function ($k) { return 'branch/' . $k; }, array_keys(dt_branches())), array_column(dt_branches(), 'name')),
        'একাডেমিক'       => ['syllabus' => 'কারিকুলাম রূপরেখা', 'academic-calendar' => 'একাডেমিক ক্যালেন্ডার', 'all-teacher' => 'সম্মানিত শিক্ষকবৃন্দ'],
        'ভর্তি'           => ['prospectus' => 'প্রসপেক্টাস'],
        'গ্যালারী'        => ['gallery' => 'ছবি', 'gallery-video' => 'ভিডিও'],
    ];
    foreach ($groups as $label => $items) {
        echo '<li class="menu-item-has-children"><a href="#">' . esc_html($label) . '</a><ul class="sub-menu">';
        foreach ($items as $slug => $text) {
            $href = $slug[0] === '#' ? esc_url(home_url('/') . $slug) : $u($slug);
            echo '<li><a href="' . $href . '">' . esc_html($text) . '</a></li>';
        }
        echo '</ul></li>';
    }
    foreach (['contact' => 'যোগাযোগ', 'faq' => 'FAQ', 'notice' => 'নোটিশ'] as $slug => $text) {
        echo '<li><a href="' . $u($slug) . '">' . esc_html($text) . '</a></li>';
    }
    echo '</ul>';
}

/** Default HTML for the three curriculum cards (copied from the reference site; editable in Theme Options). */
function dt_default_syllabus($n) {
    $bodies = [
        1 => '<ul><li>নাজেরা</li><li>হিফয</li><li>নূরানী</li><li>ইবতেদায়ী</li><li>দাখিল</li></ul>',
        2 => '<ul><li><strong>আরবি ভাষাগত দক্ষতা:</strong> শোনা, বলা, পড়া, লেখা</li><li><strong>ইংরেজি ভাষাগত দক্ষতা:</strong> শোনা, বলা, পড়া, লেখা</li><li><strong>ইসলামিক স্টাডিজ:</strong> নির্বাচিত আয়াত ও হাদীস, মাসনূন দুআ ও আদব, ঈমান, ফিকহ, সীরাত ও ইসলামী ইতিহাস, ইসলামী আদর্শ ও মূল্যবোধ</li><li><strong>জেনারেল বিষয়:</strong> বাংলা, গণিত, বিজ্ঞান, সাধারণ জ্ঞান, ড্রয়িং, হ্যান্ডরাইটিং</li></ul>',
        3 => '<ul><li><strong>মাধ্যমিক স্তর (দাখিল) সম্পন্ন করার পর:</strong> কেউ চাইলে রুট পরিবর্তন করে আলিয়াতে <strong>আলিম</strong>, কওমিতে <strong>কাফিয়া</strong> এবং জেনারেল ধারায় <strong>কলেজে</strong> ভর্তি হতে পারবে।</li></ul>',
    ];
    return $bodies[$n];
}

/** Default curriculum cards. */
function dt_default_syllabus_cards() {
    return [
        ['title' => 'শিক্ষা স্তর', 'body' => dt_default_syllabus(1)],
        ['title' => 'সিলেবাস', 'body' => dt_default_syllabus(2)],
        ['title' => 'রুট পরিবর্তন', 'body' => dt_default_syllabus(3)],
    ];
}

/** Title band shown at the top of inner pages. */
function dt_page_hero($title, $subtitle = '') {
    echo '<div class="page-hero"><div class="container"><h1>' . esc_html($title) . '</h1>';
    if ($subtitle !== '') {
        echo '<p>' . esc_html($subtitle) . '</p>';
    }
    echo '</div></div>';
}

/** Photo / Video switch shown on both gallery pages. */
function dt_gallery_tabs($active) {
    $tabs = ['photo' => ['ছবি', 'fa-image', home_url('/gallery/')], 'video' => ['ভিডিও', 'fa-circle-play', home_url('/gallery-video/')]];
    echo '<nav class="gallery-tabs" aria-label="Gallery">';
    foreach ($tabs as $key => [$label, $icon, $url]) {
        printf('<a class="%s" href="%s"%s><i class="fa-solid %s"></i> %s</a>', $key === $active ? 'is-active' : '', esc_url($url), $key === $active ? ' aria-current="page"' : '', esc_attr($icon), esc_html($label));
    }
    echo '</nav>';
}

/**
 * Turn a pasted video link into something embeddable: YouTube, Vimeo, Facebook or a direct file.
 * Returns ['iframe'|'file', src] or null when the link is not recognised.
 */
function dt_video_embed($url) {
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }
    if (preg_match('~(?:youtube\.com/(?:watch\?(?:[^#]*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m)) {
        return ['iframe', 'https://www.youtube-nocookie.com/embed/' . $m[1]];
    }
    if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
        return ['iframe', 'https://player.vimeo.com/video/' . $m[1]];
    }
    if (preg_match('~(?:facebook\.com|fb\.watch)~', $url)) {
        return ['iframe', 'https://www.facebook.com/plugins/video.php?show_text=false&href=' . rawurlencode($url)];
    }
    if (preg_match('~\.(mp4|webm|ogg)(\?|$)~i', $url)) {
        return ['file', $url];
    }
    return null;
}

/** CSS overrides from Darut Tawheed > Design. Only values that differ from the stylesheet defaults are printed. */
function dt_design_css() {
    $hex = function ($k, $fallback) { return sanitize_hex_color(dt_opt($k)) ?: $fallback; };
    $num = function ($k, $fallback, $min, $max) { $v = (int) dt_opt($k); return $v ? max($min, min($max, $v)) : $fallback; };
    $green  = $hex('design_primary', '#206850');
    $dark   = $hex('design_primary_dark', '#174f3c');
    $accent = $hex('design_accent', '#a06018');
    $vars = [
        '--green'          => $green,
        '--green-dark'     => $dark,
        '--green-tint'     => 'color-mix(in srgb, ' . $green . ' 12%, #fff)',
        '--accent'         => $accent,
        '--accent-dark'    => 'color-mix(in srgb, ' . $accent . ' 78%, #000)',
        '--accent-tint'    => 'color-mix(in srgb, ' . $accent . ' 18%, #fff)',
        '--accent-on-dark' => 'color-mix(in srgb, ' . $accent . ' 45%, #fff)',
        '--ink'            => $hex('design_text', '#292d2a'),
        '--bg'             => $hex('design_soft_bg', '#f6f9f7'),
        '--footer-bg'      => $hex('design_footer_bg', '#123a2d'),
        '--radius'         => $num('design_radius', 16, 0, 40) . 'px',
    ];
    $defaults = ['--green' => '#206850', '--green-dark' => '#174f3c', '--accent' => '#a06018'];
    // Keep the hand-tuned tints while the main colours are unchanged.
    if ($green === $defaults['--green']) {
        unset($vars['--green-tint']);
    }
    if ($accent === $defaults['--accent']) {
        unset($vars['--accent-dark'], $vars['--accent-tint'], $vars['--accent-on-dark']);
    }
    $css = ':root{';
    foreach ($vars as $k => $v) {
        $css .= $k . ':' . $v . ';';
    }
    $css .= '}';
    $font  = dt_opt('design_font') ?: 'Noto Serif Bengali';
    $size  = $num('design_font_size', 16, 12, 22);
    $width = $num('design_container', 1080, 760, 1600);
    $css .= "body{font-family:'" . esc_attr($font) . "',serif;font-size:" . $size . 'px;}';
    if ($width !== 1080) {
        $css .= '.container{width:min(' . $width . 'px,100% - 40px);}'
            . '.topbar__inner.container,.site-header__inner.container{width:min(' . ($width + 40) . 'px,100% - 32px);}'
            . '.site-footer .container{width:min(' . ($width + 160) . 'px,100% - 40px);}'
            . '.br-container{max-width:' . $width . 'px;}';
    }
    return $css;
}
