<?php
/**
 * Pre-admission form (/pre-admission/): field list, submission handling, dashboard inbox and printable copy.
 * Submissions are saved as private "dt_admission" posts (visible only in wp-admin).
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Form sections: title => [key => [label, type, required, options]].
 * Types: text, tel, email, date, textarea, select.
 */
function dt_admission_fields() {
    return [
        'ভর্তির তথ্য' => [
            'class'         => ['যে শ্রেণিতে ভর্তি হতে ইচ্ছুক', 'select', true, ['প্লে', 'নার্সারি', 'কেজি', 'প্রথম শ্রেণি', 'দ্বিতীয় শ্রেণি', 'তৃতীয় শ্রেণি', 'চতুর্থ শ্রেণি', 'পঞ্চম শ্রেণি', 'ষষ্ঠ শ্রেণি', 'সপ্তম শ্রেণি', 'অষ্টম শ্রেণি', 'নবম শ্রেণি', 'দশম শ্রেণি', 'হিফয বিভাগ']],
            'branch'        => ['শাখা', 'select', true, array_column(dt_branches(), 'name')],
            'academic_year' => ['শিক্ষাবর্ষ', 'text', true],
        ],
        'শিক্ষার্থীর তথ্য' => [
            'student_name'      => ['শিক্ষার্থীর নাম', 'text', true],
            'dob'               => ['জন্ম তারিখ', 'date', true],
            'age'               => ['বয়স', 'text', false],
            'nationality'       => ['জাতীয়তা', 'text', false],
            'father_name'       => ['পিতার নাম', 'text', true],
            'father_profession' => ['পিতার পেশা', 'text', false],
            'mother_name'       => ['মাতার নাম', 'text', true],
            'mother_profession' => ['মাতার পেশা', 'text', false],
        ],
        'ঠিকানা ও যোগাযোগ' => [
            'present_address' => ['বর্তমান ঠিকানা', 'textarea', true],
            'mobile'          => ['মোবাইল', 'tel', true],
            'tel'             => ['টেলিফোন', 'tel', false],
            'email'           => ['ই-মেইল', 'email', false],
            'perm_village'    => ['স্থায়ী ঠিকানা: গ্রাম', 'text', false],
            'perm_po'         => ['ডাকঘর', 'text', false],
            'perm_ps'         => ['থানা', 'text', false],
            'perm_district'   => ['জেলা', 'text', false],
        ],
        'পূর্ববর্তী স্কুল/মাদ্রাসা' => [
            'prev_name'    => ['প্রতিষ্ঠানের নাম', 'text', false],
            'prev_class'   => ['সর্বশেষ যে শ্রেণিতে পড়েছে', 'text', false],
            'prev_address' => ['প্রতিষ্ঠানের ঠিকানা', 'text', false],
        ],
        'স্থানীয় অভিভাবক' => [
            'lg_name'     => ['নাম', 'text', false],
            'lg_relation' => ['সম্পর্ক', 'text', false],
            'lg_tel'      => ['মোবাইল/টেলিফোন', 'tel', false],
            'lg_address'  => ['ঠিকানা', 'text', false],
        ],
        'যিনি শিক্ষার্থীকে বাড়ি নিয়ে যাবেন বা ক্যাম্পাস/হোস্টেলে দেখা করবেন' => [
            'pk_name'     => ['নাম', 'text', false],
            'pk_relation' => ['সম্পর্ক', 'text', false],
            'pk_tel'      => ['মোবাইল/টেলিফোন', 'tel', false],
            'pk_address'  => ['ঠিকানা', 'text', false],
        ],
    ];
}

/** Declarations the guardian and student must accept. */
function dt_admission_pledges() {
    return [
        'guardian_declare' => ['অভিভাবকের অঙ্গীকার', 'আমি সজ্ঞানে ঘোষণা করছি যে, এই প্রতিষ্ঠানের সকল নিয়ম-কানুন মেনে চলব এবং যাবতীয় পাওনা যথাসময়ে পরিশোধ করব।'],
        'student_oath'     => ['শিক্ষার্থীর শপথ', 'আমি প্রতিজ্ঞা করছি যে, এই প্রতিষ্ঠানের সকল নিয়ম-কানুন মেনে চলব এবং দৈনন্দিন রুটিন অনুযায়ী কাজ করব।'],
    ];
}

