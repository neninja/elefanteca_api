<?php

require_once __DIR__.'/bootstrap/app.php';

use App\Repositories\Doctrine\EntityManagerFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$em = (new EntityManagerFactory())->get();

return ConsoleRunner::createHelperSet($em);
