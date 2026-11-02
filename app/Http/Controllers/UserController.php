<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Личный кабинет
    public function profile()
    {
        $user = auth()->user();
        $active = $user->tickets()->where('status', 'paid')->orderBy('created_at', 'desc')->get();

        return view('user.profile', compact('active'));
    }
// кьюаркод
    public function showTicket(Ticket $ticket)
    {
        abort_if($ticket->user_id !== auth()->id(), 403);

        return view('user.ticket', compact('ticket'));
    }
}