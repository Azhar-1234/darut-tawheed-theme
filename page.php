<?php get_header(); ?>
<section class="section">
    <div class="container container--narrow">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('entry'); ?>>
                <h1 class="section__title"><?php the_title(); ?></h1>
                <div class="prose"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php get_footer(); ?>
