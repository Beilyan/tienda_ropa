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
                            silver: '#BDBCBE',
                            grey: '#7A7D7D',
                            dust: '#DOCFCF',
                            charcoal: '#565254' 
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#FFFBFE] min-h-screen flex flex-col font-sans antialiased text-gray-900">
    <header class="bg-white border-b border-palette-charcoal sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center">
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors">
                    ← Volver
                </a>
            </div>
            
            <div class="text-center">
                <p class="text-base font-semibold tracking-wide text-gray-800">¡Tu ropa favorita guardada!</p>
            </div>

            <div class="flex items-center gap-3 bg-gray-50 border border-palette-charcoal px-4 py-1.5 rounded-2xl">
                <a href="#" class="w-8 h-8 rounded-full bg-palette-silver flex items-center justify-center overflow-hidden hover:opacity-80 transition-opacity">
                    <svg xmlns="http://w3.org" class="h-5 w-5 text-palette-charcoal" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </a>
                <p class="text-xs font-bold text-palette-charcoal hidden sm:block">Iniciar sesión</p>
            </div>
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
                    <img src="../tienda_ropa/CSS/imagenes/images%20(3).jpg" alt="Pantalones de mezclilla" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
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
                    <img src="../tienda_ropa/CSS/imagenes/images%20(2).jpg" alt="Cachucha" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
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
                    <img src="../tienda_ropa/CSS/imagenes/images.jpg" alt="Camisa" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
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
                    <img src="../tienda_ropa/CSS/imagenes/descarga%20(1).jpg" alt="Camisa de mujer" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
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
                    <img src="../tienda_ropa/CSS/imagenes/descarga.jpg" alt="Pantalon de mujer" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
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
                    <img src="../tienda_ropa/CSS/imagenes/images%20(1).jpg" alt="Short de hombre" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
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

