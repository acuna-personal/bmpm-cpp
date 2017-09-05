<?php

error_reporting(E_ALL);

include_once "BMPM.php";

// Create json files that will be used by CPP code
if (!isset($PHP2CPP_OUTPUT_DIRECTORY)) {
  $PHP2CPP_OUTPUT_DIRECTORY = '.';
}

@mkdir($PHP2CPP_OUTPUT_DIRECTORY . "/json", 0755, true);

$bmpmData = BMPM::getAllBMPMData();
file_put_contents($PHP2CPP_OUTPUT_DIRECTORY . "/json/bmpm.json", json_encode($bmpmData));

$dmData = BMPM::getDMData();
file_put_contents($PHP2CPP_OUTPUT_DIRECTORY . "/json/dm.json", json_encode($dmData));



?>