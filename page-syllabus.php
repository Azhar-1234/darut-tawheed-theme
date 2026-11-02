<?php
/** Curriculum outline. Cards are managed in Page Options > academic. */
get_header();
dt_page_hero(dt_opt('syllabus_title'));
$colors = ['var(--green)', 'var(--accent)', '#3f6479'];
$cards = array_filter((array) dt_opt('syllabus_cards'), function ($c) {
    return trim(wp_strip_all_tags($c['body'] ?? '')) !== '' || !empty($c['title']);
});
?>
<section class="section section--page">
    <div class="container">
        <div class="syllabus-grid">
            <?php foreach (array_values($cards) as $i => $card) : ?>
                <article class="syllabus-card" style="--c: <?php echo esc_attr($colors[$i % count($colors)]); ?>">
                    <?php if (!empty($card['title'])) : ?><h2><span class="syllabus-card__dot" aria-hidden="true"></span><?php echo esc_html($card['title']); ?></h2><?php endif; ?>
                    <div class="prose"><?php echo wp_kses_post(wpautop($card['body'] ?? '')); ?></div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php get_footer();
