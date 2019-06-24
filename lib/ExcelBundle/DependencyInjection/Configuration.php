<?php

namespace Onurb\Bundle\ExcelBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Onurb\Bundle\ExcelBundle
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('onurb_excel');

        return $treeBuilder;
    }
}
