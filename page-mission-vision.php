<?php
/** Mission & vision. Text from Theme Options > Pages. */
get_header();
?>
<section class="section section--page">
    <div class="container">
        <article class="mission-card">
            <h1><span class="mission-card__dot" aria-hidden="true"></span><?php echo esc_html(dt_opt('mission_page_title')); ?></h1>
            <div class="prose"><?php echo wp_kses_post(wpautop(dt_opt('mission_page_text'))); ?></div>
        </article>
    </div>
</section>
<?php get_footer();
