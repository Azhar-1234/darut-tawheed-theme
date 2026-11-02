<?php
/** Chairman's message. Text from Theme Options > Pages; name, designation and photo from Home: chairman. */
get_header();
$photo = dt_img(dt_opt('chairman_photo'), 'medium');
?>
<section class="section section--page">
    <div class="container">
        <h1 class="section__title"><?php echo esc_html(dt_opt('chairman_page_title')); ?></h1>
        <figure class="message-card">
            <?php if ($photo) : ?><div class="message-card__photo"><img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr(dt_opt('chairman_name')); ?>"></div><?php endif; ?>
            <div class="message-card__body">
                <div class="message-card__text prose"><?php echo wp_kses_post(wpautop(dt_opt('chairman_page_text'))); ?></div>
                <figcaption>
                    <strong><?php echo esc_html(dt_opt('chairman_name')); ?></strong>
                    <span><?php echo esc_html(dt_opt('chairman_designation')); ?></span>
                </figcaption>
            </div>
        </figure>
    </div>
</section>
<?php get_footer();
