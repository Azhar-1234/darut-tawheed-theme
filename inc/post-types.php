<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    register_post_type('notice', [
        'labels' => ['name' => 'Notices', 'singular_name' => 'Notice', 'add_new_item' => 'Add New Notice', 'edit_item' => 'Edit Notice', 'all_items' => 'All Notices'],
        'public' => true,
        'has_archive' => 'notice',
        'rewrite' => ['slug' => 'notice'],
        'menu_icon' => 'dashicons-megaphone',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    register_post_type('feature', [
        'labels' => ['name' => 'Features', 'singular_name' => 'Feature', 'add_new_item' => 'Add New Feature', 'edit_item' => 'Edit Feature', 'all_items' => 'All Features'],
        'public' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'view'],
        'menu_icon' => 'dashicons-star-filled',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
        'show_in_rest' => true,
    ]);
});

add_action('init', function () {
    register_post_type('teacher', [
        'labels' => ['name' => 'Teachers', 'singular_name' => 'Teacher', 'add_new_item' => 'Add New Teacher', 'edit_item' => 'Edit Teacher', 'all_items' => 'All Teachers', 'featured_image' => 'Photo', 'set_featured_image' => 'Set photo'],
        'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-groups', 'menu_position' => 26,
        'supports' => ['title', 'thumbnail', 'page-attributes'], 'rewrite' => false, 'query_var' => false,
    ]);
    register_post_type('academic_event', [
        'labels' => ['name' => 'Academic Calendar', 'singular_name' => 'Event', 'add_new_item' => 'Add Calendar Event', 'edit_item' => 'Edit Event', 'all_items' => 'All Events', 'menu_name' => 'Academic Calendar'],
        'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-calendar-alt', 'menu_position' => 27,
        'supports' => ['title'], 'rewrite' => false, 'query_var' => false,
    ]);
});

/* Teacher details. */
add_action('add_meta_boxes_teacher', function () {
    add_meta_box('dt_teacher', 'Teacher details', function ($post) {
        wp_nonce_field('dt_teacher', 'dt_teacher_nonce');
        ?>
        <p><label><strong>Designation</strong> (e.g. উস্তায: প্রতিষ্ঠানের নাম)<br><input type="text" name="dt_designation" class="widefat" value="<?php echo esc_attr(get_post_meta($post->ID, 'designation', true)); ?>"></label></p>
        <p><label><strong>Qualification</strong> (one line per item is fine)<br><textarea name="dt_qualification" rows="4" class="widefat"><?php echo esc_textarea(get_post_meta($post->ID, 'qualification', true)); ?></textarea></label></p>
        <p class="description">Set the photo with "Photo" on the right. Order teachers with "Order" in Page Attributes.</p>
        <?php
    }, 'teacher', 'normal', 'high');
});

add_action('save_post_teacher', function ($id) {
    if (!isset($_POST['dt_teacher_nonce']) || !wp_verify_nonce($_POST['dt_teacher_nonce'], 'dt_teacher') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $id)) {
        return;
    }
    update_post_meta($id, 'designation', sanitize_text_field(wp_unslash($_POST['dt_designation'] ?? '')));
    update_post_meta($id, 'qualification', sanitize_textarea_field(wp_unslash($_POST['dt_qualification'] ?? '')));
});

add_filter('manage_teacher_posts_columns', function ($c) {
    return ['cb' => $c['cb'], 'dt_photo' => 'Photo', 'title' => 'Name', 'dt_designation' => 'Designation', 'date' => 'Date'];
});
add_action('manage_teacher_posts_custom_column', function ($col, $id) {
    if ($col === 'dt_photo') {
        echo get_the_post_thumbnail($id, [48, 48], ['style' => 'border-radius:50%;object-fit:cover']);
    } elseif ($col === 'dt_designation') {
        echo esc_html(get_post_meta($id, 'designation', true));
    }
}, 10, 2);

