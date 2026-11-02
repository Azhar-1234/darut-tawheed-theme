<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Options: one array option (dt_options). The field definitions below drive
 * the admin form, sanitising and the defaults (defaults hold the text copied from
 * the reference homepage).
 */
function dt_option_tabs() {
    return [
        'Top bar & header' => [
            'phone'          => ['Phone', 'text', '+880 1750-164143'],
            'whatsapp'       => ['WhatsApp number', 'text', '+880 1816-271003'],
            'email'          => ['Email', 'text', 'daruttawhidalmadania@gmail.com'],
            'facebook_url'   => ['Facebook URL', 'url', 'https://www.facebook.com/Darut.tawheed.al.madaniyyah.Madrasah.Feni/'],
            'youtube_url'    => ['YouTube URL', 'url', 'https://www.youtube.com/@madrasatussunnah'],
            'cta_text'       => ['Header button text', 'text', 'প্রি-এডমিশন'],
            'cta_link'       => ['Header button link (leave empty for the online admission form at /pre-admission/)', 'url', ''],
        ],
        'Admission banner' => [
            'show_banner'        => ['Show banner', 'checkbox', 1],
            'banner_title'       => ['Title', 'text', 'ভর্তির আবেদন চলছে: 2027'],
            'banner_last_date'   => ['Last date (banner hides itself after this date)', 'date', '2026-10-28'],
            'banner_date_label'  => ['Last date label', 'text', 'আবেদনের শেষ তারিখ:'],
            'banner_button_text' => ['Button text', 'text', 'আবেদন করুন'],
            'banner_button_link' => ['Button link', 'url', ''],
        ],
        'Home: layout' => [
            'home_sections' => ['Home sections (tick to show, number sets the order)', 'sections', null],
        ],
        'Home: slider & notices' => [
            'slides'            => ['Slider images (select several; recommended 1920×800)', 'gallery', []],
            'notices_title'     => ['Notices heading', 'text', 'গুরুত্বপূর্ণ আপডেট'],
            'notice_count'      => ['Notices to show', 'number', 10],
            'notice_all_text'   => ['"All notices" link text', 'text', 'সকল নোটিশ দেখুন'],
        ],
        'Home: about' => [
            'about_image'       => ['Background / side image', 'image', ''],
            'established_year'  => ['Established badge', 'text', 'প্রতিষ্ঠা : ২০২১ সাল'],
            'about_title'       => ['Title', 'text', 'কেন দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা'],
            'about_text'        => ['Text', 'textarea', "সমাজে বিদ্যমান বিভিন্ন শিক্ষাধারার মধ্যে মাদরাসা শিক্ষাধারা সুপ্রাচীন এবং কল্যাণমুখী শিক্ষার ধারক হিসেবে সুবিদিত। এই শিক্ষাধারা ইসলামী জীবন-দর্শনের আলোকে উপমহাদেশে গড়ে তুলছে পরকালমুখী মূল্যবোধসম্পন্ন আদর্শ মানুষ। কিন্তু এই শিক্ষাধারা নৈতিকতা ও মূল্যবোধের মানদণ্ডে উত্তীর্ণ একদল আদর্শ মানুষ ও দক্ষ আলেম তৈরিতে সফল হলেও এর দ্বারা সময়োপযোগী ও সৃজনশীল মানুষ সেভাবে তৈরি হচ্ছে না।\n\nপক্ষান্তরে প্রচলিত সাধারণ শিক্ষাধারা আমাদের সন্তানদের পার্থিব শিক্ষায় শিক্ষিত করছে বটে, কিন্তু এই মাধ্যমে নৈতিকতা এবং প্রকৃত জীবনবোধসম্পন্ন মানুষ গড়ে উঠছে না। এজন্য আমরা মনে করছি এরকম একটি শিক্ষাপ্রতিষ্ঠান দরকার যেটি উন্নত চরিত্রসমৃদ্ধ কুরআন-সুন্নাহর গভীর জ্ঞানসম্পন্ন মানুষ তৈরি করবে, যারা জাগতিক জ্ঞান-বিজ্ঞানেও প্রয়োজনীয় ধারণা রাখবে। এই লক্ষ্য বাস্তবায়নেই দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার অভিযাত্রা।"],
        ],
        'Home: features & facilities' => [
            'features_title'    => ['Features heading', 'text', 'যে কারণে দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা অনন্য'],
            'features_count'    => ['Features to show', 'number', 9],
            'features_more'     => ['"Read more" text', 'text', 'আরও দেখুন'],
            'facilities_title'  => ['Facilities heading', 'text', 'মাদরাসার সুযোগ-সুবিধাসমূহ'],
            'facilities'        => ['Facilities', 'repeater', [
                ['icon' => 'fa-bus', 'image' => '', 'title' => 'নিজস্ব পরিবহন ব্যবস্থা', 'short_text' => 'শিক্ষার্থীদের যাতায়াতের সুবিধার্থে দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার রয়েছে ৫টি নিজস্ব বাস। মাদরাসার এই বাসগুলো তিনটি রুটের (আফতাবনগর গেইট, উত্তর বাড্ডা ও নতুন বাজার) বিভিন্ন পয়েন্ট থেকে শিক্ষার্থীদের আনা-নেওয়া করে থাকে। আগামী শিক্ষাবর্ষ থেকে প্রয়োজন সাপেক্ষে বাসের সংখ্যা আরো বৃদ্ধি করা হবে, ইন-শা-আল্লাহ।'],
                ['icon' => 'fa-bed', 'image' => '', 'title' => 'আবাসিক ছাত্রাবাস', 'short_text' => 'দেশের বিভিন্ন প্রান্ত থেকে আগত শিক্ষার্থীদের পড়াশোনার সুবিধার্থে দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার রয়েছে সীমিত আকারে আবাসিক ব্যবস্থা। শিক্ষার্থীদের পড়াশোনা, নৈতিক মানোন্নয়ন, রুচিসম্মত খাবার পরিবেশন এবং তাদেরকে মনোরম পরিবেশে রাখার জন্য দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার সর্বাত্মক প্রচেষ্টা অব্যাহত আছে। মাদরাসার এই আবাসিক ব্যবস্থা শুধু দ্বিতীয় বা তদূর্ধ্ব শ্রেণির ছেলে শিক্ষার্থীদের জন্য প্রযোজ্য।'],
                ['icon' => 'fa-graduation-cap', 'image' => '', 'title' => 'স্কলারশিপের সুযোগ', 'short_text' => 'গরীব ও এতিম মেধাবী শিক্ষার্থীদের জন্য রয়েছে সম্পূর্ণ স্কলারশিপের সুযোগ। কোনো শিক্ষার্থী মেধাবী এবং যাকাতের হকদার হয়ে থাকলে অভিভাবকের ঐকান্তিক আগ্রহের ভিত্তিতে তার যাবতীয় ব্যয়ভার মাদ্রাসার যাকাত তহবিল থেকে বহন করা হয়, আলহামদুলিল্লাহ। এই সুযোগ সর্বোচ্চ ২০ শতাংশ শিক্ষার্থীদের জন্য প্রযোজ্য।'],
                ['icon' => 'fa-people-roof', 'image' => '', 'title' => 'একই পিতা-মাতার একাধিক সন্তানের জন্য ছাড়', 'short_text' => 'একই বাবা-মায়ের দুই সন্তান দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসাতে ভর্তি হলে দ্বিতীয়জনের জন্য ভর্তি এবং টিউশন ফি অর্ধেক।'],
            ], [
                'icon'       => ['Font Awesome icon class (used if no image)', 'text'],
                'image'      => ['Image', 'image'],
                'title'      => ['Title', 'text'],
                'short_text' => ['Short text', 'textarea'],
            ]],
            'facilities_more_text' => ['More link text', 'text', 'অন্যান্য সুযোগ-সুবিধাসমূহ'],
            'facilities_more_link' => ['More link URL', 'url', ''],
        ],
        'Home: chairman' => [
            'chairman_title'       => ['Heading', 'text', 'চেয়ারম্যানের বাণী'],
            'chairman_photo'       => ['Photo', 'image', ''],
            'chairman_quote'       => ['Quote', 'textarea', 'শিশুদের কোমল হৃদয়ে পরকালীন চেতনা জাগাতে না পারলে, দুনিয়ার যত সুন্দর পরিবেশেই লালন-পালন করা হোক না কেন—তারা প্রকৃত মানুষ হয়ে গড়ে উঠবে না।'],
            'chairman_name'        => ['Name', 'text', 'ড. মোহাম্মাদ ইমাম হোসাইন'],
            'chairman_designation' => ['Designation', 'text', 'চেয়ারম্যান, দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা'],
        ],
        'Home: contact & branches' => [
            'contact_title'      => ['Contact heading', 'text', 'আমাদের সাথে যোগাযোগ'],
            'office_hours'       => ['Office hours', 'text', 'রবি - বৃহস্পতিবার: সকাল ৯টা থেকে দুপুর ৩টা'],
            'address'            => ['Address', 'text', 'ড্যাফোডিল ভিলা, সেলিনা পারভিন সড়ক, ফেনী (বালক শাখা); গ্রীন ভিউ, নাজির রোড-১, ফেনী (বালিকা শাখা)'],
            'map_link'           => ['Map link', 'url', 'https://share.google/nDKVKMSEU734qXdtM'],
            'map_query'          => ['Map search text (used to show the map when no embed code is given)', 'text', 'Darut Tawheed Al-Madaniyyah Madrasah, Feni, Bangladesh'],
            'map_embed'          => ['Map embed (iframe code)', 'textarea', ''],
            'branches_title'     => ['Branches heading', 'text', 'শাখাসমূহ'],
            'branches'           => ['Branches (shown at /branch/ and on the home page; each branch also gets its own page at /branch/slug/)', 'repeater', [
                ['name' => 'বালিকা শাখা', 'slug' => 'balika', 'address' => 'গ্রীন ভিউ, নাজির রোড-১, ফেনী (বালিকা শাখা)', 'phones' => '', 'email' => '', 'classes' => "জেনারেল বিভাগ | প্লে থেকে দশম শ্রেণী\nহিফয বিভাগ | \nআবাসিক | \nঅনাবাসিক | \nডে-কেয়ার | "],
                ['name' => 'বালক শাখা', 'slug' => 'balok', 'address' => 'ড্যাফোডিল ভিলা, সেলিনা পারভিন সড়ক, ফেনী (বালক শাখা)', 'phones' => '', 'email' => '', 'classes' => "জেনারেল বিভাগ | প্লে থেকে দশম শ্রেণী\nহিফয বিভাগ | \nআবাসিক | \nঅনাবাসিক | \nডে-কেয়ার | "],
            ], [
                'name'    => ['Branch name', 'text'],
                'slug'    => ['Page slug (latin letters, e.g. dhaka → /branch/dhaka/)', 'text'],
                'address' => ['Address', 'text'],
                'phones'  => ['Phone numbers (one per line: number | label, e.g. 01700-000000 | অফিস). Leave empty to use the site phone', 'textarea'],
                'email'   => ['Email (leave empty to use the site email)', 'text'],
                'classes' => ['Classes / departments (one per line: name | detail, e.g. প্লে | ৩-৪ বছর)', 'textarea'],
                'slides'  => ['Slider images (leave empty to use the default slider)', 'gallery'],
                'gallery' => ['Photo gallery (leave empty to use the default gallery)', 'gallery'],
                'map'     => ['Map search text (leave empty to use the address)', 'text'],
            ]],
        ],
        'Pages: chairman & mission' => [
            'chairman_page_title' => ['Chairman message page: heading', 'text', 'চেয়ারম্যানের বার্তা'],
            'chairman_page_text'  => ['Chairman message page: message (name, designation and photo come from Home: chairman)', 'textarea', 'সমস্ত প্রশংসা মহান আল্লাহর জন্য—যিনি আমাদের সৃষ্টিকর্তা, পালনকর্তা এবং সঠিক পথের প্রদর্শক। তাঁর অশেষ করুণা ও দয়া ছাড়া কোনো সৎকর্মই আমাদের পক্ষে সম্পাদন করা সম্ভব নয়। দরূদ ও সালাম বর্ষিত হোক মানবতার দিশারী আল্লাহর রাসূল মুহাম্মাদ সাল্লাল্লাহু আলাইহি ওয়া সাল্লাম ও তাঁর পরিবার এবং সঙ্গীদের প্রতি। উম্মাহ আজ বহুমুখী চ্যালেঞ্জের সম্মুখীন। শিক্ষা সেই চ্যালেঞ্জগুলোর মধ্যে অন্যতম। একদিকে ধর্মহীন শিক্ষাব্যবস্থা জাতিকে ঈমান থেকে বিচ্যুত করছে, অপরদিকে দীনি শিক্ষা অর্জনকারীদের অনেকে জাগতিক জ্ঞান না থাকায় নানা দিক থেকে পিছিয়ে পড়ছেন।

এ পরিস্থিতিতে ওহী ভিত্তিক নৈতিক এবং কল্যাণমুখী শিক্ষার প্রচার এবং প্রসার সময়ের অপরিহার্য দাবি। এই প্রয়োজনীয়তা উপলব্ধি করেই দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার অভিযাত্রা। বর্তমান সময়ে চ্যালেঞ্জ মোকাবিলায় আমাদের শিশুদের মধ্যে সুদৃঢ় বিশ্বাস, উন্নত চরিত্র ও ভারসাম্যপূর্ণ দৃষ্টিভঙ্গি গড়ে তোলার প্রয়োজনীয়তা অনস্বীকার্য। সেই লক্ষ্য পূরণে দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা সহযোগী ভূমিকা পালন করবে, ইন-শা-আল্লাহ। মহান আল্লাহ তাআলা এই প্রতিষ্ঠানসহ সকল দীনি প্রতিষ্ঠানকে কবুল করুন।'],
            'mission_page_title'  => ['Mission & vision page: heading', 'text', 'মিশন ও ভিশন'],
            'mission_page_text'   => ['Mission & vision page: text', 'textarea', 'হিফযুল কুরআনসহ দীনি শিক্ষার পাশাপাশি ভাষা হিসেবে আরবি ও ইংরেজিকে বিশেষ গুরুত্ব দিয়ে আত্মস্থ করানো এবং বিজ্ঞানসহ প্রয়োজনীয় জাগতিক বিষয়ে ধারণা দেওয়ার মাধ্যমে কুরআন-সুন্নাহর গভীর জ্ঞানসম্পন্ন যুগসচেতন দক্ষ আলেম ও বিচক্ষণ দা\'ঈ ইলাল্লাহ তৈরি করা দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসার লক্ষ্য।

এর ফলে আমাদের শিক্ষার্থীরা যুগসচেতন ও দূরদৃষ্টিসম্পন্ন বিজ্ঞ আলেম হয়ে জাতীয় ও আন্তর্জাতিক পরিমণ্ডলে উম্মাহর সেবায় নিরত হবে ইন-শা-আল্লাহ। এই মহান মিশন বাস্তবায়নে আমাদের যাবতীয় প্রয়াস নিবদ্ধ রয়েছে।'],
        ],
        'Pages: academic' => [
            'syllabus_title'      => ['Curriculum page: heading', 'text', 'কারিকুলাম রূপরেখা'],
            'syllabus_cards'      => ['Curriculum cards (add as many as you need; use the arrows to reorder)', 'repeater', dt_default_syllabus_cards(), [
                'title' => ['Card title', 'text'],
                'body'  => ['Card content', 'editor'],
            ]],
            'calendar_title'      => ['Academic calendar page: heading', 'text', 'একাডেমিক ক্যালেন্ডার'],
            'teachers_title'      => ['Teachers page: heading', 'text', 'আমাদের সম্মানিত শিক্ষকবৃন্দ'],
            'teachers_empty'      => ['Teachers page: message when there are no teachers yet', 'text', 'শিক্ষকদের তালিকা শীঘ্রই যুক্ত করা হবে।'],
        ],
        'Pages: admission' => [
            'prospectus_title'    => ['Prospectus page: heading (optional, leave empty for none)', 'text', ''],
            'prospectus_images'   => ['Prospectus pages (select all the page images; drag to reorder)', 'gallery', []],
            'prospectus_pdf'      => ['Download link (PDF, optional)', 'url', ''],
            'prospectus_pdf_text' => ['Download button text', 'text', 'প্রসপেক্টাস ডাউনলোড করুন'],
            'prospectus_empty'    => ['Message shown when no images are added yet', 'text', 'প্রসপেক্টাস শীঘ্রই যুক্ত করা হবে।'],
        ],
        'Pages: gallery' => [
            'gallery_title'        => ['Photo gallery: heading', 'text', 'ফটো গ্যালারি'],
            'gallery_images'       => ['Photos (select many; drag to reorder; caption comes from the media library)', 'gallery', []],
            'gallery_empty'        => ['Photo gallery: message when empty', 'text', 'ছবি শীঘ্রই যুক্ত করা হবে।'],
            'video_title'          => ['Video gallery: heading', 'text', 'ভিডিও গ্যালারী (Video Gallery)'],
            'video_subtitle'       => ['Video gallery: sub heading', 'text', 'দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা-এর শিক্ষা, কার্যক্রম ও অনুষ্ঠানের বিভিন্ন ভিডিও সমূহ এখানে দেখুন।'],
            'gallery_videos'       => ['Videos (paste a YouTube, Vimeo or Facebook video link)', 'repeater', [], [
                'url'   => ['Video link', 'url'],
                'title' => ['Title (optional)', 'text'],
            ]],
            'video_empty'          => ['Video gallery: message when empty', 'text', 'ভিডিও শীঘ্রই যুক্ত করা হবে।'],
        ],
        'Pages: contact & FAQ' => [
            'contact_page_title'    => ['Contact page: heading', 'text', 'যোগাযোগ'],
            'contact_page_subtitle' => ['Contact page: sub heading (phone, email, address, map and branches come from Home: contact & branches)', 'text', 'আপনার যেকোনো প্রশ্ন, পরামর্শ বা সহযোগিতার জন্য আমাদের সাথে যোগাযোগ করুন।'],
            'contact_form_title'    => ['Contact page: form heading', 'text', 'আপনার মূল্যবান মন্তব্য'],
            'contact_map_title'     => ['Contact page: map heading', 'text', 'ম্যাপে আমাদের খুঁজুন'],
            'faq_title'             => ['FAQ page: heading', 'text', 'প্রশ্নোত্তর (FAQ)'],
            'faq_subtitle'          => ['FAQ page: sub heading', 'text', 'নিচে আমাদের মাদ্রাসা সম্পর্কে কিছু সাধারণ প্রশ্নের উত্তর দেওয়া হলো।'],
            'faq_items'             => ['Questions and answers (drag with ↑/↓ to reorder)', 'repeater', [
                ['question' => 'মাদ্রাসার সিলেবাস কেমন?', 'answer' => 'এখানে জেনারেল শিক্ষা ও ইসলামী শিক্ষার সমন্বয় করে পাঠদান করা হয়। আরবি ভাষা ও ইসলামিক স্টাডিজ ছাড়াও বাংলা, গণিত ও ইংরেজিসহ ক্লাসভিত্তিক অন্যান্য জেনারেল বিষয় পড়ানো হয়।'],
                ['question' => 'ভাষার উপর গুরুত্ব কতটা?', 'answer' => 'আরবি, ইংরেজি ও বাংলা—এই তিন ভাষার বিষয়সমূহ গুরুত্ব দিয়ে পড়ানো হয়।'],
                ['question' => 'আদব-আখলাক ও মূল্যবোধ শিক্ষা কীভাবে দেওয়া হয়?', 'answer' => 'ক্লাস ও পরিবেশ উভয়টিই এমনভাবে সাজানো হয়েছে, যাতে শিক্ষার্থীরা বাস্তব জীবনে ইসলামী আদর্শ ও নৈতিকতা প্রয়োগ করতে শিখে।'],
                ['question' => 'ভর্তি সম্পর্কে বিস্তারিত কোথায় পাব?', 'answer' => 'ভর্তি সংক্রান্ত সকল তথ্য প্রসপেক্টাস পেজে দেওয়া আছে। আরও জানতে আমাদের সাথে যোগাযোগ করুন।'],
            ], [
                'question' => ['Question', 'text'],
                'answer'   => ['Answer', 'textarea'],
            ]],
            'faq_empty'             => ['FAQ page: message when empty', 'text', 'প্রশ্নোত্তর শীঘ্রই যুক্ত করা হবে।'],
        ],
        'Footer' => [
            'footer_logo'  => ['Footer logo (falls back to site logo)', 'image', ''],
            'footer_about' => ['About text', 'textarea', "'দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা' একটি আদর্শ দ্বীনি শিক্ষা প্রতিষ্ঠান। ইলম, আমল ও তারবিয়াহর মাধ্যমে আমরা এমন সচেতন ও সময়োপযোগী প্রজন্ম গড়ে তোলার চেষ্টা করি—যারা উম্মাহর সংকট নিরসন ও মানবকল্যাণে নেতৃত্ব দেবে, ইন-শা-আল্লাহ"],
            'links_title'  => ['Links heading', 'text', 'গুরুত্বপূর্ণ লিংক'],
            'contact_heading' => ['Contact heading', 'text', 'যোগাযোগ'],
            'fb_title'     => ['Facebook heading', 'text', 'আমাদের ফেসবুক পেজ'],
            'fb_page_url'  => ['Facebook page URL (for embed)', 'url', 'https://www.facebook.com/Darut.tawheed.al.madaniyyah.Madrasah.Feni/'],
            'terms_url'    => ['Terms of service URL (optional)', 'url', ''],
            'privacy_url'  => ['Privacy policy URL (optional)', 'url', ''],
            'copyright'    => ['Copyright', 'text', '© {year} দারুত তাওহীদ আল-মাদানিয়্যাহ মাদ্রাসা - সর্বস্বত্ত্ব সংরক্ষিত।'],
        ],
    ];
}

/** Admin menu each tab appears under: Theme Options = site and home page, Page Options = inner pages. */
function dt_page_option_tabs() {
    return ['Pages: chairman & mission', 'Pages: academic', 'Pages: admission', 'Pages: gallery', 'Pages: contact & FAQ'];
}

function dt_option_fields() {
    $all = [];
    foreach (dt_option_tabs() as $fields) {
        $all += $fields;
    }
    return $all;
}

function dt_option_defaults() {
    $d = [];
    foreach (dt_option_fields() as $key => $f) {
        if ($f[1] === 'sections') {
            continue;
        }
        $d[$key] = $f[2] ?? '';
    }
    return $d;
}

add_action('admin_menu', function () {
    add_menu_page('Theme Options', 'Theme Options', 'manage_options', 'dt-options', function () { dt_options_page('dt-options'); }, 'dashicons-admin-generic', 59);
    add_menu_page('Page Options', 'Page Options', 'manage_options', 'dt-pages', function () { dt_options_page('dt-pages'); }, 'dashicons-admin-page', 60);
});

add_action('admin_init', function () {
    register_setting('dt_options_group', 'dt_options', ['sanitize_callback' => 'dt_sanitize_options']);
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['toplevel_page_dt-options', 'toplevel_page_dt-pages'], true)) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_editor();
    wp_enqueue_script('dt-admin', get_template_directory_uri() . '/assets/js/admin.js', ['jquery', 'jquery-ui-sortable'], DT_VERSION, true);
});

