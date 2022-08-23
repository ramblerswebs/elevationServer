<?php

// options
// index.php?data=[[x,y],[lat,long]]
//error_reporting(-1);
//ini_set('display_errors', 'On');

require_once 'SRTMGeoTIFFReader.php';
require_once 'classes/autoload.php';
spl_autoload_register('autoload');
$opts = new Options();

$datapoints = $opts->posts("data");
if ($datapoints === null) {
    $datapoints = $opts->gets("data");  // use the get while testing
}

//$datapoints = '[[0,0],[55.891843956394005,-1.5826391585140878],[53.073997,-4.090965],[53.073997,-4.093465],[53.073997,-4.090965]]';

if ($datapoints !== null) {
    $points = json_decode($datapoints);
    $dataReader = new SRTMGeoTIFFReader("../srtmelevationdata");
    $dataReader->showErrors = false;
    $results = [];
    foreach ($points as $point) {
        $lat = $point[0];
        $lon = $point[1];
        try {
            $elevation = $dataReader->getElevation($lat, $lon);
            if ($elevation < -32000) {
                $elevation = -1;
            }
            $newpt = [$lat, $lon, $elevation];
            $results[] = $newpt;
        } catch (Exception $e) {
            $elevation = -2;
            $newpt = [$lat, $lon, $elevation, 'unable to calculate elevation'];
            $results[] = $newpt;
        }
    }

    header("Access-Control-Allow-Origin: *");
    header("Content-type: application/json");
    echo json_encode($results);
}