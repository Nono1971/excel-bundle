<?php
namespace OnurbTest\Bundle\ExcelBundle\Factory;

use Onurb\Bundle\ExcelBundle\Factory\ExcelFactory as Factory;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PHPUnit\Framework\TestCase;

class ExcelFactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    public function setup()
    {
        $this->factory = new Factory();
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Spreadsheet::class, $this->factory->createSpreadsheet());
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory
     */
    public function testCreateReader()
    {
        $this->assertInstanceOf(IReader::class, $this->factory->createReader());
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory
     */
    public function testCreateWriter()
    {
        $this->assertInstanceOf(IWriter::class, $this->factory->createWriter($this->factory->createSpreadsheet()));
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory
     */
    public function testCreateStreamedResponse()
    {
        $writer = $this->createMock(IWriter::class);
        $writer->expects($this->once())
            ->method('save')
            ->with('php://output');
        $this->factory->createStreamedResponse($writer)->sendContent();
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory
     */
    public function testCreateHelperHtml()
    {
        $helperHtml = $this->factory->createHelperHTML();

        $this->assertInstanceOf(Html::class, $helperHtml);
    }

    /**
     * @covers \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory
     */
    public function testCreateSpreadsheetWorksheetDrawing()
    {
        $drawing = $this->factory->createSpreadsheetWorksheetDrawing();

        $this->assertInstanceOf(Drawing::class, $drawing);
    }


    /**
     * @expectedException \InvalidArgumentException
     * @covers \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory
     */
    public function testCreateReaderThrowsExceptionIfTypeIsWrong()
    {
        $this->factory->createReader('WrongType');
    }
}
