import { useEffect, useState } from 'react';

export default function Slider({ slides, bare }) {
  const [i, setI] = useState(0);
  const [tick, setTick] = useState(0);
  const n = slides.length;

  useEffect(() => {
    if (n < 2) return undefined;
    const t = setInterval(() => setI((x) => (x + 1) % n), 5000);
    return () => clearInterval(t);
  }, [n, tick]);

  if (!n) return null;

  const pick = (idx) => {
    setI((idx + n) % n);
    setTick((t) => t + 1);
  };

  // The frame takes the first image's proportions so the whole picture is visible.
  const ratio = slides[0].w && slides[0].h ? `${slides[0].w} / ${slides[0].h}` : '16 / 7';

  const slider = (
    <div className="hero-slider" style={{ '--slide-ratio': ratio }}>
      {slides.map((s, idx) => (
        <div key={idx} className={'hero-slider__slide' + (idx === i ? ' is-active' : '')} style={{ '--bg-img': `url("${s.src}")` }}>
          <img src={s.src} srcSet={s.srcset || undefined} sizes="(min-width:1728px) 1120px, 100vw" alt={s.alt} loading={idx ? 'lazy' : 'eager'} />
        </div>
      ))}
      {n > 1 && (
        <>
          <button className="hero-slider__nav hero-slider__nav--prev" onClick={() => pick(i - 1)} aria-label="Previous">❮</button>
          <button className="hero-slider__nav hero-slider__nav--next" onClick={() => pick(i + 1)} aria-label="Next">❯</button>
          <div className="hero-slider__dots">
            {slides.map((_, idx) => (
              <button key={idx} className={idx === i ? 'is-active' : ''} onClick={() => pick(idx)} aria-label={`Slide ${idx + 1}`} />
            ))}
          </div>
        </>
      )}
    </div>
  );

  return bare ? slider : <section className="section section--slider"><div className="container">{slider}</div></section>;
}