function dt_sanitize_value($type, $v, $f = null) {
    switch ($type) {
        case 'url':      return esc_url_raw($v);
        case 'textarea': return ($f && $f[0] === 'Map embed (iframe code)') ? dt_kses_iframe($v) : wp_kses_post($v);
        case 'image':    return absint($v);
        case 'editor':   return wp_kses_post($v);
        case 'number':   return absint($v);
        case 'gallery':  return array_values(array_filter(array_map('absint', explode(',', (string) $v))));
        case 'checkbox': return empty($v) ? 0 : 1;
        case 'date':     return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $v) ? $v : '';
        default:         return sanitize_text_field($v);
    }
}

function dt_sanitize_options($input) {
    $input = is_array($input) ? wp_unslash($input) : [];
    $out = [];
    foreach (dt_option_fields() as $key => $f) {
        $type = $f[1];
        if ($type === 'sections') {
            foreach (array_keys(dt_home_section_labels()) as $slug) {
                $row = $input[$key][$slug] ?? [];
                $out[$key][$slug] = ['enabled' => empty($row['enabled']) ? 0 : 1, 'order' => absint($row['order'] ?? 0)];
            }
        } elseif ($type === 'repeater') {
            $out[$key] = [];
            foreach ((array) ($input[$key] ?? []) as $row) {
                $clean = [];
                foreach ($f[3] as $sk => $sf) {
                    $clean[$sk] = dt_sanitize_value($sf[1], $row[$sk] ?? '');
                }
                if (array_filter($clean)) {
                    $out[$key][] = $clean;
                }
            }
        } else {
            $out[$key] = dt_sanitize_value($type, $input[$key] ?? '', $f);
        }
    }
    return $out;
}

