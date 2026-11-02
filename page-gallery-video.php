<?php
/** Video gallery: links are pasted in Page Options > gallery and shown as embedded players. */
get_header();
dt_page_hero(dt_opt('video_title'), dt_opt('video_subtitle'));
$videos = array_filter((array) dt_opt('gallery_videos'), function ($v) { return !empty($v['url']); });
?>
<section class="section section--page">
    <div class="container">
        <?php dt_gallery_tabs('video'); ?>
        <?php if (!$videos) : ?>
            <p class="center"><?php echo esc_html(dt_opt('video_empty')); ?></p>
        <?php else : ?>
            <div class="video-grid">
                <?php foreach (array_values($videos) as $v) :
                    $embed = dt_video_embed($v['url']);
                    $title = $v['title'] ?? ''; ?>
                    <figure class="video-card">
                        <div class="video-card__frame">
                            <?php if ($embed && $embed[0] === 'iframe') : ?>
                                <iframe src="<?php echo esc_url($embed[1]); ?>" title="<?php echo esc_attr($title ?: 'Video'); ?>" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                            <?php elseif ($embed) : ?>
                                <video src="<?php echo esc_url($embed[1]); ?>" controls preload="metadata"></video>
                            <?php else : ?>
                                <a class="video-card__link" href="<?php echo esc_url($v['url']); ?>" target="_blank" rel="noopener"><i class="fa-solid fa-circle-play"></i> ভিডিও দেখুন</a>
                            <?php endif; ?>
                        </div>
                        <?php if ($title) : ?><figcaption><?php echo esc_html($title); ?></figcaption><?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer();
