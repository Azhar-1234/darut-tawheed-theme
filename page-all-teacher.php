<?php
/** Teachers. Add them under Teachers in the dashboard. */
get_header();
dt_page_hero(dt_opt('teachers_title'));
$teachers = dt_teachers();
$placeholder = get_template_directory_uri() . '/assets/img/teacher.svg';
?>
<section class="section section--page">
    <div class="container">
        <?php if (!$teachers) : ?>
            <p class="center"><?php echo esc_html(dt_opt('teachers_empty')); ?></p>
        <?php else : ?>
            <div class="teacher-grid">
                <?php foreach ($teachers as $t) :
                    $photo = get_the_post_thumbnail_url($t, 'medium') ?: $placeholder;
                    $desig = get_post_meta($t->ID, 'designation', true);
                    $qual  = get_post_meta($t->ID, 'qualification', true);
                    ?>
                    <article class="teacher-card">
                        <img class="teacher-card__photo" src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($t->post_title); ?>" loading="lazy">
                        <h2><?php echo esc_html($t->post_title); ?></h2>
                        <?php if ($desig) : ?><p class="teacher-card__role"><?php echo esc_html($desig); ?></p><?php endif; ?>
                        <?php if ($qual) : ?><p class="teacher-card__qual"><?php echo nl2br(esc_html($qual)); ?></p><?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer();
