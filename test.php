<?php

require_once 'SRTMGeoTIFFReader.php';
$dataReader = new SRTMGeoTIFFReader("../srtmelevationdata");
// where GeoData is the directory containing your SRTM data files
// get single elevation
$lat = 53.073997;
$lon = -4.095965;
$elevation = $dataReader->getElevation($lat, $lon);

