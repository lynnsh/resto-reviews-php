<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resto;
use App\Review;

class RestoController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        else {
            $index = 0;
            $restos = $this -> getRestosNear(session('latitude'), session('longitude'));         
            return view('resto.index', ['restos' => $restos, 
                                        'add' => $this -> getRatingAndReviews($restos),
                                        'index' => $index]);
        }
    }
    
    //validation
    public function view(Resto $resto) {
        $reviews = Review:: select('reviews.*') -> where('resto_id', '=', $resto->id)->paginate(15);
        return view('resto.view', ['resto' => $resto, 'reviews' => $reviews]);
    }
    
    public function getRestosNear($latitude, $longitude, $radius = 50) {
        //eager loading?
        $restos = Resto::select('restos.*')
            ->selectRaw('( 6371 * acos( cos( radians(?) ) *
                               cos( radians( latitude ) )
                               * cos( radians( longitude ) - radians(?))
                               + sin( radians(?) ) *
                               sin( radians(latitude ) ) )
                             ) AS distance', [$latitude, $longitude, $latitude])
            ->whereRaw("'distance' < ? ", [$radius])
            ->orderBy('distance')->take(20)->get();
        
        return $restos;
    }
    
    private function getRatingAndReviews($restos) {
        $add = [];
        foreach($restos as $resto) {
            $rating = number_format($resto -> reviews() -> avg('rating'), 2, '.', '') ?? 0;
            $add[] = ['reviews' => $resto -> reviews() -> count(),
                      'rating' => $rating];
        }
        return $add;
    }
}
