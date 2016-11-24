<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resto;
use App\Review;
use App\Utilities;

class RestoController extends Controller {
    
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
    }
      
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {      
        $restos = $this -> getRestosNear(session('latitude'), session('longitude'));         
        return view('resto.index', ['restos' => $restos, 
                                    'add' => $this -> getRatingAndReviews($restos),
                                    'index' => 0]);
    }
    
    
    public function view(Resto $resto) {
        $reviews = $resto -> reviews() -> paginate(15);
        return view('resto.view', ['resto' => $resto, 'reviews' => $reviews]);
    }
    
    public function create() {
        return view('resto.create');
    }
    
    public function create_resto(Request $request) {
        $this -> validate($request, ['name' => 'required|max:255',
                                     'genre' => 'required|max:255',
                                     'price' => array('required','regex:/\$|\$\$|\$\$\$|\$\$\$\$/'),
                                     'address' => 'required|max:255',
                                     'postalcode' => Utilities::postalRegex,
                                    ]);
        $util = new Utilities();
        $pair = $util -> GetGeocodingSearchResults($request['postalcode']);
        $request -> user() -> restos() -> create([
            'name' => $request -> name, 'genre' => $request -> genre,
            'price' => $request -> price, 'address' => $request -> address,
            'latitude' => $pair['latitude'], 'longitude' => $pair['longitude'],              
        ]);           
        return redirect('/');
    }
    
    public function search(Request $request) {
        $key = $request['key'];
        $restos = Resto::where('name', 'like', '%'.$key.'%') 
                -> orWhere('genre', 'like', '%'.$key.'%') 
                -> orWhere('address', 'like', '%'.$key.'%')
                -> paginate(1);
        return view('resto.search', ['restos' => $restos, 
                                    'add' => $this -> getRatingAndReviews($restos),
                                    'index' => 0, 'key' => $key]);
    }
    
    public function edit(Resto $resto) {
        
    }
    
    public function edit_resto(Request $request) {
        
    }
    
    public function add_review(Resto $resto) {
        
    }
    
    public function add_review_resto(Request $request) {
        
    }
    
    public function getRestosNear($latitude, $longitude, $radius = 50) {
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
