<x-header-component />
<!-- Genres Modal -->
<div id="genres-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div id="genres-box"
        class="bg-white dark:bg-gray-800 p-6 rounded shadow max-h-[80vh] overflow-y-auto w-11/12 md:w-1/2 transform transition-all duration-300 opacity-0 scale-95">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Thể loại</h2>
        <ul class="space-y-2">
            @foreach ($genres as $g)
                <li>
                    <a href="#"
                        class="text-gray-700 dark:text-gray-300 hover:text-pastelBlue">{{ $g->name }}</a>
                </li>
            @endforeach
        </ul>
        <div class="text-right mt-6">
            <button id="genres-close" class="px-4 py-2 bg-red-600 text-white rounded">Đóng</button>
        </div>
    </div>
</div>

<main class="container mx-auto px-4 py-8">
    <!-- Featured Carousel -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Nổi bật</h2>
        <div class="relative">
            <div class="carousel-container flex overflow-x-auto scrollbar-hide space-x-4 pb-4">
                @foreach ($featured as $manga)
                    <a href="{{ route('manga.show', $manga) }}"
                        class="carousel-item flex-shrink-0 w-4/5 md:w-1/3 lg:w-1/4 rounded-2xl overflow-hidden glow-hover relative">
                        <img src="{{ $manga->cover }}" alt="{{ $manga->title }}"
                            class="w-full h-64 md:h-80 object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                            <h3 class="text-white font-bold mt-2">{{ $manga->title }}</h3>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach ($manga->genres as $g)
                                    <span
                                        class="bg-pastelBlue/90 text-white text-xs px-2 py-0.5 rounded-full">{{ $g->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <button
                class="carousel-prev absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 p-2 rounded-full shadow-md text-gray-800 dark:text-white hover:bg-white dark:hover:bg-gray-700 ml-2">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button
                class="carousel-next absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 p-2 rounded-full shadow-md text-gray-800 dark:text-white hover:bg-white dark:hover:bg-gray-700 mr-2">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="lg:w-full px-8">
            <!-- Latest Updates Section -->
            <section class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Mới nhất</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($latest as $manga)
                        <!-- Comic Card 1 -->
                        <a href="{{ route('manga.show', $manga) }}">
                            <div
                                class="comic-card bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 glow-hover animate-fade-in">
                                <div class="relative">
                                    <img src="{{ $manga->cover }}" alt="{{ $manga->title }}"
                                        class="comic-cover w-full h-48 object-cover">
                                    <span
                                        class="absolute top-2 right-2 bg-pastelOrange text-white text-xs font-bold px-2 py-0.5 rounded-full">MỚI</span>
                                </div>
                                <div class="p-3">
                                    <h3
                                        class="font-semibold text-gray-800 dark:text-white text-sm mb-1 leading-tight line-clamp-2">
                                        {{ $manga->title }}
                                    </h3>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach ($manga->genres as $g)
                                            <span
                                                class="bg-pastelBlue/90 text-white text-xs px-2 py-0.5 rounded-full">{{ $g->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $latest->links() }}
                </div>
            </section>
        </div>
    </div>
</main>

<script>
    // Simple carousel functionality
    const carousel = document.querySelector('.carousel-container');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const items = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;

    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % items.length;
        scrollToItem(currentIndex);
    });

    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        scrollToItem(currentIndex);
    });

    function scrollToItem(index) {
        const item = items[index];
        carousel.scrollTo({
            left: item.offsetLeft - carousel.offsetLeft,
            behavior: 'smooth'
        });
    }

    // Genres modal toggle
    const genresModal = document.getElementById('genres-modal');
    const genresBox = document.getElementById('genres-box');
    const genresToggle = document.getElementById('genres-toggle');
    const genresClose = document.getElementById('genres-close');

    genresToggle?.addEventListener('click', () => {
        genresModal.classList.remove('hidden');
        genresModal.classList.add('flex');
        // trigger reflow then animate box
        requestAnimationFrame(() => {
            genresBox.classList.remove('opacity-0', 'scale-95');
            genresBox.classList.add('opacity-100', 'scale-100');
        });
        genresModal.classList.remove('hidden');
        genresModal.classList.add('flex');
    });

    function closeGenres() {
        genresBox.classList.add('opacity-0', 'scale-95');
        genresBox.classList.remove('opacity-100', 'scale-100');
        setTimeout(() => {
            genresModal.classList.add('hidden');
            genresModal.classList.remove('flex');
        }, 300);
    }
    genresClose && genresClose.addEventListener('click', closeGenres);
    genresModal?.addEventListener('click', (e) => {
        if (e.target === genresModal) {
            closeGenres();
            genresModal.classList.add('hidden');
            genresModal.classList.remove('flex');
        }
    });
</script>
