<?php

namespace  Onurb\Bundle\ExcelBundle\Controller;

use Onurb\Bundle\ExcelBundle\Factory\ExcelFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class WrongParameterController extends Controller
{
    public function wrongTypeWriterAction()
    {
        // create an empty object
        $phpSpreadsheet = $this->createSpreadsheet();
        // create the writer
        $this->get('phpspreadsheet')->createWriter($phpSpreadsheet, 'Wrong type');
    }

    public function wrongTypeReaderAction()
    {
        // create the writer
        $this->get('phpspreadsheet')->createReader('Wrong type');
    }

    /**
     * utility class
     * @return mixed
     */
    private function createSpreadsheet()
    {
        /** @var ExcelFactory $factory */
        $factory = $this->get('phpspreadsheet');
        $spreadsheet = $factory->createSpreadsheet();

        $htmlHelper = $factory->createHelperHTML();

        $spreadsheet->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C3', $htmlHelper->toRichTextObject('<b>In Bold!</b>'));
        $spreadsheet->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }
}
