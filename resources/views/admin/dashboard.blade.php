@extends('admin.layout')

@section('title', 'Trang chủ')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Chào mừng đến với trang Admin</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Tổng số truyện</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Manga::count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Tổng số thể loại</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Genre::count() }}</p>
        </div>
    </div>
@endsection
