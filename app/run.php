<?php

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/config.php";

$url = SEARCH_URL;
$client = new Goutte\Client();
$title_and_company = \Acme\App\Wikipedia::run($client, $url);
var_dump($title_and_company);