<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('status', 'approved');

        if ($request->filled('search')) {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'asc');
        $query->orderBy('date', $sort)->orderBy('time', $sort);

        $events = $query->get();

        return view('home', compact('events'));
    }
}