add_action('init', function () {
    register_post_type('dt_admission', [
        'labels' => ['name' => 'Admissions', 'singular_name' => 'Admission', 'all_items' => 'All Applications', 'edit_item' => 'View Application', 'search_items' => 'Search Applications', 'not_found' => 'No applications yet.'],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 57,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => [],
        'capability_type' => 'post',
        'capabilities' => ['create_posts' => 'do_not_allow'],
        'map_meta_cap' => true,
        'rewrite' => false,
        'query_var' => false,
        'show_in_rest' => false,
    ]);
});

/* ---------- Submission ---------- */

/** Errors from the last POST on this request, and the submitted values to refill the form. */
$GLOBALS['dt_admission_state'] = ['errors' => [], 'values' => []];

add_action('template_redirect', function () {
    if (!is_page('pre-admission') || ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !isset($_POST['dt_admission'])) {
        return;
    }
    $errors = [];
    $values = [];
    if (!isset($_POST['dt_adm_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dt_adm_nonce'])), 'dt_admission')) {
        $errors['_form'] = 'ফর্মের মেয়াদ শেষ হয়ে গেছে। আবার জমা দিন।';
    }
    // Honeypot: real visitors never fill this in.
    if (!empty($_POST['dt_website'])) {
        wp_safe_redirect(add_query_arg('dt_adm', '1', get_permalink()));
        exit;
    }

    $len = function ($s) { return function_exists('mb_strlen') ? mb_strlen($s) : strlen($s); };
    foreach (dt_admission_fields() as $fields) {
        foreach ($fields as $k => [$label, $type, $req]) {
            $raw = wp_unslash($_POST['adm'][$k] ?? '');
            $v = $type === 'textarea' ? sanitize_textarea_field($raw) : ($type === 'email' ? sanitize_email($raw) : sanitize_text_field($raw));
            $values[$k] = $v;
            if ($req && $v === '') {
                $errors[$k] = $label . ' দিতে হবে।';
            } elseif ($len($v) > ($type === 'textarea' ? 500 : 150)) {
                $errors[$k] = $label . ' অনেক বড় হয়ে গেছে।';
            } elseif ($v !== '' && $type === 'tel' && !preg_match('/^[0-9০-৯+\-\s()]{6,20}$/u', $v)) {
                $errors[$k] = $label . ' সঠিক নয়।';
            } elseif ($v !== '' && $type === 'email' && !is_email($v)) {
                $errors[$k] = $label . ' সঠিক নয়।';
            } elseif ($v !== '' && $type === 'date' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
                $errors[$k] = $label . ' সঠিক নয়।';
            } elseif ($v !== '' && $type === 'select' && !in_array($v, $fields[$k][3], true)) {
                $errors[$k] = $label . ' তালিকা থেকে বাছুন।';
            }
        }
    }
    foreach (dt_admission_pledges() as $k => [$label]) {
        $values[$k] = empty($_POST['adm'][$k]) ? 0 : 1;
        if (!$values[$k]) {
            $errors[$k] = $label . ' গ্রহণ করতে হবে।';
        }
    }

    // Rate limit per IP: one every 30 seconds, five per hour.
    $ip  = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    $key = 'dt_adm_' . md5($ip);
    $log = get_transient($key);
    $log = is_array($log) ? $log : [];
    $now = time();
    $log = array_values(array_filter($log, function ($t) use ($now) { return $t > $now - HOUR_IN_SECONDS; }));
    if (!$errors && (count($log) >= 5 || ($log && end($log) > $now - 30))) {
        $errors['_form'] = 'অল্প সময়ে অনেকবার আবেদন পাঠানো হয়েছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
    }

    if (!$errors) {
        $id = wp_insert_post(wp_slash(['post_type' => 'dt_admission', 'post_status' => 'publish', 'post_title' => $values['student_name']]), true);
        if (is_wp_error($id) || !$id) {
            $errors['_form'] = 'আবেদন জমা দেওয়া যায়নি, অনুগ্রহ করে আবার চেষ্টা করুন।';
        } else {
            update_post_meta($id, 'dt_adm', $values);
            update_post_meta($id, 'dt_read', 0);
            $log[] = $now;
            set_transient($key, $log, HOUR_IN_SECONDS);

            // Notification email is best-effort: the application is already safe in the dashboard.
            $lines = [];
            foreach (dt_admission_fields() as $title => $fields) {
                $lines[] = "\n== " . $title . ' ==';
                foreach ($fields as $k => [$label]) {
                    if ($values[$k] !== '') {
                        $lines[] = $label . ': ' . $values[$k];
                    }
                }
            }
            $lines[] = "\n" . admin_url('post.php?post=' . $id . '&action=edit');
            wp_mail(dt_opt('email') ?: get_option('admin_email'), sprintf('[%s] ভর্তির আবেদন: %s', get_bloginfo('name'), $values['student_name']), implode("\n", $lines));

            wp_safe_redirect(add_query_arg('dt_adm', '1', get_permalink()));
            exit;
        }
    }
    $GLOBALS['dt_admission_state'] = ['errors' => $errors, 'values' => $values];
});

