<?php

namespace Avram\Vokativ\VokativBundle\Twig;

use Avram\Vokativ\Vokativ;

class VokativExtension extends \Twig_Extension
	{
	/**
	 * @var Vokativ
	 */
	protected $generator;
	
	public function __construct(Vokativ $generator)
		{
		$this->generator = $generator;
		}

	public function getName()
		{
		return 'vokativ_extension';
		}

	public function getFilters()
		{
		return array(
			new \Twig_SimpleFilter('vokativ', array($this, 'generateVokativ')),
			);
		}

	public function generateVokativ($nominativ)
		{
		return $this->generator->make($nominativ);
		}
	}