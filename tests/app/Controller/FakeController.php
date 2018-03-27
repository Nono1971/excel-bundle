<?php

namespace  Onurb\Bundle\ExcelBundle\Controller;

use Onurb\Bundle\ExcelBundle\Factory\ExcelFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FakeController extends Controller
{
    /**
     * @return StreamedResponse
     */
    public function streamAction()
    {
        /** @var ExcelFactory $factory */
        $factory = $this->get('phpspreadsheet');

        // create an empty object
        $phpExcelObject = $this->createSpreadsheet();
        // create the writer
        $writer = $factory->createWriter($phpExcelObject, 'Xls');
        // create the response
        $response = $factory->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=stream-file.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

    /**
     * @return Response
     */
    public function storeAction()
    {
        /** @var ExcelFactory $factory */
        $factory = $this->get('phpspreadsheet');

        // create an empty object
        $spreadsheet = $this->createSpreadsheet();
        // create the writer
        $writer = $factory->createWriter($spreadsheet, 'Xls');
        $tmpFilename = tempnam(sys_get_temp_dir(), 'xls-');
        $filename = $tmpFilename . '.xls';
        // create filename
        $writer->save($filename);
        unlink($tmpFilename);

        return new Response($filename, 201);
    }

    /**
     * @return Response
     */
    public function readAndSaveAction()
    {
        /** @var ExcelFactory $factory */
        $factory = $this->get('phpspreadsheet');

        // create an object from a filename
        $spreadsheet = $this->createSpreadsheet();
        // create the writer
        $writer = $factory->createWriter($spreadsheet, 'Xls');
        $tmpFilename = tempnam(sys_get_temp_dir(), 'xls-');
        $filename = $tmpFilename . '.xls';

        // create filename
        $writer->save($filename);
        unlink($tmpFilename);

        return new Response($filename, 201);
    }

    /**
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function readerAction()
    {
        $filename = $this->container->getParameter('xls_fixture_absolute_path');

        // load the factory
        /** @var \Onurb\Bundle\ExcelBundle\Factory\ExcelFactory $factory */
        $factory = $this->get('phpspreadsheet');
        // create a reader
        $reader = $factory->createReader('Xls');
        // check that the file can be read
        $canread = $reader->canRead($filename);
        // check that an empty temporary file cannot be read
        $someFile = tempnam($this->getParameter('kernel.root_dir'), "tmp");
        $cannotread = $reader->canRead($someFile);
        unlink($someFile);

        // load the excel file
        $spreadsheet = $reader->load($filename);
        // read some data
        $sheet = $spreadsheet->getActiveSheet();
        $hello = $sheet->getCell('A1')->getValue();
        $world = $sheet->getCell('B2')->getValue();

        return new Response($canread && !$cannotread ? "$hello $world" : 'I should no be able to read this.');
    }

    public function createSpreadSheetWithDrawingAction()
    {
        /** @var ExcelFactory $factory */
        $factory = $this->get('phpspreadsheet');
        $spreadsheet = $this->createSpreadsheet();

        $writer = $factory->createWriter($spreadsheet, 'Xls');

        $drawing = $factory->createSpreadsheetWorksheetDrawing();

        $drawing->setPath(__DIR__ . '/../fixture/doctrine.png')
            ->setName('Test')
            ->setDescription('Test drawing object');

        $drawing->setWorksheet($spreadsheet->getActiveSheet());
        $tmpFilename = tempnam(sys_get_temp_dir(), 'xls-');
        $filename = $tmpFilename . '.xls';

        $writer->save($filename);

        //clean the tmp file

        unlink($tmpFilename);

        return new Response($filename, 201);
    }

    /**
     * utility class
     * @return Spreadsheet
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
