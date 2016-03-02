<?php

namespace Avram\Vokativ;

use Avram\Vokativ\Dictionary\VokativDictionaryInterface;
use Avram\Vokativ\Exception\VokativException;

/**
 * Class Vokativ
 * @package Avram\Vokativ
 */
class Vokativ
{

    const SOURCE_ALGORITHM = 'algorithm';
    const SOURCE_DICTIONARY = 'dictionary';

    /** @var VokativDictionaryInterface|null */
    protected $adapter = null;

    /** @var null|bool */
    public $cyrillic = null;

    /** @var string|null */
    protected $source = null;

    /** @var array */
    protected $azbuka = array(
        "а" => "a",
        "б" => "b",
        "в" => "v",
        "г" => "g",
        "д" => "d",
        "ђ" => "đ",
        "е" => "e",
        "ж" => "ž",
        "з" => "z",
        "и" => "i",
        "ј" => "j",
        "к" => "k",
        "л" => "l",
        "љ" => "lj",
        "м" => "m",
        "н" => "n",
        "њ" => "nj",
        "о" => "o",
        "п" => "p",
        "р" => "r",
        "с" => "s",
        "т" => "t",
        "ћ" => "ć",
        "у" => "u",
        "ф" => "f",
        "х" => "h",
        "ц" => "c",
        "ч" => "č",
        "џ" => "dž",
        "ш" => "š",
        "А" => "A",
        "Б" => "B",
        "В" => "V",
        "Г" => "G",
        "Д" => "D",
        "Ђ" => "Đ",
        "Е" => "E",
        "Ж" => "Ž",
        "З" => "Z",
        "И" => "I",
        "Ј" => "J",
        "К" => "K",
        "Л" => "L",
        "Љ" => "LJ",
        "М" => "M",
        "Н" => "N",
        "Њ" => "NJ",
        "О" => "O",
        "П" => "P",
        "Р" => "R",
        "С" => "S",
        "Т" => "T",
        "Ћ" => "Ć",
        "У" => "U",
        "Ф" => "F",
        "Х" => "H",
        "Ц" => "C",
        "Ч" => "Č",
        "Џ" => "DŽ",
        "Ш" => "Š",
    );

