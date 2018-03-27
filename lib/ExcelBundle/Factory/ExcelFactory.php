<?php

namespace Onurb\Bundle\ExcelBundle\Factory;

use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

/**
 * Factory for Spreadsheet objects
 *
 * Factory  inspired by the Liuggio\ExcelBundle\Factory, migrated for phpSpreadSheet Usage
 * (PhpExcel used by liuggio is abandoned)
 *
 * @package Onurb\Bundle\ExcelBundle
 */
class ExcelFactory
{
    /**
     * @var CompatibilityHelper
     */
    private $compatibilytyHelper;

    public function __construct()
    {
        $this->compatibilytyHelper = new CompatibilityHelper();
    }

    /**
     * Creates an empty Spreadsheet Object if the filename is empty, otherwise loads the file into the object.
     *
     * @param string $filename
     *
     * @return Spreadsheet
     */
    public function createSpreadsheet($filename = null)
    {
        return (null === $filename) ? new Spreadsheet() : IOFactory::load($filename);
    }

    /**
     * Create a worksheet drawing
     * @return Drawing
     */
    public function createSpreadsheetWorksheetDrawing()
    {
        return new Drawing();
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
        $type =  $this->compatibilytyHelper->convertType($type);

        $class = '\\PhpOffice\\PhpSpreadsheet\\Reader\\' . $type;

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                'The reader [' . $type . '] does not exist or is not supported by PhpSpreadsheet.'
            );
        }

        return new $class();
    }

    /**
     * Create a writer given the PHPExcelObject and the type,
     *   the type could be one of PHPExcel_IOFactory::$_autoResolveClasses
     *
     * @param Spreadsheet $spreadSheetObject
     * @param string $type
     *
     * @return IWriter
     */
    public function createWriter(Spreadsheet $spreadSheetObject, $type = 'Xlsx')
    {
        $type =  $this->compatibilytyHelper->convertType($type);

        return IOFactory::createWriter($spreadSheetObject, $type);
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
        return new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $status,
            $headers
        );
    }

    /**
     * Create a PHPExcel Helper HTML Object
     *
     * @return Html
     */
    public function createHelperHTML()
    {
        return new Html();
    }
}
