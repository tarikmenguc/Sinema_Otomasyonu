<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function PHPSTORM_META\type;

class FilmController extends Controller
{
   public function index(Request $request)
    {
        if ($title = $request->input('title')) {
           
            $request->validate(['title' => 'required|string|min:2']);

          
            $search = Http::get(config('services.omdb.url'), [
                'apikey' => config('services.omdb.key'),
                's'      => $title,
                'type'   => 'movie',
            ])->json();

            if (($search['Response'] ?? 'False') === 'True' && !empty($search['Search'][0]['imdbID'])) {
                $imdbId = $search['Search'][0]['imdbID'];

                
                $detail = Http::get(config('services.omdb.url'), [
                    'apikey' => config('services.omdb.key'),
                    'i'      => $imdbId,
                    'plot'   => 'short',
                ])->json();

                if (($detail['Response'] ?? 'False') === 'True') {
                
                    
                    Film::updateOrCreate(
                        ['imdb_id' => $detail['imdbID']],
                        [
                         'title' => $detail['Title']?? null,
                    'year'=> $detail['Year']?? null,
                            'released'=> $detail['Released']?? null,
                            'runtime'=> $detail['Runtime']?? null,
                            'genre'=> $detail['Genre']?? null,
                            'director'=> $detail['Director']?? null,
                            'writer'=> $detail['Writer']?? null,
                            'actors' => $detail['Actors']?? null,
                        'plot'=> $detail['Plot']?? null,
                            'awards'=> $detail['Awards']?? null,
                            'poster'=> $detail['Poster']?? null,
                            'ratings'=> $detail['Ratings']?? [],
                            'language'=> $detail['Language']?? null,
                            'imdb_rating' => $detail['imdbRating'] ?? null,
                        ]
                    );
      session()->flash('status', "“{$title}” başarıyla eklendi.");
     } else {
                    session()->flash('error', 'Film detayları çekilemedi.');
                }
            } else {
                session()->flash('error', 'OMDb’de film bulunamadı.');
            }
            return redirect()->route('admin.films.index');
        }

     $films = Film::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.films.index', compact('films'));
    }
    public function show (Film $film){
    return view("admin.films.show",compact("film"));
    }
    public function destroy(Film $film){
   $film ->delete();
   return redirect()->route("admin.films.index")->with("status","film silindi");
    }
}
