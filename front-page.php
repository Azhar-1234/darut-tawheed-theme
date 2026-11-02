<?php
/**
 * Front page. The sections are rendered by the React app in src/ (built to assets/dist).
 * Section order/visibility comes from Theme Options > Home: layout.
 */
get_header();
?>
<div id="dt-home"></div>
<script id="dt-home-data" type="application/json"><?php echo wp_json_encode(dt_home_payload(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?></script>
<noscript><p class="center">Please enable JavaScript to view this page.</p></noscript>
<?php get_footer();
