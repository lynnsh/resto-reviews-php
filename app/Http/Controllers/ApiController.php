<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Resto;
use App\Utilities;

class ApiController extends Controller
{   
    public function restos(Request $request) {
        $util = new Utilities();
        $restos = $util -> getRestosNear(10, $request['latitude'], $request['longitude']);
        return response()->json($restos);
    }
    
    //security
    public function reviews(Request $request) {
        $reviews = Resto::find($request['resto_id']) -> reviews()->get();
        return response()->json($reviews);
    }
    
    public function create(Request $request) {
        //check credentials
        $credentials = $request->only('email', 'password');
        $valid = \Auth::once($credentials); //logs in for single request

        if (!$valid)
            return response()->json(['error' => 'invalid_credentials'], 401);
        else {
            $validator = $this->restoValidator($request->all());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $util = new Utilities();
            $util -> addResto($request);     
            return response()->json(['OK' => 'resto is added to the database'], 200);
        }
    }
    
    public function add_review(Request $request) {
        //check credentials
        $credentials = $request->only('email', 'password');
        $valid = \Auth::once($credentials); //logs in for single request

        if (!$valid)
            return response()->json(['error' => 'invalid_credentials'], 401);
        //custom error messages
        else {
            $validator = $this->reviewValidator($request->all());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            //not found
            Resto::find($request['resto_id']) -> reviews() -> create([
                'user_id' => $request -> user() -> id,
                'title' => $request -> title, 
                'content' => $request -> content,
                'rating' => $request -> rating,            
            ]);         
            return response()->json(['OK' => 'review is added to the database'], 200);
        }
    }
    
    private function restoValidator($data) {
        return Validator::make($data, [
                'name' => 'required|max:255',
                'genre' => 'required|max:255',
                'price' => array('required','regex:/^\${1,4}$/'),
                'address' => 'required_without_all:postalcode|max:255',
                'postalcode' => 'required_without_all:address|'
                    .'regex:/^[A-Za-z][0-9][A-Za-z][ ]?[0-9][A-Za-z][0-9]$/',
            ]);
    }
    
    private function reviewValidator($data) {
        return Validator::make($data, [
                'resto_id' => 'required|numeric',
                'title' => 'required|max:50',
                'content' => 'required|max:255',
                'rating' => array('required','regex:/^[1-5]$/'),
               ]);
    }
}
