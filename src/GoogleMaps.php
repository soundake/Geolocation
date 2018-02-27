<?php
/**
 * Created by PhpStorm.
 * User: soundake
 * Date: 27.02.18
 * Time: 9:46
 */

namespace soundake\utils;

use Nette\SmartObject;

class GoogleMaps
{
    use SmartObject;

    //http://www.barattalo.it/2011/01/24/php-geocoding-function-from-address-to-coordinates-lat-long/

    public function getLatLong(string $address): array
    {
        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.rawurlencode($address).'&sensor=false');
        $output= json_decode($geocode);
        $lat = $output->results[0]->geometry->location->lat;
        $long = $output->results[0]->geometry->location->lng;

        $_coords = [$lat,$long];
        return $_coords;
    }
}