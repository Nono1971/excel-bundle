<?php
namespace OnurbTest\Bundle\ExcelBundle\DependencyInjection;

use Onurb\Bundle\ExcelBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigutationTest extends TestCase
{

    /**
     * @covers Onurb\Bundle\ExcelBundle\DependencyInjection\Configuration
     */
    public function testIsInstanceOf()
    {
        $configuration = new Configuration();
        $this->assertInstanceOf("Symfony\\Component\\Config\\Definition\\ConfigurationInterface", $configuration);
    }

    /**
     * @covers Onurb\Bundle\ExcelBundle\DependencyInjection\Configuration
     */
    public function testGetConfigBuilderInstanceOf()
    {
        $configuration = new Configuration();
        $return = $configuration->getConfigTreeBuilder();
        $this->assertInstanceOf('Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder', $return);
    }
}
