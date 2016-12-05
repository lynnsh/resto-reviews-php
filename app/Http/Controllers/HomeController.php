<?php

namespace App\Http\Controllers;

/**
 * The Controller class that redirects the user to the appropriate page.
 * 
 * @author Alena Shulzhenko
 * @version 2016-12-05
 */
class HomeController extends Controller {

    /**
     * Routes to the appropriate address. If user's location is set, the user
     * is redirected to the resto page; otherwise to geolocation page.
     */
    public function index() {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        else
            return redirect('/resto');
    }
}
