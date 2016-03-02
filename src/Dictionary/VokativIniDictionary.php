<?php

namespace Avram\Vokativ\Dictionary;

/**
 * Class VokativIniDictionary
 * @package Avram\Vokativ\Dictionary
 */
class VokativIniDictionary implements VokativDictionaryInterface {

	/**
	 * @var null|string
	 */
	protected $file = null;

	/**
	 * VokativIniDictionary constructor.
	 * @param null|string $file
	 */
	public function __construct($file = null)
	{
		if ($file === null) {
			$file = dirname(__DIR__) . '/Data/vokativ.ini';
		}

		$this->file = $file;
	}

	/**
	 * @return array
	 */
	public function provide_exceptions()
	{
		return parse_ini_file($this->file);
	}

}

?>