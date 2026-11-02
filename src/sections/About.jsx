export default function About({ bg, badge, title, html }) {
  if (!title && !html) return null;
  const solo = !bg;
  return (
    <section className="section section--about">
      <div className={'container about' + (solo ? ' about--solo' : '')}>
        {bg && (
          <div className="about__media">
            <img src={bg} alt={title} />
            {badge && <span className="badge">{badge}</span>}
          </div>
        )}
        <div className="about__body">
          {solo && badge && <span className="badge">{badge}</span>}
          {title && <h2 className="section__title">{title}</h2>}
          {html && <div className="prose" dangerouslySetInnerHTML={{ __html: html }} />}
        </div>
      </div>
    </section>
  );
}
