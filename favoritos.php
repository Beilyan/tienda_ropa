<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de favoritos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        palette: {
                            snow: '#FFFBFE',
                            silver: '#adefff',
                            grey: '#70ddf8',
                            dust: '#DOCFCF',
                            charcoal: '#00b7ff' 
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#FFFBFE] min-h-screen flex flex-col font-sans antialiased text-gray-900">

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
            <a href="favoritos.php" class="flex justify-between py-6 border-b border-slate-200 text-blue-500 text-lg">
                Favoritos <span class="text-xs font-bold">01</span>
            </a>

            <a href="perfil.html" class="flex justify-between py-6 border-b border-slate-200 text-blue-500 text-lg">
                Perfil <span class="text-xs font-bold">01</span>
            </a>

            <a href="lista_ropa.php" class="flex justify-between py-6 border-b border-slate-200 text-blue-500 text-lg">
                Lista de ropa <span class="text-xs font-bold">01</span>
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

            <div class="text-center">
                <p class="text-base font-semibold tracking-wide text-gray-800">¡Tu ropa favorita guardada!</p>
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

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8 border-b border-palette-charcoal/20 pb-4">
            <h1 class="text-3xl font-extrabold text-palette-charcoal flex items-center gap-2 tracking-tight">
                ❤️ Tus favoritos!
            </h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <div class="bg-white rounded-2xl border-2 border-palette-charcoal overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                    <img src="../tienda_ropa/imagenes/pantalon_mezclilla.jpg" alt="Pantalones de mezclilla" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                </div>
                <div class="p-5 flex flex-col flex-grow bg-white">
                    <h2 class="font-bold text-palette-charcoal text-xl">Pantalones de mezclilla</h2>
                    <div class="mt-3 space-y-2 text-sm text-gray-700 flex-grow">
                        <p><span class="font-semibold text-gray-500">Talla:</span> M</p>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-500">Color:</span>
                            <div class="w-5 h-5 rounded-full bg-blue-600 border border-palette-charcoal" title="Azul"></div>
                        </div>
                    </div>
                    <!-- Botones -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-palette-charcoal/20">
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-200">
                            Eliminar
                        </a>
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-white bg-palette-charcoal hover:opacity-90 rounded-xl transition-colors shadow-sm">
                            Comprar
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-palette-charcoal overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                    <img src="../tienda_ropa/imagenes/cachucha.jpg" alt="Cachucha" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                </div>
                <div class="p-5 flex flex-col flex-grow bg-white">
                    <h2 class="font-bold text-palette-charcoal text-xl">Cachucha</h2>
                    <div class="mt-3 space-y-2 text-sm text-gray-700 flex-grow">
                        <p><span class="font-semibold text-gray-500">Talla:</span> M</p>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-500">Color:</span>
                            <div class="w-5 h-5 rounded-full bg-palette-charcoal border border-white" title="Negro"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-palette-charcoal/20">
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-200">
                            Eliminar
                        </a>
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-white bg-palette-charcoal hover:opacity-90 rounded-xl transition-colors shadow-sm">
                            Comprar
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-palette-charcoal overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                    <img src="../tienda_ropa/imagenes/camisa_hombre.jpg" alt="Camisa" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                </div>
                <div class="p-5 flex flex-col flex-grow bg-white">
                    <h2 class="font-bold text-palette-charcoal text-xl">Camisa</h2>
                    <div class="mt-3 space-y-2 text-sm text-gray-700 flex-grow">
                        <p><span class="font-semibold text-gray-500">Talla:</span> M</p>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-500">Color:</span>
                            <div class="w-5 h-5 rounded-full bg-white border border-palette-charcoal" title="Blanco"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-palette-charcoal/20">
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-200">
                            Eliminar
                        </a>
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-white bg-palette-charcoal hover:opacity-90 rounded-xl transition-colors shadow-sm">
                            Comprar
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-palette-charcoal overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                    <img src="../tienda_ropa/imagenes/camisa_de_mujer.jpg" alt="Camisa de mujer" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                </div>
                <div class="p-5 flex flex-col flex-grow bg-white">
                    <h2 class="font-bold text-palette-charcoal text-xl">Camisa de mujer</h2>
                    <div class="mt-3 space-y-2 text-sm text-gray-700 flex-grow">
                        <p><span class="font-semibold text-gray-500">Talla:</span> M</p>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-500">Color:</span>
                            <div class="w-5 h-5 rounded-full bg-pink-300 border border-palette-charcoal" title="Rosa"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-palette-charcoal/20">
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-200">
                            Eliminar
                        </a>
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-white bg-palette-charcoal hover:opacity-90 rounded-xl transition-colors shadow-sm">
                            Comprar
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-palette-charcoal overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                    <img src="../tienda_ropa/imagenes/pantalon_de_mujer.jpg" alt="Pantalon de mujer" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                </div>
                <div class="p-5 flex flex-col flex-grow bg-white">
                    <h2 class="font-bold text-palette-charcoal text-xl">Pantalón de mujer</h2>
                    <div class="mt-3 space-y-2 text-sm text-gray-700 flex-grow">
                        <p><span class="font-semibold text-gray-500">Talla:</span> M</p>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-500">Color:</span>
                            <div class="w-5 h-5 rounded-full bg-amber-700 border border-palette-charcoal" title="Café"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-palette-charcoal/20">
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-200">
                            Eliminar
                        </a>
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-white bg-palette-charcoal hover:opacity-90 rounded-xl transition-colors shadow-sm">
                            Comprar
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-palette-charcoal overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                    <img src="../tienda_ropa/imagenes/short.jpg" alt="Short de hombre" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                </div>
                <div class="p-5 flex flex-col flex-grow bg-white">
                    <h2 class="font-bold text-palette-charcoal text-xl">Short de hombre</h2>
                    <div class="mt-3 space-y-2 text-sm text-gray-700 flex-grow">
                        <p><span class="font-semibold text-gray-500">Talla:</span> M</p>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-500">Color:</span>
                            <div class="w-5 h-5 rounded-full bg-palette-grey border border-palette-charcoal" title="Gris"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-palette-charcoal/20">
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-200">
                            Eliminar
                        </a>
                        <a href="#" class="py-2.5 text-center text-sm font-bold text-white bg-palette-charcoal hover:opacity-90 rounded-xl transition-colors shadow-sm">
                            Comprar
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>

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

    <footer class="bg-white border-t-2 border-palette-charcoal py-8 text-center text-sm text-gray-500 mt-16">
        <div class="max-w-7xl mx-auto px-4 space-y-3">
            <a href="#" class="inline-block text-palette-charcoal font-bold hover:underline tracking-wide bg-palette-dust/30 px-6 py-2 rounded-2xl border border-palette-charcoal">
                ¡Conoce más de nuestros catálogos!
            </a>
            <p class="pt-2 text-xs font-medium">Todos los derechos reservados a Tienda Ropa</p>
        </div>
    </footer>

</body>
</html>

