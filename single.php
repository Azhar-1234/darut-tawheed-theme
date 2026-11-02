<?php get_header(); ?>
<section class="section">
    <div class="container container--narrow">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('entry'); ?>>
                <h1 class="section__title"><?php the_title(); ?></h1>
                <?php if (get_post_type() === 'notice' || get_post_type() === 'post') : ?>
                    <p class="entry__meta"><?php echo esc_html(dt_bn_date(get_post_time('U'))); ?></p>
                <?php endif; ?>
                <?php if (has_post_thumbnail()) { the_post_thumbnail('large'); } ?>
                <div class="prose"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php get_footer(); ?>
