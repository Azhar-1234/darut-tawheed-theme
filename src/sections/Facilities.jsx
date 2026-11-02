export default function Facilities({ title, items, moreText, moreUrl }) {
  if (!items.length) return null;
  return (
    <section className="section section--facilities">
      <div className="container">
        <h2 className="section__title">{title}</h2>
        <div className="grid grid--2">
          {items.map((f, i) => (
            <article className="facility" key={i}>
              <div className="facility__icon">
                {f.image ? <img src={f.image} alt="" /> : f.icon ? <i className={'fa-solid ' + f.icon} /> : null}
              </div>
              <div>
                <h3>{f.title}</h3>
                {f.text && <p>{f.text}</p>}
              </div>
            </article>
          ))}
        </div>
        {moreText && <p className="center"><a className="btn btn--primary" href={moreUrl}>{moreText}</a></p>}
      </div>
    </section>
  );
}
