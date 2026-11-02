// Icons follow the card order; extra cards reuse them in a loop.
const icons = ['fa-book-open', 'fa-book-quran', 'fa-globe', 'fa-book', 'fa-desktop', 'fa-paper-plane', 'fa-award', 'fa-users', 'fa-user'];

export default function Features({ title, moreText, items }) {
  if (!items.length) return null;
  return (
    <section className="section section--features">
      <div className="container">
        <h2 className="section__title">{title}</h2>
        <div className="grid grid--3">
          {items.map((f, i) => (
            <article className="feature-card" key={f.url}>
              <div className="feature-card__head">
                <span className="feature-card__num">{f.num}</span>
                <span className="feature-card__icon"><i className={'fa-solid ' + icons[i % icons.length]} /></span>
              </div>
              <h3>{f.title}</h3>
              <p>{f.excerpt}</p>
              <a className="feature-card__more" href={f.url}>{moreText} <i className="fa-solid fa-arrow-right" /></a>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
