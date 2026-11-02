<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //админка
    public function panel()
    {
        return view('admin.panel');
    }
}