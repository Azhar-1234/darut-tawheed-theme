<?php
/** Academic calendar. Events are managed under Academic Calendar in the dashboard. */
get_header();
dt_page_hero(dt_opt('calendar_title'));
wp_enqueue_script('dt-calendar', get_template_directory_uri() . '/assets/js/calendar.js', [], filemtime(get_template_directory() . '/assets/js/calendar.js'), true);
?>
<section class="section section--page">
    <div class="container container--calendar">
        <div id="dt-calendar" class="cal" data-today="<?php echo esc_attr(wp_date('Y-m-d')); ?>"></div>
        <script id="dt-calendar-data" type="application/json"><?php echo wp_json_encode(dt_calendar_events(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?></script>
        <noscript><p class="center">Please enable JavaScript to view the calendar.</p></noscript>
    </div>
</section>
<?php get_footer();
