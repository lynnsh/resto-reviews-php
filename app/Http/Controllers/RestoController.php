<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resto;
use App\Utilities;

class RestoController extends Controller {
      
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {  
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        $restos = $this -> getRestosNear($lat, session('longitude'));         
        return view('resto.index', ['restos' => $restos, 
                                    'add' => $this -> getRatingAndReviews($restos),
                                    'index' => 0]);
    }
    
    
    public function view(Resto $resto) {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        $reviews = $resto -> reviews() -> paginate(15);
        return view('resto.view', ['resto' => $resto, 'reviews' => $reviews]);
    }
    
    public function create() {
        //$this->authorize('create');
        return view('resto.create');
    }
    
    public function create_resto(Request $request) {
        $this -> validate($request, [
            'name' => 'required|max:255',
            'genre' => 'required|max:255',
            'price' => array('required','regex:/^\${1,4}$/'),
            'address' => 'required_without_all:postalcode|max:255',
            'postalcode' => 'required_without_all:address|'
                .'regex:/^[A-Za-z][0-9][A-Za-z][ ]?[0-9][A-Za-z][0-9]$/',
        ]);
        $util = new Utilities();
        $address = !empty($request['postalcode']) ? $request['postalcode'] : $request['address'];
        $pair = $util -> GetGeocodingSearchResults($address);
        $full_address = $request['address'].' '.$request['postalcode'];
        $resto = $request -> user() -> restos() -> create([
            'name' => $request -> name, 'genre' => $request -> genre,
            'price' => $request -> price, 'address' => $full_address,
            'latitude' => $pair['latitude'], 'longitude' => $pair['longitude'],              
        ]);         
        return redirect('/resto/view/'.$resto->id);
    }
    
    public function search(Request $request) {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        $key = $request['key'];
        $restos = Resto::where('name', 'like', '%'.$key.'%') 
                -> orWhere('genre', 'like', '%'.$key.'%') 
                -> orWhere('address', 'like', '%'.$key.'%')
                -> paginate(15);
        return view('resto.search', ['restos' => $restos, 
                                    'add' => $this -> getRatingAndReviews($restos),
                                    'index' => 0, 'key' => $key]);
    }
    
    public function edit(Resto $resto) {
        $this->authorize('update', $resto);
        return view('resto.edit', ['resto' => $resto]);
    }
    
    public function edit_resto(Request $request) {
        $this -> validate($request, ['name' => 'required|max:100',
                                     'genre' => 'required|max:255',
                                     'price' => array('required','regex:/^\${1,4}$/'),
                                     'address' => 'required|max:255',
                                    ]);
        $util = new Utilities();
        $pair = $util -> GetGeocodingSearchResults($request['address']);
        $resto = Resto::find($request -> id);
        $resto -> name = $request -> name; 
        $resto -> genre = $request -> genre;
        $resto -> price = $request -> price;
        $resto -> address = $request -> address;
        $resto -> latitude = $pair['latitude'];
        $resto -> longitude = $pair['longitude'];
        $resto -> save();
        return redirect('/resto/view/'.$resto->id);
    }
    
    public function add_review(Resto $resto) {
        $this->authorize('update', $resto);
        return view('resto.add-review', ['resto' => $resto]);
    }
    
    public function add_review_resto(Request $request) {
        $this -> validate($request, ['title' => 'required|max:50',
                                     'content' => 'required|max:255',
                                     'rating' => array('required','regex:/^[1-5]$/'),
                                    ]);
        $id = $request -> resto_id;
        Resto::find($id) -> reviews() -> create([
                'user_id' => $request -> user() -> id,
                'title' => $request -> title, 
                'content' => $request -> content,
                'rating' => $request -> rating,            
            ]);         
        return redirect("resto/view/$id");
    }
    
    private function getRestosNear($latitude, $longitude, $radius = 50) {
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
