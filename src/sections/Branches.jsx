export default function Branches({ title, items }) {
  if (!items.length) return null;
  return (
    <section className="section section--branches" id="branches">
      <div className="container">
        <h2 className="section__title">{title}</h2>
        <div className="grid grid--2 branches-grid">
          {items.map((b, n) => (
            <article className="branch-card" key={n}>
              <h3>{b.url ? <a href={b.url}>{b.title}</a> : b.title}</h3>
              {b.address && <p><i className="fa-solid fa-location-dot" /> {b.address}</p>}
              {b.contacts.map(([label, info, href, icon], i) => (
                <p key={i}><i className={'fa-solid ' + icon} /> <a href={href}>{info}</a>{label ? ` (${label})` : ''}</p>
              ))}
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
