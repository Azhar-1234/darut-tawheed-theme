<?php
/** Contact: details, branches and map come from Theme Options > Home: contact & branches. */
get_header();
dt_page_hero(dt_opt('contact_page_title'), dt_opt('contact_page_subtitle'));
$phone = dt_opt('phone');
$wa    = dt_opt('whatsapp');
$email = dt_opt('email');
$addr  = dt_opt('address');
$embed = dt_kses_iframe(dt_opt('map_embed'));
$sent  = isset($_GET['dt_sent']) ? sanitize_key(wp_unslash($_GET['dt_sent'])) : '';
$note  = dt_feedback_notice($sent);
?>
<section class="section section--page section--contact" id="contact">
    <div class="container">
        <h2 class="section__title"><?php echo esc_html(dt_opt('contact_title')); ?></h2>
        <div class="grid grid--3 contact-cards">
            <?php if ($phone) : ?>
                <div class="contact-card"><i class="fa-solid fa-phone"></i><h3>ফোন করুন</h3>
                    <a href="tel:<?php echo esc_attr(dt_tel($phone)); ?>"><?php echo esc_html($phone); ?></a>
                    <?php if ($wa) : ?><a href="<?php echo esc_url('https://wa.me/' . ltrim(dt_tel($wa), '+')); ?>" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-whatsapp"></i> <?php echo esc_html($wa); ?></a><?php endif; ?>
                    <?php if (dt_opt('office_hours')) : ?><small><?php echo esc_html(dt_opt('office_hours')); ?></small><?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($email) : ?>
                <div class="contact-card"><i class="fa-solid fa-envelope"></i><h3>ইমেইল পাঠান</h3>
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                    <small>আপনার প্রশ্ন বা ফিডব্যাক পাঠান</small>
                </div>
            <?php endif; ?>
            <?php if ($addr) : ?>
                <div class="contact-card"><i class="fa-solid fa-location-dot"></i><h3>আমাদের অবস্থান</h3>
                    <span><?php echo esc_html($addr); ?></span>
                    <?php if (dt_opt('map_link')) : ?><a href="<?php echo esc_url(dt_opt('map_link')); ?>" target="_blank" rel="noopener noreferrer">ম্যাপে দেখুন</a><?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="contact-panel">
            <form class="feedback" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <h3><?php echo esc_html(dt_opt('contact_form_title')); ?></h3>
                <?php if ($note) : ?><p class="<?php echo esc_attr($note[0]); ?>" role="status"><?php echo esc_html($note[1]); ?></p><?php endif; ?>
                <input type="hidden" name="action" value="dt_feedback">
                <input type="hidden" name="dt_nonce" value="<?php echo esc_attr(wp_create_nonce('dt_feedback')); ?>">
                <input type="text" name="dt_website" tabindex="-1" autocomplete="off" class="hp" aria-hidden="true">
                <div class="feedback__row">
                    <input type="text" name="dt_name" placeholder="আপনার সম্পূর্ণ নাম *" required minlength="2" maxlength="80" autocomplete="name">
                    <input type="tel" name="dt_phone" placeholder="আপনার ফোন (ঐচ্ছিক)" maxlength="20" pattern="[0-9+\-\s()]{6,20}" autocomplete="tel">
                </div>
                <input type="email" name="dt_email" placeholder="আপনার ইমেইল *" required maxlength="100" autocomplete="email">
                <textarea name="dt_message" rows="5" placeholder="আপনার মন্তব্য *" required minlength="5" maxlength="2000"></textarea>
                <button class="btn btn--primary" type="submit">মন্তব্য প্রেরণ করুন</button>
            </form>
            <div class="map">
                <h3><?php echo esc_html(dt_opt('contact_map_title')); ?></h3>
                <?php if ($embed) : echo $embed; else : ?>
                    <iframe title="Map" src="<?php echo esc_url('https://www.google.com/maps?output=embed&q=' . rawurlencode(dt_opt('map_query') ?: $addr)); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php $branches = dt_branches(); ?>
<?php if ($branches) : ?>
<section class="section section--page section--branches" id="branches">
    <div class="container">
        <h2 class="section__title"><?php echo esc_html(dt_opt('branches_title')); ?></h2>
        <div class="grid grid--2 branches-grid">
            <?php foreach ($branches as $slug => $b) : ?>
                <article class="branch-card">
                    <h3><a href="<?php echo esc_url(home_url('/branch/' . $slug . '/')); ?>"><?php echo esc_html($b['name']); ?></a></h3>
                    <?php if (!empty($b['address'])) : ?><p><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($b['address']); ?></p><?php endif; ?>
                    <?php foreach (dt_branch_contacts($b) as [$label, $info, $href, $icon]) : ?>
                        <p><i class="fa-solid <?php echo esc_attr($icon); ?>"></i> <a href="<?php echo esc_url($href); ?>"><?php echo esc_html($info); ?></a><?php echo $label ? ' (' . esc_html($label) . ')' : ''; ?></p>
                    <?php endforeach; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php get_footer();
