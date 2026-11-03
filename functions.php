<?php
/**
 * Darut Tawheed theme functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DT_VERSION', '1.0.5');

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/options.php';
require get_template_directory() . '/inc/seed.php';
require get_template_directory() . '/inc/home-data.php';
require get_template_directory() . '/inc/migrate.php';
require get_template_directory() . '/inc/feedback.php';
require get_template_directory() . '/inc/admission.php';

add_action('after_setup_theme', function () {
    load_theme_textdomain('darut-tawheed', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', ['height' => 80, 'width' => 260, 'flex-width' => true, 'flex-height' => true]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'darut-tawheed'),
        'footer'  => __('Footer Links', 'darut-tawheed'),
    ]);

    add_image_size('hero', 1920, 800, true);
    add_image_size('card', 600, 400, true);
});

add_action('wp_enqueue_scripts', function () {
    $font = dt_opt('design_font') ?: 'Noto Serif Bengali';
    wp_enqueue_style('dt-fonts', 'https://fonts.googleapis.com/css2?family=' . str_replace(' ', '+', $font) . ':wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('dt-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
    wp_enqueue_style('dt-style', get_stylesheet_uri(), ['dt-fonts'], DT_VERSION);
    wp_add_inline_style('dt-style', dt_design_css());
    if (is_front_page()) {
        $dist = get_template_directory() . '/assets/dist/';
        $uri  = get_template_directory_uri() . '/assets/dist/';
        if (file_exists($dist . 'home.css')) {
            wp_enqueue_style('dt-home', $uri . 'home.css', ['dt-style'], filemtime($dist . 'home.css'));
        }
        if (file_exists($dist . 'home.js')) {
            wp_enqueue_script('dt-home', $uri . 'home.js', [], filemtime($dist . 'home.js'), true);
        }
    }
    wp_enqueue_script('dt-theme', get_template_directory_uri() . '/assets/js/theme.js', [], DT_VERSION, true);
});
