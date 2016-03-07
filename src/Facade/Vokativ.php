<?php
namespace Avram\Vokativ\Facade;

use Avram\Vokativ\Vokativ as VokativLib;
use Illuminate\Support\Facades\Facade;

class Vokativ extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vokativ';
    }

    /**
     * @param $nominativ
     * @return string
     */
    public static function safe($nominativ)
    {
        $vokativ = static::make($nominativ);
        $source = static::source();
        return ($source == VokativLib::SOURCE_DICTIONARY) ? $vokativ : $nominativ;
    }
}