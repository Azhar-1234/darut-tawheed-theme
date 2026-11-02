<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="topbar">
    <div class="container topbar__inner">
        <div class="topbar__contact">
            <?php if ($phone = dt_opt('phone')) : ?>
                <span><span class="topbar__label">Call Us:</span> <a href="tel:<?php echo esc_attr(dt_tel($phone)); ?>"><?php echo esc_html($phone); ?></a></span>
            <?php endif; ?>
            <?php if ($wa = dt_opt('whatsapp')) : ?>
                <span><span class="topbar__label">WhatsApp:</span> <a href="<?php echo esc_url('https://wa.me/' . ltrim(dt_tel($wa), '+')); ?>" target="_blank" rel="noopener"><?php echo esc_html($wa); ?></a></span>
            <?php endif; ?>
            <?php if ($email = dt_opt('email')) : ?>
                <span><span class="topbar__label">Email:</span> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></span>
            <?php endif; ?>
        </div>
        <div class="topbar__links">
            <?php if ($fb = dt_opt('facebook_url')) : ?><a class="topbar__social" href="<?php echo esc_url($fb); ?>" aria-label="Facebook" target="_blank" rel="noopener"><i class="fa-brands fa-facebook-f"></i></a><?php endif; ?>
            <?php if ($yt = dt_opt('youtube_url')) : ?><a class="topbar__social" href="<?php echo esc_url($yt); ?>" aria-label="YouTube" target="_blank" rel="noopener"><i class="fa-brands fa-youtube"></i></a><?php endif; ?>
        </div>
    </div>
</div>

<header class="site-header">
    <div class="container site-header__inner">
        <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">
            <?php if (has_custom_logo()) : echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full'); else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>" alt="<?php bloginfo('name'); ?>" width="72" height="72">
            <?php endif; ?>
        </a>

        <button class="nav-toggle" aria-expanded="false" aria-controls="primary-nav" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>

        <nav id="primary-nav" class="primary-nav" aria-label="Primary">
            <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'dt_fallback_menu', 'depth' => 2]); ?>
            <?php if (dt_opt('cta_text')) : ?>
                <a class="btn btn--primary primary-nav__cta" href="<?php echo esc_url(dt_opt('cta_link') ?: home_url('/pre-admission/')); ?>"><?php echo esc_html(dt_opt('cta_text')); ?></a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?php
// Admission banner: hidden automatically once the last date has passed.
$last = dt_opt('banner_last_date');
$expired = $last && strtotime($last . ' 23:59:59 ' . wp_timezone_string()) < time();
if (is_front_page() && dt_opt('show_banner') && dt_opt('banner_title') && !$expired) : ?>
    <div class="admission-banner">
        <div class="container">
            <div class="admission-card">
                <div class="admission-card__text">
                    <strong><span class="admission-card__dot" aria-hidden="true"></span><?php echo esc_html(dt_opt('banner_title')); ?></strong>
                    <?php if ($last) : ?>
                        <span><i class="fa-regular fa-calendar-days"></i> <?php echo esc_html(dt_opt('banner_date_label')); ?> <?php echo esc_html(dt_bn_date(strtotime($last))); ?></span>
                    <?php endif; ?>
                </div>
                <?php if (dt_opt('banner_button_text')) : ?>
                    <a class="admission-card__btn" href="<?php echo esc_url(dt_opt('banner_button_link') ?: home_url('/prospectus/')); ?>"><?php echo esc_html(dt_opt('banner_button_text')); ?> <i class="fa-solid fa-arrow-right"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<main id="main">
