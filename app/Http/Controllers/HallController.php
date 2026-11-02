<?php

namespace App\Http\Controllers;

use App\Models\Halls;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Middleware;

#[Middleware('auth')]
#[Middleware('role:admin')]
class HallController extends Controller
{
    public function index()
    {
        $halls = Halls::orderBy('name')->paginate(15);
        return view('admin.halls', compact('halls'));
    }

    public function form(Request $request, Halls $hall = null)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|max:100',
                'rows_count' => 'required|integer|min:1|max:50',
                'seats_per_row' => 'required|integer|min:1|max:50',
            ];

            $rules['name'] .= $hall ? (',name,' . $hall->id) : ',unique:halls,name';

            $data = $request->validate($rules);

            if ($hall) {
                $hall->update($data);
                $message = 'Зал обновлен';
            } else {
                $hall = Halls::create($data);
                $message = 'Зал добавлен';
            }

            return redirect()->route('admin.halls')->with('success', $message);
        }

        return view('admin.hallsForm', compact('hall'));
    }

    public function destroy(Halls $hall)
    {
        if ($hall->screenings()->exists()) {
            return back()->with('error', 'Нельзя удалить зал, в котором запланированы сеансы.');
        }
        $hall->delete();
        return back()->with('success', 'Зал удален');
    }
}