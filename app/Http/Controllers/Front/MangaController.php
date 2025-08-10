<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MangaController extends Controller
{
    /**
     * Homepage – list newest and featured mangas.
     */
    public function index()
    {
        $featured = Manga::latest()->take(8)->get(); // top 8 featured (placeholder)
        $latest   = Manga::latest()->paginate(20);
        return view('dashboard', compact('featured', 'latest'));
    }

    /**
     * Manga detail page with chapter list.
     */
    public function show(Manga $manga)
    {
        $manga->load('genres', 'chapters');
        return view('front.manga.show', compact('manga'));
    }

    /**
     * Reading view for a chapter.
     */
    public function read(Manga $manga, Chapter $chapter)
    {
        $chapter->load(['pages' => fn($q)=>$q->orderBy('page_number')]);
        // load all chapters for dropdown
        $chapters = $manga->chapters()->orderBy('number')->get();
        // previous/next chapter ids
        $prev = $manga->chapters()->where('number', '<', $chapter->number)->orderByDesc('number')->first();
        $next = $manga->chapters()->where('number', '>', $chapter->number)->orderBy('number')->first();
        return view('front.manga.read', compact('manga', 'chapter', 'prev', 'next', 'chapters'));
    }

    /**
     * AJAX search – returns JSON list of mangas (id,title,cover).
     */
    public function search(Request $request)
    {
        $q = $request->input('q');
        if (!$q) {
            return response()->json([]);
        }
        $results = Manga::where('title', 'like', '%' . $q . '%')
            ->take(10)
            ->get(['id', 'title', 'cover']);
        return response()->json($results);
    }
}
