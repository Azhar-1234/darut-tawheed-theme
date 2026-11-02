export default function Notices({ title, items, allText, allUrl, bare }) {
  if (!items.length) return null;
  const box = (
    <div className="notice-box">
      <h2 className="notice-box__title"><i className="fa-solid fa-bell" /> {title}</h2>
      <ul className="notice-list">
        {items.map((n) => (
          <li key={n.url}>
            <a href={n.url}>
              <time dateTime={n.iso}>{n.date}</time>
              <span>{n.title}</span>
            </a>
          </li>
        ))}
      </ul>
      {allUrl && <a className="notice-box__more" href={allUrl}>{allText} <i className="fa-solid fa-chevron-right" /></a>}
    </div>
  );
  return bare ? box : <section className="section section--notices"><div className="container">{box}</div></section>;
}
