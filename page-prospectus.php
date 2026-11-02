<?php
/** Prospectus: the page images are chosen in Page Options > admission. */
get_header();
$ids = array_filter(array_map(function ($r) { return is_array($r) ? (int) ($r['image'] ?? 0) : (int) $r; }, (array) dt_opt('prospectus_images')));
if (dt_opt('prospectus_title')) {
    dt_page_hero(dt_opt('prospectus_title'));
}
?>
<section class="section section--page section--prospectus">
    <div class="container">
        <?php if (!$ids) : ?>
            <p class="center"><?php echo esc_html(dt_opt('prospectus_empty')); ?></p>
        <?php else : ?>
            <?php if (dt_opt('prospectus_pdf')) : ?>
                <p class="center"><a class="btn btn--primary" href="<?php echo esc_url(dt_opt('prospectus_pdf')); ?>" target="_blank" rel="noopener"><i class="fa-solid fa-download"></i> <?php echo esc_html(dt_opt('prospectus_pdf_text')); ?></a></p>
            <?php endif; ?>
            <div class="prospectus">
                <?php foreach (array_values($ids) as $n => $id) :
                    $img = wp_get_attachment_image($id, 'large', false, ['loading' => $n ? 'lazy' : 'eager', 'alt' => get_post_meta($id, '_wp_attachment_image_alt', true) ?: sprintf('%s – %d', get_bloginfo('name'), $n + 1), 'sizes' => '(min-width: 900px) 820px, 100vw']);
                    if (!$img) {
                        continue;
                    } ?>
                    <a class="prospectus__page" href="<?php echo esc_url(wp_get_attachment_image_url($id, 'full')); ?>" target="_blank" rel="noopener"><?php echo $img; ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer();
