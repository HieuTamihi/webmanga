<x-headerComponent></x-headerComponent>

<body class="bg-beige dark:bg-charcoal font-sans transition-colors duration-200">
    <div id="app" class="flex flex-col">
        <!-- Chapter Navigation -->
        <div class="sticky top-16 z-40 bg-white/80 dark:bg-navy/80 backdrop-blur-sm py-2 shadow-sm ui-element">
            <div class="container mx-auto px-4 flex items-center justify-between">
                @if ($prev)
                    <a href="{{ route('manga.read', [$manga->id, $prev->id]) }}"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Trước
                    </a>
                @else
                    <span class="px-4 py-2 text-gray-400 dark:text-gray-500 cursor-not-allowed">Trước</span>
                @endif
                <div class="relative">
                    <button id="chapter-dropdown-btn"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <span>Chương {{ $chapter->number }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="chapter-dropdown"
                        class="hidden absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50">
                        <div class="max-h-96 overflow-y-auto py-1">
                            @foreach ($chapters as $c)
                                <a href="{{ route('manga.read', [$manga->id, $c->id]) }}"
                                    class="block px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $c->id == $chapter->id ? 'font-semibold bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Chương {{ $c->number }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if ($next)
                    <a href="{{ route('manga.read', [$manga->id, $next->id]) }}"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        Tiếp
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="px-4 py-2 text-gray-400 dark:text-gray-500 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Comic Content -->
    <main class="flex-grow container mx-auto px-4 py-6">
        <div class="space-y-6">
            @if ($chapter->content)
                <p class="whitespace-pre-line leading-relaxed bg-white/60 dark:bg-gray-800/60 p-4 rounded-lg shadow">
                    {{ $chapter->content }}
                </p>
            @endif
            @foreach ($chapter->pages as $page)
                <div class="fade-in">
                    <img src="{{ $page->image_path }}" alt="Trang {{ $page->page_number }}" class="comic-image">
                </div>
            @endforeach
        </div>
    </main>

    <!-- Bottom Chapter Navigation -->
    <div class="bottom-16 bg-white/80 dark:bg-navy/80 backdrop-blur-sm py-3 shadow-sm ui-element">
        <div class="container mx-auto px-4 flex items-center justify-center space-x-4">
            @if ($prev)
                <a href="{{ route('manga.read', [$manga->id, $prev->id]) }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Previous
                    Chapter</a>
            @else
                <span class="px-4 py-2 text-gray-400 dark:text-gray-500 cursor-not-allowed">Trước</span>
            @endif
            @if ($next)
                <a href="{{ route('manga.read', [$manga->id, $next->id]) }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">Tiếp</a>
            @else
                <span class="px-4 py-2 text-gray-400 dark:text-gray-500 cursor-not-allowed">Tiếp</span>
            @endif

        </div>
    </div>
    </div>

    <script>
        // Chapter dropdown
        const chapterDropdownBtn = document.getElementById('chapter-dropdown-btn');
        const chapterDropdown = document.getElementById('chapter-dropdown');

        chapterDropdownBtn.addEventListener('click', function() {
            chapterDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!chapterDropdownBtn.contains(event.target) && !chapterDropdown.contains(event.target)) {
                chapterDropdown.classList.add('hidden');
            }
        });

        // Mobile swipe navigation
        let touchStartX = 0;
        let touchEndX = 0;

        document.querySelectorAll('.touch-controls').forEach(control => {
            control.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            control.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);
        });

        function handleSwipe() {
            const threshold = 50; // Minimum swipe distance
            const action = Math.abs(touchEndX - touchStartX) > threshold ?
                (touchEndX < touchStartX ? 'next' : 'prev') :
                null;

            if (action) {
                console.log(`Navigating to ${action} chapter`);
                // In a real app, this would trigger chapter navigation
            }
        }

        // Lazy loading for images (would be implemented with actual image sources)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            observer.observe(img);
        });

        // Smooth scroll to top when changing chapters
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    </script>
</body>

</html>
