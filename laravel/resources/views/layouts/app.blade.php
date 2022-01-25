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
    <livewire:styles />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans text-gray-900 bg-gray-background text-sm">
    <header class="flex items-center justify-between py-4 px-8">
        <a href="/"><img src="{{ asset('/img/logo.svg') }}" alt=""></a>
        <div class="flex items-center">
            @if (Route::has('login'))
                <div class="px-6 py-4 ">
                    @auth
                        <div class="flex items-center space-x-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </a>
                            </form>

                            <livewire:comment-notifications />
                        </div>
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
            <div class="bg-white border-2 border-blue rounded-xl mt-16" style="
                          border-image-source: linear-gradient(to bottom, rgba(50, 138, 241, 0.22), rgba(99, 123, 255, 0));
                            border-image-slice: 1;
                            background-image: linear-gradient(to bottom, #ffffff, #ffffff), linear-gradient(to bottom, rgba(50, 138, 241, 0.22), rgba(99, 123, 255, 0));
                            background-origin: border-box;
                            background-clip: content-box, border-box;
                    ">
                <div class="text-center px-6 py-2 pt-6">
                    <h3 class="font-semibold text-base">Add an idea</h3>

                    <p class="text-xs mt-4">
                        @auth
                            Let us know what you would like and well take a look over!
                        @else
                            Please login to create an idea.
                        @endauth
                    </p>

                </div>
                <livewire:create-idea />
            </div>
        </div>

        <div class="w-175">
            <livewire:status-filter />

            <div class="mt-8">
                {{ $slot }}
            </div>
        </div>
    </main>
    @if (session('success_message'))
        <x-notification-success
        :redirect="true"
        message-to-display="{{ session('success_message') }}"
        />
    @endif
    @if (session('error_message'))
        <x-notification-success
        type="error"
        :redirect="true"
        message-to-display="{{ session('error_message') }}"
        />
    @endif
    <livewire:scripts />
</body>

</html>
