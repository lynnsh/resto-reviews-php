<?php

namespace App;
use Illuminate\Http\Request;

/**
 * The Utility helper class for managing Restos and locations.
 *
 * @author Alena Shulzhenko
 * @version 2016-12-05
 */
class Utilities {
    //Montreal location is the default
    const defaultLatitude = '45.5016889';
    const defaultLongitude = '-73.5672560';
    //Canadian postal code
    const postalRegex = 'required|regex:'
                . '/^[A-Za-z][0-9][A-Za-z][ ]?[0-9][A-Za-z][0-9]$/';
    
    /**
     * Returns the restos close to the location with 
     * the specified longitude and latitude.
     * @param $number the number of restos to get from the database.
     * @param $latitude the specified location latitude.
     * @param $longitude the specified location longitude.
     * @param $radius the radius within which the restos are found,
     * @return the restos close to the specified longitude and latitude.
     */
    public function getRestosNear($number, $latitude, $longitude, $radius = 50) {
        //find the rating for each resto
        $rating = Review::selectRaw('avg(rating)') 
                    -> whereRaw('reviews.resto_id=restos.id');
        //find the number of reviews for each resto
        $numReviews = Review::selectRaw('count(*)') 
                    -> whereRaw('reviews.resto_id=restos.id');
        //get all info and distances for each resto using the Haversine formula
        $distances = Resto::select('restos.*')             
            ->selectRaw('( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) ) * cos( radians( longitude ) 
                - radians(?)) + sin( radians(?) ) * sin( radians(latitude ) ) )
              ) AS distance', [$latitude, $longitude, $latitude])
            ->selectRaw("({$rating->toSql()}) as rating ")
                ->mergeBindings($rating->getQuery())
            ->selectRaw("({$numReviews->toSql()}) as reviews")
                ->mergeBindings($numReviews->getQuery());
        //get all restos within the specified distance
        $restos = \DB::table( \DB::raw("({$distances->toSql()}) as restodistance") )
            ->mergeBindings($distances->getQuery())
            ->whereRaw("distance < ? ", [$radius])
            ->orderBy('distance')->take($number)
            ->get();

        return $restos;
    }
    
    /**
     * Adds Resto to the database.
     * @param Request $request object and contains all information 
     *                         in order to add a resto.
     * @return Resto the created Resto object.
     */
    public function addResto(Request $request) {
        //find latitude and longitude for the resto
        $address = !empty($request['postalcode']) ? 
                          $request['postalcode'] : $request['address'];
        $pair = $this -> GetGeocodingSearchResults($address);
        $full_address = $request['address'].' '.$request['postalcode'];
        
        $price = $request -> price;
        //check if the request information comes from Android
        if(is_numeric($price))
            $price = $this -> getStringPrice($price);
        
        return $request -> user() -> restos() -> create([
            'name' => $request -> name, 'genre' => $request -> genre,
            'price' => $price, 'address' => $full_address,
            'latitude' => $pair['latitude'], 'longitude' => $pair['longitude'],              
        ]);        
    }
    
    /**
     * Returns latitude and longitude for the specified address.
     * @param $address the address which latitude and longitude is requested.
     * @return an array with latitude and longitude for the specified address.
     */
    public function GetGeocodingSearchResults($address) {
        //Url encode since it was provided by user
        $address = urlencode($address); 
        $url = "http://maps.google.com/maps/api/geocode/xml?address={$address}"
              ."&sensor=false";
        $pairs = [];
        
        // Retrieve the XML file
        $results = file_get_contents($url);
        $xml = new \DOMDocument();//backslash to indicate global namespace
        $xml->loadXML($results);
        $xpath = new \DOMXpath($xml);
        $result = $xpath->query("//result");
        $i = 0;
        //get all latitude and longitude pairs from google maps api
        foreach($result as $item) {
            $latitude=$xpath->query("//location/lat") -> item($i) -> nodeValue;
            $longitude=$xpath->query("//location/lng") -> item($i++) -> nodeValue;
            $pairs[] = ['latitude' => $latitude, 'longitude' => $longitude];
        }
        return $this -> getLocation($pairs);
    }
    
    /**
     * Returns latitude and longitude pair.
     * If the in the locations array all latitude and longitude 
     * pairs are the same or there is only one pair, that pair is returned.
     * If there are multiple pairs with different latitude and longitude,
     * the default latitude and longitude is returned, which is the one
     * for the Montreal city.
     * @param $locations latitude/longitude pairs from google maps api.
     * @return an array with one unique value for each latitude and longitude.
     */
    private function getLocation($locations) {
        $same = true;
        $length = count($locations);
        $pair = [];
        for($i = 1; $i < $length && $same; $i++) {
            if($locations[$i]['latitude'] !== $locations[$i-1]['latitude']
              && $locations[$i]['longitude'] !== $locations[$i-1]['longitude'])
                $same = false;
        }
        if($same && $length > 0) {
            $pair['latitude'] = $locations[0]['latitude'];
            $pair['longitude'] = $locations[0]['longitude'];
        }
        else {
            $pair['latitude'] = Utilities::defaultLatitude; 
            $pair['longitude'] = Utilities::defaultLongitude;
        }      
        return $pair;
    }
    
    /**
     * Converts numeric representation of price to a string.
     * @param $price the price as a numeric value (1-4).
     * @return the price as a string ($-$$$$).
     */
    private function getStringPrice($price) {
        $str = '';
        for($i = 0; $i < $price; $i++)
            $str.='$';
        return $str;
    }
}
