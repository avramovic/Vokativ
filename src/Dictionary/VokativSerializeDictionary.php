<?php

namespace Avram\Vokativ\Dictionary;

/**
 * Class VokativSerializeDictionary
 * @package Avram\Vokativ\Dictionary
 */
class VokativSerializeDictionary implements VokativDictionaryInterface {

	/**
	 * @var null|string
	 */
	protected $file = null;

	/**
	 * VokativSerializeDictionary constructor.
	 * @param null|string $file
	 */
	public function __construct($file = null)
	{
		if ($file === null) {
			$file = dirname(__DIR__) . '/Data/vokativ.dat';
		}

		$this->file = $file;
	}

	/**
	 * @return array
	 */
	public function provide_exceptions() {
		return unserialize(file_get_contents($this->file));
	}

}

?>