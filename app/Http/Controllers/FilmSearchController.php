<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FilmSearchController extends Controller
{
     public function show(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:2',
        ]);

        $title = $data['title'];

       
        $film = Film::where('title', $title)->first();
        if ($film) {
            return response()->json($film); // 200
        }

     $search = Http::get(config('services.omdb.url'), [
            'apikey' => config('services.omdb.key'),
            's'      => $title,
            'type'   => 'movie',
        ])->json();

           if (($search['Response'] ?? 'False') === 'False') {
            return response()->json(['message' => 'Film OMDb’de bulunamadı.'], 404);
        }

        
        $first = $search['Search'][0] ?? null;
        if (!$first || empty($first['imdbID'])) {
            return response()->json(['message' => 'Geçerli sonuç bulunamadı.'], 404);
        }

        $imdbId = $first['imdbID'];

        
        $detail = Http::get(config('services.omdb.url'), [
            'apikey' => config('services.omdb.key'),
            'i'      => $imdbId,
            'plot'   => 'short',
        ])->json();

        if (($detail['Response'] ?? 'False') === 'False') {
            return response()->json(['message' => 'Detay çekilemedi.'], 502);
        }

       
        $film = Film::updateOrCreate(
            ['imdb_id' => $detail['imdbID']],
            [
                'title'       => $detail['Title']      ?? null,
                'year'        => $detail['Year']       ?? null,
                'released'    => $detail['Released']   ?? null,
                 
            'runtime'     => $detail['Runtime']    ?? null,
                'genre'       => $detail['Genre']      ?? null,
                'director'    => $detail['Director']   ?? null,
                'writer'      => $detail['Writer']     ?? null,
                'actors'      => $detail['Actors']     ?? null,
                'plot'        => $detail['Plot']       ?? null,
                'awards'      => $detail['Awards']     ?? null,
                'poster'      => $detail['Poster']     ?? null,
                'ratings'     => $detail['Ratings']    ?? [],
                'language'    => $detail['Language']   ?? null,
                'imdb_rating' => $detail['imdbRating'] ?? null, 
            ]
        );

        return response()->json($film, 201); // Created
    }
}
