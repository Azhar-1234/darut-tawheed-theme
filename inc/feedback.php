<?php
/**
 * Home page feedback form: validation, storage and the dashboard inbox.
 * Submissions are saved as private "dt_feedback" posts (visible only in wp-admin).
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    register_post_type('dt_feedback', [
        'labels' => ['name' => 'Feedback', 'singular_name' => 'Feedback', 'all_items' => 'All Feedback', 'edit_item' => 'View Feedback', 'search_items' => 'Search Feedback', 'not_found' => 'No feedback yet.'],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 58,
        'menu_icon' => 'dashicons-email-alt',
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

/** Message shown above the form after a submission (code from ?dt_sent=). Returns [class, text] or null. */
function dt_feedback_notice($code) {
    $errors = [
        '0'       => 'বার্তা পাঠানো যায়নি, অনুগ্রহ করে আবার চেষ্টা করুন।',
        'email'   => 'ইমেইল ঠিকানাটি সঠিক নয়। অনুগ্রহ করে একটি বৈধ ইমেইল দিন।',
        'invalid' => 'নাম (২–৮০ অক্ষর) ও মন্তব্য (৫–২০০০ অক্ষর) সঠিকভাবে দিন; মন্তব্যে অতিরিক্ত লিংক দেওয়া যাবে না।',
        'rate'    => 'অল্প সময়ে অনেকবার বার্তা পাঠানো হয়েছে। কিছুক্ষণ পর আবার চেষ্টা করুন।',
        'expired' => 'ফর্মের মেয়াদ শেষ হয়ে গেছে। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।',
    ];
    if ($code === '1') {
        return ['notice-ok', 'ধন্যবাদ, আপনার বার্তা সফলভাবে পাঠানো হয়েছে।'];
    }
    return $code === '' ? null : ['notice-err', $errors[$code] ?? $errors['0']];
}


function dt_feedback_redirect($code) {
    $back = wp_get_referer() ?: home_url('/');
    $back = remove_query_arg('dt_sent', $back);
    wp_safe_redirect(add_query_arg('dt_sent', $code, $back) . '#contact');
    exit;
}

/** Returns true if the address looks deliverable: valid syntax and a domain that can receive mail. */
function dt_email_is_valid($email) {
    if (!is_email($email) || strlen($email) > 100) {
        return false;
    }
    $check_dns = !in_array(wp_get_environment_type(), ['local', 'development'], true);
    if (apply_filters('dt_feedback_check_dns', $check_dns) && function_exists('checkdnsrr')) {
        $domain = substr(strrchr($email, '@'), 1) . '.';
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            return false;
        }
    }
    return true;
}

function dt_handle_feedback() {
    if (!isset($_POST['dt_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dt_nonce'])), 'dt_feedback')) {
        dt_feedback_redirect('expired');
    }
    // Honeypot: real visitors never fill this in.
    if (!empty($_POST['dt_website'])) {
        dt_feedback_redirect('1');
    }

    // Rate limit per IP: one every 20 seconds, five per hour.
    $ip  = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    $key = 'dt_fb_' . md5($ip);
    $log = get_transient($key);
    $log = is_array($log) ? $log : [];
    $now = time();
    $log = array_values(array_filter($log, function ($t) use ($now) { return $t > $now - HOUR_IN_SECONDS; }));
    if (count($log) >= 5 || ($log && end($log) > $now - 20)) {
        dt_feedback_redirect('rate');
    }

    $name    = sanitize_text_field(wp_unslash($_POST['dt_name'] ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['dt_email'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['dt_message'] ?? ''));
    $phone   = sanitize_text_field(wp_unslash($_POST['dt_phone'] ?? ''));

    $len = function ($s) { return function_exists('mb_strlen') ? mb_strlen($s) : strlen($s); };
    if ($len($name) < 2 || $len($name) > 80 || $len($message) < 5 || $len($message) > 2000) {
        dt_feedback_redirect('invalid');
    }
    if ($phone !== '' && !preg_match('/^[0-9+\-\s()]{6,20}$/', $phone)) {
        dt_feedback_redirect('invalid');
    }
    if (!dt_email_is_valid($email)) {
        dt_feedback_redirect('email');
    }
    if (preg_match_all('#https?://|www\.#i', $message) > 2) {
        dt_feedback_redirect('invalid');
    }

    $id = wp_insert_post(wp_slash([
        'post_type'    => 'dt_feedback',
        'post_status'  => 'publish',
        'post_title'   => $name,
        'post_content' => $message,
    ]), true);
    if (is_wp_error($id) || !$id) {
        dt_feedback_redirect('0');
    }
    update_post_meta($id, 'dt_email', $email);
    update_post_meta($id, 'dt_phone', $phone);
    update_post_meta($id, 'dt_read', 0);

    $log[] = $now;
    set_transient($key, $log, HOUR_IN_SECONDS);

    // Notification email is best-effort: the message is already safe in the dashboard.
    $to = dt_opt('email') ?: get_option('admin_email');
    wp_mail($to, sprintf('[%s] %s', get_bloginfo('name'), $name), $message . "\n\n" . $email . ($phone ? "\n" . $phone : ''), ['Reply-To: ' . $name . ' <' . $email . '>']);

    dt_feedback_redirect('1');
}
add_action('admin_post_nopriv_dt_feedback', 'dt_handle_feedback');
add_action('admin_post_dt_feedback', 'dt_handle_feedback');

