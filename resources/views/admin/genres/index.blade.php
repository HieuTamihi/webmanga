@extends('admin.layout')

@section('title', 'Thể loại')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Thể loại</h2>
        <a href="{{ route('admin.genres.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Thêm thể loại</a>
    </div>

    @if($genres->count())
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên thể loại</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($genres as $genre)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration + ($genres->currentPage()-1)*$genres->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $genre->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.genres.edit', $genre) }}" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" class="inline" onsubmit="return confirm('Delete this genre?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $genres->links() }}
        </div>
    @else
        <p>No genres found.</p>
    @endif
@endsection
