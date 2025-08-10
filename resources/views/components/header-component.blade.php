<!DOCTYPE html>
<html lang="en">
@php
    $setting = \App\Models\Setting::first();
    $siteTitle = optional($setting)->site_title ?? 'MangaVerse';
    $logoPath = optional($setting)->logo;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if ($logoPath)
        <link rel="icon" href="{{ asset('storage/' . $logoPath) }}">
    @endif
    <title>{{ $siteTitle }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        beige: '#F5F0E6',
                        charcoal: '#2D3748',
                        pastelBlue: '#A7C7E7',
                        pastelLavender: '#C8A2C8',
                        pastelOrange: '#FFB347',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                    },
                }
            }
        }
    </script>
    <style type="text/css">
        .comic-card:hover .comic-cover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .glow-hover:hover {
            box-shadow: 0 0 15px rgba(167, 199, 231, 0.6);
        }

        .dark .glow-hover:hover {
            box-shadow: 0 0 15px rgba(200, 162, 200, 0.6);
        }

        .comic-cover {
            transition: all 0.3s ease;
        }

        .carousel-item {
            scroll-snap-align: start;
        }

        .carousel-container {
            scroll-snap-type: x mandatory;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .page-enter {
            opacity: 0;
            transform: translateY(10px);
        }

        .page-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 300ms, transform 300ms;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .distraction-free .ui-element {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .distraction-free:hover .ui-element {
            opacity: 1;
            pointer-events: auto;
        }

        .comic-image {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            display: block;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .touch-controls {
            position: fixed;
            left: 0;
            right: 0;
            height: 50%;
            z-index: 10;
        }

        .touch-left {
            top: 0;
            bottom: 50%;
        }

        .touch-right {
            top: 50%;
            bottom: 0;
        }
    </style>
</head>

<body class="bg-beige dark:bg-charcoal font-poppins transition-colors duration-300 min-h-screen">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ asset('/') }}">
                <div class="flex items-center space-x-2">
                    @if ($logoPath)
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="w-16 h-16 object-contain">
                    @else
                        <i class="fas fa-book-open text-2xl text-pastelOrange dark:text-pastelLavender"></i>
                    @endif
                    <span class="text-xl font-bold text-gray-800 dark:text-white">{{ $siteTitle }}</span>
                </div>
            </a>

            <!-- Search and Dark Mode Toggle -->
            <div class="flex items-center space-x-4">
                <div class="hidden md:block relative">
                    <input id="search-desktop" type="text" placeholder="Tìm kiếm..."
                        class="search-input pl-10 pr-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-pastelBlue dark:focus:ring-pastelLavender w-64 transition">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
                    <div id="search-results-desktop"
                        class="search-results absolute left-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg hidden z-50">
                    </div>
                </div>
            </div>
            <button id="theme-toggle"
                class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:block"></i>
            </button>
        </div>
        </div>
    </header>
    <!-- Mobile Search (hidden on desktop) -->
    <div class="md:hidden px-4 py-3 bg-gray-50 dark:bg-gray-800">
        <div class="relative">
            <input id="search-mobile" type="text" placeholder="Tìm kiếm..."
                class="search-input pl-10 pr-4 py-2 rounded-full bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-pastelBlue dark:focus:ring-pastelLavender w-full transition">
            <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
            <div id="search-results-mobile"
                class="search-results absolute left-0 mt-2 w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg hidden z-50">
            </div>
        </div>
    </div>
    <script>
        // Dark mode toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });

        // Check for saved theme preference
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Live search functionality
        const debounce = (fn, d = 300) => {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), d);
            };
        };
        const renderResults = (wrapper, items) => {
            if (!items.length) {
                wrapper.innerHTML = '<div class="p-3 text-gray-500">Không tìm thấy</div>';
                return;
            }
            wrapper.innerHTML = items.map(i => `
                <a href=\"/manga/${i.id}\" class=\"flex gap-3 p-2 hover:bg-gray-100 dark:hover:bg-gray-700\">
                    <img src=\"${i.cover}\" class=\"w-12 h-16 object-cover rounded\" />
                    <span class=\"text-gray-800 dark:text-gray-200 line-clamp-2\">${i.title}</span>
                </a>`).join('');
        };
        document.querySelectorAll('.search-input').forEach(input => {
            const wrapper = input.parentElement.querySelector('.search-results');
            if (!wrapper) return;
            const doSearch = debounce(async () => {
                const q = input.value.trim();
                if (q.length < 2) {
                    wrapper.classList.add('hidden');
                    return;
                }
                try {
                    const res = await fetch(`/search?q=${encodeURIComponent(q)}`);
                    const data = await res.json();
                    renderResults(wrapper, data);
                    wrapper.classList.remove('hidden');
                } catch (err) {
                    console.error(err);
                }
            });
            input.addEventListener('input', doSearch);
            document.addEventListener('click', e => {
                if (!wrapper.contains(e.target) && !input.contains(e.target)) {
                    wrapper.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>
