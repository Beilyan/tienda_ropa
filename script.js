'use strict';

/* =====================================================
   1. CONFIGURACIÓN
   Qué hace: valores que puedes cambiar fácilmente.
   ===================================================== */
const CONFIG = {
  // Si pones una clave aquí ('muertos', 'navidad', 'amor', 'independencia', 'nuevo'),
  // TODOS los visitantes verán ese sello. Con null se decide por la fecha de hoy.
  festividadForzada: null,

  // Clave del panel de administración. IMPORTANTE: al ser JavaScript del navegador,
  // cualquiera puede leerla. Es solo una demostración; en una tienda real
  // la autenticación debe hacerse en un servidor.
  claveAdmin: 'admin2026',

  claves: {
    carrito: 'trama_carrito_v1',
    festividad: 'trama_festividad_v1'
  },
  maxPorArticulo: 10
};

const TALLAS = ['S', 'M', 'L', 'XL'];

/* =====================================================
   2. DATOS DE PRODUCTOS
   Qué hace: catálogo inicial. Para usar fotos reales agrega la ruta en "imagen"
   (por ejemplo "img/cobalto.jpg"). Si es null, o la foto no carga,
   se dibuja una camiseta en percha con SVG como respaldo.
   ===================================================== */
const PRODUCTOS = [
  {
    id: 'cobalto', nombre: 'Camiseta Cobalto Clásica', color: 'Azul cobalto', familia: 'azul', hex: '#3F66A8',
    precio: 32, categoria: 'CLÁSICO', categoriaClave: 'clasico', tallas: ['S', 'M', 'L', 'XL'], imagen: null,
    descripcion: 'Algodón peinado de peso medio, cuello redondo reforzado y corte recto. La básica azul que combina con todo.'
  },
  {
    id: 'violeta', nombre: 'Camiseta Neblina Violeta', color: 'Violeta', familia: 'violeta', hex: '#7A5A9E',
    precio: 30, categoria: 'BRIGHTS', categoriaClave: 'brights', tallas: ['S', 'M', 'L'], imagen: null,
    descripcion: 'Violeta apagado con acabado jaspeado. Tela suave y caída holgada para uso diario.'
  },
  {
    id: 'bosque', nombre: 'Camiseta Bosque Cuello Redondo', color: 'Verde bosque', familia: 'verde', hex: '#4F7F4F',
    precio: 28, categoria: 'CLÁSICO', categoriaClave: 'clasico', tallas: ['M', 'L', 'XL'], imagen: null,
    descripcion: 'Verde profundo de tono natural. Cuello redondo, costuras dobles y tela que mejora con cada lavado.'
  },
  {
    id: 'jaspeado', nombre: 'Camiseta Gris Jaspeado', color: 'Gris jaspeado', familia: 'gris', hex: '#8C9090',
    precio: 24, categoria: 'CLÁSICO', categoriaClave: 'clasico', tallas: ['S', 'M', 'L', 'XL'], imagen: null,
    descripcion: 'Gris con textura jaspeada, ligera y fresca. Ideal para combinar con cualquier otra prenda.'
  },
  {
    id: 'atardecer', nombre: 'Camiseta Atardecer Naranja', color: 'Naranja', familia: 'naranja', hex: '#E8782E',
    precio: 29, categoria: 'BRIGHTS', categoriaClave: 'brights', tallas: ['S', 'M', 'L', 'XL'], imagen: null,
    descripcion: 'Naranja intenso para destacar. Algodón suave con corte unisex y cuello que no se deforma.'
  },
  {
    id: 'menta', nombre: 'Camiseta Menta Esencial', color: 'Verde menta', familia: 'verde', hex: '#8FD9BC',
    precio: 26, categoria: 'BRIGHTS', categoriaClave: 'brights', tallas: ['S', 'M'], imagen: null,
    descripcion: 'Verde menta claro y fresco. Tela ligera pensada para días de calor.'
  },
  {
    id: 'negro', nombre: 'Camiseta Negro Estándar', color: 'Negro', familia: 'negro', hex: '#1E1E1E',
    precio: 32, categoria: 'CLÁSICO', categoriaClave: 'clasico', tallas: ['S', 'M', 'L', 'XL'], imagen: null,
    descripcion: 'Negro profundo que conserva el color. Corte recto, peso medio y acabado limpio.'
  },
  {
    id: 'blanca', nombre: 'Camiseta Blanca Diaria', color: 'Blanco', familia: 'blanco', hex: '#F4F4F0',
    precio: 24, categoria: 'CLÁSICO', categoriaClave: 'clasico', tallas: ['M', 'L', 'XL'], imagen: null,
    descripcion: 'La blanca de todos los días. Algodón opaco, cuello firme y tacto suave.'
  }
];

