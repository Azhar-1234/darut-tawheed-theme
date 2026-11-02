<?php
/** Photo gallery: masonry grid with a slide lightbox. Photos are chosen in Page Options > gallery. */
get_header();
dt_page_hero(dt_opt('gallery_title'));
$ids = array_filter(array_map(function ($r) { return is_array($r) ? (int) ($r['image'] ?? 0) : (int) $r; }, (array) dt_opt('gallery_images')));
if ($ids) {
    wp_enqueue_script('dt-gallery', get_template_directory_uri() . '/assets/js/gallery.js', [], filemtime(get_template_directory() . '/assets/js/gallery.js'), true);
}
?>
<section class="section section--page">
    <div class="container">
        <?php dt_gallery_tabs('photo'); ?>
        <?php if (!$ids) : ?>
            <p class="center"><?php echo esc_html(dt_opt('gallery_empty')); ?></p>
        <?php else : ?>
            <div class="masonry" data-lightbox>
                <?php foreach (array_values($ids) as $n => $id) :
                    $img = wp_get_attachment_image($id, 'large', false, ['loading' => $n < 4 ? 'eager' : 'lazy', 'alt' => get_post_meta($id, '_wp_attachment_image_alt', true) ?: sprintf('%s – %d', get_bloginfo('name'), $n + 1)]);
                    if (!$img) {
                        continue;
                    } ?>
                    <a class="masonry__item" href="<?php echo esc_url(wp_get_attachment_image_url($id, 'full')); ?>" data-caption="<?php echo esc_attr(wp_get_attachment_caption($id)); ?>"><?php echo $img; ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer();
