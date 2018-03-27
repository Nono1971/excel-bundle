<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Onurb\Bundle\ExcelBundle\DependencyInjection\OnurbExcelExtension;
use Onurb\Bundle\ExcelBundle\Factory\CompatibilityFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Onurb\Bundle\ExcelBundle\Factory\ExcelFactory;

class OnurbYumlExtensionTest extends TestCase
{
    private $extension;
    private $container;

    protected function setUp()
    {
        $this->extension = new OnurbExcelExtension();
        $this->container = new ContainerBuilder();

        $this->container->registerExtension($this->extension);
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\DependencyInjection\OnurbExcelExtension
     */
    public function testIsInstanceOf()
    {
        $this->assertInstanceOf("Symfony\\Component\\HttpKernel\\DependencyInjection\\Extension", $this->extension);
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\DependencyInjection\OnurbExcelExtension
     */
    public function testDefaultConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertTrue($this->container->has('phpspreadsheet'));
        $this->assertInstanceOf(ExcelFactory::class, $this->container->get('phpspreadsheet'));

        $this->assertTrue($this->container->has('phpexcel'));
        $this->assertInstanceOf(CompatibilityFactory::class, $this->container->get('phpexcel'));
    }
}