    /**
     * Vokativ constructor.
     * @param VokativDictionaryInterface $adapter
     */
    public function __construct(VokativDictionaryInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return array
     * @throws VokativException
     */
    protected function exceptions()
    {
        $exceptions = $this->adapter->provide_exceptions();

        if ($this->isAssociative($exceptions)) {
            return $exceptions;
        } else {
            throw new VokativException("Method provide_exceptions() on the " . get_class($this->adapter) . " did not return associative array!");
        }
    }

    /**
     * @param string $string
     * @return string
     */
    protected function mbUcfirst($string)
    {
        $string = mb_strtoupper(mb_substr($string, 0, 1, 'utf-8'), 'utf-8') . mb_substr($string, 1, mb_strlen($string, 'utf-8'), 'utf-8');
        return $string;
    }

    /**
     * @param string $str
     * @return string
     */
    protected function correctCase($str)
    {
        return $this->mbUcfirst(mb_strtolower($str, 'utf-8'));
    }

    /**
     * @param string $str
     * @param int $start
     * @return string
     */
    protected function mySubString($str, $start)
    {
        return mb_substr($str, $start, mb_strlen($str, 'utf-8'), 'utf-8');
    }

    /**
     * @param string $text
     * @return bool
     */
    public function isCyrillic($text)
    {
        foreach ($this->azbuka as $cir => $lat) {
            if (strpos($text, $lat) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $input
     * @return string
     */
    protected function removeExtras($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = strip_tags($input);

        if (strpos($input, ' ') !== false) {
            $tmp = explode(' ', $input);
            $input = $tmp[0];
        }

        $input = $this->transliterate($input);

        $regex = '/[^a-zA-ZŠĐČĆŽšđčćž]/';

        $input = preg_replace($regex, '', $input);

        return mb_strtoupper($input, 'utf-8');
    }

    /**
     * @param string $nominativ
     * @return string
     * @throws VokativException
     */
    public function make($nominativ)
    {
        $vokativ = '';

        if ($this->cyrillic === null) {
            $this->cyrillic = $this->isCyrillic($nominativ);
        }

        if ($this->cyrillic) {
            $nominativ = $this->transliterate($nominativ);
        }

        $nominativ = $this->removeExtras($nominativ); //sređujemo string

        $exceptions = $this->exceptions();

        if (isset($exceptions[$nominativ])) {
            //izvor je rečnik
            $this->source = self::SOURCE_DICTIONARY;
            //ako postoji izuzetak odmah vraćamo njegov vokativ u ćirilici ili latinici
            return ($this->cyrillic) ? $this->correctCase($this->transliterate($exceptions[$nominativ], false)) : $this->correctCase($exceptions[$nominativ]);
        }

        if (substr($nominativ, -3) == 'TAR' || substr($nominativ, -3) == 'DAR') //PETAR, ALEKSANDAR
            $vokativ = substr_replace($nominativ, 'RE', -2);

        elseif (substr($nominativ, -3) == 'ICA' && strlen($nominativ) > 4)    //MILICA , LJUBICA ALI NE  I MICA i CICA
            $vokativ = substr_replace($nominativ, 'CE', -2);

        elseif (substr($nominativ, -2) == 'CA')    //MACA, CECA...
            $vokativ = substr_replace($nominativ, 'CO', -2);

        elseif (substr($nominativ, -2) == 'SA')    //PERSA, BOSA
            $vokativ = substr_replace($nominativ, 'SO', -2);

        elseif (substr($nominativ, -4) == 'OLAC')    //KOLAC
            $vokativ = substr_replace($nominativ, 'OČE', -4);

        elseif (substr($nominativ, -4) == 'ALAC')    //ZNALAC
            $vokativ = substr_replace($nominativ, 'ALČE', -4);

        elseif (substr($nominativ, -4) == 'ILAC')    //MISLILAC
            $vokativ = substr_replace($nominativ, 'IOČE', -4);

        elseif (substr($nominativ, -4) == 'ELAC')    //ŽETELAC
            $vokativ = substr_replace($nominativ, 'EOČE', -4);

        elseif (substr($nominativ, -3) == 'SAC')    //PISAC
            $vokativ = substr_replace($nominativ, 'ŠČE', -3);

        elseif (substr($nominativ, -3) == 'RAC')    //MUDRAC
            $vokativ = substr_replace($nominativ, 'AČE', -2);

        elseif (substr($nominativ, -3) == 'VAC')    //VALJEVAC
            $vokativ = substr_replace($nominativ, 'VČE', -3);

        elseif (substr($nominativ, -3) == 'ANJ')    //SUŽANJ
            $vokativ = substr_replace($nominativ, 'NJU', -3);

        elseif (substr($nominativ, -2) == 'GA')    //KAVGA
            $vokativ = substr_replace($nominativ, 'GO', -2);

        elseif ((substr($nominativ, -2) == 'KA') && (strlen($nominativ) > 4))  //DARINKA, MILKA, BORKA, ALI NE I RAKA, MIKA
            $vokativ = $nominativ;

        elseif (substr($nominativ, -2) == 'JA' && substr($nominativ, -3) != 'IJA' && substr($nominativ, -3) != 'DJA' && substr($nominativ, -3) != 'NJA') //MAJA ALI NE I MARIJA, SANJA, NITI PEDJA (NE PEĐA)
            $vokativ = substr_replace($nominativ, 'JO', -2);

        elseif (substr($nominativ, -2) == 'VA' || substr($nominativ, -2) == 'DA' || substr($nominativ, -2) == 'BA' || substr($nominativ, -2) == 'KA') //SAVA, DADA, SLOBA, RAKA
            $vokativ = substr_replace($nominativ, 'O', -1);

        elseif (substr($nominativ, -3) == 'ARA' || substr($nominativ, -3) == 'ERA' || substr($nominativ, -3) == 'ORA') //PERA, DARA, BORA
            $vokativ = substr_replace($nominativ, 'RO', -2);

        elseif (substr($nominativ, -1) == 'K')    //CUTUK
            $vokativ = substr_replace($nominativ, 'Č', -1) . 'E';

        elseif (substr($nominativ, -1) == 'G')        //PREDRAG
            $vokativ = substr_replace($nominativ, 'Ž', -1) . 'E';

        //ć, đ, č, dž, š, ž, lj, nj, j
        elseif ($this->mySubString($nominativ, -1) == 'Ć' || $this->mySubString($nominativ, -1) == 'Đ' || $this->mySubString($nominativ, -1) == 'Č' || $this->mySubString($nominativ, -2) == 'DŽ' || $this->mySubString($nominativ, -1) == 'Š' || $this->mySubString($nominativ, -1) == 'Ž' || $this->mySubString($nominativ, -2) == 'LJ' || $this->mySubString($nominativ, -2) == 'NJ' || $this->mySubString($nominativ, -1) == 'J')
            $vokativ = $nominativ . 'U';

        elseif (substr($nominativ, -1) == 'A' || substr($nominativ, -1) == 'O' || substr($nominativ, -1) == 'E' || substr($nominativ, -1) == 'I') //VANJA, MARKO, MILE, MIKI
            $vokativ = $nominativ;

        else
            $vokativ = $nominativ . 'E';

        //ako koristimo ćirilicu vraćamo napravljeni vokativ iz latinice u ćirilicu
        if ($this->cyrillic) {
            $vokativ = $this->transliterate($vokativ, false);
        }

        //izvor je algoritam
        $this->source = self::SOURCE_ALGORITHM;

        //vraćamo sređen vokativ (mala slova, prvo veliko)
        return $this->correctCase($vokativ);
    }

    /**
     * @return null|string
     */
    public function source()
    {
        return $this->source;
    }

    /**
     * @param string $text
     * @param bool|true $cir_u_lat
     * @return string
     */
    protected function transliterate($text, $cir_u_lat = true)
    {
        $azbuka = $this->azbuka;

        if (!$cir_u_lat)
            $azbuka = array_flip($azbuka);

        return strtr($text, $azbuka);
    }

    /**
     * @param array $array
     * @return bool
     */
    protected function isAssociative(array $array)
    {
        if (!is_array($array))
            return false;

        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}

