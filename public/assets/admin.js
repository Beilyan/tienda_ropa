'use strict';

/* =====================================================
   JAVASCRIPT DEL PANEL DEL DUEÑO
   Menú en celular, confirmaciones, vista previa de imágenes
   y cálculo de descuento mientras se escribe.
   ===================================================== */

/* ---------- Menú lateral en celular ---------- */
(() => {
  const lateral = document.getElementById('lateral');
  const fondo = document.getElementById('fondoLateral');
  if (!lateral || !fondo) return;
  const abrir = () => {
    lateral.classList.remove('-translate-x-full', 'invisible');
    fondo.classList.remove('hidden');
  };
  const cerrar = () => {
    lateral.classList.add('-translate-x-full', 'invisible');
    fondo.classList.add('hidden');
  };
  document.querySelectorAll('[data-abrir-lateral]').forEach((b) => b.addEventListener('click', abrir));
  document.querySelectorAll('[data-cerrar-lateral]').forEach((b) => b.addEventListener('click', cerrar));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrar(); });
})();

/* ---------- Confirmación antes de borrar o acciones delicadas ---------- */
document.addEventListener('submit', (e) => {
  const msg = e.target.dataset ? e.target.dataset.confirmar : null;
  if (msg && !window.confirm(msg)) e.preventDefault();
});

/* ---------- Vista previa de la imagen elegida ---------- */
document.querySelectorAll('input[type="file"][data-vista]').forEach((input) => {
  input.addEventListener('change', () => {
    const destino = document.getElementById(input.dataset.vista);
    const archivo = input.files && input.files[0];
    if (!destino || !archivo) return;
    const url = URL.createObjectURL(archivo);
    destino.innerHTML = '';
    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Vista previa de la imagen elegida';
    img.className = destino.hasAttribute('data-contener') ? 'max-h-16 w-auto max-w-[220px] object-contain' : 'w-full h-full object-cover';
    destino.appendChild(img);
  });
});

/* ---------- Color: el cuadro y el texto hexadecimal van juntos ---------- */
document.querySelectorAll('[data-color-par]').forEach((caja) => {
  const selector = caja.querySelector('input[type="color"]');
  const texto = caja.querySelector('input[type="text"]');
  if (!selector || !texto) return;
  selector.addEventListener('input', () => { texto.value = selector.value; });
  texto.addEventListener('input', () => {
    if (/^#[0-9a-fA-F]{6}$/.test(texto.value)) selector.value = texto.value;
  });
});

/* ---------- Cálculo de descuento mientras se escribe el precio ---------- */
document.querySelectorAll('[data-precios]').forEach((bloque) => {
  const precio = bloque.querySelector('[data-precio]');
  const oferta = bloque.querySelector('[data-oferta]');
  const salida = bloque.querySelector('[data-descuento]');
  if (!precio || !oferta || !salida) return;
  const num = (v) => parseFloat(String(v).replace(/[$,\s]/g, ''));
  function calcular() {
    const p = num(precio.value);
    const o = num(oferta.value);
    if (!isNaN(p) && !isNaN(o) && p > 0 && o > 0 && o < p) {
      salida.textContent = `Descuento: ${Math.round((1 - o / p) * 100)}% (el cliente verá −$${(p - o).toLocaleString('es-MX')})`;
      salida.className = 'text-sm text-emerald-600 font-medium mt-2';
    } else if (!isNaN(o) && o > 0) {
      salida.textContent = 'El precio de oferta debe ser menor al precio normal.';
      salida.className = 'text-sm text-rose-600 font-medium mt-2';
    } else {
      salida.textContent = '';
    }
  }
  precio.addEventListener('input', calcular);
  oferta.addEventListener('input', calcular);
  calcular();
});
