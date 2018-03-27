<?php

namespace Onurb\Bundle\ExcelBundle\Factory;

use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

/**
 * Factory for Spreadsheet objects
 *
 * Factory  inspired by the liuggio\ExcelBundle\Factory, migrated for phpoffice/phpSpreadSheet Usage
 * (PhpExcel used by liuggio is abandoned)
 *
 * @package Onurb\Bundle\ExcelBundle
 */
class CompatibilityFactory
{
    /**
     * @var ExcelFactory
     */
    private $factory;

    public function __construct(ExcelFactory $factory = null)
    {
        $this->factory = $factory ? $factory : new ExcelFactory();
    }

    /**
     * Creates an empty Spreadsheet Object if the filename is empty, otherwise loads the file into the object.
     *
     * @param string $filename
     *
     * @return Spreadsheet
     */
    public function createPHPExcelObject($filename = null)
    {
        return $this->factory->createSpreadsheet($filename);
    }

    /**
     * @return Drawing
     */
    public function createPHPExcelWorksheetDrawing()
    {
        return $this->factory->createSpreadsheetWorksheetDrawing();
    }

    /**
     * Create a reader
     *
     * @param string $type
     *
     * @return IReader
     */
    public function createReader($type = 'Xlsx')
    {
        return $this->factory->createReader($type);
    }

    /**
     * Create a writer given the PHPExcelObject and the type,
     *
     * @param Spreadsheet $phpExcelObject
     * @param string $type
     * @return IWriter
     */
    public function createWriter(Spreadsheet $phpExcelObject, $type = 'Xlsx')
    {
        return $this->factory->createWriter($phpExcelObject, $type);
    }

    /**
     * Stream the file as Response.
     *
     * @param IWriter $writer
     * @param int                      $status
     * @param array                    $headers
     *
     * @return StreamedResponse
     */
    public function createStreamedResponse(IWriter $writer, $status = 200, $headers = array())
    {
        return $this->factory->createStreamedResponse($writer, $status, $headers);
    }

    /**
     * Create a PHPExcel Helper HTML Object
     *
     * @return Html
     */
    public function createHelperHTML()
    {
        return $this->factory->createHelperHTML();
    }
}
