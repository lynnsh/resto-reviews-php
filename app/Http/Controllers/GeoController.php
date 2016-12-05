<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities;

/**
 * The Controller class that handles geolocation of the user.
 * 
 * @author Alena Shulzhenko
 * @version 2016-12-05
 */
class GeoController extends Controller {
    
    /**
     * The index page that determines if the user location is determined.
     * If it is, the user is redirected to the resto page, otherwise the page
     * with the postal code form is returned.
     */
    public function index(){
        $lat = session('latitude');
        if(isset($lat))
            return redirect('/resto');
        return view('geo.index');
    }
    
    /**
     * Sets user's latitude and longitude either from JavaScript data,
     * or, if it was not accessible, from the postal code form.
     * @param Request $request the Request object with postal code.
     */
    public function locate(Request $request) {
        if($request['error'] == 0) {
            session(['latitude' => $request['latitude'],
                     'longitude' => $request['longitude']]);
        }
        else {
            $this -> validate($request, ['postal' => Utilities::postalRegex]);
            $util = new Utilities();
            $pair = $util -> GetGeocodingSearchResults($request['postal']);
            session(['latitude'=> $pair['latitude'], 'longitude' => $pair['longitude']]);
        }
        return redirect('/resto');
    }
}
