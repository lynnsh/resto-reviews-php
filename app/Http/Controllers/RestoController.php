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
        $util = new Utilities();
        $restos = $util -> getRestosNear(20, $lat, session('longitude'));         
        return view('resto.index', ['restos' => $restos]);
    }
    
    
    public function view(Resto $resto) {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        $reviews = $resto -> reviews() -> paginate(15);
        return view('resto.view', ['resto' => $resto, 'reviews' => $reviews]);
    }
    
    public function create() {
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
        $resto = $util -> addResto($request);        
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
        $this -> validate($request, [
            'name' => 'required|max:100', 'genre' => 'required|max:255',
            'price' => array('required','regex:/^\${1,4}$/'),
            'address' => 'required|max:255']);
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
    
    public function delete(Request $request, Resto $resto) {
        $this->authorize('delete', $resto);
        $resto->delete();
        return redirect("/resto");
    }
    
    private function getRatingAndReviews($restos) {
        $add = [];
        foreach($restos as $resto) {
            $rating = number_format($resto -> reviews() 
                            -> avg('rating'), 2, '.', '') ?? 0;
            $add[] = ['reviews' => $resto -> reviews() -> count(),
                      'rating' => $rating];
        }
        return $add;
    }

}
