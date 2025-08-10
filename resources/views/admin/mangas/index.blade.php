@extends('admin.layout')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Danh sách truyện</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('admin.mangas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Thêm truyện</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Ảnh bìa</th>
                        <th class="p-2 border">Tiêu đề</th>
                        <th class="p-2 border">Thể loại</th>
                        <th class="p-2 border">Hành động</th>
                    </tr>
                </thead>
                @php use Illuminate\Support\Str; @endphp
            <tbody>
                    @forelse($mangas as $manga)
                        <tr>
                            <td class="p-2 border">{{ $manga->id }}</td>
                            <td class="p-2 border">
                                <img src="{{ $manga->cover }}" alt="cover" class="h-16 w-12 object-cover">
                            </td>
                            <td class="p-2 border">{{ $manga->title }}</td>
                            <td class="p-2 border">
                                {{ $manga->genres->pluck('name')->join(', ') }}
                            </td>
                            <td class="p-2 border space-x-2">
                                <a href="{{ route('admin.mangas.edit', $manga) }}"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded">Sửa</a>
                                <form action="{{ route('admin.mangas.destroy', $manga) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center">Chưa có truyện nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $mangas->links() }}
        </div>
    </div>
@endsection
