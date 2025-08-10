<x-headerComponent></x-headerComponent>
<section class="bg-beige py-10">
    <div class="max-w-5xl mx-auto px-4 md:px-6">
        <div class="max-w-5xl mx-auto space-y-10 px-4 md:px-6 text-center">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <img src="{{ $manga->cover }}" alt="{{ $manga->title }}"
                    class="w-60 h-88 object-cover rounded-2xl shadow-lg d-block m-auto">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-charcoal mb-3">{{ $manga->title }}</h1>
                    <div class="text-4xl font-bold text-charcoal mb-3">
                        @foreach ($manga->genres as $g)
                            <span
                                class="bg-pastelBlue text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $g->name }}</span>
                        @endforeach
                    </div>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $manga->description }}</p>
                </div>
            </div>

            <h2 class="text-2xl font-semibold mt-10 mb-4 text-charcoal">Danh sách chương</h2>
            <div class="grid grid-cols-1">
                @foreach ($manga->chapters->sortByDesc('number') as $chapter)
                    <a href="{{ route('manga.read', [$manga->id, $chapter->id]) }}"
                        class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition flex justify-between items-center my-2">
                        <span class="text-charcoal dark:text-gray-200">Chương {{ $chapter->number }}</span>
                        <span class="text-sm text-gray-500 line-clamp-1">{{ $chapter->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
