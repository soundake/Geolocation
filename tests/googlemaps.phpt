<?php
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/GoogleMaps.php';

\Tester\Environment::setup();

$geo = new \soundake\utils\GoogleMaps();

Assert::false($geo->getLatLong("UYGBNJUYGHJGBJYGBHJUYGBHYGBHYG"));
$g = $geo->getLatLong("Na Maninách 12, Praha");
if ($g) {
    Assert::same([50.1017197, 14.4527291], $g);
}