<?php
/** Branch page: /branch/ shows the first branch, /branch/slug/ a specific one. Managed in Theme Options > Home: contact & branches. */
get_header();
$branches = dt_branches();
$current = sanitize_title(get_query_var('dt_branch'));
if ($current === '' && $branches) {
    $current = array_key_first($branches);
}
$b = $branches[$current] ?? null;
if (!$b) {
    status_header(404);
    ?>
<section class="section section--page"><div class="container"><p class="center">শাখা পাওয়া যায়নি।</p></div></section>
<?php
    get_footer();
    return;
}
$classes = dt_branch_classes($b);
$slides = dt_branch_images($b, 'slides');
$photos = dt_branch_images($b, 'gallery');
$map = trim((string) ($b['map'] ?? '')) ?: trim((string) ($b['address'] ?? ''));
$svg = [
    'pin'   => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
    'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
    'mail'  => '<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/>',
    'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    'zoom'  => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/><path d="M11 8.5v5M8.5 11h5"/>',
    'arrow' => '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>',
];
$icon = function ($k) use ($svg) {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $svg[$k] . '</svg>';
};
?>
<div class="br-page">
<section class="br-container br-body">
    <?php if ($slides) : ?>
    <div class="br-slider" data-br-slider aria-roledescription="carousel" aria-label="<?php echo esc_attr($b['name']); ?>">
        <?php foreach ($slides as $n => $im) : ?>
            <img class="br-slider__slide<?php echo $n ? '' : ' is-active'; ?>" src="<?php echo esc_url($im['full']); ?>" alt="<?php echo esc_attr($im['alt'] ?: $b['name']); ?>" loading="<?php echo $n ? 'lazy' : 'eager'; ?>">
        <?php endforeach; ?>
        <?php if (count($slides) > 1) : ?>
            <button type="button" class="br-slider__btn br-slider__btn--prev" aria-label="আগের ছবি">&#10094;</button>
            <button type="button" class="br-slider__btn br-slider__btn--next" aria-label="পরের ছবি">&#10095;</button>
            <div class="br-slider__dots">
                <?php foreach ($slides as $n => $im) : ?><button type="button" class="<?php echo $n ? '' : 'is-active'; ?>" aria-label="<?php echo esc_attr('ছবি ' . ($n + 1)); ?>"></button><?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <h1 class="br-title"><?php echo esc_html($b['name']); ?></h1>

    <?php if ($classes) : ?>
    <div class="br-block">
        <h2 class="br-sec-title">শ্রেণিসমূহ</h2>
        <div class="br-classes__grid">
            <?php foreach ($classes as [$name, $age]) : ?>
                <div class="br-class-card">
                    <span class="br-class-name"><?php echo esc_html($name); ?></span>
                    <?php if ($age !== '') : ?><span class="br-class-age"><?php echo $icon('clock'); ?> <?php echo esc_html($age); ?></span><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($photos) : ?>
    <div class="br-block">
        <h2 class="br-sec-title">গ্যালারী</h2>
        <div class="br-gallery__grid">
            <?php foreach ($photos as $im) : ?>
                <a class="br-gallery__item" href="<?php echo esc_url($im['full']); ?>">
                    <img src="<?php echo esc_url($im['src']); ?>" alt="<?php echo esc_attr($im['alt'] ?: $b['name']); ?>" loading="lazy">
                    <span class="br-gallery__icon" aria-hidden="true"><span><?php echo $icon('zoom'); ?></span></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="br-layout br-block">
        <div class="br-card">
            <h2>যোগাযোগ</h2>
            <ul class="br-list">
                <?php if (!empty($b['address'])) : ?>
                    <li><span class="br-ic"><?php echo $icon('pin'); ?></span><span><span class="br-label">ঠিকানা</span><span class="br-value"><?php echo esc_html($b['address']); ?></span></span></li>
                <?php endif; ?>
                <?php foreach (dt_branch_contacts($b) as [$label, $info, $href, $fa]) :
                    $mail = $fa === 'fa-envelope'; ?>
                    <li><span class="br-ic"><?php echo $icon($mail ? 'mail' : 'phone'); ?></span><span><span class="br-label"><?php echo $mail ? 'ই-মেইল' : 'ফোন'; ?></span><a class="br-value" href="<?php echo esc_url($href); ?>"><?php echo esc_html($info); ?><?php echo $label ? ' (' . esc_html($label) . ')' : ''; ?></a></span></li>
                <?php endforeach; ?>
            </ul>
            <div class="br-actions">
                <a class="br-btn br-btn--primary" href="<?php echo esc_url(dt_opt('cta_link') ?: home_url('/pre-admission/')); ?>"><?php echo $icon('arrow'); ?> ভর্তির আবেদন করুন</a>
                <a class="br-btn br-btn--ghost" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo $icon('mail'); ?> যোগাযোগ</a>
            </div>
        </div>
        <?php if ($map !== '') : ?>
            <div class="br-map"><iframe title="<?php echo esc_attr($b['name']); ?>" src="<?php echo esc_url('https://www.google.com/maps?output=embed&q=' . rawurlencode($map)); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
        <?php endif; ?>
    </div>