/* =====================================================
   3. UTILIDADES
   ===================================================== */
const $ = (selector, contexto = document) => contexto.querySelector(selector);
const $$ = (selector, contexto = document) => [...contexto.querySelectorAll(selector)];

// Formato de moneda: $32.00
const formatoMXN = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' });
const precioTexto = (valor) => formatoMXN.format(valor);

// Quita acentos y mayúsculas para comparar textos ("clasico" encuentra "CLÁSICO")
const normalizar = (texto) =>
  texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

// Convierte un texto HTML en un elemento del DOM
function htmlAElemento(html) {
  const plantilla = document.createElement('template');
  plantilla.innerHTML = html.trim();
  return plantilla.content.firstElementChild;
}

// localStorage con protección (puede estar bloqueado en modo privado)
const almacen = {
  leer(clave) {
    try { return localStorage.getItem(clave); } catch { return null; }
  },
  guardar(clave, valor) {
    try { localStorage.setItem(clave, valor); } catch { /* sin almacenamiento disponible */ }
  },
  borrar(clave) {
    try { localStorage.removeItem(clave); } catch { /* sin almacenamiento disponible */ }
  }
};

/* =====================================================
   4. IMÁGENES DE PRODUCTO
   Qué hace: dibuja una camiseta en percha de madera sobre muro claro (SVG).
   Todas comparten composición, proporción 4:3 y fondo neutro.
   ===================================================== */
const FORMA_CAMISETA =
  'M160 82 Q200 112 240 82 L286 98 L326 142 L298 168 L278 148 L278 262 Q278 268 272 268 ' +
  'L128 268 Q122 268 122 262 L122 148 L102 168 L74 142 L114 98 Z';

function crearCamisetaSVG(p, sufijo) {
  const id = `${p.id}-${sufijo}`; // ids únicos por cada SVG de la página
  return `
<svg viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" role="img"
     aria-label="${p.nombre}: camiseta color ${p.color.toLowerCase()} colgada de una percha de madera sobre un muro claro">
  <defs>
    <pattern id="muro-${id}" width="80" height="40" patternUnits="userSpaceOnUse">
      <rect width="80" height="40" fill="#E2E2DE"/>
      <path d="M0 0H80M0 20H80M0 40H80M0 0V20M40 20V40" stroke="#F4F4F1" stroke-width="2.5" fill="none"/>
    </pattern>
    <pattern id="tela-${id}" width="4" height="3" patternUnits="userSpaceOnUse">
      <rect width="4" height="1" fill="#000" opacity=".08"/>
    </pattern>
    <filter id="ruido-${id}" x="0" y="0" width="100%" height="100%">
      <feTurbulence type="fractalNoise" baseFrequency="1.1" numOctaves="2" seed="7"/>
      <feColorMatrix type="saturate" values="0"/>
    </filter>
    <filter id="sombra-${id}" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="5"/>
    </filter>
    <clipPath id="forma-${id}"><path d="${FORMA_CAMISETA}"/></clipPath>
  </defs>

  <!-- Fondo: muro de ladrillo blanco con grano -->
  <rect width="400" height="300" fill="url(#muro-${id})"/>
  <rect width="400" height="300" filter="url(#ruido-${id})" opacity=".14" style="mix-blend-mode:multiply"/>

  <!-- Sombra proyectada de la camiseta -->
  <path d="${FORMA_CAMISETA}" transform="translate(5 8)" fill="#000" opacity=".22" filter="url(#sombra-${id})"/>

  <!-- Camiseta: color base, trama, jaspeado y pliegues -->
  <path d="${FORMA_CAMISETA}" fill="${p.hex}"/>
  <g clip-path="url(#forma-${id})">
    <rect width="400" height="300" fill="url(#tela-${id})"/>
    <rect width="400" height="300" filter="url(#ruido-${id})" opacity=".3" style="mix-blend-mode:multiply"/>
    <path d="M150 140 Q158 200 148 268" stroke="#000" stroke-opacity=".08" stroke-width="10" fill="none"/>
    <path d="M252 150 Q244 205 256 268" stroke="#fff" stroke-opacity=".1" stroke-width="12" fill="none"/>
  </g>
  <path d="${FORMA_CAMISETA}" fill="none" stroke="#000" stroke-opacity=".28" stroke-width="1.5" stroke-linejoin="round"/>

  <!-- Dobladillos y cuello -->
  <path d="M79.5 136 L107.5 162 M320.5 136 L292.5 162 M122 258 L278 258" stroke="#000" stroke-opacity=".2" stroke-width="1.2" fill="none"/>
  <path d="M166 81 Q200 106 234 81 Q200 90 166 81Z" fill="#000" fill-opacity=".28"/>
  <path d="M158 82 Q200 118 242 82" fill="none" stroke="#000" stroke-opacity=".22" stroke-width="6" stroke-linecap="round"/>

  <!-- Percha de madera -->
  <path d="M200 78 L200 62 C200 50 214 46 219 52 C223 58 218 64 212 61" fill="none" stroke="#9A9A96" stroke-width="5" stroke-linecap="round"/>
  <path d="M200 78 L200 62 C200 50 214 46 219 52 C223 58 218 64 212 61" fill="none" stroke="#EDEDEA" stroke-width="2.4" stroke-linecap="round"/>
  <path d="M120 99 Q200 58 280 99" fill="none" stroke="#7A5230" stroke-width="9" stroke-linecap="round"/>
  <path d="M120 99 Q200 58 280 99" fill="none" stroke="#C99B63" stroke-width="5.5" stroke-linecap="round"/>
</svg>`;
}

