<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities;

class GeoController extends Controller {
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $lat = session('latitude');
        if(isset($lat))
            return redirect('/resto');
        return view('geo.index');
    }
    
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
