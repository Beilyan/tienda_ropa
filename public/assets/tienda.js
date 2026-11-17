'use strict';

/* =====================================================
   JAVASCRIPT DE LA TIENDA
   Menú lateral, carrusel del inicio, favoritos sin recargar
   y selector de cantidad. Funciona sin dependencias.
   ===================================================== */

/* ---------- Menú lateral ---------- */
(() => {
  const menu = document.getElementById('menuLateral');
  const fondo = document.getElementById('fondoMenu');
  if (!menu || !fondo) return;
  let ultimoFoco = null;

  function abrir() {
    ultimoFoco = document.activeElement;
    menu.classList.remove('-translate-x-full', 'invisible');
    fondo.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    const cerrar = menu.querySelector('[data-cerrar-menu]');
    if (cerrar) cerrar.focus();
  }
  function cerrar() {
    menu.classList.add('-translate-x-full', 'invisible');
    fondo.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    if (ultimoFoco) ultimoFoco.focus();
  }

  document.querySelectorAll('[data-abrir-menu]').forEach((b) => b.addEventListener('click', abrir));
  document.querySelectorAll('[data-cerrar-menu]').forEach((b) => b.addEventListener('click', cerrar));
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !menu.classList.contains('invisible')) cerrar();
  });
})();

/* ---------- Carrusel de banners del inicio ---------- */
(() => {
  const cont = document.querySelector('[data-carrusel]');
  if (!cont) return;
  const slides = [...cont.querySelectorAll('[data-slide]')];
  const puntos = [...cont.querySelectorAll('[data-punto]')];
  if (slides.length < 2) return;
  let actual = 0;
  let temporizador = null;
  const reducir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function mostrar(i) {
    actual = (i + slides.length) % slides.length;
    slides.forEach((s, n) => {
      s.classList.toggle('hidden', n !== actual);
      s.setAttribute('aria-hidden', n === actual ? 'false' : 'true');
    });
    puntos.forEach((p, n) => {
      p.setAttribute('aria-current', n === actual ? 'true' : 'false');
      p.classList.toggle('bg-white', n === actual);
      p.classList.toggle('bg-white/40', n !== actual);
    });
  }
  function iniciar() {
    if (reducir) return;
    detener();
    temporizador = setInterval(() => mostrar(actual + 1), 6500);
  }
  function detener() {
    if (temporizador) clearInterval(temporizador);
  }

  puntos.forEach((p, n) => p.addEventListener('click', () => { mostrar(n); iniciar(); }));
  cont.addEventListener('mouseenter', detener);
  cont.addEventListener('mouseleave', iniciar);
  cont.addEventListener('focusin', detener);
  cont.addEventListener('focusout', iniciar);
  mostrar(0);
  iniciar();
})();

/* ---------- Favoritos: cambia el corazón sin recargar la página ---------- */
document.querySelectorAll('form[data-favorito]').forEach((form) => {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const boton = form.querySelector('button');
    try {
      const resp = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'fetch', Accept: 'application/json' },
        credentials: 'same-origin',
      });
      if (resp.status === 401) { window.location.href = form.action.replace('favorito.php', 'login.php'); return; }
      const datos = await resp.json();
      if (!datos.ok) throw new Error('fallo');
      boton.setAttribute('aria-pressed', datos.favorito ? 'true' : 'false');
      const corazon = boton.querySelector('[data-corazon]');
      if (corazon) corazon.setAttribute('fill', datos.favorito ? 'currentColor' : 'none');
      // En la página de favoritos, quitar la tarjeta al desmarcar
      if (!datos.favorito && document.body.dataset.pagina === 'favoritos') {
        const tarjeta = form.closest('article');
        if (tarjeta) tarjeta.remove();
      }
    } catch (err) {
      form.submit(); // si algo falla, se envía como formulario normal
    }
  });
});

/* ---------- Selector de cantidad (producto y carrito) ---------- */
document.querySelectorAll('[data-cantidad]').forEach((caja) => {
  const campo = caja.querySelector('input[type="number"]');
  const max = parseInt(campo.getAttribute('max') || '99', 10);
  caja.querySelectorAll('[data-paso]').forEach((b) => {
    b.addEventListener('click', () => {
      const nuevo = (parseInt(campo.value, 10) || 1) + parseInt(b.dataset.paso, 10);
      campo.value = Math.min(Math.max(nuevo, 1), max);
      campo.dispatchEvent(new Event('change', { bubbles: true }));
    });
  });
});

/* Los formularios del carrito se envían solos al cambiar la cantidad */
document.querySelectorAll('form[data-autoenviar]').forEach((f) => {
  f.addEventListener('change', () => f.requestSubmit());
});

/* ---------- Producto: al elegir talla se muestra su disponibilidad ---------- */
document.querySelectorAll('input[name="talla"][data-stock]').forEach((radio) => {
  radio.addEventListener('change', () => {
    const aviso = document.getElementById('aviso-talla');
    const campo = document.getElementById('campo-cantidad');
    const stock = parseInt(radio.dataset.stock, 10);
    if (campo) campo.setAttribute('max', String(Math.min(stock, 20)));
    if (campo && parseInt(campo.value, 10) > stock) campo.value = stock;
    if (aviso) aviso.textContent = stock <= 3 ? `¡Últimas ${stock} piezas en talla ${radio.value}!` : `Talla ${radio.value} disponible.`;
  });
});
