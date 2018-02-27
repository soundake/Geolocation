<?php
namespace soundake\utils;

class Geolocation
{
    use \Nette\SmartObject;

	/**
	 * Get latitude and longitude from text interpretation
	 * @param $input (Loc: 50°5'42.12"N,14°29'14.75"E or N 50°8.92478', E 15°32.08008' or 50.1487464N, 15.5346681E)
	 * @return array((double) x,(double) y)
	 */
	public static function getCoordsFromText($input)
	{
		$parts = array();
		$input = preg_replace("/\xc2/", '', $input);
		if (preg_match("/^Loc: (\d\d)\xb0(\d{1,2})'(\d{1,2}(?:\.\d{1,3})?)\"N,\s*(\d\d)\xb0(\d{1,2})'(\d{1,2}(?:\.\d{1,3})?)\"E$/i", trim($input), $parts)) ;
        elseif (preg_match("/^(\d\d)\xb0(\d{1,2})'(\d{1,2}(?:\.\d{1,3})?)\"N,\s*(\d\d)\xb0(\d{1,2})'(\d{1,2}(?:\.\d{1,3})?)\"E$/i", trim($input), $parts)) ;
        elseif (preg_match("/^N\s(\d{1,2})\xb0(\d{1,2}.\d{1,10})',\s*E\s(\d{1,2})\xb0(\d{1,2}.\d{1,10})'$/i", trim($input), $parts));
        elseif (preg_match("/^(\-?\d+\.\d+?)N,\s*(\-?\d+\.\d+?)E$/i", trim($input), $parts));
//        dump($input);
//        dump($parts);

        if (count($parts) == 7) {
            list($odpad, $degx, $minx, $secx, $degy, $miny, $secy) = $parts;
            $pozicex = (double)$degx + ($minx * 60 + $secx) / 3600;
            $pozicey = (double)$degy + ($miny * 60 + $secy) / 3600;
        } elseif (count($parts) == 5) {
            list($odpad, $degx, $minx, $degy, $miny) = $parts;
            $pozicex = (double)$degx + ($minx * 60) / 3600;
            $pozicey = (double)$degy + ($miny * 60) / 3600;
        } elseif (count($parts) == 3) {
            list($odpad, $pozicex, $pozicey) = $parts;
		} else {
			return false;
		}
//        dump(array($pozicex, $pozicey));
		return array($pozicex, $pozicey);
	}

	/**
	 * Get distance between two subjects in metres.
	 * @param $sourceLat double
	 * @param $sourceLon double
	 * @param $subjLat double
	 * @param $subjLon double
	 * @return double
	 */
	public static function getDistance($sourceLat, $sourceLon, $subjLat, $subjLon)
	{
		// getDistance
		$lat1 = deg2rad($subjLat);
		$lat2 = deg2rad($sourceLat);
		$lon1 = deg2rad($subjLon);
		$lon2 = deg2rad($sourceLon);

		$dlat = $lat2 - $lat1;
		$dlon = $lon2 - $lon1;
		$a = self::haversine($dlat) + cos($lat1) * cos($lat2) * self::haversine($dlon);
		$b = min(sqrt($a), 1);
		$c = 2 * asin($b);
		$d = (double)6372795.477598 * $c;
		return $d; // vzdalenost v metrech

//		$delta_lat = $lat2 - $lat1 ;
//		$delta_lon = $lon2 - $lon1 ;
//		$earth_radius = 3960.00; // in miles
//
//		$alpha    = $delta_lat/2;
//		$beta     = $delta_lon/2;
//		$a        = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
//		$c        = asin(min(1, sqrt($a)));
//		$distance = 2*$earth_radius * $c * 1609.344;
//		$distance = round($distance, 4);
//		return $distance;
	}

	private static function haversine($x)
	{
		$res = sin($x / 2);
		return $res * $res;
	}

	//http://www.barattalo.it/2011/01/24/php-geocoding-function-from-address-to-coordinates-lat-long/

	public function getLatLong($address)
	{
		if (!is_string($address)) die("All Addresses must be passed as a string");
		$_url = sprintf('http://maps.google.com/maps?output=js&q=%s', rawurlencode($address));
		$_result = false;
		if ($_result = file_get_contents($_url)) {
			if (strpos($_result, 'errortips') > 1 || strpos($_result, 'Did you mean:') !== false) return false;
			preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
			$_coords['lat'] = $_match[1];
			$_coords['long'] = $_match[2];
		}
		return $_coords;
	}


}