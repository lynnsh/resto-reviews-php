<?php

namespace App;

/**
 * Description of Utilities
 *
 * @author aline
 */
class Utilities {
    
    public function GetGeocodingSearchResults($address) {
        $address = urlencode($address); //Url encode since it was provided by user
        $url = "http://maps.google.com/maps/api/geocode/xml?address={$address}&sensor=false";

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

        return $pairs;
    }
}
