<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Attributes\Middleware;

#[Middleware('auth')]
#[Middleware('role:admin')]
class GenreController extends Controller
{
    public function index()
    {
        $genres = Genres::orderBy('name')->paginate(15);
        return view('admin.genres', compact('genres'));
    }

  
    public function form(Request $request, Genres $genre = null)
    {

        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|max:50',
                'slug' => 'nullable|string|max:50',
            ];

  
            $rules['name'] .= $genre ? (',name,' . $genre->id) : ',unique:genres,name';
            $rules['slug'] .= $genre ? (',slug,' . $genre->id) : ',unique:genres,slug';

            $data = $request->validate($rules);

            if (empty(trim($data['slug']))) {
                $baseSlug = Str::slug($data['name'], '-');
                $data['slug'] = $baseSlug;
                
                $existingCount = Genres::where('slug', 'like', $baseSlug . '%')->count();
                if ($existingCount > 0) {
                    $data['slug'] = $baseSlug . '-' . ($existingCount + 1);
                }
            }

            if ($genre) {
                $genre->update($data);
                $message = 'Жанр обновлен';
            } else {
                Genres::create($data);
                $message = 'Жанр добавлен';
            }

            return redirect()->route('admin.genres')->with('success', $message);
        }


        return view('admin.genreForm', compact('genre'));
    }

    public function destroy(Genres $genre)
    {
        if ($genre->movies()->exists()) {
            return back()->with('error', 'Нельзя удалить жанр, к которому привязаны фильмы.');
        }
        $genre->delete();
        return back()->with('success', 'Жанр удален');
    }
}