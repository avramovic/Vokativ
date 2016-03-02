<?php

namespace Avram\Vokativ\Dictionary;

/**
 * Class VokativJsonDictionary
 * @package Avram\Vokativ\Dictionary
 */
class VokativJsonDictionary implements VokativDictionaryInterface {

	/**
	 * @var null|string
	 */
	protected $file = null;

	/**
	 * VokativJsonDictionary constructor.
	 * @param null|string $file
	 */
	public function __construct($file = null) {
		if ($file === null) {
			$file = dirname(__DIR__) . '/Data/vokativ.json';
		}

		$this->file = $file;
	}

	/**
	 * @return array
	 */
	public function provide_exceptions() {
		return json_decode(file_get_contents($this->file));
	}

}

?>