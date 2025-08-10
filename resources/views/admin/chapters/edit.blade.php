@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa chương</h1>
    <form action="{{ route('admin.chapters.update', [$manga, $chapter]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">Số chương</label>
            <input type="number" name="number" class="w-full border rounded p-2" value="{{ old('number', $chapter->number) }}" min="1" required>
            @error('number')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tiêu đề</label>
            <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title', $chapter->title) }}">
            @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nội dung chữ (tuỳ chọn)</label>
            <textarea name="content" class="w-full border rounded p-2" rows="4">{{ old('content', $chapter->content) }}</textarea>
            @error('content')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <h2 class="text-lg font-semibold mt-6 mb-2">Ảnh trang hiện tại</h2>
        <div class="grid grid-cols-3 gap-4 mb-4">
            @foreach($chapter->pages as $page)
                <div class="relative">
                    @php($src = Str::startsWith($page->image_path,'http') ? $page->image_path : Storage::url($page->image_path))
                    <img src="{{ $src }}" class="object-cover h-40 w-full rounded" alt="page">
                    <label class="absolute top-1 right-1 bg-white rounded p-1 shadow">
                        <input type="checkbox" name="delete_pages[]" value="{{ $page->id }}"> Xoá
                    </label>
                </div>
            @endforeach
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-1">Thêm ảnh trang mới</label>
            <input type="file" name="pages[]" multiple accept="image/*">
            @error('pages')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-1">Hoặc dán URL ảnh (mỗi dòng 1 URL, theo thứ tự thêm)</label>
            <textarea name="page_urls" rows="6" class="w-full border rounded p-2" placeholder="https://drive.google.com/uc?id=..."></textarea>
            @error('page_urls')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Cập nhật chương</button>
        <a href="{{ route('admin.mangas.edit', $manga) }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded">Quay lại truyện</a>
    </form>
</div>
@endsection
