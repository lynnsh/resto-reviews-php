<?php

namespace App;

/**
 * Description of Utilities
 *
 * @author aline
 */
class Utilities {
    //Montreal location is the default
    const defaultLatitude = '45.5016889';
    const defaultLongitude = '-73.5672560';
    const postalRegex = 'required|regex:'
                . '/^[A-Za-z][0-9][A-Za-z][ ]?[0-9][A-Za-z][0-9]$/';
    /*^((\d{5}-\d{4})|(\d{5})|([AaBbCcEeGgHhJjKkLlMmNnPpRrSsTtVvXxYy]\d[A-Za-z]\s?\d[A-Za-z]\d))$*/
    
    public function GetGeocodingSearchResults($address) {
        $address = urlencode($address); //Url encode since it was provided by user
        $url = "http://maps.google.com/maps/api/geocode/xml?address={$address}&sensor=false";
        $pairs = [];
        
        // Retrieve the XML file
        $results = file_get_contents($url);
        $xml = new \DOMDocument();//backslash to indicate global namespace
        $xml->loadXML($results);

        $xpath = new \DOMXpath($xml);
        $result = $xpath->query("//result");
        $i = 0;
        foreach($result as $item) {
            $latitude=$xpath->query("//location/lat") -> item($i) -> nodeValue;
            $longitude=$xpath->query("//location/lng") -> item($i) -> nodeValue;
            $pairs[] = ['latitude' => $latitude, 'longitude' => $longitude];
            $i++;
        }

        return $this -> getLocation($pairs);
    }
    
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
            $latitude=$locations[0]['latitude'];
            $longitude=$locations[0]['longitude'];
            $pair['latitude'] = $latitude; 
            $pair['longitude'] = $longitude;
        }
        else {
            $pair['latitude'] = Utilities::defaultLatitude; 
            $pair['longitude'] = Utilities::defaultLongitude;
        }
        
        return $pair;
    }
}