function dt_field_input($name, $type, $val, $label = '') {
    switch ($type) {
        case 'textarea':
            echo '<textarea name="' . esc_attr($name) . '" rows="5" class="large-text">' . esc_textarea($val) . '</textarea>';
            break;
        case 'checkbox':
            echo '<label><input type="checkbox" name="' . esc_attr($name) . '" value="1"' . checked(!empty($val), true, false) . '> Yes</label>';
            break;
        case 'image':
            $url = $val ? wp_get_attachment_image_url((int) $val, 'thumbnail') : '';
            echo '<div class="dt-media"><input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '">';
            echo '<img src="' . esc_url($url) . '" style="max-width:120px;height:auto;display:' . ($url ? 'block' : 'none') . '">';
            echo '<button type="button" class="button dt-media-pick">Select image</button> <button type="button" class="button-link dt-media-clear">Remove</button></div>';
            break;
        case 'gallery':
            $ids = is_array($val) ? array_filter(array_map(function ($r) { return is_array($r) ? absint($r['image'] ?? 0) : absint($r); }, $val)) : [];
            echo '<div class="dt-gallery"><input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr(implode(',', $ids)) . '"><div class="dt-gallery__items" style="display:flex;flex-wrap:wrap;gap:8px;margin:8px 0">';
            foreach ($ids as $id) {
                $u = wp_get_attachment_image_url($id, 'thumbnail');
                if ($u) {
                    echo '<span class="dt-gallery__item" data-id="' . (int) $id . '" style="position:relative"><img src="' . esc_url($u) . '" style="width:100px;height:70px;object-fit:cover;display:block"><button type="button" class="dt-gallery-remove" style="position:absolute;top:0;right:0;cursor:pointer" aria-label="Remove">×</button></span>';
                }
            }
            echo '</div><button type="button" class="button dt-gallery-pick">Select images</button></div>';
            break;
        case 'editor':
            echo '<textarea class="dt-wysiwyg large-text" rows="8" name="' . esc_attr($name) . '">' . esc_textarea($val) . '</textarea>';
            break;
        case 'number':
            echo '<input type="number" min="0" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '" class="small-text">';
            break;
        case 'date':
            echo '<input type="date" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '">';
            break;
        default:
            echo '<input type="' . ($type === 'url' ? 'url' : 'text') . '" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '" class="regular-text">';
    }
}

