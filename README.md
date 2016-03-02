# Vokativ

PHP class used to convert first names from Serbian language into their vocative case.

## Introduction

Serbian language uses vocative case in order to address a person, so when someone register to a site with name Filip it is okay to greet (address) them in English with "Hello Filip", but in Serbian it would be "Zdravo Filipe", and "Zdravo Filip" is not correct and really sounds strange. But vocative is not as simple as appending "e" to a name. It has a set of rules and this PHP class tries to apply those rules to all Serbian names. If it can't, it'll use the built-in dictionary (nominative => vocative) to find a correct form.

Note that it is not perfect and please notify me if you find any erroneously converted names. PRs are also welcome.

## Intallation

Install using composer with:

`composer require avram/vokativ`

Or manually by cloning this repository:

`git clone https://github.com/avramovic/Vokativ.git`

## Usage

    <?php
    require 'vendor/autoload.php';

    use Avram\Vokativ\Vokativ;
    use Avram\Vokativ\Dictionary\VokativIniDictionary;

    $nominativ = 'Filip';
    $vokativ = new Vokativ(new VokativIniDictionary());

    header('Content-Type: text/html; charset=utf-8');

    echo "Vocative case for the \"$nominativ\" is: " . $vokativ->make($nominativ) . " (source: " . $vokativ->source() . ")";

You should get output like this:

    Vocative case for the "Filip" is: Filipe (source: dictionary)

Inspect the source code to see what methods are available in this class. But usually the `make()` method will be all you need.