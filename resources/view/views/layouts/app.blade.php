<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'CASP Indonesia – Konsultasi Hukum')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet"/>

    {{-- Tailwind CSS CDN (ganti dengan vite/mix untuk production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:  ['"DM Sans"', 'sans-serif'],
                        serif: ['"DM Serif Display"', 'serif'],
                    },
                    colors: {
                        'brand': {
                            900: '#0A2342',
                            800: '#123364',
                            700: '#1A4A8A',
                            600: '#1E5EBF',
                            500: '#2563EB',
                            400: '#3B82F6',
                            300: '#93C5FD',
                            100: '#DBEAFE',
                            50:  '#EFF6FF',
                        },
                    },
                },
            },
        }
    </script>

    <style>
        /* CSS Variables untuk kompatibilitas gaya lama */
        :root {
            --blue-900: #0A2342; --blue-800: #123364; --blue-700: #1A4A8A;
            --blue-600: #1E5EBF; --blue-500: #2563EB; --blue-400: #3B82F6;
            --blue-300: #93C5FD; --blue-100: #DBEAFE; --blue-50:  #EFF6FF;
            --white: #FFFFFF; --gray-50: #F8FAFC; --gray-100: #F1F5F9;
            --gray-200: #E2E8F0; --gray-300: #CBD5E1; --gray-400: #94A3B8;
            --gray-600: #475569; --gray-900: #0F172A;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--gray-50); color: var(--gray-900); overflow-x: hidden; }
        h1, h2, h3, .serif { font-family: 'DM Serif Display', serif; }
        .fade-in { animation: fadeIn .3s ease forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    @stack('styles')
</head>
<body class="antialiased">

    @yield('content')

    @stack('scripts')
</body>
</html>