/* ---------- Dashboard ---------- */

add_filter('manage_dt_admission_posts_columns', function () {
    return ['cb' => '<input type="checkbox">', 'dt_student' => 'Student', 'dt_class' => 'Class', 'dt_branch' => 'Branch', 'dt_mobile' => 'Mobile', 'date' => 'Date'];
});

add_action('manage_dt_admission_posts_custom_column', function ($col, $id) {
    $v = (array) get_post_meta($id, 'dt_adm', true);
    switch ($col) {
        case 'dt_student':
            printf('<a href="%s"><strong>%s</strong></a>%s', esc_url(get_edit_post_link($id)), esc_html(get_the_title($id)), get_post_meta($id, 'dt_read', true) ? '' : ' <span class="dashicons dashicons-star-filled" style="color:#d9a82f" title="Unread"></span>');
            break;
        case 'dt_class':
            echo esc_html($v['class'] ?? '');
            break;
        case 'dt_branch':
            echo esc_html($v['branch'] ?? '');
            break;
        case 'dt_mobile':
            $m = $v['mobile'] ?? '';
            printf('<a href="tel:%s">%s</a>', esc_attr(dt_tel($m)), esc_html($m));
            break;
    }
}, 10, 2);

add_filter('post_row_actions', function ($actions, $post) {
    if ($post->post_type === 'dt_admission') {
        unset($actions['inline hide-if-no-js'], $actions['edit']);
        $actions = ['view' => sprintf('<a href="%s">View</a>', esc_url(get_edit_post_link($post->ID))), 'print' => sprintf('<a href="%s" target="_blank">Print</a>', esc_url(dt_admission_print_url($post->ID)))] + $actions;
    }
    return $actions;
}, 10, 2);

function dt_admission_print_url($id) {
    return wp_nonce_url(admin_url('admin-post.php?action=dt_admission_print&id=' . (int) $id), 'dt_admission_print_' . (int) $id);
}

add_action('add_meta_boxes_dt_admission', function () {
    remove_meta_box('submitdiv', 'dt_admission', 'side');
    add_meta_box('dt_admission_view', 'Application', function ($post) {
        update_post_meta($post->ID, 'dt_read', 1);
        $v = (array) get_post_meta($post->ID, 'dt_adm', true);
        echo '<p><a class="button button-primary" href="' . esc_url(dt_admission_print_url($post->ID)) . '" target="_blank">Print form</a> ';
        echo '<a class="button-link-delete" href="' . esc_url(get_delete_post_link($post->ID)) . '">Move to Trash</a></p>';
        echo '<p>Received: ' . esc_html(get_the_date('j F Y, g:i a', $post)) . '</p>';
        foreach (dt_admission_fields() as $title => $fields) {
            echo '<h3>' . esc_html($title) . '</h3><table class="form-table">';
            foreach ($fields as $k => [$label, $type]) {
                $val = (string) ($v[$k] ?? '');
                if ($type === 'tel' && $val !== '') {
                    $out = '<a href="tel:' . esc_attr(dt_tel($val)) . '">' . esc_html($val) . '</a>';
                } elseif ($type === 'email' && $val !== '') {
                    $out = '<a href="mailto:' . esc_attr($val) . '">' . esc_html($val) . '</a>';
                } else {
                    $out = nl2br(esc_html($val));
                }
                echo '<tr><th>' . esc_html($label) . '</th><td>' . ($out !== '' ? $out : '—') . '</td></tr>';
            }
            echo '</table>';
        }
        echo '<h3>অঙ্গীকার</h3><ul>';
        foreach (dt_admission_pledges() as $k => [$label]) {
            echo '<li>' . (!empty($v[$k]) ? '✔' : '✘') . ' ' . esc_html($label) . '</li>';
        }
        echo '</ul>';
    }, 'dt_admission', 'normal', 'high');
});