// Devuelve <img> si el producto tiene foto real; si no, el SVG de respaldo
function htmlImagenProducto(p, sufijo) {
  if (p.imagen) {
    return `<img src="${p.imagen}" alt="${p.nombre}: camiseta color ${p.color.toLowerCase()} colgada de una percha de madera"
                 loading="lazy" decoding="async" data-respaldo="${p.id}:${sufijo}">`;
  }
  return crearCamisetaSVG(p, sufijo);
}

// Si una foto no carga, se reemplaza por el SVG de respaldo
document.addEventListener('error', (evento) => {
  const img = evento.target;
  if (!(img instanceof HTMLImageElement) || !img.dataset.respaldo) return;
  const [id, sufijo] = img.dataset.respaldo.split(':');
  const producto = PRODUCTOS.find((p) => p.id === id);
  if (producto) img.replaceWith(htmlAElemento(crearCamisetaSVG(producto, sufijo)));
}, true);

/* =====================================================
   5. ESTADO Y REFERENCIAS AL DOM
   ===================================================== */
const estado = {
  categoria: 'todo',
  busqueda: '',
  talla: null,
  color: 'todos',
  precio: 'todos',
  orden: 'destacados'
};

const NOMBRE_CATEGORIA = { todo: 'TODO', clasico: 'CLÁSICO', brights: 'BRIGHTS' };

const el = {
  titulo: $('#titulo-catalogo'),
  formBusqueda: $('#form-busqueda'),
  busqueda: $('#busqueda'),
  limpiarBusqueda: $('#limpiar-busqueda'),
  filtrosCategoria: $$('.filtro'),
  chipsTalla: $$('.chip'),
  filtroColor: $('#filtro-color'),
  filtroPrecio: $('#filtro-precio'),
  orden: $('#orden'),
  limpiarFiltros: $('#limpiar-filtros'),
  resultado: $('#resultado'),
  catalogo: $('#catalogo'),
  sinResultados: $('#sin-resultados'),
  reiniciarTodo: $('#reiniciar-todo'),
  aviso: $('#aviso'),

  modal: $('#modal-producto'),
  modalImagen: $('#modal-imagen'),
  modalCategoria: $('#modal-categoria'),
  modalTitulo: $('#modal-titulo'),
  modalPrecio: $('#modal-precio'),
  modalDescripcion: $('#modal-descripcion'),
  modalTallas: $('#modal-tallas'),
  modalError: $('#modal-error'),
  modalAgregar: $('#modal-agregar'),

  abrirCarrito: $('#abrir-carrito'),
  contador: $('#carrito-contador'),
  panelCarrito: $('#panel-carrito'),
  carritoVacio: $('#carrito-vacio'),
  carritoLista: $('#carrito-lista'),
  carritoPie: $('#carrito-pie'),
  carritoSubtotal: $('#carrito-subtotal'),
  vaciarCarrito: $('#vaciar-carrito'),

  sello: $('#sello'),
  abrirAdmin: $('#abrir-admin'),
  modalAdmin: $('#modal-admin'),
  adminLogin: $('#admin-login'),
  adminClave: $('#admin-clave'),
  adminError: $('#admin-error'),
  adminPanel: $('#admin-panel'),
  adminOpciones: $('#admin-opciones')
};

