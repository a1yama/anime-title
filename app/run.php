<?php

require __DIR__ . "/../bootstrap.php";

$title_and_company = \Acme\App\Wikipedia::run();
\Model\AnimeTitle::insert($title_and_company);
