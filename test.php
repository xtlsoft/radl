<?php

require_once "vendor/autoload.php";

use \Radl\Radl;

$r = new Radl;

$in = $r->generateFromFile("test2.radl");

echo $in;

echo PHP_EOL;

echo $r->executeProgram(__DIR__ . "/test.o", $in);