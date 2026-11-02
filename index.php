<?php get_header(); ?>
<section class="section">
    <div class="container container--narrow">
        <?php if (is_archive() || is_home() || is_search()) : ?>
            <h1 class="section__title"><?php echo is_search() ? esc_html(sprintf('"%s"', get_search_query())) : esc_html(get_the_archive_title() ?: get_the_title(get_option('page_for_posts'))); ?></h1>
        <?php endif; ?>
        <?php if (have_posts()) : ?>
            <ul class="notice-list notice-list--page">
                <?php while (have_posts()) : the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(dt_bn_date(get_post_time('U'))); ?></time>
                        <span><?php the_title(); ?></span></a></li>
                <?php endwhile; ?>
            </ul>
            <div class="pagination"><?php the_posts_pagination(['mid_size' => 2]); ?></div>
        <?php else : ?>
            <p class="center">কিছু পাওয়া যায়নি।</p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