function dt_options_page($page) {
    // Tabs are addressed by slug: labels contain "&" and ":", which break unencoded query strings.
    $inner = dt_page_option_tabs();
    $tabs = [];
    foreach (dt_option_tabs() as $label => $fields) {
        $tabs[sanitize_title($label)] = ['label' => $label, 'fields' => $fields, 'page' => in_array($label, $inner, true) ? 'dt-pages' : 'dt-options'];
    }
    $mine = array_filter($tabs, function ($t) use ($page) { return $t['page'] === $page; });
    $current = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : '';
    if (!isset($mine[$current])) {
        $current = array_key_first($mine);
    }
    ?>
    <div class="wrap">
        <h1><?php echo $page === 'dt-pages' ? 'Page Options' : 'Theme Options'; ?></h1>
        <?php settings_errors(); ?>
        <h2 class="nav-tab-wrapper">
            <?php foreach ($mine as $slug => $tab) : ?>
                <a class="nav-tab<?php echo $slug === $current ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url(add_query_arg(['page' => $page, 'tab' => rawurlencode($slug)], admin_url('admin.php'))); ?>"><?php echo esc_html(preg_replace('/^Pages: /', '', $tab['label'])); ?></a>
            <?php endforeach; ?>
        </h2>
        <form method="post" action="options.php">
            <?php settings_fields('dt_options_group'); ?>
            <?php
            // options.php replaces the whole array, so keep every other tab's values (on both menus) as hidden fields.
            foreach ($tabs as $slug => $tab) {
                if ($slug === $current) {
                    continue;
                }
                echo '<div hidden>';
                foreach ($tab['fields'] as $key => $f) {
                    dt_render_row($key, $f, true);
                }
                echo '</div>';
            }
            ?>
            <table class="form-table" role="presentation">
                <?php foreach ($tabs[$current]['fields'] as $key => $f) { dt_render_row($key, $f, false); } ?>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function dt_render_row($key, $f, $bare) {
    $label = $f[0];
    $type  = $f[1];
    $name  = 'dt_options[' . $key . ']';
    $val   = dt_opt($key);

    ob_start();
    if ($type === 'sections') {
        $saved = is_array($val) ? $val : [];
        $i = 0;
        foreach (dt_home_section_labels() as $slug => $text) {
            $i++;
            $row = $saved[$slug] ?? ['enabled' => 1, 'order' => $i];
            echo '<p><label><input type="checkbox" name="' . esc_attr($name . '[' . $slug . '][enabled]') . '" value="1"' . checked(!empty($row['enabled']), true, false) . '> ' . esc_html($text) . '</label> ';
            echo '<input type="number" min="0" class="small-text" name="' . esc_attr($name . '[' . $slug . '][order]') . '" value="' . esc_attr($row['order']) . '"></p>';
        }
    } elseif ($type === 'repeater') {
        $rows = is_array($val) ? array_values($val) : [];
        echo '<div class="dt-repeater" data-name="' . esc_attr($name) . '">';
        echo '<div class="dt-rows">';
        foreach ($rows as $i => $row) {
            dt_repeater_row($name, $i, $f[3], $row);
        }
        echo '</div>';
        echo '<script type="text/template" class="dt-row-tpl">';
        dt_repeater_row($name, '__i__', $f[3], []);
        echo '</script>';
        echo '<p><button type="button" class="button dt-add-row">Add row</button></p></div>';
    } elseif ($type === 'editor') {
        if ($bare) {
            echo '<textarea hidden name="' . esc_attr($name) . '">' . esc_textarea($val) . '</textarea>';
        } else {
            wp_editor((string) $val, 'dt_ed_' . $key, ['textarea_name' => $name, 'textarea_rows' => 10, 'media_buttons' => false]);
        }
    } else {
        dt_field_input($name, $type, $val);
    }
    $html = ob_get_clean();

    if ($bare) {
        echo $html; // phpcs:ignore -- built from escaped parts above
    } else {
        echo '<tr><th scope="row">' . esc_html($label) . '</th><td>' . $html . '</td></tr>'; // phpcs:ignore
    }
}

function dt_repeater_row($name, $i, $subfields, $row) {
    echo '<fieldset class="dt-row" style="border:1px solid #c3c4c7;padding:10px;margin-bottom:10px;background:#fff">';
    foreach ($subfields as $sk => $sf) {
        echo '<p><strong>' . esc_html($sf[0]) . '</strong><br>';
        dt_field_input($name . '[' . $i . '][' . $sk . ']', $sf[1], $row[$sk] ?? '');
        echo '</p>';
    }
    echo '<button type="button" class="button dt-move-row" data-dir="-1" aria-label="Move up">↑</button> <button type="button" class="button dt-move-row" data-dir="1" aria-label="Move down">↓</button> ';
    echo '<button type="button" class="button-link-delete dt-remove-row">Remove row</button></fieldset>';
}