/* =====================================================
   6. BÚSQUEDA, FILTROS Y ORDENAMIENTO
   Qué hace: combina búsqueda + categoría + talla + color + precio + orden.
   ===================================================== */
function coincidePrecio(precio, rango) {
  switch (rango) {
    case 'hasta-26': return precio <= 26;
    case '27-30': return precio >= 27 && precio <= 30;
    case 'mas-30': return precio > 30;
    default: return true;
  }
}

function obtenerProductosVisibles() {
  const palabras = normalizar(estado.busqueda.trim()).split(/\s+/).filter(Boolean);

  const lista = PRODUCTOS.filter((p) => {
    if (estado.categoria !== 'todo' && p.categoriaClave !== estado.categoria) return false;
    if (estado.talla && !p.tallas.includes(estado.talla)) return false;
    if (estado.color !== 'todos' && p.familia !== estado.color) return false;
    if (!coincidePrecio(p.precio, estado.precio)) return false;

    if (palabras.length) {
      const texto = normalizar(`${p.nombre} ${p.categoria} ${p.color} camiseta`);
      if (!palabras.every((palabra) => texto.includes(palabra))) return false;
    }
    return true;
  });

  switch (estado.orden) {
    case 'precio-asc': lista.sort((a, b) => a.precio - b.precio); break;
    case 'precio-desc': lista.sort((a, b) => b.precio - a.precio); break;
    case 'nombre-az': lista.sort((a, b) => a.nombre.localeCompare(b.nombre, 'es')); break;
    default: break; // "destacados": orden original
  }
  return lista;
}

function hayFiltrosActivos() {
  return Boolean(
    estado.busqueda.trim() || estado.categoria !== 'todo' || estado.talla ||
    estado.color !== 'todos' || estado.precio !== 'todos'
  );
}

/* =====================================================
   7. RENDERIZADO DEL CATÁLOGO
   ===================================================== */
function plantillaTarjeta(p) {
  return `
<li class="catalogo__item">
  <button type="button" class="tarjeta" data-id="${p.id}">
    <span class="tarjeta__imagen">${htmlImagenProducto(p, 'tarjeta')}</span>
    <span class="tarjeta__info">
      <span class="tarjeta__meta">
        <span class="etiqueta">CAMISA</span>
        <span class="etiqueta etiqueta--categoria">${p.categoria}</span>
      </span>
      <span class="tarjeta__nombre">${p.nombre}</span>
      <span class="tarjeta__precio">${precioTexto(p.precio)} <abbr title="Pesos mexicanos">MXN</abbr></span>
    </span>
  </button>
</li>`;
}

function renderCatalogo() {
  const visibles = obtenerProductosVisibles();

  el.catalogo.innerHTML = visibles.map(plantillaTarjeta).join('');
  el.catalogo.hidden = visibles.length === 0;
  el.sinResultados.hidden = visibles.length !== 0;

  const total = visibles.length;
  el.resultado.textContent = total === 1 ? '1 producto' : `${total} productos`;

  // Título según la categoría activa
  el.titulo.textContent = NOMBRE_CATEGORIA[estado.categoria];

  // Botón de limpiar búsqueda y de limpiar filtros
  el.limpiarBusqueda.hidden = estado.busqueda === '';
  el.limpiarFiltros.hidden = !hayFiltrosActivos();

  // Estado visual de botones de categoría y chips de talla
  el.filtrosCategoria.forEach((boton) => {
    const activo = boton.dataset.categoria === estado.categoria;
    boton.classList.toggle('is-activo', activo);
    boton.setAttribute('aria-pressed', String(activo));
  });
  el.chipsTalla.forEach((chip) => {
    chip.setAttribute('aria-pressed', String(chip.dataset.talla === estado.talla));
  });
}

function reiniciarTodo() {
  Object.assign(estado, { categoria: 'todo', busqueda: '', talla: null, color: 'todos', precio: 'todos' });
  el.busqueda.value = '';
  el.filtroColor.value = 'todos';
  el.filtroPrecio.value = 'todos';
  renderCatalogo();
}

