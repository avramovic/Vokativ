<?php

namespace Avram\Vokativ\VokativBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
	{
	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder()
		{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('avram_vokativ');
		// Here you should define the parameters that are allowed to
		// configure your bundle. See the documentation linked above for
		// more information on that topic.
		return $treeBuilder;
		}
	}