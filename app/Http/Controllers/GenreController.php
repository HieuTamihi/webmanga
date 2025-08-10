<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.genres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Genre::create($data);
        return redirect()->route('admin.genres.index')->with('success', 'Genre created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $genre->update($data);
        return redirect()->route('admin.genres.index')->with('success', 'Genre updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        if ($genre->mangas()->exists()) {
            return redirect()->route('admin.genres.index')->with('error', 'Cannot delete: genre is used by mangas');
        }
        $genre->delete();
        return redirect()->route('admin.genres.index')->with('success', 'Genre deleted');
    }
}
