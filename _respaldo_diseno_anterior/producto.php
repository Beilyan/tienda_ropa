<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ============ METADATOS Y RECURSOS ============ -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Producto | TRAMA SHOP</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS (versión fija, por CDN: no necesita instalación) -->
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script>
    /* Misma paleta y tipografías que el resto de la tienda */
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            paper: '#F3F2ED', card: '#F7F6F1', ink: '#171717', mute: '#5A5A5A',
            mint: '#A9E8D1', sun: '#F5E94D', danger: '#B3261E'
          },
          fontFamily: {
            display: ['Anton', 'Arial Narrow', 'Impact', 'sans-serif'],
            mono: ['Space Mono', 'Courier New', 'monospace']
          },
          boxShadow: {
            hard: '3px 3px 0 #171717',
            'hard-sm': '2px 2px 0 #171717',
            'hard-lg': '6px 6px 0 #171717'
          }
        }
      }
    };
  </script>

  <!-- Solo lo que Tailwind no cubre: textura de papel y foco visible -->
  <style>
    body {
      background-color: #F3F2ED;
      background-image:
        url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='180' height='180'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 .35 0 0 0 -.08'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>"),
        repeating-linear-gradient(0deg, rgba(23,23,23,.045) 0 1px, transparent 1px 4px);
    }
    :focus-visible { outline: 3px solid #171717; outline-offset: 3px; }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
  </style>

  <!-- productos.js (datos compartidos) debe cargarse antes que producto.js -->
  <script src="productos.js" defer></script>
  <script src="producto.js" defer></script>
</head>

<body class="min-h-screen font-mono text-sm text-ink">
  <a href="#contenido" class="absolute -top-16 left-2 z-50 bg-ink px-3 py-2 text-white focus:top-2">Saltar al contenido</a>

  <div class="mx-auto max-w-6xl px-4 py-4 sm:p-[26px]">

    <!-- ============ ENCABEZADO ============ -->
    <header class="flex items-start justify-between gap-4">
      <a href="index.html" class="flex flex-col" aria-label="TRAMA SHOP, ir a la tienda">
        <span class="font-display text-4xl uppercase leading-none tracking-wide">
          TRAMA<span class="ml-0.5 bg-mint px-[.12em]">SHOP</span>
        </span>
        <span class="text-[11px] font-bold tracking-[.12em] text-mute">DETALLE</span>
      </a>

      <!-- Enlace al carrito con contador -->
      <a id="enlace-carrito" href="carrito.html" aria-label="Ir al carrito, 0 artículos"
         class="inline-flex items-center gap-2 border-2 border-ink bg-card px-3 py-2 text-xs font-bold uppercase tracking-wider shadow-hard transition hover:translate-x-px hover:translate-y-px hover:bg-mint hover:shadow-hard-sm">
        <svg class="h-[18px] w-[18px] fill-none stroke-current stroke-2" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M5 8h14l-1 12H6L5 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        <span class="hidden sm:inline">Carrito</span>
        <span id="contador" class="grid h-6 min-w-6 place-items-center bg-ink px-1.5 text-xs text-white">0</span>
      </a>
    </header>

    <hr class="my-6 border-0 border-t-2 border-ink">

    <main id="contenido">
      <!-- Ruta de navegación -->
      <nav aria-label="Ruta" class="mb-6 text-xs uppercase tracking-wider text-mute">
        <a href="index.html" class="border-b-2 border-ink text-ink hover:bg-mint">&larr; Catálogo</a>
        <span aria-hidden="true"> / </span>
        <span id="ruta-nombre"></span>
      </nav>

      <!-- ============ DETALLE ============ -->
      <section id="detalle" class="hidden grid items-start gap-8 lg:grid-cols-[1.2fr_1fr]" aria-labelledby="nombre">
        <!-- Imagen ampliada (proporción 4:3) -->
        <div id="imagen"
             class="aspect-[4/3] overflow-hidden border-2 border-ink bg-[#E2E2DE] shadow-hard-lg [&>img]:block [&>img]:h-full [&>img]:w-full [&>img]:object-cover [&>svg]:block [&>svg]:h-full [&>svg]:w-full"></div>

        <div class="flex flex-col gap-4">
          <p class="text-[11px] font-bold uppercase tracking-widest text-mute">
            Camisa / <span id="categoria" class="text-ink"></span>
          </p>
          <h1 id="nombre" class="font-display text-[clamp(2.4rem,6vw,3.8rem)] font-normal uppercase leading-none tracking-wide"></h1>
          <p class="text-2xl font-bold"><span id="precio"></span> <span class="text-xs font-normal text-mute">MXN</span></p>

          <p id="descripcion" class="text-mute"></p>

          <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider">
            <span class="text-mute">Color</span>
            <span id="muestra" class="inline-block h-5 w-5 border-2 border-ink" aria-hidden="true"></span>
            <span id="color"></span>
          </p>

          <!-- Selector de tallas -->
          <fieldset>
            <legend class="mb-2 text-[11px] font-bold uppercase tracking-widest text-mute">Elige tu talla</legend>
            <div id="tallas" class="flex flex-wrap gap-2"></div>
          </fieldset>

          <p id="error" role="alert" class="min-h-[1.5em] text-xs font-bold text-danger"></p>

          <button type="button" id="agregar" aria-disabled="true"
                  class="w-full border-2 border-ink bg-ink px-4 py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-hard transition aria-[disabled=false]:hover:translate-x-px aria-[disabled=false]:hover:translate-y-px aria-[disabled=false]:hover:shadow-hard-sm aria-disabled:cursor-not-allowed aria-disabled:border-mute aria-disabled:bg-neutral-300 aria-disabled:text-mute aria-disabled:shadow-none">
            Agregar al carrito
          </button>

          <p class="text-xs text-mute">Tienda de demostración: el pago aún no está disponible.</p>
        </div>
      </section>

      <!-- Producto no encontrado -->
      <section id="no-encontrado" class="hidden max-w-xl border-2 border-ink bg-card p-7 shadow-hard-lg">
        <p class="font-display text-4xl uppercase leading-none">Producto no encontrado</p>
        <p class="mb-5 mt-3 text-mute">No existe esa camiseta o el enlace está incompleto.</p>
        <a href="index.html" class="inline-block border-2 border-ink bg-ink px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-hard transition hover:translate-x-px hover:translate-y-px hover:bg-neutral-700 hover:shadow-hard-sm">Ver catálogo</a>
      </section>

      <!-- ============ MÁS CAMISETAS ============ -->
      <section id="relacionados" class="mt-14 hidden" aria-labelledby="titulo-relacionados">
        <h2 id="titulo-relacionados" class="font-display text-3xl font-normal uppercase leading-none">Más camisetas</h2>
        <hr class="my-4 border-0 border-t-2 border-ink">
        <ul id="lista-relacionados" class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4"></ul>
      </section>
    </main>

    <footer class="mt-12 border-t-2 border-ink pt-4">
      <p class="text-xs text-mute">TRAMA SHOP. Tienda de demostración.</p>
    </footer>
  </div>

  <!-- Aviso temporal con acceso al carrito -->
  <div id="aviso" role="status"
       class="fixed bottom-5 left-1/2 z-50 hidden max-w-[calc(100vw-2rem)] -translate-x-1/2 items-center gap-4 border-2 border-ink bg-ink px-4 py-3 text-xs font-bold uppercase tracking-wider text-white shadow-[4px_4px_0_#A9E8D1]">
    <span id="aviso-texto"></span>
    <a href="carrito.html" class="border-b-2 border-mint text-mint hover:bg-mint hover:text-ink">Ver carrito</a>
  </div>
</body>
</html>