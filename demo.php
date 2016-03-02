<?php

require 'vendor/autoload.php';

use Avram\Vokativ\Vokativ;
use Avram\Vokativ\Dictionary\VokativIniDictionary;


header('Content-Type: text/html; charset=utf-8');

$nominativ = 'Filip';
$vokativ = new Vokativ(new VokativIniDictionary());
echo "Vocative case for the \"$nominativ\" is: " . $vokativ->make($nominativ) . " (source: " . $vokativ->source() . ")";
