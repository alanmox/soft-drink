<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 font-sans text-stone-900 antialiased">
    <header class="border-b border-stone-200 bg-white">
        <nav class="mx-auto flex max-w-5xl items-center gap-6 px-4 py-3">
            <span class="font-semibold">Soft Drink Shop</span>
            @foreach ([
                'dashboard' => 'Dashboard',
                'products.index' => 'Inventory',
                'sales.index' => 'Sales',
            ] as $route => $label)
                <a href="{{ route($route) }}"
                   @class([
                       'text-sm hover:text-stone-900',
                       'font-medium text-stone-900 underline underline-offset-8' => request()->routeIs($route),
                       'text-stone-500' => ! request()->routeIs($route),
                   ])>{{ $label }}</a>
            @endforeach
        </nav>
    </header>

    <main class="mx-auto max-w-5xl space-y-6 px-4 py-8">
        @if (session('status'))
            <div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
