<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resto;
use App\Utilities;

/**
 * The Controller class that handles web requests for restaurants,
 * such as adding, editing, deleting, viewing, and searching for a resto.
 * 
 * @author Alena Shulzhenko
 * @version 2016-01-03
 */
class RestoController extends Controller {
      
    /**
     * Returns a view that displays the restos that are close to the user.
     * If user's location is undefined, the user is redirected 
     * to the geolocation page.
     * Sends a json with the restos list for the view to display it on the map.
     * @return a view that displays the restos that are close to the user.
     */
    public function index() {  
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        $util = new Utilities();
        $restos = $util -> getRestosNear(20, $lat, session('longitude'));         
        return view('resto.index', ['restos' => $restos, 'json' => json_encode($restos)]);
    }
    
    /**
     * Returns a view that displays the information about the selected resto
     * along with the reviews associated with this resto (paginated).
     * @param Resto $resto the resto which information is displayed.
     * @return a view that displays the information about the selected resto.
     */
    public function view(Resto $resto) {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        $reviews = $resto -> reviews() -> paginate(15);
        return view('resto.view', ['resto' => $resto, 'reviews' => $reviews]);
    }
    
    /**
     * Returns a view that displays a form for creating a new resto.
     * @return a view that displays a form for creating a new resto.
     */
    public function create() {
        return view('resto.create');
    }
    
    /**
     * Saves the new resto to the database if the entered information is valid.
     * Any authenticated user can create a resto. 
     * @param Request $request the request containing the resto information.
     * @return redirects to the view page for the created resto when
     *         the resto is successfully created.
     */
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
    
    /**
     * Returns a view with restos corresponding to the search key.
     * The key is searched in name, genre or address o tfhe resto.
     * @param Request $request the request with the search key.
     * @return a view with restos corresponding to the search key.
     */
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
    
    /**
     * Returns a view that displays a form for editing the selected resto.
     * @return a view that displays a form for editing the selected resto.
     */
    public function edit(Resto $resto) {
        $this->authorize('update', $resto);
        return view('resto.edit', ['resto' => $resto]);
    }
    
    /**
     * Saves the edited resto to the database if the entered information is valid.
     * Any authenticated user can edit a resto.
     * @param Request $request the request containing the resto information.
     * @return redirects to the view page for the edited resto when
     *         the database is successfully updated.
     */
    public function edit_resto(Request $request) {
        $this -> validate($request, [
            'name' => 'required|max:100', 'genre' => 'required|max:255',
            'price' => array('required','regex:/^\${1,4}$/'),
            'address' => 'required|max:255']);
        $util = new Utilities();
        //get new latitude and longitude for the resto
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
    
    /**
     * Deletes the requested resto from the database.
     * Only the user that created a resto can delete it.
     * @param Request $request
     * @param Resto $resto the resto to delete.
     * @return redirects to the view with the resto list.
     */
    public function delete(Request $request, Resto $resto) {
        $this->authorize('delete', $resto);
        $resto->delete();
        return redirect("/resto");
    }
    
    /**
     * Returns reviews and rating for each resto in the array.
     * @param $restos Resto array.
     * @return reviews and rating for each resto in the array.
     */
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
