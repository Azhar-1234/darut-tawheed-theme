export default function Chairman({ title, photo, quote, name, designation }) {
  if (!quote) return null;
  return (
    <section className="section section--chairman">
      <div className="container">
        <h2 className="section__title">{title}</h2>
        <figure className="chairman">
          {photo && <div className="chairman__photo"><img src={photo} alt={name} /></div>}
          <div className="chairman__body">
            <blockquote>“ {quote} ”</blockquote>
            <figcaption>
              <strong>{name}</strong>
              <span>{designation}</span>
            </figcaption>
          </div>
        </figure>
      </div>
    </section>
  );
}
