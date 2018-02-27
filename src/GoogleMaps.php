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

    public function getLatLong(string $address)
    {
        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.rawurlencode($address).'&sensor=false');
        $output= json_decode($geocode);
        if ($output->status != "OK") return FALSE;

        $lat = $output->results[0]->geometry->location->lat;
        $long = $output->results[0]->geometry->location->lng;
        $_coords = [$lat,$long];
        return $_coords;
    }
}