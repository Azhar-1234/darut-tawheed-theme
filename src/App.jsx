import Slider from './sections/Slider.jsx';
import Notices from './sections/Notices.jsx';
import About from './sections/About.jsx';
import Features from './sections/Features.jsx';
import Facilities from './sections/Facilities.jsx';
import Chairman from './sections/Chairman.jsx';
import Contact from './sections/Contact.jsx';
import Branches from './sections/Branches.jsx';

// Keys match the section slugs saved in Theme Options > Home: layout.
const registry = {
  slider: (d) => <Slider slides={d.slides} />,
  notice_ticker: (d) => <Notices {...d.notices} />,
  about: (d) => <About {...d.about} />,
  features: (d) => <Features {...d.features} />,
  facilities: (d) => <Facilities {...d.facilities} />,
  chairman: (d) => <Chairman {...d.chairman} />,
  contact: (d) => <Contact {...d.contact} />,
  branches: (d) => <Branches {...d.branches} />,
};

export default function App({ data }) {
  // Slider and notices sit side by side (at the position of whichever comes first).
  const hero = data.sections.includes('slider') && data.sections.includes('notice_ticker');
  const heroAt = hero ? data.sections.find((s) => s === 'slider' || s === 'notice_ticker') : null;

  return (
    <>
      {data.sections.map((slug) => {
        if (hero && slug === heroAt) {
          return (
            <section key="hero" className="section section--slider">
              <div className="container hero-row">
                <div className="hero-row__main"><Slider slides={data.slides} bare /></div>
                <div className="hero-row__side"><Notices {...data.notices} bare /></div>
              </div>
            </section>
          );
        }
        if (hero && (slug === 'slider' || slug === 'notice_ticker')) return null;
        const render = registry[slug];
        return render ? <div key={slug}>{render(data)}</div> : null;
      })}
    </>
  );
}
