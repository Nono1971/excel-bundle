<?php
namespace OnurbTest\Bundle\ExcelBundle\Factory;

use Onurb\Bundle\ExcelBundle\Factory\CompatibilityHelper;
use PHPUnit\Framework\TestCase;

class CompatibilityHelperTest extends TestCase
{
    /**
     * @var CompatibilityHelper
     */
    private $compatibilityHelper;

    public function setup()
    {
        $this->compatibilityHelper = new CompatibilityHelper();
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\Factory\CompatibilityHelper
     */
    public function testConvertType()
    {
        $this->assertSame('Csv', $this->compatibilityHelper->convertType('CSV'));
        $this->assertSame('Xml', $this->compatibilityHelper->convertType('Excel2003XML'));
        $this->assertSame('Xlsx', $this->compatibilityHelper->convertType('Excel2007'));
        $this->assertSame('Xls', $this->compatibilityHelper->convertType('Excel5'));
        $this->assertSame('Html', $this->compatibilityHelper->convertType('HTML'));
        $this->assertSame('Ods', $this->compatibilityHelper->convertType('OOCalc'));
        $this->assertSame('Ods', $this->compatibilityHelper->convertType('OpenDocument'));
        $this->assertSame('Pdf', $this->compatibilityHelper->convertType('PDF'));
        $this->assertSame('Slk', $this->compatibilityHelper->convertType('SYLK'));
        $this->assertSame('UnListed', $this->compatibilityHelper->convertType('UnListed'));
    }
}