</section>
</div>

<?php if ($photos) : ?>
<div class="br-lightbox" id="brLightbox" role="dialog" aria-modal="true" aria-label="গ্যালারী" hidden>
    <button type="button" class="br-lightbox__close" aria-label="বন্ধ করুন">&times;</button>
    <button type="button" class="br-lightbox__nav br-lightbox__nav--prev" aria-label="আগের ছবি">&#10094;</button>
    <figure class="br-lightbox__figure"><img src="" alt=""><figcaption class="br-lightbox__count"></figcaption></figure>
    <button type="button" class="br-lightbox__nav br-lightbox__nav--next" aria-label="পরের ছবি">&#10095;</button>
</div>
<?php endif; ?>

<script>
(function () {
    // Hero slider: fade, autoplay, arrows, dots, swipe.
    var box = document.querySelector('[data-br-slider]');
    if (box) {
        var slides = box.querySelectorAll('.br-slider__slide'), dots = box.querySelectorAll('.br-slider__dots button'), i = 0, timer;
        var show = function (n) {
            i = (n + slides.length) % slides.length;
            slides.forEach(function (s, k) { s.classList.toggle('is-active', k === i); });
            dots.forEach(function (d, k) { d.classList.toggle('is-active', k === i); });
        };
        var play = function () { clearInterval(timer); if (slides.length > 1) timer = setInterval(function () { show(i + 1); }, 4000); };
        var prev = box.querySelector('.br-slider__btn--prev'), next = box.querySelector('.br-slider__btn--next');
        if (prev) prev.addEventListener('click', function () { show(i - 1); play(); });
        if (next) next.addEventListener('click', function () { show(i + 1); play(); });
        dots.forEach(function (d, k) { d.addEventListener('click', function () { show(k); play(); }); });
        var x0 = null;
        box.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
        box.addEventListener('touchend', function (e) { if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 40) { show(i + (dx < 0 ? 1 : -1)); play(); } x0 = null; });
        box.addEventListener('mouseenter', function () { clearInterval(timer); });
        box.addEventListener('mouseleave', play);
        play();
    }

    // Gallery lightbox: click a photo, then step through all of them.
    var lb = document.getElementById('brLightbox');
    if (!lb) return;
    var items = Array.prototype.slice.call(document.querySelectorAll('.br-gallery__item'));
    var bn = function (n) { return String(n).replace(/\d/g, function (d) { return '০১২৩৪৫৬৭৮৯'[d]; }); };
    var img = lb.querySelector('img'), count = lb.querySelector('.br-lightbox__count'), cur = 0, last = null;
    var go = function (n) {
        cur = (n + items.length) % items.length;
        img.src = items[cur].getAttribute('href');
        img.alt = items[cur].querySelector('img').alt;
        count.textContent = bn(cur + 1) + ' / ' + bn(items.length);
    };
    var open = function (n) { last = document.activeElement; go(n); lb.hidden = false; document.body.style.overflow = 'hidden'; lb.querySelector('.br-lightbox__close').focus(); };
    var close = function () { lb.hidden = true; document.body.style.overflow = ''; if (last) last.focus(); };
    items.forEach(function (a, k) { a.addEventListener('click', function (e) { e.preventDefault(); open(k); }); });
    lb.querySelector('.br-lightbox__close').addEventListener('click', close);
    lb.querySelector('.br-lightbox__nav--prev').addEventListener('click', function () { go(cur - 1); });
    lb.querySelector('.br-lightbox__nav--next').addEventListener('click', function () { go(cur + 1); });
    lb.addEventListener('click', function (e) { if (e.target === lb || e.target.classList.contains('br-lightbox__figure')) close(); });
    document.addEventListener('keydown', function (e) {
        if (lb.hidden) return;
        if (e.key === 'Escape') close();
        else if (e.key === 'ArrowLeft') go(cur - 1);
        else if (e.key === 'ArrowRight') go(cur + 1);
    });
    var lx = null;
    lb.addEventListener('touchstart', function (e) { lx = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) { if (lx === null) return; var dx = e.changedTouches[0].clientX - lx; if (Math.abs(dx) > 40) go(cur + (dx < 0 ? 1 : -1)); lx = null; });
})();
</script>
<?php get_footer();
