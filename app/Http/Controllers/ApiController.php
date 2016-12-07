<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Resto;
use App\Utilities;

/**
 * The Controller class that handles api requests from the android application.
 * 
 * @author Alena Shulzhenko
 * @version 2016-12-05
 */
class ApiController extends Controller {   
    
    /**
     * Responds to a GET request and returns JSON containing the restaurants 
     * that are close to the provided location (latitude and longitude).
     * @param Request $request the Request object with latitude and longitude.
     * @return JSON containing the restaurants that are close to the provided location,
     *         or code 400 if latitude or longitude were invalid.
     */
    public function restos(Request $request) {
        $util = new Utilities();
        $lat = $request['latitude'];
        $long = $request['longitude'];
        if(is_numeric($lat) && is_numeric($long)) {
            $restos = $util -> getRestosNear(10, $lat, $long);
            //convert price to an integer for android
            $this->getPriceAsInt($restos);
            return response()->json($restos);
        }
        else
            return response()->json(['error' => 'invalid latitude or longitude'], 400);
    }
    
    /**
     * Responds to a GET request and returns JSON containing the reviews 
     * corresponding to the provided restaurant.
     * @param Request $request the Request object with the restaurant id.
     * @return JSON containing the reviews corresponding to the provided restaurant, 
     *         code 404 if the restaurant provided is not in the database,
     *         or code 400 if the resto id has an invalid format.
     */
    public function reviews(Request $request) {
        $id = $request['resto_id'];
        if(!is_numeric($id))
            return response()->json(['error' => 'invalid id for resto'], 400);
        $resto = Resto::find($id);
        if(isset($resto)) {
            $reviews = $resto -> reviews() -> get();
            return response()->json($reviews);
        }
        //the restaurant is not in the database
        else
            return response()->json(['error' => 'the resto is not found'], 404);
    }
    
    /**
     * Responds to a POST request and creates a new restaurant with the
     * provided information.
     * Validator object is created to support requests without
     * header requesting JSON object in return.
     * @param Request $request the Request object with the information 
     *                         to create a new restaurant.
     * @return code 200 if the  restaurant was added successfully;
     *         code 401 if the user had invalid credentials;
     *         code 400 if some restaurant fields were invalid.
     */
    public function create(Request $request) {
        //check credentials
        $credentials = $request->only('email', 'password');
        $valid = \Auth::once($credentials); //logs in for single request

        if (!$valid)
            return response()->json(['error' => 'invalid_credentials'], 401);
        else {
            //to validate resto fields
            $validator = $this->restoValidator($request->all());
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $util = new Utilities();
            $resto = $util -> addResto($request);     
            return response()->json(['id' => $resto->id], 200);
        }
    }
    
    /**
     * Responds to a POST request and adds a new review for the provided
     * restaurant.
     * @param Request $request the Request object with the information 
     *                         to add a new review.
     * @return code 200 if the review was added successfully;
     *         code 401 if the user had invalid credentials;
     *         code 400 if some review fields were invalid;
     *         code 404 if the restaurant provided is not in the database.
     */
    public function add_review(Request $request) {
        //check credentials
        $credentials = $request->only('email', 'password');
        $valid = \Auth::once($credentials); //logs in for single request
        if (!$valid)
            return response()->json(['error' => 'invalid_credentials'], 401);
        else {
            //validation
            $this -> validate($request, [
                'title' => 'required|max:50', 'content' => 'required|max:255',
                'rating' => array('required','regex:/^([1-4]\.[0-9]+)|5\.0$/')]);
            $resto = Resto::find($request['resto_id']);
            if(isset($resto)) {
                return $this -> createReview($resto, $request);                  
            }
            //the restaurant is not in the database
            else
                return response()->json(['error' => 'the resto is not found'], 404);
        }
    }
    
    /**
     * Creates new review in the database.
     * @param Resto $resto the Resto to which this review corresponds to.
     * @param Request $request the Request object with necessary information
     *                         to create a review.
     * @return JSON with code 200, signalling that the review was added successfully.
     */
    private function createReview(Resto $resto, Request $request) {
        //converting float rating from android
        $rating = intval(round($request -> rating));
        $review = $resto -> reviews() -> create([
                    'user_id' => $request -> user() -> id,
                    'title' => $request -> title, 
                    'content' => $request -> content,
                    'rating' => $rating,            
        ]);         
        return response()->json(['id' => $review->id], 200);
    }
    
    /**
     * The Validator that verifies Resto fields submitted through a POST request.
     * @param $data the data submitted through a POST request to verify.
     * @return the result of the validation.
     */
    private function restoValidator($data) {
        return Validator::make($data, [
                'name' => 'required|max:255',
                'genre' => 'required|max:255',
                'price' => array('required','regex:/^[1-4]$/'),
                'address' => 'required_without_all:postalcode|max:255',
                'postalcode' => 'required_without_all:address|'
                    .'regex:/^[A-Za-z][0-9][A-Za-z][ ]?[0-9][A-Za-z][0-9]$/',
            ]);
    }
    
    /**
     * Converts price from $ to an integer for Android.
     * @param $restos the array of Resto objects.
     */
    private function getPriceAsInt($restos) {
        foreach($restos as $resto) {
            $resto->price = strlen($resto->price);
        }
    }
}
