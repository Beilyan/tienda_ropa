    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de ropa - Tienda de Moda</title>
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
            <a href="favoritos.php" class="flex justify-between py-6 border-b border-slate-200 text-blue-500 text-lg">
                Favoritos <span class="text-xs font-bold">01</span>
            </a>

            <a href="perfil.html" class="flex justify-between py-6 border-b border-slate-200 text-blue-500 text-lg">
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
 
    
    <!-- CONTENIDO PRINCIPAL -->
    <main class="px-5 md:px-[30px] py-10">

        <!-- BANNER PRINCIPAL -->
        <section id="colecciones"
            class="relative overflow-hidden bg-[#258cf0] rounded-[30px] min-h-[475px] flex items-center">

            <!-- CÍRCULO DECORATIVO -->
            <div class="absolute w-[750px] h-[750px] border border-white/20 rounded-full -right-32 top-3"></div>

            <!-- TEXTO -->
            <div class="relative z-10 px-8 md:px-20 py-16">
                <p class="text-white/80 font-bold tracking-[0.25em] text-xs md:text-sm mb-5">
                    LISTA DE COLECCIÓN 2025
                </p>

                <h1 class="text-white text-5xl md:text-6xl font-bold tracking-tight leading-tight">
                    Tu estilo,
                    <br>
                    <span class="italic font-serif">sin reglas.</span>
                </h1>

                <a href="#novedades"
                    class="inline-block mt-10 bg-white text-blue-500 font-semibold px-7 py-5 rounded-xl hover:bg-blue-50 transition duration-300">
                    Ver colección   
                </a>
            </div>

            <!-- ILUSTRACIÓN DE GANCHO -->
            <div class="absolute right-0 md:right-16 bottom-0 w-[45%] h-full hidden sm:flex items-center justify-center">

                <svg viewBox="0 0 500 450" class="w-full max-w-[480px] h-full" fill="none">

                    <path d="M250 35 C250 -10 320 -10 320 40 C320 85 265 105 250 145"
                        stroke="white" stroke-width="6" stroke-linecap="round"/>

                    <path d="M250 145 L90 275 C65 295 85 325 115 322 L385 305 C420 303 430 275 405 257 L250 145Z"
                        stroke="white" stroke-width="6" stroke-linejoin="round"/>

                    <path d="M185 210 L315 200 L365 390 L155 390 Z"
                        fill="#f8fafc"/>

                    <path d="M185 210 Q250 300 315 200"
                        stroke="#258cf0" stroke-width="6"/>

                    <path d="M260 250 L270 390"
                        stroke="#dbeafe" stroke-width="4"/>

                    <path d="M170 365 L350 350"
                        stroke="#258cf0" stroke-width="6"/>

                </svg>
            </div>
        </section>

        <!-- Sección de Categorías (Entre el Banner y Novedades) -->
        <section class="my-10">
    <!-- Encabezado sutil alineado con el estilo de la página -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-5 gap-2">
        <div>
            <span class="block text-[11px] font-bold tracking-widest text-blue-500 uppercase mb-1">
                FILTRA TU BÚSQUEDA
            </span>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                Explora por Categorías
            </h2>
        </div>
        <p class="text-xs text-slate-400">7 colecciones disponibles</p>
    </div>

    <!-- Cuadrícula de Categorías Minimalistas -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3.5">
        
        <!-- 1. Camisas -->
        <a href="#" class="bg-[#e6f2fd] hover:bg-[#f2f8fe] rounded-[20px] p-4 flex flex-col items-center justify-center text-center transition-all duration-300 group">
            <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-xl mb-2.5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                👕
            </div>
            <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                Camisas
            </span>
        </a>

        <!-- 2. Pantalones -->
        <a href="#" class="bg-[#e6f2fd] hover:bg-[#f2f8fe] rounded-[20px] p-4 flex flex-col items-center justify-center text-center transition-all duration-300 group">
            <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-xl mb-2.5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                👖
            </div>
            <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                Pantalones
            </span>
        </a>

        <!-- 3. Calzados -->
                <a href="#" class="bg-[#e6f2fd] hover:bg-[#f2f8fe] rounded-[20px] p-4 flex flex-col items-center justify-center text-center transition-all duration-300 group">
                <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-xl mb-2.5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                👟
                </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                    Calzados
                    </span>
                </a>

                <!-- 4. Bolsos -->
                <a href="#" class="bg-[#e6f2fd] hover:bg-[#f2f8fe] rounded-[20px] p-4 flex flex-col items-center justify-center text-center transition-all duration-300 group">
                <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-xl mb-2.5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                👜
                </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                    Bolsos
                    </span>
                </a>

                <!-- 5. Sombreros -->
                <a href="#" class="bg-[#e6f2fd] hover:bg-[#f2f8fe] rounded-[20px] p-4 flex flex-col items-center justify-center text-center transition-all duration-300 group">
                <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-xl mb-2.5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                🧢
                </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                    Sombreros
                    </span>
                </a>

                <!-- 6. En descuentos -->
                <a href="#" class="bg-[#e6f2fd] hover:bg-[#f2f8fe] rounded-[20px] p-4 flex flex-col items-center justify-center text-center transition-all duration-300 group">
                <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-xl mb-2.5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                🏷️
                </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                    En descuentos
                    </span>
                </a>

                <!-- 7. En ¡OFERTA! -->
                <a href="#" class="bg-[#e6f2fd] hover:bg-[#f2f8fe] rounded-[20px] p-4 flex flex-col items-center justify-center text-center transition-all duration-300 group col-span-2 sm:col-span-1">
                <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-xl mb-2.5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                🔥
                </div>
                    <span class="text-xs sm:text-sm font-bold text-red-500 group-hover:text-red-600 transition-colors">
                    En ¡OFERTA!
                    </span>
                </a>

            </div>
        </section>


        <!-- NOVEDADES -->
        <section id="novedades" class="mt-16">

            <div class="flex justify-between items-end mb-8">
                <div>
                    <p class="text-blue-500 text-xs font-bold tracking-[0.25em] mb-4">
                        SELECCIÓN PARA TI DE NUESTRA LISTA
                    </p>

                    <h2 class="text-3xl md:text-4xl font-semibold tracking-tight">
                        Novedades, Ofertas, Descuentos
                    </h2>
                </div>

                <span class="text-slate-400 text-sm">
                    4 prendas
                </span>
            </div>


            <!-- TARJETAS DE PRODUCTOS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">

                <!-- PRODUCTO 1 -->
                <article class="group cursor-pointer">
                    <div class="relative bg-[#e6f2fd] rounded-[20px] aspect-square flex items-center justify-center overflow-hidden">
        
                    <span class="absolute top-5 left-5 text-xs tracking-widest text-slate-500 z-10">01</span>

                    <!-- REEMPLAZA EL <svg> POR ESTA ETIQUETA <img> -->
                    <img 
                    src="../tienda_ropa/imagenes/camisa_hombre.jpg" 
                    alt="Camisa Essential" 
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                    >   

                    </div>

                    <div class="pt-5 px-1">
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-slate-400">Camisas</span>
                            <span class="text-blue-500 font-bold text-lg">$890</span>
                        </div>
                        <h3 class="font-semibold text-base md:text-lg">Camisa Essential</h3>
                    </div>
                </article>



                <!-- PRODUCTO 2 -->
                <article class="group cursor-pointer">
                    <div class="relative bg-[#e7ebee] rounded-[20px] aspect-square flex items-center justify-center overflow-hidden">

                        <span class="absolute top-5 left-5 text-xs tracking-widest text-slate-500">02</span>

                        <img 
                        src="../tienda_ropa/imagenes/chaqueta.jpg" 
                        alt="Camisa Essential" 
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                        >  
                    </div>

                    <div class="pt-5 px-1">
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-slate-400">Chaquetas</span>
                            <span class="text-blue-500 font-bold text-lg">$1,490</span>
                        </div>
                        <h3 class="font-semibold text-base md:text-lg">Chaqueta Urban</h3>
                    </div>
                </article>


                <!-- PRODUCTO 3 -->
                <article class="group cursor-pointer">
                    <div class="relative bg-[#f2ebe5] rounded-[20px] aspect-square flex items-center justify-center overflow-hidden">

                        <span class="absolute top-5 left-5 text-xs tracking-widest text-slate-500">03</span>

                        <svg viewBox="0 0 200 200" class="w-[75%] h-[75%] group-hover:scale-110 transition duration-500">
                            <path d="M65 35 L40 48 L15 85 L40 105 L55 88 L55 170 L145 170 L145 88 L160 105 L185 85 L160 48 L135 35 Q100 75 65 35Z"
                                fill="#cba27f"/>
                            <path d="M65 35 Q100 75 135 35" fill="none" stroke="#f8fafc" stroke-width="3"/>
                        </svg>
                    </div>

                    <div class="pt-5 px-1">
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-slate-400">Vestidos</span>
                            <span class="text-blue-500 font-bold text-lg">$1,190</span>
                        </div>
                        <h3 class="font-semibold text-base md:text-lg">Vestido Aura</h3>
                    </div>
                </article>


                <!-- PRODUCTO 4 -->
                <article class="group cursor-pointer">
                    <div class="relative bg-[#eaf0f3] rounded-[20px] aspect-square flex items-center justify-center overflow-hidden">

                        <span class="absolute top-5 left-5 text-xs tracking-widest text-slate-500">04</span>

                        <svg viewBox="0 0 200 200" class="w-[75%] h-[75%] group-hover:scale-110 transition duration-500">
                            <path d="M65 35 L40 48 L15 85 L40 105 L55 88 L55 170 L145 170 L145 88 L160 105 L185 85 L160 48 L135 35 Q100 75 65 35Z"
                                fill="#9eacb8"/>
                            <path d="M65 35 Q100 75 135 35" fill="none" stroke="#f8fafc" stroke-width="3"/>
                        </svg>
                    </div>

                    <div class="pt-5 px-1">
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-slate-400">Sudaderas</span>
                            <span class="text-blue-500 font-bold text-lg">$990</span>
                        </div>
                        <h3 class="font-semibold text-base md:text-lg">Sudadera Classic</h3>
                    </div>
                </article>

            </div>
        </section>

    </main>

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

        <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500 mt-20">
            <p>© Todos los derechos reservados a Tienda Ropa</p>
        </footer>

    </body>
    </html>