/* =====================================================
   8. MODAL DE DETALLE (imagen ampliada, descripción y tallas)
   ===================================================== */
let productoAbierto = null;
let tallaElegida = null;

function abrirModal(id) {
  const p = PRODUCTOS.find((producto) => producto.id === id);
  if (!p) return;
  productoAbierto = p;
  tallaElegida = null;

  el.modalImagen.innerHTML = htmlImagenProducto(p, 'modal');
  el.modalCategoria.textContent = `CAMISA / ${p.categoria}`;
  el.modalTitulo.textContent = p.nombre;
  el.modalPrecio.textContent = `${precioTexto(p.precio)} MXN`;
  el.modalDescripcion.textContent = `${p.descripcion} Color: ${p.color}.`;
  el.modalError.textContent = '';
  el.modalAgregar.setAttribute('aria-disabled', 'true');

  // Tallas: las que no existen para este producto quedan deshabilitadas
  el.modalTallas.innerHTML = TALLAS.map((talla) => {
    const disponible = p.tallas.includes(talla);
    return `
      <label class="talla">
        <input type="radio" name="talla" value="${talla}" ${disponible ? '' : 'disabled'}
               aria-label="Talla ${talla}${disponible ? '' : ', agotada'}">
        <span>${talla}</span>
      </label>`;
  }).join('');

  el.modal.showModal();
}

// Elegir talla (solo se aceptan valores válidos y disponibles)
el.modalTallas.addEventListener('change', (evento) => {
  const valor = evento.target.value;
  if (!TALLAS.includes(valor) || !productoAbierto.tallas.includes(valor)) return;
  tallaElegida = valor;
  el.modalError.textContent = '';
  el.modalAgregar.setAttribute('aria-disabled', 'false');
});

el.modalAgregar.addEventListener('click', () => {
  if (!tallaElegida) {
    el.modalError.textContent = 'Elige una talla para poder agregar la camiseta.';
    return;
  }
  agregarAlCarrito(productoAbierto.id, tallaElegida);
  el.modal.close();
  mostrarAviso(`Agregada: ${productoAbierto.nombre}, talla ${tallaElegida}`);
});

/* =====================================================
   9. CARRITO (con localStorage)
   Estructura: [{ id, talla, cantidad }]
   ===================================================== */
function cargarCarrito() {
  try {
    const guardado = JSON.parse(almacen.leer(CONFIG.claves.carrito) || '[]');
    if (!Array.isArray(guardado)) return [];
    // Solo se aceptan líneas válidas (producto existente, talla válida, cantidad entera)
    return guardado
      .filter((l) => PRODUCTOS.some((p) => p.id === l.id) && TALLAS.includes(l.talla) &&
                     Number.isInteger(l.cantidad) && l.cantidad > 0)
      .map((l) => ({ id: l.id, talla: l.talla, cantidad: Math.min(l.cantidad, CONFIG.maxPorArticulo) }));
  } catch {
    return [];
  }
}

let carrito = cargarCarrito();

const guardarCarrito = () => almacen.guardar(CONFIG.claves.carrito, JSON.stringify(carrito));
const claveLinea = (linea) => `${linea.id}|${linea.talla}`;

function agregarAlCarrito(id, talla) {
  const existente = carrito.find((l) => l.id === id && l.talla === talla);
  if (existente) {
    existente.cantidad = Math.min(existente.cantidad + 1, CONFIG.maxPorArticulo);
  } else {
    carrito.push({ id, talla, cantidad: 1 });
  }
  guardarCarrito();
  renderCarrito();
}

function cambiarCantidad(clave, cambio) {
  const linea = carrito.find((l) => claveLinea(l) === clave);
  if (!linea) return;
  linea.cantidad = Math.min(Math.max(linea.cantidad + cambio, 1), CONFIG.maxPorArticulo);
  guardarCarrito();
  renderCarrito();
}

function quitarDelCarrito(clave) {
  carrito = carrito.filter((l) => claveLinea(l) !== clave);
  guardarCarrito();
  renderCarrito();
}

