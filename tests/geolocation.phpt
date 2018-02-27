<?php
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/Geolocation.php';

\Tester\Environment::setup();

$geo = new \soundake\utils\Geolocation();

Assert::type("array", $geo::getCoordsFromText("Loc: 50°5'42.12\"N,14°29'14.75\"E"));

Assert::same(0.0, $geo::getDistance(12,12,12,12));
Assert::same(0.0, $geo::getDistance(50.2,14.8,50.2,14.8));

//Assert::same([50.095033333333, 14.487430555556], $geo::getCoordsFromText("Loc: 50°5'42.12\"N,14°29'14.75\"E"));
//Assert::same([50.095033333333, 14.487430555556], $geo::getCoordsFromText("N 50°8.92478', E 15°32.08008'"));
//Assert::same([50.095033333333, 14.487430555556], $geo::getCoordsFromText("50.1487464N, 15.5346681E"));