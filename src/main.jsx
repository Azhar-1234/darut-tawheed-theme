import { createRoot } from 'react-dom/client';
import App from './App.jsx';

const mount = document.getElementById('dt-home');
const dataEl = document.getElementById('dt-home-data');

if (mount && dataEl) {
  createRoot(mount).render(<App data={JSON.parse(dataEl.textContent)} />);
}
