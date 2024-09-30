<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nimi - Cursos, tutoriales, clases y más</title>
    @vite(['resources/css/app.css'])
    @vite(['resources/css/normalize.css'])
    @vite(['resources/js/inicioSesion.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body class="bg-[var(--background-index)] overflow-hidden h-screen font-[var(----main-font)]  ">
    <main class=" overflow-hidden relative h-[100vh] flex justify-center items-center text-[var(--text-color-index)] ">
        <div class="stars"></div>
        <div class="cosmicCircle" style="width: 300px; height: 300px; top: -100px; left: -100px;"></div>
        <div class="cosmicCircle " style="width: 300px; height: 300px; bottom: -300px; right: -200px;"></div>

        <div class="text-[1.5rem] bg-[#11112718] shadowNav  my-[10px] rounded-[10px] w-[100%] px-10 py-10 max-w-[400px] fadeInFocus [&>form>div]:mb-[24px] [&>form>div]:flex [&>form>div]:flex-col [&>form>div]:gap-5 [&>form>div>label]:uppercase [&>form>div>label]:font-medium [&>form>div>input]:p-[12px] ">

            <div class="flex justify-center">
                <img class="w-[35%] h-auto" src="/img/Logo2.png" alt="Nimi logo">
            </div>

            <form class="" id="loginForm" method="post" action="login">
                @csrf
                <h2 class="uppercase font-bold text-center text-[2.5rem] text-[var(--highlight-color)] tracking-wider my-[24px]">Iniciar Sesión</h2>
                <div>
                    <label for="email">Correo Electrónico</label>
                    <input class="indexInput" type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>
                </div>
                <div>
                    <label for="password">Contraseña</label>
                    <input class="indexInput" type="password" id="password" name="password" placeholder="*********" required>
                </div>
                <button id="loginButton" class="indexButton" type="submit">Iniciar Sesión</button>
            </form>

            <form id="registerForm" style="display: none;" method="post" action="/register">
                @csrf
                <h2 class="uppercase font-bold text-center text-[2.5rem] text-[var(--highlight-color)] tracking-wider my-[24px]">Registrarse</h2>
                <div>
                    <label for="registerName">Nombre</label>
                    <input class="indexInput" type="text" id="registerName" name="registerName" placeholder="Ingresa tu nombre" required>
                </div>
                <div>
                    <label for="registerEmail">Correo Electrónico</label>
                    <input class="indexInput" type="email" id="registerEmail" name="registerEmail" placeholder="correo@ejemplo.com" required>
                </div>
                <div>
                    <label for="registerPassword">Contraseña</label>
                    <input class="indexInput" type="password" id="registerPassword" name="registerPassword" placeholder="*********" required>
                </div>
                <div>
                    <label for="reapetPassword">Repetir Contraseña</label>
                    <input class="indexInput" type="password" id="reapetPassword" name="registerReapetPassword" placeholder="*********" required>
                    
                </div>
                <button id="registerButton" class="indexButton" type="submit">Registrarse</button>
            </form>

            <form id="resetForm" style="display: none;">
                <h2 class="uppercase font-bold text-center text-[2.5rem] text-[var(--highlight-color)] tracking-wider my-[24px]">Restablecer Contraseña</h2>
                <div>
                    <label for="resetEmail">Correo Electrónico</label>
                    <input class="indexInput" type="email" id="resetEmail" name="recoveryEmail" placeholder="correo@ejemplo.com" required>
                </div>
                <button class="indexButton" type="submit">Enviar código</button>
            </form>

            <x-toggleForm>
                <x-slot name="id">
                    toggleRegister
                </x-slot>
                ¿No tienes cuenta? Regístrate
            </x-toggleForm>
            <x-toggleForm>
                <x-slot name="id">
                    toggleReset
                </x-slot>
                ¿Olvidaste tu contraseña?
            </x-toggleForm>

            <div id="showAlerts">

            </div>

        </div>
    </main>
</body>
</html>
