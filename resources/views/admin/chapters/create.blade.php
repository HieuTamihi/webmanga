@extends('admin.layout')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Thêm chương mới cho truyện: {{ $manga->title }}</h1>
    <form action="{{ route('admin.chapters.store', $manga) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Số chương</label>
            <input type="number" name="number" class="w-full border rounded p-2" value="{{ old('number') }}" required min="1">
            @error('number')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tiêu đề chương</label>
            <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title') }}">
            @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nội dung chữ (tuỳ chọn)</label>
            <textarea name="content" class="w-full border rounded p-2" rows="4">{{ old('content') }}</textarea>
            @error('content')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mb-6">
            <label class="block font-semibold mb-1">Dán URL ảnh (mỗi dòng 1 URL, theo thứ tự trang)</label>
            <textarea name="page_urls" rows="6" class="w-full border rounded p-2" placeholder="https://drive.google.com/file/d/...">{{ old('page_urls') }}</textarea>
            @error('page_urls')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Lưu chương</button>
        <a href="{{ route('admin.mangas.edit', $manga) }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded">Huỷ</a>
    </form>
</div>
@endsection