/* ---------- Dashboard ---------- */

add_filter('manage_dt_feedback_posts_columns', function () {
    return ['cb' => '<input type="checkbox">', 'dt_name' => 'Name', 'dt_email' => 'Email', 'dt_msg' => 'Message', 'date' => 'Date'];
});

add_action('manage_dt_feedback_posts_custom_column', function ($col, $id) {
    $unread = !get_post_meta($id, 'dt_read', true);
    switch ($col) {
        case 'dt_name':
            printf('<a href="%s"><strong>%s</strong></a>%s', esc_url(get_edit_post_link($id)), esc_html(get_the_title($id)), $unread ? ' <span class="dashicons dashicons-star-filled" style="color:#d9a82f" title="Unread"></span>' : '');
            break;
        case 'dt_email':
            $e = get_post_meta($id, 'dt_email', true);
            printf('<a href="mailto:%s">%s</a>', esc_attr($e), esc_html($e));
            break;
        case 'dt_msg':
            echo esc_html(wp_trim_words(get_post_field('post_content', $id), 20));
            break;
    }
}, 10, 2);

add_filter('post_row_actions', function ($actions, $post) {
    if ($post->post_type === 'dt_feedback') {
        unset($actions['inline hide-if-no-js'], $actions['edit']);
        $actions = ['view' => sprintf('<a href="%s">View</a>', esc_url(get_edit_post_link($post->ID)))] + $actions;
    }
    return $actions;
}, 10, 2);

add_action('add_meta_boxes_dt_feedback', function () {
    remove_meta_box('submitdiv', 'dt_feedback', 'side');
    add_meta_box('dt_feedback_view', 'Message', function ($post) {
        update_post_meta($post->ID, 'dt_read', 1);
        $e = get_post_meta($post->ID, 'dt_email', true);
        echo '<table class="form-table"><tr><th>Name</th><td>' . esc_html($post->post_title) . '</td></tr>';
        echo '<tr><th>Email</th><td><a href="mailto:' . esc_attr($e) . '">' . esc_html($e) . '</a></td></tr>';
        if ($ph = get_post_meta($post->ID, 'dt_phone', true)) {
            echo '<tr><th>Phone</th><td><a href="tel:' . esc_attr(dt_tel($ph)) . '">' . esc_html($ph) . '</a></td></tr>';
        }
        echo '<tr><th>Received</th><td>' . esc_html(get_the_date('j F Y, g:i a', $post)) . '</td></tr>';
        echo '<tr><th>Message</th><td>' . nl2br(esc_html($post->post_content)) . '</td></tr></table>';
        echo '<p><a class="button button-primary" href="mailto:' . esc_attr($e) . '?subject=' . rawurlencode('Re: ' . get_bloginfo('name')) . '">Reply by email</a> ';
        echo '<a class="button-link-delete" href="' . esc_url(get_delete_post_link($post->ID)) . '">Move to Trash</a></p>';
    }, 'dt_feedback', 'normal', 'high');
});

// Unread count bubble on the admin menu.
add_action('admin_menu', function () {
    global $menu;
    $unread = new WP_Query(['post_type' => 'dt_feedback', 'post_status' => 'publish', 'meta_query' => [['key' => 'dt_read', 'value' => 0]], 'fields' => 'ids', 'posts_per_page' => 99, 'no_found_rows' => true]);
    $n = count($unread->posts);
    if (!$n) {
        return;
    }
    foreach ($menu as $i => $item) {
        if ($item[2] === 'edit.php?post_type=dt_feedback') {
            $menu[$i][0] .= ' <span class="awaiting-mod">' . (int) $n . '</span>';
            break;
        }
    }
}, 99);
