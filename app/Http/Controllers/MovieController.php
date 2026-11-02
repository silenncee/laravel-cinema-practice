<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use App\Models\Genres;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Attributes\Middleware;
use Illuminate\Support\Facades\Storage;

#[Middleware('auth')]
#[Middleware('role:admin')]
class MovieController extends Controller
{
    public function index()
    {
        $movies = Movies::with('genres')->orderBy('title')->paginate(15);
        return view('admin.movies', compact('movies'));
    }

    public function form(Request $request, Movies $movie = null)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'title' => 'required|string|max:200',
                'slug' => 'nullable|string|max:200',
                'description' => 'nullable|string',
                'duration' => 'required|integer|min:1',
                'release_year' => 'required|date_format:Y',
                'age_rating' => 'required|in:0+,6+,12+,16+,18+',
                'genres' => 'nullable|array',
                'poster' => 'nullable|image|max:2048', 
                'frames.*' => 'nullable|image|max:2048',
                'trailer' => 'nullable|mimes:mp4,mov,ogg,webm|max:100000',
            ];

            $rules['title'] .= $movie ? (',title,' . $movie->id) : ',unique:movies,title';
            $rules['slug'] .= $movie ? (',slug,' . $movie->id) : ',unique:movies,slug';

            $data = $request->validate($rules);

            

            // постер (сохранение)
            if ($request->hasFile('poster')) {
                if ($movie && $movie->poster_url) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $movie->poster_url));
                }
                $path = $request->file('poster')->store('posters', 'public');
                $data['poster_url'] = 'storage/' . $path; 
            }

            // трейлер
            if ($request->hasFile('trailer')) {
                if ($movie && $movie->trailer_url && str_contains($movie->trailer_url, 'storage/')) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $movie->trailer_url));
                }
                $path = $request->file('trailer')->store('trailers', 'public');
                $data['trailer_url'] = 'storage/' . $path;
            }

            // ссылка на трейлер
            if (empty($data['trailer_url']) && $request->filled('trailer_url_input')) {
                 $data['trailer_url'] = $request->input('trailer_url_input');
            }

            if ($movie) {
                $movie->update($data);
            } else {
                $movie = Movies::create($data);
            }

            //привязывает жанры
            $genreIds = $request->input('genres', []);
            $movie->genres()->sync($genreIds);

            // сохранение кадров
            if ($request->hasFile('frames')) {
                if ($movie) {
                    $oldFrames = $movie->images()->where('type', 'frame')->get();
                    foreach ($oldFrames as $oldFrame) {
                        Storage::disk('public')->delete(str_replace('storage/', '', $oldFrame->image_url));
                    }
                    $movie->images()->where('type', 'frame')->delete();
                }
                
                $sortOrder = 0;
                foreach ($request->file('frames') as $frame) {
                    $path = $frame->store('frames', 'public');
                    $movie->images()->create([
                        'image_url' => 'storage/' . $path,
                        'type' => 'frame',
                        'sort_order' => $sortOrder++
                    ]);
                }
            }

            $message = $movie->wasRecentlyCreated ? 'Фильм добавлен' : 'Фильм обновлен';
            return redirect()->route('admin.movies')->with('success', $message);
        }

        $allGenres = Genres::orderBy('name')->get();
        return view('admin.moviesForm', compact('movie', 'allGenres'));
    }

    public function destroy(Movies $movie)
    {
        if ($movie->screenings()->exists()) {
            return back()->with('error', 'Нельзя удалить фильм, у которого есть сеансы.');
        }
        
        if ($movie->poster_url && str_contains($movie->poster_url, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $movie->poster_url));
        }
        foreach ($movie->images as $img) {
            if ($img->image_url && str_contains($img->image_url, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $img->image_url));
            }
        }

        $movie->genres()->detach();
        $movie->images()->delete();
        $movie->delete();

        return back()->with('success', 'Фильм удален');
    }
}