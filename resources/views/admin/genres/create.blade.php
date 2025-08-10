@extends('admin.layout')

@section('title', 'Add Genre')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Add Genre</h2>

    <form action="{{ route('admin.genres.store') }}" method="POST" class="bg-white p-6 rounded shadow w-full max-w-lg">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.genres.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Back</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
@endsection
