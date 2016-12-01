<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    /**
     * Routes to the appropriate address.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $lat = session('latitude');
        if(! isset($lat))
            return redirect('/geo');
        else
            return redirect('/resto');
    }
}
