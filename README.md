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

### Native

    <?php
    require 'vendor/autoload.php';

    use Avram\Vokativ\Vokativ;
    use Avram\Vokativ\Dictionary\VokativIniDictionary;

    $nominativ = 'Filip';
    $vokativ = new Vokativ(new VokativIniDictionary());

    echo "Vocative case for \"$nominativ\" is: " . $vokativ->make($nominativ) . " (source: " . $vokativ->source() . ")";

You should get output like this:

    Vocative case for "Filip" is: Filipe (source: dictionary)

### Laravel

This package comes with Laravel service provider and facade to make it easier to use it from any place in your code. Simply open `config/app.php` file and add this to service provider list:

    Avram\Vokativ\Provider\VokativServiceProvider::class,

Then, right below that, add the facade:

    'Vokativ'   => Avram\Vokativ\Facade\Vokativ::class,

After that, you can use the following code anywhere in your project:

    $vokativ = Vokativ::make("Filip");

    //or in views
    Zdravo {{ Vokativ::make("Filip") }}!

*Note*: While this library will work fine with most names, it can fail with some. The Laravel facade provides another function, `Vokativ::safe("Filip")` which will return vocative case only if it's found in the dictionary, and if not it will return nominative case (original string). For such cases you might want to extend the default dictionary, and you can do so by publishing assets from this package:

    php artisan vendor:publish

This will copy the dictionary files into `storage/avram/vokativ` and since the Laravel integraton uses INI file by default, you'll want to edit `storage/avram/vokativ/vokativ.ini`. The facade will automatically switch to using published dictionary once it's published.