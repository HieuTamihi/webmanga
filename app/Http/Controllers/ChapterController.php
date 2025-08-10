<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    /**
     * Show form to create a new chapter for a manga.
     */
    public function create(Manga $manga)
    {
        return view('admin.chapters.create', compact('manga'));
    }

    /**
     * Store a newly created chapter.
     */
    public function store(Request $request, Manga $manga)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'number' => 'required|integer|min:1',
            'content' => 'nullable|string',
            'page_urls' => 'required|string',
        ]);

        $chapter = Chapter::create([
            'manga_id' => $manga->id,
            'title'     => $request->title,
            'number'    => $request->number,
            'content'   => $request->content,
        ]);

        // thêm trang
        $urls = array_filter(array_map('trim', preg_split('/\r?\n/', $request->page_urls)));
        $index = 1;
        foreach ($urls as $raw) {
            $url = $this->convertDriveLink($raw);
            Page::create([
                'chapter_id'  => $chapter->id,
                'image_path'  => $url,
                'page_number' => $index++,
            ]);
        }

        return redirect()->route('admin.mangas.edit', $manga)->with('success', 'Đã thêm chương mới');
    }

    /**
     * Show the form for editing the specified chapter.
     */
    public function edit(Manga $manga, Chapter $chapter)
    {
        $chapter->load('pages');
        return view('admin.chapters.edit', compact('manga', 'chapter'));
    }

    /**
     * Update the specified chapter in storage.
     */
    public function update(Request $request, Manga $manga, Chapter $chapter)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'number' => 'required|integer|min:1',
            'content' => 'nullable|string',
            'delete_pages' => 'array',
            'page_urls' => 'nullable|string',
        ]);

        // Xoá trang được chọn khỏi DB
        if ($request->filled('delete_pages')) {
            Page::whereIn('id', $request->delete_pages)->delete();
        } 
        // Thêm trang từ URL nếu có
        if ($request->filled('page_urls')) {
            $urls = array_filter(array_map('trim', preg_split('/\r?\n/', $request->page_urls)));
            $index = $chapter->pages()->max('page_number') + 1;
            foreach ($urls as $raw) {
                $url = $this->convertDriveLink($raw);
                Page::create([
                    'chapter_id'  => $chapter->id,
                    'image_path'  => $url,
                    'page_number' => $index++,
                ]);
            }
        }
        $chapter->update($request->only('title', 'number', 'content'));
        return redirect()->route('admin.mangas.edit', $manga)->with('success', 'Đã cập nhật chương');
    }

    /**
     * Remove the specified chapter from storage.
     */
    /**
     * Convert Google Drive share link to direct thumbnail
     */
    private function convertDriveLink($url, $size = 1000)
    {
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $m)) {
            $fileId = $m[1];
            return "https://drive.google.com/thumbnail?id={$fileId}&sz=w{$size}";
        }
        return $url;
    }

    public function destroy(Manga $manga, Chapter $chapter)
    {
        foreach ($chapter->pages as $page) {
            $page->delete();
        }
        $chapter->delete();
        return redirect()->route('admin.mangas.edit', $manga)->with('success', 'Đã xoá chương');
    }
}
