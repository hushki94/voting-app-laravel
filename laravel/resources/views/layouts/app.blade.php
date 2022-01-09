<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Voting 4U</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Suns:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans text-gray-900 bg-gray-background text-sm">
    <header class="flex items-center justify-between py-4 px-8">
        <a href="#"><img src="{{ asset('/img/logo.svg') }}" alt=""></a>
        <div class="flex items-center">
            @if (Route::has('login'))
                <div class="px-6 py-4 ">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log
                            in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            <a href="">
                <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp" alt="avatar"
                    class="w-10 h-10 rounded-full">
            </a>
        </div>
    </header>
    <main class="container mx-auto max-w-custom flex">
        <div class="w-70 mr-5">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam nemo vitae voluptates dignissimos qui
                ipsa, autem quis rerum doloribus eligendi aut, iusto reprehenderit, esse quibusdam? Quia perferendis
                similique adipisci minima vel laudantium natus soluta libero iure voluptatibus voluptas laborum iste, at
                vero. Ab, cum eaque quo ullam sint quia consequuntur doloremque ea distinctio a dolorem neque aperiam
                harum quidem officiis, nostrum totam molestias, nihil aspernatur expedita ad libero ratione? Sequi eos
                reprehenderit placeat architecto itaque esse consequuntur aliquam optio excepturi, laboriosam commodi
                illum quia animi illo earum laudantium molestias, odio ad recusandae magnam atque labore non. In error
                nobis quos!</p>
        </div>

        <div class="w-175">
            <nav class="flex items-center justify-between text-xs">
                <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
                    <li><a href="#" class="border-b-4 pb-3 border-blue">All Ideas (87)</a></li>
                    <li>
                        <a href="#"
                            class="text-gray-400 transition duration-150 ease-in border-b-4 pb-3 hover:border-blue">Considering
                            (6)</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-400 transition duration-150 ease-in border-b-4 pb-3 hover:border-blue">In
                            Progress (1)</a>
                    </li>
                </ul>
                <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
                    <li>
                        <a href="#"
                            class="text-gray-400 transition duration-150 ease-in border-b-4 pb-3 hover:border-blue">Impelemented
                            (10)</a>
                    </li>
                    <li>
                        <a href="#"
                            class="text-gray-400 transition duration-150 ease-in border-b-4 pb-3 hover:border-blue">Closed
                            (55)</a>
                    </li>
                </ul>
            </nav>
            <div class="mt-8">
                {{$slot}}
            </div>
        </div>
    </main>
</body>

</html>
