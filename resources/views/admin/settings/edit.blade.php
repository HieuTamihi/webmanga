@extends('admin.layout')

@section('title', 'Cài đặt')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Cài đặt</h2>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-xl">
        @csrf
        <div>
            <label class="block font-medium mb-1" for="site_title">Tên trang web</label>
            <input type="text" name="site_title" id="site_title" value="{{ old('site_title', $setting->site_title ?? '') }}" class="w-full border rounded p-2" />
        </div>

        <div>
            <label class="block font-medium mb-1" for="logo">Logo</label>
            @if(isset($setting->logo))
                <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo" class="h-16 mb-2">
            @endif
            <input type="file" name="logo" id="logo" class="file:border file:p-2" />
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Lưu</button>
    </form>
@endsection
