const errors = {
  0: 'বার্তা পাঠানো যায়নি, অনুগ্রহ করে আবার চেষ্টা করুন।',
  email: 'ইমেইল ঠিকানাটি সঠিক নয়। অনুগ্রহ করে একটি বৈধ ইমেইল দিন।',
  invalid: 'নাম (২–৮০ অক্ষর) ও মন্তব্য (৫–২০০০ অক্ষর) সঠিকভাবে দিন; মন্তব্যে অতিরিক্ত লিংক দেওয়া যাবে না।',
  rate: 'অল্প সময়ে অনেকবার বার্তা পাঠানো হয়েছে। কিছুক্ষণ পর আবার চেষ্টা করুন।',
  expired: 'ফর্মের মেয়াদ শেষ হয়ে গেছে। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।',
};

export default function Contact({ title, phone, phoneHref, whatsapp, waHref, email, hours, address, mapLink, mapEmbed, mapSrc, sent, action, nonce }) {
  return (
    <section className="section section--contact" id="contact">
      <div className="container">
        <h2 className="section__title">{title}</h2>
        <div className="grid grid--3 contact-cards">
          {phone && (
            <div className="contact-card"><i className="fa-solid fa-phone" /><h3>ফোন করুন</h3>
              <a href={'tel:' + phoneHref}>{phone}</a>
              {whatsapp && <a href={waHref} target="_blank" rel="noopener noreferrer"><i className="fa-brands fa-whatsapp" /> {whatsapp}</a>}
              {hours && <small>{hours}</small>}
            </div>
          )}
          {email && (
            <div className="contact-card"><i className="fa-solid fa-envelope" /><h3>ইমেইল পাঠান</h3>
              <a href={'mailto:' + email}>{email}</a>
              <small>আপনার প্রশ্ন বা ফিডব্যাক পাঠান</small>
            </div>
          )}
          {address && (
            <div className="contact-card"><i className="fa-solid fa-location-dot" /><h3>আমাদের অবস্থান</h3>
              <span>{address}</span>
              {mapLink && <a href={mapLink} target="_blank" rel="noopener noreferrer">ম্যাপে দেখুন</a>}
            </div>
          )}
        </div>

        <div className="contact-panel">
          <form className="feedback" method="post" action={action}>
            <h3>আপনার মূল্যবান মন্তব্য</h3>
            {sent === '1' && <p className="notice-ok" role="status">ধন্যবাদ, আপনার বার্তা সফলভাবে পাঠানো হয়েছে।</p>}
            {sent && sent !== '1' && <p className="notice-err" role="alert">{errors[sent] || errors['0']}</p>}
            <input type="hidden" name="action" value="dt_feedback" />
            <input type="hidden" name="dt_nonce" value={nonce} />
            <input type="text" name="dt_website" tabIndex={-1} autoComplete="off" className="hp" aria-hidden="true" />
            <div className="feedback__row">
              <input type="text" name="dt_name" placeholder="আপনার সম্পূর্ণ নাম *" required minLength={2} maxLength={80} autoComplete="name" />
              <input type="tel" name="dt_phone" placeholder="আপনার ফোন (ঐচ্ছিক)" maxLength={20} pattern="[0-9+\-\s()]{6,20}" autoComplete="tel" />
            </div>
            <input type="email" name="dt_email" placeholder="আপনার ইমেইল *" required maxLength={100} autoComplete="email" />
            <textarea name="dt_message" rows={5} placeholder="আপনার মন্তব্য *" required minLength={5} maxLength={2000} />
            <button className="btn btn--primary" type="submit">মন্তব্য প্রেরণ করুন</button>
          </form>
          <div className="map">
            <h3>ম্যাপে আমাদের খুঁজুন</h3>
            {mapEmbed
              ? <div dangerouslySetInnerHTML={{ __html: mapEmbed }} />
              : mapSrc && <iframe title="Map" src={mapSrc} loading="lazy" referrerPolicy="no-referrer-when-downgrade" allowFullScreen />}
          </div>
        </div>
      </div>
    </section>
  );
}
