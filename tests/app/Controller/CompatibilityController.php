<?php

namespace  Onurb\Bundle\ExcelBundle\Controller;

use Onurb\Bundle\ExcelBundle\Factory\CompatibilityFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class CompatibilityController extends Controller
{
    public function streamAction()
    {
        // create an empty object
        $phpExcelObject = $this->createXLSObject();
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
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
        // create an empty object
        $phpExcelObject = $this->createXLSObject();
        // create the writer
        /** @var IWriter $writer */
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $filename = $this->container->getParameter('xls_fixture_absolute_path');

        // create filename
        $writer->save($filename);


        return new Response($filename, 201);
    }

    /**
     * @return Response
     */
    public function readAndSaveAction()
    {
        $filename = $this->container->getParameter('xls_fixture_absolute_path');
        // create an object from a filename
        $phpExcelObject = $this->createXLSObject($filename);
        // create the writer
        /** @var iWriter $writer */
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
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
        $factory = $this->get('phpexcel');
        // create a reader
        /** @var IReader $reader */
        $reader = $factory->createReader('Excel5');
        // check that the file can be read
        $canread = $reader->canRead($filename);
        // check that an empty temporary file cannot be read
        $someFile = tempnam($this->getParameter('kernel.root_dir'), "tmp");
        $cannotread = $reader->canRead($someFile);
        unlink($someFile);

        // load the excel file
        $phpExcelObject = $reader->load($filename);
        // read some data
        $sheet = $phpExcelObject->getActiveSheet();
        $hello = $sheet->getCell('A1')->getValue();
        $world = $sheet->getCell('B2')->getValue();

        return new Response($canread && !$cannotread ? "$hello $world" : 'I should no be able to read this.');
    }


    /**
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function createSpreadSheetWithDrawingAction()
    {
        /** @var CompatibilityFactory $factory */
        $factory = $this->get('phpexcel');
        $phpExcelObject = $this->createXLSObject();

        $writer = $factory->createWriter($phpExcelObject, 'Xls');

        $drawing = $factory->createPHPExcelWorksheetDrawing();


        $drawing->setPath(__DIR__ . '/../fixture/doctrine.png')
            ->setName('Test')
            ->setDescription('Test drawing object')
            ->setWorksheet($phpExcelObject->getActiveSheet());

        $tmpFilename = tempnam(sys_get_temp_dir(), 'xls-');
        $filename = $tmpFilename . '.xls';

        $writer->save($filename);
        unlink($tmpFilename);

        return new Response($filename, 201);
    }

    /**
     * utility class
     * @param string $filename
     * @return mixed
     */
    private function createXLSObject($filename = null)
    {
        /** @var CompatibilityFactory $factory */
        $factory = $this->get('phpexcel');
        $phpExcelObject = $factory->createPHPExcelObject($filename);

        $htmlHelper = $factory->createHelperHTML();

        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C3', $htmlHelper->toRichTextObject('<b>In Bold!</b>'));
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }
}
