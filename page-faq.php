<?php
/** FAQ: questions and answers are managed in Page Options > contact & FAQ. */
get_header();
dt_page_hero(dt_opt('faq_title'), dt_opt('faq_subtitle'));
$items = array_filter((array) dt_opt('faq_items'), function ($r) { return !empty($r['question']) && !empty($r['answer']); });
?>
<section class="section section--page section--faq">
    <div class="container">
        <?php if (!$items) : ?>
            <p class="center"><?php echo esc_html(dt_opt('faq_empty')); ?></p>
        <?php else : ?>
            <div class="faq">
                <?php foreach ($items as $n => $r) : ?>
                    <details class="faq__item"<?php echo $n === array_key_first($items) ? ' open' : ''; ?>>
                        <summary><?php echo esc_html($r['question']); ?></summary>
                        <div class="faq__answer"><?php echo wp_kses_post(wpautop($r['answer'])); ?></div>
                    </details>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer();