function plantillaLinea(linea) {
  const p = PRODUCTOS.find((producto) => producto.id === linea.id);
  const clave = claveLinea(linea);
  return `
<li class="linea">
  <span class="linea__imagen">${htmlImagenProducto(p, `carrito-${linea.talla}`)}</span>
  <div>
    <p class="linea__nombre">${p.nombre}</p>
    <p class="etiqueta">TALLA ${linea.talla}</p>
    <p class="linea__precio">${precioTexto(p.precio * linea.cantidad)}</p>
    <div class="cantidad" role="group" aria-label="Cantidad de ${p.nombre}, talla ${linea.talla}">
      <button type="button" data-accion="menos" data-clave="${clave}" aria-label="Disminuir cantidad" ${linea.cantidad <= 1 ? 'disabled' : ''}>−</button>
      <output aria-live="polite">${linea.cantidad}</output>
      <button type="button" data-accion="mas" data-clave="${clave}" aria-label="Aumentar cantidad" ${linea.cantidad >= CONFIG.maxPorArticulo ? 'disabled' : ''}>+</button>
    </div>
  </div>
  <button type="button" class="linea__quitar" data-accion="quitar" data-clave="${clave}" aria-label="Eliminar ${p.nombre}, talla ${linea.talla}, del carrito">QUITAR</button>
</li>`;
}

function renderCarrito() {
  const unidades = carrito.reduce((suma, l) => suma + l.cantidad, 0);
  const subtotal = carrito.reduce((suma, l) => {
    const p = PRODUCTOS.find((producto) => producto.id === l.id);
    return suma + p.precio * l.cantidad;
  }, 0);

  el.contador.textContent = unidades;
  el.contador.classList.toggle('hay-articulos', unidades > 0);
  el.abrirCarrito.setAttribute('aria-label',
    `Abrir carrito, ${unidades} ${unidades === 1 ? 'artículo' : 'artículos'}`);

  el.carritoLista.innerHTML = carrito.map(plantillaLinea).join('');
  el.carritoVacio.hidden = carrito.length > 0;
  el.carritoPie.hidden = carrito.length === 0;
  el.carritoSubtotal.textContent = `${precioTexto(subtotal)} MXN`;
}

// Acciones dentro de la lista del carrito (delegación de eventos)
el.carritoLista.addEventListener('click', (evento) => {
  const boton = evento.target.closest('button[data-accion]');
  if (!boton) return;
  const { accion, clave } = boton.dataset;

  if (accion === 'mas') cambiarCantidad(clave, 1);
  if (accion === 'menos') cambiarCantidad(clave, -1);
  if (accion === 'quitar') quitarDelCarrito(clave);

  // Devuelve el foco al control usado (la lista se vuelve a dibujar)
  const siguiente = accion === 'quitar'
    ? $('.carrito__titulo', el.panelCarrito)
    : $(`[data-clave="${clave}"][data-accion="${accion}"]:not(:disabled)`, el.carritoLista) ||
      $(`[data-clave="${clave}"][data-accion="quitar"]`, el.carritoLista);
  if (siguiente) {
    if (accion === 'quitar') siguiente.setAttribute('tabindex', '-1');
    siguiente.focus();
  }
});

el.vaciarCarrito.addEventListener('click', () => {
  carrito = [];
  guardarCarrito();
  renderCarrito();
  mostrarAviso('Carrito vaciado');
});

/* =====================================================
   10. AVISO TEMPORAL
   ===================================================== */
let temporizadorAviso;
function mostrarAviso(mensaje) {
  el.aviso.textContent = mensaje;
  el.aviso.hidden = false;
  clearTimeout(temporizadorAviso);
  temporizadorAviso = setTimeout(() => { el.aviso.hidden = true; }, 2600);
}

/* =====================================================
   11. SELLO FESTIVO
   Qué hace: dibuja el sello amarillo de la esquina superior derecha.
   Prioridad: 1) elección guardada en el panel de administración (este navegador),
   2) CONFIG.festividadForzada (para todos), 3) fecha de hoy.
   ===================================================== */
