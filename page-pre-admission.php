<?php
/** Pre-admission form: fields in inc/admission.php; applications appear under Admissions in wp-admin. */
get_header();
dt_page_hero('ভর্তি ফরম', 'অনলাইনে প্রাথমিক ভর্তির আবেদন করুন। তারকা (*) চিহ্নিত ঘরগুলো পূরণ করা আবশ্যক।');
$state  = $GLOBALS['dt_admission_state'] ?? ['errors' => [], 'values' => []];
$errors = $state['errors'];
$values = $state['values'] + ['academic_year' => dt_bn(wp_date('Y')), 'nationality' => 'বাংলাদেশী'];
$done   = isset($_GET['dt_adm']) && $_GET['dt_adm'] === '1';
$n = 0;
?>
<section class="section section--page section--admission">
    <div class="container">
        <?php if ($done) : ?>
            <div class="adm-done">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <h2>আপনার আবেদন জমা হয়েছে</h2>
                <p>জাযাকাল্লাহু খাইরান। আমাদের অফিস থেকে শীঘ্রই আপনার দেওয়া মোবাইল নম্বরে যোগাযোগ করা হবে, ইন-শা-আল্লাহ।</p>
                <a class="btn btn--primary" href="<?php echo esc_url(home_url('/')); ?>">হোমে ফিরে যান</a>
            </div>
        <?php else : ?>
            <form class="adm" method="post" action="<?php echo esc_url(get_permalink()); ?>" novalidate>
                <input type="hidden" name="dt_admission" value="1">
                <input type="hidden" name="dt_adm_nonce" value="<?php echo esc_attr(wp_create_nonce('dt_admission')); ?>">
                <input class="hp" type="text" name="dt_website" tabindex="-1" autocomplete="off" aria-hidden="true">

                <?php if ($errors) : ?>
                    <div class="adm-alert" role="alert">
                        <strong><?php echo esc_html($errors['_form'] ?? 'কিছু তথ্য ঠিক নেই। নিচে লাল চিহ্নিত ঘরগুলো দেখুন।'); ?></strong>
                    </div>
                <?php endif; ?>

                <?php foreach (dt_admission_fields() as $title => $fields) : $n++; ?>
                    <fieldset class="adm-sec">
                        <legend><span class="adm-sec__num"><?php echo esc_html(dt_bn($n)); ?></span> <?php echo esc_html($title); ?></legend>
                        <div class="adm-grid">
                            <?php foreach ($fields as $k => $fd) :
                                [$label, $type, $req] = $fd;
                                $id  = 'adm-' . $k;
                                $val = (string) ($values[$k] ?? '');
                                $err = $errors[$k] ?? '';
                                $attr = 'id="' . esc_attr($id) . '" name="adm[' . esc_attr($k) . ']"' . ($req ? ' required' : '') . ($err ? ' aria-invalid="true" aria-describedby="' . esc_attr($id) . '-err"' : '');
                                ?>
                                <div class="adm-field<?php echo $type === 'textarea' ? ' adm-field--full' : ''; ?><?php echo $err ? ' has-error' : ''; ?>">
                                    <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?><?php echo $req ? ' <span class="req">*</span>' : ''; ?></label>
                                    <?php if ($type === 'textarea') : ?>
                                        <textarea <?php echo $attr; ?> rows="3"><?php echo esc_textarea($val); ?></textarea>
                                    <?php elseif ($type === 'select') : ?>
                                        <select <?php echo $attr; ?>>
                                            <option value="">— বাছাই করুন —</option>
                                            <?php foreach ($fd[3] as $opt) : ?>
                                                <option value="<?php echo esc_attr($opt); ?>"<?php selected($val, $opt); ?>><?php echo esc_html($opt); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <input type="<?php echo esc_attr($type); ?>" value="<?php echo esc_attr($val); ?>" <?php echo $attr; ?><?php echo $type === 'tel' ? ' inputmode="tel" autocomplete="tel"' : ''; ?><?php echo $type === 'email' ? ' autocomplete="email"' : ''; ?>>
                                    <?php endif; ?>
                                    <?php if ($err) : ?><span class="adm-err" id="<?php echo esc_attr($id); ?>-err"><?php echo esc_html($err); ?></span><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>

                <fieldset class="adm-sec">
                    <legend><span class="adm-sec__num"><?php echo esc_html(dt_bn(++$n)); ?></span> অঙ্গীকার</legend>
                    <?php foreach (dt_admission_pledges() as $k => [$label, $text]) : $err = $errors[$k] ?? ''; ?>
                        <label class="adm-pledge<?php echo $err ? ' has-error' : ''; ?>">
                            <input type="checkbox" name="adm[<?php echo esc_attr($k); ?>]" value="1" required<?php checked(!empty($values[$k])); ?>>
                            <span><strong><?php echo esc_html($label); ?>:</strong> <?php echo esc_html($text); ?></span>
                        </label>
                        <?php if ($err) : ?><span class="adm-err"><?php echo esc_html($err); ?></span><?php endif; ?>
                    <?php endforeach; ?>
                </fieldset>

                <div class="adm-submit">
                    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-paper-plane"></i> আবেদন জমা দিন</button>
                    <p>জমা দেওয়ার পর ভর্তি পরীক্ষা ও পরবর্তী ধাপ সম্পর্কে অফিস থেকে জানানো হবে।</p>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php get_footer();