/* Calendar event details. */
add_action('add_meta_boxes_academic_event', function () {
    add_meta_box('dt_event', 'Event dates', function ($post) {
        wp_nonce_field('dt_event', 'dt_event_nonce');
        ?>
        <p><label><strong>Start date</strong> *<br><input type="date" name="dt_start" required value="<?php echo esc_attr(get_post_meta($post->ID, 'start', true)); ?>"></label></p>
        <p><label><strong>End date</strong> (optional, for events that last several days)<br><input type="date" name="dt_end" value="<?php echo esc_attr(get_post_meta($post->ID, 'end', true)); ?>"></label></p>
        <p><label><input type="checkbox" name="dt_highlight" value="1" <?php checked((bool) get_post_meta($post->ID, 'highlight', true)); ?>> Highlight (for exams and important dates)</label></p>
        <?php
    }, 'academic_event', 'normal', 'high');
});

add_action('save_post_academic_event', function ($id) {
    if (!isset($_POST['dt_event_nonce']) || !wp_verify_nonce($_POST['dt_event_nonce'], 'dt_event') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $id)) {
        return;
    }
    $date = function ($v) { $v = sanitize_text_field(wp_unslash($v)); return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : ''; };
    $start = $date($_POST['dt_start'] ?? '');
    $end   = $date($_POST['dt_end'] ?? '');
    if ($end && $start && $end < $start) {
        $end = $start;
    }
    update_post_meta($id, 'start', $start);
    update_post_meta($id, 'end', $end);
    update_post_meta($id, 'highlight', empty($_POST['dt_highlight']) ? 0 : 1);
});

add_filter('manage_academic_event_posts_columns', function ($c) {
    return ['cb' => $c['cb'], 'title' => 'Event', 'dt_start' => 'Start', 'dt_end' => 'End', 'dt_hl' => 'Highlight'];
});
add_action('manage_academic_event_posts_custom_column', function ($col, $id) {
    if ($col === 'dt_start' || $col === 'dt_end') {
        echo esc_html(get_post_meta($id, $col === 'dt_start' ? 'start' : 'end', true));
    } elseif ($col === 'dt_hl') {
        echo get_post_meta($id, 'highlight', true) ? '★' : '';
    }
}, 10, 2);

/** Events as plain arrays for the calendar script. */
function dt_calendar_events() {
    $q = new WP_Query(['post_type' => 'academic_event', 'post_status' => 'publish', 'posts_per_page' => -1, 'no_found_rows' => true, 'meta_key' => 'start', 'orderby' => 'meta_value', 'order' => 'ASC']);
    $out = [];
    foreach ($q->posts as $p) {
        $start = get_post_meta($p->ID, 'start', true);
        if (!$start) {
            continue;
        }
        $end = get_post_meta($p->ID, 'end', true);
        $out[] = ['title' => get_the_title($p), 'start' => $start, 'end' => $end && $end >= $start ? $end : $start, 'hl' => (bool) get_post_meta($p->ID, 'highlight', true)];
    }
    return $out;
}

/** Teachers in the order set in the dashboard. */
function dt_teachers() {
    return get_posts(['post_type' => 'teacher', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC']]);
}

/* Notices archive: 20 per page, newest first. */
add_action('pre_get_posts', function ($q) {
    if (!is_admin() && $q->is_main_query() && $q->is_post_type_archive('notice')) {
        $q->set('posts_per_page', 20);
    }
});

add_filter('get_the_archive_title', function ($title) {
    return is_post_type_archive('notice') ? 'নোটিশ' : $title;
});

/** /branch/slug/ renders the Branch page with the branch picked by slug. */
add_filter('query_vars', function ($vars) {
    $vars[] = 'dt_branch';
    return $vars;
});
add_action('init', function () {
    add_rewrite_rule('^branch/([^/]+)/?$', 'index.php?pagename=branch&dt_branch=$matches[1]', 'top');
    if (get_option('dt_branch_rewrite') !== '1') {
        flush_rewrite_rules(false);
        update_option('dt_branch_rewrite', '1');
    }
});
