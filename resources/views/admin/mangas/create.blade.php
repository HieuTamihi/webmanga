@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Thêm truyện mới</h1>
    <form action="{{ route('admin.mangas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tiêu đề</label>
            <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title') }}" required>
            @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Mô tả</label>
            <textarea name="description" class="w-full border rounded p-2" rows="4">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">URL ảnh bìa</label>
            <input type="url" name="cover_url" class="w-full border rounded p-2" placeholder="https://drive.google.com/..." required>
            @error('cover_url')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Thể loại</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($genres as $genre)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}">
                        <span>{{ $genre->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('genres')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <hr class="my-6">
        <h2 class="text-xl font-bold mb-4">Thông tin chương đầu</h2>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Số chương</label>
            <input type="number" name="chapter_number" class="w-full border rounded p-2" value="1" min="1" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tiêu đề chương</label>
            <input type="text" name="chapter_title" class="w-full border rounded p-2" value="Chương 1" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nội dung chữ (tuỳ chọn)</label>
            <textarea name="chapter_content" class="w-full border rounded p-2" rows="3">{{ old('chapter_content') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Hoặc dán URL ảnh (mỗi dòng 1 URL, theo thứ tự trang)</label>
            <textarea name="page_urls" rows="6" class="w-full border rounded p-2" placeholder="https://drive.google.com/uc?id=..."></textarea>
            @error('page_urls')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Lưu truyện</button>
    </form>
</div>
@endsection