const ICONOS = {
  estrella: '<path d="M10 1.5l2.5 5.3 5.5.7-4 3.9 1 5.6L10 14.4 5 17l1-5.6-4-3.9 5.5-.7z" fill="#171717"/>',
  calavera: '<path d="M10 1.5C5.6 1.5 3 4.6 3 8.2c0 2.2 1 3.7 2.5 4.6V16h9v-3.2C16 11.9 17 10.4 17 8.2c0-3.6-2.6-6.7-7-6.7z" fill="#fff" stroke="#171717" stroke-width="1.4"/><circle cx="7" cy="8.6" r="1.9" fill="#171717"/><circle cx="13" cy="8.6" r="1.9" fill="#171717"/><path d="M10 10.8l-1 2h2z M8 16v-2 M10 16v-2 M12 16v-2" fill="#171717" stroke="#171717" stroke-width="1"/>',
  arbol: '<path d="M10 1l5 7h-3l4 6H4l4-6H5z" fill="#2F7D4F" stroke="#171717" stroke-width="1.2" stroke-linejoin="round"/><rect x="9" y="14" width="2" height="4" fill="#171717"/>',
  corazon: '<path d="M10 17.5C4 12.6 2 9.6 2 6.8A4 4 0 0 1 10 5.8 4 4 0 0 1 18 6.8c0 2.8-2 5.8-8 10.7z" fill="#E4572E" stroke="#171717" stroke-width="1.3" stroke-linejoin="round"/>',
  bandera: '<rect x="2" y="3" width="5.3" height="13" fill="#2F7D4F"/><rect x="7.3" y="3" width="5.4" height="13" fill="#fff"/><rect x="12.7" y="3" width="5.3" height="13" fill="#D7332A"/><rect x="2" y="3" width="16" height="13" fill="none" stroke="#171717" stroke-width="1.3"/>'
};

const FESTIVIDADES = {
  nuevo:         { nombre: 'Nueva colección', lineas: ['NUEVA', 'COLECCIÓN'], icono: ICONOS.estrella },
  muertos:       { nombre: 'Día de Muertos',  lineas: ['DÍA DE', 'MUERTOS'],  icono: ICONOS.calavera },
  navidad:       { nombre: 'Navidad',         lineas: ['FELIZ', 'NAVIDAD'],   icono: ICONOS.arbol },
  amor:          { nombre: 'Amor y Amistad',  lineas: ['AMOR Y', 'AMISTAD'],  icono: ICONOS.corazon },
  independencia: { nombre: 'Fiestas patrias', lineas: ['VIVA', 'MÉXICO'],     icono: ICONOS.bandera }
};

// Elige la festividad según la fecha
function festividadPorFecha(fecha) {
  const mes = fecha.getMonth() + 1;
  const dia = fecha.getDate();
  if (mes === 9 && dia <= 16) return 'independencia';
  if ((mes === 10 && dia >= 15) || (mes === 11 && dia <= 3)) return 'muertos';
  if (mes === 12 || (mes === 1 && dia <= 6)) return 'navidad';
  if (mes === 2 && dia <= 14) return 'amor';
  return 'nuevo';
}

function festividadActiva() {
  const guardada = almacen.leer(CONFIG.claves.festividad);
  if (guardada && FESTIVIDADES[guardada]) return guardada;
  if (CONFIG.festividadForzada && FESTIVIDADES[CONFIG.festividadForzada]) return CONFIG.festividadForzada;
  return festividadPorFecha(new Date());
}

// Puntos de una estrella de varias puntas (forma del sello)
function puntosEstrella(puntas = 14, radioExterior = 48, radioInterior = 41) {
  const puntos = [];
  for (let i = 0; i < puntas * 2; i++) {
    const radio = i % 2 ? radioInterior : radioExterior;
    const angulo = (Math.PI * i) / puntas - Math.PI / 2;
    puntos.push(`${(50 + radio * Math.cos(angulo)).toFixed(1)},${(50 + radio * Math.sin(angulo)).toFixed(1)}`);
  }
  return puntos.join(' ');
}

function renderSello() {
  const f = FESTIVIDADES[festividadActiva()];
  el.sello.innerHTML = `
    <svg viewBox="0 0 100 100" role="img" aria-label="Temporada: ${f.nombre}">
      <polygon points="${puntosEstrella()}" fill="#F5E94D" stroke="#171717" stroke-width="2" stroke-linejoin="miter"/>
      <g transform="translate(40 20)">${f.icono}</g>
      <text class="sello__texto" x="50" y="57" text-anchor="middle">${f.lineas[0]}</text>
      <text class="sello__texto" x="50" y="69" text-anchor="middle">${f.lineas[1]}</text>
    </svg>`;
}

/* =====================================================
   12. PANEL DE ADMINISTRACIÓN (elegir sello festivo)
   ===================================================== */
let adminAutenticado = false;

