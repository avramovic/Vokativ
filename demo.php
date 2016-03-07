<?php

require 'vendor/autoload.php';

use Avram\Vokativ\Vokativ;
use Avram\Vokativ\Dictionary\VokativIniDictionary;

$nominativ = 'Filip';
$vokativ = new Vokativ(new VokativIniDictionary());

echo "Vocative case for the \"$nominativ\" is: " . $vokativ->make($nominativ) . " (source: " . $vokativ->source() . ")";