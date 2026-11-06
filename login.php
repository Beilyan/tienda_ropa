
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white">

    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">

        <!-- LADO IZQUIERDO -->
        <div class="bg-blue-500 p-10 md:p-16 text-white flex flex-col">

            <!-- LOGO -->
            <div class="text-2xl font-bold tracking-widest">
                ◇ MODA
            </div>

            <!-- TEXTO PRINCIPAL -->
            <div class="mt-24">
                <p class="text-sm font-bold tracking-widest mb-5">
                    NUEVA COLECCIÓN
                </p>

                <h1 class="text-5xl font-bold leading-tight">
                    Viste como
                    <span class="block italic font-serif">
                        quieres sentirte.
                    </span>
                </h1>

                <p class="mt-6 text-blue-100 text-lg">
                    Descubre tu estilo y expresa tu personalidad.
                </p>
            </div>
            
        </div>


        <!-- LADO DERECHO -->
        <div class="bg-slate-50 flex justify-center items-center p-8 md:p-16">

            <div class="w-full max-w-lg">

                <!-- ENCABEZADO -->
                <h2 class="text-4xl font-semibold text-slate-900">
                    ¡Hola de nuevo!
                </h2>

                <p class="text-gray-500 mt-4 mb-12">
                    Ingresa a tu cuenta para continuar.
                </p>

                <!-- FORMULARIO -->
                <form>

                    <!-- CORREO -->
                    <div class="mb-7">
                        <label class="block text-sm font-medium mb-3">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            placeholder="nombre@correo.com"
                            class="w-full p-5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <!-- CONTRASEÑA -->
                    <div class="mb-7">
                        <label class="block text-sm font-medium mb-3">
                            Contraseña
                        </label>

                        <input
                            type="password"
                            placeholder="Tu contraseña"
                            class="w-full p-5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <!-- RECORDAR Y RECUPERAR -->
                    <div class="flex justify-between items-center mb-8">

                        <label class="flex items-center gap-3 text-sm text-gray-600">
                            <input type="checkbox"
                                class="w-5 h-5 accent-blue-500">
                            Recordarme
                        </label>

                        <a href="#"
                            class="text-blue-500 text-sm font-semibold hover:text-blue-700">
                            ¿Olvidaste tu contraseña?
                        </a>

                    </div>

                    <!-- BOTÓN -->
                    <a href="principal.php"><button
                        type="button"
                        class="w-full bg-blue-500 text-white p-5 rounded-xl font-semibold hover:bg-blue-600 transition duration-300 shadow-lg shadow-blue-200">
                        Iniciar sesión →
                    </button></a>

                </form>

                <!-- REGISTRO -->
                <p class="text-center text-gray-500 mt-10">
                    ¿No tienes cuenta?

                    <a href="#"
                        class="text-blue-500 font-semibold hover:text-blue-700">
                        Crear cuenta
                    </a>
                </p>

            </div>

        </div>

    </div>

</body>
</html>