function mostrarPasoAdmin() {
  el.adminLogin.hidden = adminAutenticado;
  el.adminPanel.hidden = !adminAutenticado;
  if (!adminAutenticado) {
    el.adminClave.value = '';
    el.adminError.textContent = '';
    return;
  }
  // Opciones: automático + cada festividad
  const guardada = almacen.leer(CONFIG.claves.festividad);
  const seleccion = guardada && FESTIVIDADES[guardada] ? guardada : 'auto';
  const opciones = [['auto', 'Automático (según la fecha)'],
    ...Object.entries(FESTIVIDADES).map(([clave, f]) => [clave, f.nombre])];

  el.adminOpciones.innerHTML = opciones.map(([clave, nombre]) => `
    <label class="admin__opcion">
      <input type="radio" name="festividad" value="${clave}" ${clave === seleccion ? 'checked' : ''}>
      <span>${nombre}</span>
    </label>`).join('');
}

el.abrirAdmin.addEventListener('click', () => {
  mostrarPasoAdmin();
  el.modalAdmin.showModal();
});

el.adminLogin.addEventListener('submit', (evento) => {
  evento.preventDefault();
  if (el.adminClave.value === CONFIG.claveAdmin) {
    adminAutenticado = true;
    mostrarPasoAdmin();
  } else {
    el.adminError.textContent = 'Clave incorrecta.';
    el.adminClave.select();
  }
});

el.adminPanel.addEventListener('submit', (evento) => {
  evento.preventDefault();
  const elegido = new FormData(el.adminPanel).get('festividad');
  if (elegido === 'auto') almacen.borrar(CONFIG.claves.festividad);
  else if (FESTIVIDADES[elegido]) almacen.guardar(CONFIG.claves.festividad, elegido);
  renderSello();
  el.modalAdmin.close();
  mostrarAviso('Sello festivo actualizado');
});

/* =====================================================
   13. EVENTOS GENERALES
   ===================================================== */

// Búsqueda en vivo
el.busqueda.addEventListener('input', () => {
  estado.busqueda = el.busqueda.value;
  renderCatalogo();
});
el.busqueda.addEventListener('keydown', (evento) => {
  if (evento.key === 'Escape' && el.busqueda.value) {
    evento.preventDefault();
    estado.busqueda = '';
    el.busqueda.value = '';
    renderCatalogo();
  }
});
el.formBusqueda.addEventListener('submit', (evento) => evento.preventDefault());
el.limpiarBusqueda.addEventListener('click', () => {
  estado.busqueda = '';
  el.busqueda.value = '';
  renderCatalogo();
  el.busqueda.focus();
});

// Filtro por categoría
el.filtrosCategoria.forEach((boton) => {
  boton.addEventListener('click', () => {
    estado.categoria = boton.dataset.categoria;
    renderCatalogo();
  });
});

// Filtro por talla (clic de nuevo para quitarlo)
el.chipsTalla.forEach((chip) => {
  chip.addEventListener('click', () => {
    estado.talla = estado.talla === chip.dataset.talla ? null : chip.dataset.talla;
    renderCatalogo();
  });
});

// Selectores de color, precio y orden
el.filtroColor.addEventListener('change', () => { estado.color = el.filtroColor.value; renderCatalogo(); });
el.filtroPrecio.addEventListener('change', () => { estado.precio = el.filtroPrecio.value; renderCatalogo(); });
el.orden.addEventListener('change', () => { estado.orden = el.orden.value; renderCatalogo(); });

// Limpiar filtros
el.limpiarFiltros.addEventListener('click', reiniciarTodo);
el.reiniciarTodo.addEventListener('click', reiniciarTodo);

// Clic en una tarjeta: abre el detalle
el.catalogo.addEventListener('click', (evento) => {
  const tarjeta = evento.target.closest('.tarjeta');
  if (tarjeta) abrirModal(tarjeta.dataset.id);
});

// Abrir carrito
el.abrirCarrito.addEventListener('click', () => el.panelCarrito.showModal());

// Cerrar paneles: botones [data-cerrar] y clic en el fondo oscuro.
// (La tecla Escape ya la maneja <dialog> de forma nativa.)
document.addEventListener('click', (evento) => {
  const boton = evento.target.closest('[data-cerrar]');
  if (boton) boton.closest('dialog').close();
});
$$('dialog').forEach((dialogo) => {
  dialogo.addEventListener('click', (evento) => {
    if (evento.target === dialogo) dialogo.close();
  });
});

/* =====================================================
   14. INICIO
   ===================================================== */
renderSello();
renderCatalogo();
renderCarrito();