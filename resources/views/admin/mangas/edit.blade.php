@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa truyện</h1>
    <form action="{{ route('admin.mangas.update', $manga) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tiêu đề</label>
            <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title', $manga->title) }}" required>
            @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Mô tả</label>
            <textarea name="description" class="w-full border rounded p-2" rows="4">{{ old('description', $manga->description) }}</textarea>
            @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">URL ảnh bìa</label>
            <input type="url" name="cover_url" class="w-full border rounded p-2" value="{{ old('cover_url', $manga->cover) }}" required>
            @error('cover_url')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Thể loại</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($genres as $genre)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" @checked(in_array($genre->id, old('genres', $manga->genres->pluck('id')->all())))>
                        <span>{{ $genre->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('genres')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Cập nhật</button>
        <a href="{{ route('admin.mangas.index') }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded">Hủy</a>
    </form>
</div>

<div class="max-w-4xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Danh sách chương</h2>
            <a href="{{ route('admin.chapters.create', $manga) }}" class="px-3 py-1 bg-blue-600 text-white rounded">Thêm chương</a>
        </div>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">#</th>
                <th class="p-2 border">Tiêu đề</th>
                <th class="p-2 border">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($manga->chapters as $chapter)
                <tr>
                    <td class="p-2 border">{{ $chapter->number }}</td>
                    <td class="p-2 border">{{ $chapter->title }}</td>
                    <td class="p-2 border space-x-2">
                        <a href="{{ route('admin.chapters.edit', [$manga, $chapter]) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Sửa</a>
                        <form action="{{ route('admin.chapters.destroy', [$manga, $chapter]) }}" method="POST" class="inline" onsubmit="return confirm('Xoá chương này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-4 text-center">Chưa có chương.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
