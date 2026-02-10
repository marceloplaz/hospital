<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Estilos mínimos de Tailwind 4 para renderizado inmediato */
                /* (Aquí iría el bloque CSS que enviaste anteriormente) */
                {{-- Nota: He omitido el bloque gigante de CSS para que sea legible, 
                   pero en tu archivo real, Tailwind se encargará de esto --}}
            </style>
            <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-sans">
        
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm transition-all">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm transition-all">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row shadow-sm rounded-lg overflow-hidden border border-[#19140035] dark:border-[#3E3E3A]">
                
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC]">
                    <h1 class="mb-1 font-medium text-base">Comencemos</h1>
                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">Laravel tiene un ecosistema increíblemente rico. <br>Te sugerimos comenzar con lo siguiente:</p>
                    
                    <ul class="flex flex-col mb-4 lg:mb-6">
                        <li class="flex items-start gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:absolute before:top-[1.2rem] before:bottom-0 before:left-[0.4rem]">
                            <span class="relative mt-1.5 w-2 h-2 shrink-0 rounded-full bg-[#f53003]"></span>
                            <div>
                                <a href="https://laravel.com/docs" class="font-medium text-[#1b1b18] dark:text-white underline decoration-[#f53003]/30 hover:decoration-[#f53003]">Documentación</a>
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">Guías detalladas sobre cada aspecto del framework.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:absolute before:top-0 before:bottom-0 before:left-[0.4rem]">
                            <span class="relative mt-1.5 w-2 h-2 shrink-0 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]"></span>
                            <div>
                                <a href="https://laracasts.com" class="font-medium text-[#1b1b18] dark:text-white underline decoration-[#f53003]/30 hover:decoration-[#f53003]">Laracasts</a>
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">Cursos en video de nivel experto sobre Laravel y PHP.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4 py-2 relative before:absolute before:top-0 before:h-[1.2rem] before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]">
                            <span class="relative mt-1.5 w-2 h-2 shrink-0 rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A]"></span>
                            <div>
                                <a href="https://laravel-news.com" class="font-medium text-[#1b1b18] dark:text-white underline decoration-[#f53003]/30 hover:decoration-[#f53003]">Laravel News</a>
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">Mantente al día con las últimas novedades de la comunidad.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="relative flex-1 bg-[#FDFDFC] dark:bg-[#1C1C1A] border-b lg:border-b-0 lg:border-l border-[#19140015] dark:border-[#3E3E3A] p-6 flex flex-col justify-center items-center text-center">
                    <div class="mb-4 text-[#f53003]">
                        <svg class="h-12 w-auto" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M61.8548 14.6253L24.7563 1.00005L1.7242 16.1251V50.3752L31.3976 64.5003L61.8548 50.3752V14.6253Z" fill="currentColor" fill-opacity="0.1"/>
                            <path d="M61.8548 14.6253L31.3976 28.7503L1.7242 14.6253L31.3976 1.00005L61.8548 14.6253ZM31.3976 28.7503V64.5003M1.7242 50.3752L31.3976 64.5003M61.8548 50.3752L31.3976 64.5003" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-medium">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</h2>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">(PHP v{{ PHP_VERSION }})</p>
                </div>

            </main>
        </div>

        <footer class="py-10 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
         
        </footer>
    </body>
</html>