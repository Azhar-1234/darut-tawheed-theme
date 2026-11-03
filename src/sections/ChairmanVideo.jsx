import { useEffect, useRef, useState } from 'react';

// Click-to-play by default: only a cover image loads until the visitor presses play.
// With "autoplay" on, the video starts muted and on repeat once it scrolls into view.
export default function ChairmanVideo({ kind, id, src, poster, title, subtitle, autoplay, name, designation }) {
  const [playing, setPlaying] = useState(false);
  const [inView, setInView] = useState(false);
  const box = useRef(null);

  useEffect(() => {
    if (!autoplay || !box.current || !('IntersectionObserver' in window)) return undefined;
    const io = new IntersectionObserver(([e]) => { if (e.isIntersecting) { setInView(true); io.disconnect(); } }, { rootMargin: '200px' });
    io.observe(box.current);
    return () => io.disconnect();
  }, [autoplay]);

  const auto = autoplay && inView;
  const show = playing || auto;
  const label = title || 'Video';

  let player = null;
  if (show && kind === 'youtube') {
    const q = auto && !playing ? `autoplay=1&mute=1&loop=1&playlist=${id}&controls=1` : 'autoplay=1';
    player = <iframe src={`https://www.youtube-nocookie.com/embed/${id}?rel=0&modestbranding=1&playsinline=1&${q}`} title={label} allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowFullScreen />;
  } else if (show && kind === 'iframe') {
    player = <iframe src={src} title={label} allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowFullScreen />;
  } else if (show && kind === 'file') {
    player = auto && !playing
      ? <video src={src} poster={poster || undefined} autoPlay muted loop playsInline controls />
      : <video src={src} poster={poster || undefined} autoPlay controls playsInline />;
  }

  return (
    <section className="section section--cvideo">
      <div className="container">
        {title && <h2 className="section__title">{title}</h2>}
        {subtitle && <p className="cvideo__subtitle">{subtitle}</p>}
        <div className="cvideo" ref={box}>
          <div className="cvideo__frame">
            {player || (
              <button type="button" className="cvideo__cover" onClick={() => setPlaying(true)} aria-label={`${label} চালু করুন`}>
                {poster ? <img src={poster} alt="" loading="lazy" /> : <span className="cvideo__blank" />}
                <span className="cvideo__play" aria-hidden="true"><i className="fa-solid fa-play" /></span>
              </button>
            )}
          </div>
          {(name || designation) && (
            <p className="cvideo__caption"><strong>{name}</strong>{designation && <span>{designation}</span>}</p>
          )}
        </div>
      </div>
    </section>
  );
}