// Unread count bubble on its item in the Darut Tawheed menu.
add_action('admin_menu', function () {
    $unread = new WP_Query(['post_type' => 'dt_admission', 'post_status' => 'publish', 'meta_query' => [['key' => 'dt_read', 'value' => 0]], 'fields' => 'ids', 'posts_per_page' => 99, 'no_found_rows' => true]);
    dt_menu_badge('edit.php?post_type=dt_admission', count($unread->posts));
}, 1000);

/* ---------- Printable form (paper layout with signature lines and the office section) ---------- */

add_action('admin_post_dt_admission_print', function () {
    $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
    if (!$id || get_post_type($id) !== 'dt_admission' || !current_user_can('edit_post', $id) || !check_admin_referer('dt_admission_print_' . $id)) {
        wp_die('Not allowed.');
    }
    $v = (array) get_post_meta($id, 'dt_adm', true);
    $f = function ($k) use ($v) { return esc_html((string) ($v[$k] ?? '')); };
    $row = function ($label, $val) { return '<div class="r"><span class="l">' . esc_html($label) . ':</span><span class="v">' . $val . '</span></div>'; };
    $logo = dt_img(dt_opt('footer_logo')) ?: get_template_directory_uri() . '/assets/img/logo.png';
    ?><!DOCTYPE html>
<html lang="bn"><head><meta charset="utf-8"><title><?php echo esc_html('ভর্তি ফরম – ' . ($v['student_name'] ?? '')); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;600;700&display=swap">
<style>
@page { size: A4; margin: 14mm; }
body { font-family: 'Noto Serif Bengali', serif; color: #222; font-size: 13px; line-height: 1.6; margin: 0; }
.page { max-width: 780px; margin: 0 auto; padding: 20px; }
.head { display: flex; align-items: center; gap: 16px; border-bottom: 3px solid #206850; padding-bottom: 10px; margin-bottom: 14px; }
.head img { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; }
.head h1 { margin: 0; color: #206850; font-size: 20px; }
.head p { margin: 0; color: #555; }
.title { text-align: center; font-size: 18px; font-weight: 700; margin: 6px 0 14px; letter-spacing: .02em; }
h2 { font-size: 14px; color: #206850; margin: 14px 0 6px; border-bottom: 1px solid #cfe1d8; padding-bottom: 2px; }
.grid { display: grid; grid-template-columns: 1fr 1fr; column-gap: 24px; }
.r { display: flex; gap: 6px; padding: 3px 0; }
.r .l { flex: 0 0 auto; color: #444; }
.r .v { flex: 1; border-bottom: 1px dotted #888; min-height: 1.4em; font-weight: 600; }
.full { grid-column: 1 / -1; }
.pledge { margin: 4px 0; }
.sign { display: flex; justify-content: flex-end; margin: 26px 0 6px; }
.sign span { border-top: 1px solid #333; padding-top: 2px; min-width: 220px; text-align: center; }
.office { border: 1.5px solid #333; padding: 10px 14px; margin-top: 18px; }
.office h2 { border: 0; color: #222; text-align: center; margin-top: 0; }
.box { display: inline-block; width: 12px; height: 12px; border: 1px solid #333; vertical-align: middle; margin: 0 4px 0 10px; }
.signs { display: flex; justify-content: space-between; margin-top: 34px; }
.signs span { border-top: 1px solid #333; padding-top: 2px; min-width: 200px; text-align: center; }
.noprint { text-align: center; margin: 16px 0; }
.noprint button { font: inherit; padding: 8px 24px; background: #206850; color: #fff; border: 0; border-radius: 8px; cursor: pointer; }
@media print { .noprint { display: none; } .page { padding: 0; } }
</style></head><body><div class="page">
<div class="noprint"><button onclick="window.print()">প্রিন্ট করুন</button></div>
<div class="head"><img src="<?php echo esc_url($logo); ?>" alt=""><div><h1><?php echo esc_html(get_bloginfo('name')); ?></h1><p><?php echo esc_html(dt_opt('address')); ?></p></div></div>
<div class="title">ভর্তি ফরম (Admission Form)</div>
<div class="grid">
<?php
echo $row('যে শ্রেণিতে ভর্তি হতে ইচ্ছুক', $f('class'));
echo $row('শিক্ষাবর্ষ', $f('academic_year'));
echo '<div class="full">' . $row('শাখা', $f('branch')) . '</div>';
?>
</div>
<h2>১. শিক্ষার্থীর তথ্য</h2>
<div class="grid">
<?php
echo '<div class="full">' . $row('শিক্ষার্থীর নাম', $f('student_name')) . '</div>';
echo $row('বয়স', $f('age'));
echo $row('জন্ম তারিখ', $f('dob'));
echo '<div class="full">' . $row('জাতীয়তা', $f('nationality')) . '</div>';
echo $row('পিতার নাম', $f('father_name'));
echo $row('পেশা', $f('father_profession'));
echo $row('মাতার নাম', $f('mother_name'));
echo $row('পেশা', $f('mother_profession'));
?>
</div>
<h2>২. ঠিকানা</h2>
<div class="grid">
<?php
echo '<div class="full">' . $row('ক. বর্তমান ঠিকানা', nl2br($f('present_address'))) . '</div>';
echo $row('মোবাইল', $f('mobile'));
echo $row('টেলিফোন', $f('tel'));
echo '<div class="full">' . $row('ই-মেইল', $f('email')) . '</div>';
echo $row('খ. স্থায়ী ঠিকানা: গ্রাম', $f('perm_village'));
echo $row('ডাকঘর', $f('perm_po'));
echo $row('থানা', $f('perm_ps'));
echo $row('জেলা', $f('perm_district'));
?>
</div>
<h2>৩. পূর্ববর্তী স্কুল/মাদ্রাসা</h2>
<div class="grid">
<?php
echo $row('নাম', $f('prev_name'));
echo $row('সর্বশেষ শ্রেণি', $f('prev_class'));
echo '<div class="full">' . $row('ঠিকানা', $f('prev_address')) . '</div>';
?>
</div>
<?php $pl = dt_admission_pledges(); ?>
<h2>৪. <?php echo esc_html($pl['guardian_declare'][0]); ?></h2>
<p class="pledge"><?php echo esc_html($pl['guardian_declare'][1]); ?></p>
<div class="sign"><span>অভিভাবকের স্বাক্ষর ও তারিখ</span></div>
<h2>৫. <?php echo esc_html($pl['student_oath'][0]); ?></h2>
<p class="pledge">আমি এই মর্মে প্রতিজ্ঞা করছি যে—<br>• এই প্রতিষ্ঠানের সকল নিয়ম-কানুন মেনে চলব।<br>• দৈনন্দিন রুটিন অনুযায়ী কাজ করব।</p>
<div class="sign"><span>শিক্ষার্থীর স্বাক্ষর ও তারিখ</span></div>
<h2>৬. স্থানীয় অভিভাবক</h2>
<div class="grid">
<?php
echo $row('নাম', $f('lg_name'));
echo $row('সম্পর্ক', $f('lg_relation'));
echo $row('ঠিকানা', $f('lg_address'));
echo $row('টেলিফোন', $f('lg_tel'));
?>
</div>
<h2>৭. যিনি শিক্ষার্থীকে বাড়ি নিয়ে যাবেন বা ক্যাম্পাস/হোস্টেলে দেখা করবেন</h2>
<div class="grid">
<?php
echo $row('নাম', $f('pk_name'));
echo $row('সম্পর্ক', $f('pk_relation'));
echo $row('ঠিকানা', $f('pk_address'));
echo $row('টেলিফোন', $f('pk_tel'));
?>
</div>
<div class="office">
<h2>অফিসিয়াল ব্যবহারের জন্য (For official use only)</h2>
<div class="grid">
<?php echo $row('ভর্তি পরীক্ষার তারিখ', ''); ?>
<div class="r"><span class="l">ফলাফল:</span><span>লিখিত<span class="box"></span> মৌখিক<span class="box"></span></span></div>
<?php echo $row('শ্রেণির জন্য সুপারিশ', ''); ?>
<div class="r"><span>আবাসিক<span class="box"></span> অনাবাসিক<span class="box"></span></span></div>
</div>
<div class="signs"><span>সুপারিশকারীর স্বাক্ষর ও তারিখ</span><span>অধ্যক্ষের স্বাক্ষর ও তারিখ</span></div>
</div>
<p style="text-align:right;color:#777;font-size:11px;margin-top:10px">অনলাইনে আবেদন: <?php echo esc_html(get_the_date('j F Y, g:i a', $id)); ?></p>
</div></body></html>
<?php
    exit;
});
