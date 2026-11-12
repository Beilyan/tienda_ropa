<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moda - Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f6f8fa] text-[#202c38]">

    <!-- MENÚ LATERAL -->
    <div id="fondoMenu"
        class="fixed inset-0 bg-black/35 z-40 hidden"
        onclick="cerrarMenu()">
    </div>

    <aside id="menuLateral"
        class="fixed top-0 left-0 h-screen w-full sm:w-[450px] bg-white z-50 -translate-x-full transition-transform duration-300 flex flex-col">

        <div class="flex justify-between items-center px-10 py-10">
            <div class="flex items-center gap-4">
                <svg class="w-8 h-9 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 30">
                    <path d="M12 1 21 6 12 11 3 6 12 1Z"/>
                    <path d="M3 6v17l9 5V11M21 6v17l-9 5"/>
                </svg>
                <span class="text-2xl font-bold tracking-[0.25em]">MODA</span>
            </div>

            <button onclick="cerrarMenu()"
                class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 text-3xl hover:bg-slate-200">
                ×
            </button>
        </div>

        <nav class="px-10 mt-10">
            <a href="lista_favoritos.php" class="flex justify-between py-6 border-b border-slate-200 text-blue-500 text-lg">
                Favoritos <span class="text-xs font-bold">01</span>
            </a>

            <a href="perfil.php" class="flex justify-between py-6 border-b border-slate-200 text-blue-500 text-lg">
                Perfil <span class="text-xs font-bold">01</span>
            </a>
        </nav>

        <div class="mt-auto px-10 py-6">
            <a href="login.php"
                class="flex justify-between items-center text-slate-500 hover:text-blue-500">
                Cerrar sesión
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 17l5-5-5-5M15 12H3m12-8h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-5"/>
                </svg>
            </a>
        </div>
    </aside>


    <!-- ENCABEZADO -->
    <header class="bg-white border-b border-slate-200 px-6 md:px-16 py-5">
        <div class="flex items-center justify-between gap-5">

            <!-- BOTÓN MENÚ -->
            <button onclick="abrirMenu()"
                class="w-14 h-14 shrink-0 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-100">

                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- LOGOTIPO -->
            <div class="hidden sm:flex items-center gap-4">
                <svg class="w-8 h-9 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 30">
                    <path d="M12 1 21 6 12 11 3 6 12 1Z"/>
                    <path d="M3 6v17l9 5V11M21 6v17l-9 5"/>
                </svg>
                <span class="text-2xl font-bold tracking-[0.25em]">MODA</span>
            </div>

            <!-- BUSCADOR -->
            <div class="flex-1 max-w-2xl relative">
                <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m16 16 5 5"/>
                </svg>

                <input type="text"
                    placeholder="Buscar prendas..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-full py-5 pl-14 pr-5 outline-none focus:border-blue-400">
            </div>

            <!-- CARRITO -->
            <a href="indexx.html"><button class="relative w-14 h-14 shrink-0 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-100">

                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 8h14l-1 13H6L5 8Z"/>
                    <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                </svg>

                <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                    0
                </span>
            </button></a>

        </div>
    </header>

    <!-- CONTENIDO DEL PERFIL -->

    <!-- Contact -->
<div class="bg-white">
  <div class="max-w-5xl px-4 xl:px-0 py-10 lg:py-20 mx-auto">
    <!-- Title -->
    <div class="max-w-3xl mb-10 lg:mb-14">
      <h2 class="text-foreground font-semibold text-2xl md:text-4xl md:leading-tight">Perfil</h2>
      <p class="mt-1 text-muted-foreground-1 text-lg">Toda tu información.</p>
    </div>
    <!-- End Title -->

      <div class="space-y-14">
        <!-- Item -->
        <div class="flex gap-x-5">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user preview-icon">
          <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/></svg>
          <div class="grow">
            <p class="text-foreground font-semibold text-lg">Nombre:</p>
            <p class="mt-1 text-muted-foreground-1 text-lg">Nombre del usuario...</p>
          </div>
        </div>
        <!-- End Item -->

        <!-- Item -->
        <div class="flex gap-x-5">
          <svg class="shrink-0 size-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z"/><path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10"/></svg>
          <div class="grow">
            <p class="text-foreground font-semibold text-lg">Correo:</p>

            <a class="mt-1 text-muted-foreground-1 text-lg hover:text-foreground focus:outline-hidden focus:text-foreground" href="#mailto:example@site.co" target="_blank">
              correo@ejemplo.com
            </a>
          </div>
        </div>
        <!-- End Item -->

        <!-- Item -->
        <div class="flex gap-x-5">
          <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><title>telephone</title><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.6 14.522c-2.395 2.52-8.504-3.534-6.1-6.064c1.468-1.545-.19-3.31-1.108-4.609c-1.723-2.435-5.504.927-5.39 3.066c.363 6.746 7.66 14.74 14.726 14.042c2.21-.218 4.75-4.21 2.215-5.669c-1.268-.73-3.009-2.17-4.343-.767"/></svg>
          <div class="grow">
            <p class="text-foreground font-semibold text-lg">Telefono:</p>
            <p class="mt-1 text-muted-foreground-1 text-lg">###-####-####</p>
          </div>
        </div>
        <!-- End Item -->
      </div>
      <!-- End Col -->
    </div>
    <!-- End Grid -->
</div>
<!-- End Contact -->

    <!-- FIN -->

<!-- JAVASCRIPT DEL MENÚ -->
    <script>
        const menu = document.getElementById('menuLateral');
        const fondo = document.getElementById('fondoMenu');

        function abrirMenu() {
            menu.classList.remove('-translate-x-full');
            fondo.classList.remove('hidden');
        }

        function cerrarMenu() {
            menu.classList.add('-translate-x-full');
            fondo.classList.add('hidden');
        }
    </script>

</body>
</html>
