<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    
    public function index()
    {
            $films = Film::orderBy('created_at', 'desc')->get();
    return response()->json($films);
    }

   
    public function show(Request $request)
    {
        $film = Film::findOrFail($request->route('id'));
        return response()->json($film);
    }

    
    public function showByTitle($title)
    {
        $film = Film::where('title', $title)->first();

        if (!$film) {
            return response()->json(['message' => 'Film bulunamadı'], 404);
        }

        return response()->json($film);
    }
  
}
