<?php
namespace OnurbTest\Bundle\ExcelBundle\Controller;

use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WrongParameterControllerTest extends WebTestCase
{
    /**
     * @expectedException Exception
     */
    public function testWrongTypeWriterThrowsException()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/wrong/type/writer');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongTypeReaderThrowsException()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/wrong/type/reader');
    }
}
