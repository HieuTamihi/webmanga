<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Genre;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class MangaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Danh sách truyện kèm thể loại, phân trang
        $mangas = Manga::with('genres')->latest()->paginate(15);
        return view('admin.mangas.index', compact('mangas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::all();
        return view('admin.mangas.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_url' => 'required|url',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
            'chapter_number' => 'required|integer|min:1',
            'chapter_title' => 'required|string|max:255',
            'chapter_content' => 'nullable|string',
            'page_urls' => 'nullable|string',
        ]);

        // Chuyển link share Google Drive sang link direct
        $coverUrl = $this->convertDriveLink($request->cover_url);

        // Tạo manga
        $manga = Manga::create([
            'title'       => $request->title,
            'description' => $request->description,
            'cover'       => $coverUrl,
        ]);
        $manga->genres()->sync($request->genres);

        // Tạo chapter đầu tiên
        $chapter = Chapter::create([
            'manga_id' => $manga->id,
            'title'    => $request->chapter_title,
            'number'   => $request->chapter_number,
            'content'  => $request->chapter_content,
        ]);

        // Tạo pages từ danh sách URL nếu có
        if ($request->filled('page_urls')) {
            $urls = array_filter(array_map('trim', preg_split('/\r?\n/', $request->page_urls)));
            $index = 1;
            foreach ($urls as $url) {
                Page::create([
                    'chapter_id'  => $chapter->id,
                    'image_path'  => $this->convertDriveLink($url),
                    'page_number' => $index++,
                ]);
            }
        }

        return redirect()->route('admin.mangas.index')->with('success', 'Thêm truyện thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Manga $manga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manga $manga)
    {
        $genres = Genre::all();
        $manga->load('genres');
        return view('admin.mangas.edit', compact('manga', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manga $manga)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_url' => 'required|url',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        // Chuyển link Google Drive sang link ảnh gốc
        $coverUrl = $this->convertDriveLink($request->cover_url);

        $manga->update([
            'cover'       => $coverUrl,
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        $manga->genres()->sync($request->genres);

        return redirect()->route('admin.mangas.index')->with('success', 'Cập nhật truyện thành công');
    }

    public function destroy(Manga $manga)
    {
        DB::transaction(function () use ($manga) {
            // Xoá các trang của từng chương
            foreach ($manga->chapters as $chapter) {
                foreach ($chapter->pages as $page) {
                    // Xoá file ảnh nếu có
                    if ($page->image) {
                        try {
                            Storage::delete($page->image);
                        } catch (\Exception $e) {
                            // ignore
                        }
                    }
                    $page->delete();
                }
                $chapter->delete();
            }
            // Xoá ảnh bìa
            try {
                Storage::delete($manga->cover);
            } catch (\Exception $e) {
            }
            // detach genres rồi xoá manga
            $manga->genres()->detach();
            $manga->delete();
        });

        return redirect()->route('admin.mangas.index')->with('success', 'Đã xoá truyện');
    }

    private function convertDriveLink($url, $size = 1000)
    {
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
            return "https://drive.google.com/thumbnail?id={$fileId}&sz=w{$size}";
        }
        return $url;
    }
}
