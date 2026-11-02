</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-col footer-col--about">
            <?php
            $flogo = dt_img(dt_opt('footer_logo'));
            if ($flogo) {
                echo '<img class="footer-logo" src="' . esc_url($flogo) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
            } elseif (has_custom_logo()) {
                echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'footer-logo']);
            } else {
                echo '<img class="footer-logo" src="' . esc_url(get_template_directory_uri() . '/assets/img/logo.png') . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
            }
            ?>
            <?php if ($about = dt_opt('footer_about')) : ?><p><?php echo esc_html($about); ?></p><?php endif; ?>
        </div>

        <div class="footer-col">
            <h3><?php echo esc_html(dt_opt('links_title')); ?></h3>
            <?php
            wp_nav_menu(['theme_location' => 'footer', 'container' => false, 'depth' => 1, 'fallback_cb' => function () {
                $links = ['prospectus' => 'এডমিশন', 'notice' => 'নোটিশ', 'gallery' => 'গ্যালারী', 'contact' => 'যোগাযোগ', 'faq' => 'FAQ'];
                echo '<ul class="menu">';
                foreach ($links as $slug => $text) {
                    echo '<li><a href="' . esc_url(home_url('/' . $slug . '/')) . '">' . esc_html($text) . '</a></li>';
                }
                echo '</ul>';
            }]);
            ?>
        </div>

        <div class="footer-col">
            <h3><?php echo esc_html(dt_opt('contact_heading')); ?></h3>
            <ul class="menu">
                <?php if ($p = dt_opt('phone')) : ?><li><i class="fa-solid fa-phone"></i> <a href="tel:<?php echo esc_attr(dt_tel($p)); ?>"><?php echo esc_html($p); ?></a></li><?php endif; ?>
                <?php if ($w = dt_opt('whatsapp')) : ?><li><i class="fa-brands fa-whatsapp"></i> <a href="<?php echo esc_url('https://wa.me/' . ltrim(dt_tel($w), '+')); ?>" target="_blank" rel="noopener"><?php echo esc_html($w); ?></a></li><?php endif; ?>
                <?php if ($e = dt_opt('email')) : ?><li><i class="fa-solid fa-envelope"></i> <a href="mailto:<?php echo esc_attr($e); ?>"><?php echo esc_html($e); ?></a></li><?php endif; ?>
                <?php if ($ad = dt_opt('address')) : ?><li><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($ad); ?></li><?php endif; ?>
            </ul>
        </div>

        <?php if ($fbp = dt_opt('fb_page_url')) : ?>
            <div class="footer-col">
                <h3><?php echo esc_html(dt_opt('fb_title')); ?></h3>
                <iframe loading="lazy" title="Facebook" width="340" height="260" style="border:0;max-width:100%" allow="encrypted-media"
                    src="<?php echo esc_url('https://www.facebook.com/plugins/page.php?' . http_build_query(['href' => $fbp, 'tabs' => 'timeline', 'width' => 340, 'height' => 260, 'small_header' => 'true'])); ?>"></iframe>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom__inner">
            <span><?php echo esc_html(str_replace('{year}', dt_bn(wp_date('Y')), dt_opt('copyright'))); ?></span>
            <?php if (dt_opt('terms_url') || dt_opt('privacy_url')) : ?>
                <span class="footer-bottom__links">
                    <?php if (dt_opt('terms_url')) : ?><a href="<?php echo esc_url(dt_opt('terms_url')); ?>">পরিষেবার শর্তাবলী</a><?php endif; ?>
                    <?php if (dt_opt('privacy_url')) : ?><a href="<?php echo esc_url(dt_opt('privacy_url')); ?>">গোপনীয়তা নীতি</a><?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
