<?php
require __DIR__ . "/../bootstrap.php";

\Model\AnimeTitle::insert(\Acme\App\Wikipedia::run());
