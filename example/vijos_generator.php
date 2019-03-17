<?php

require_once "vendor/autoload.php";

use \Radl\Radl;

$r = new Radl;

if (count($argv) == 1) {
    echo <<< EOF
USAGE: php example/vijos_generator.php [NUMBER] [RADL_FILE] [COMPILED] [OUTPUT_DIR]

PARAMETERS:
    NAME        DESCRIPTION                     EXAMPLE
    NUMBER      The number of judge points.     3
    RADL_FILE   The radl generator file.        ./test.radl
    COMPILED    Compiled program to generate    ./test.o
                output.
    OUTPUT_DIR  Problem output dir.             ./test.d

EOF;
    die();
}

$argv = array_slice($argv, 1);

$number = $argv[0];
$radl = $argv[1];
$compiled = $argv[2];
$output = $argv[3];

system("mkdir $output");
system("mkdir $output/input");
system("mkdir $output/output");

$conf = "$number\r\n";
$point = ceil(100 / $number);

for ($i = 1; $i <= $number; ++$i) {
    $conf .= "$i.in|$i.out|1|$point|32768\r\n";
    $in = $r->generateFromFile($radl);
    file_put_contents("$output/input/$i.in", $in);
    $out = $r->executeProgram($compiled, $in);
    file_put_contents("$output/output/$i.out", $out);
}

file_put_contents("$output/config.ini", $conf);

echo "OK\r\n";