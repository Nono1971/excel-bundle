<?php
namespace OnurbTest\Bundle\ExcelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompatibilityControllerTest extends WebTestCase
{

    public function testStreamAction()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/compatibility/stream');
        $client->getResponse()->sendContent();
        $content = ob_get_contents();
        ob_clean();
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $this->assertStringStartsWith(
            'attachment;filename=',
            $client->getResponse()->headers->get('content-disposition')
        );
        $this->assertNotEmpty($content, 'Response should not be empty');
        $this->assertNotNull($content, 'Response should not be null');
    }


    public function testSaveAction()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/compatibility/store');
        $this->assertEquals(
            Response::HTTP_CREATED,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $content = $client->getResponse()->getContent();
        $this->assertStringEndsWith('.xls', $content);
        $this->assertFileExists($content, sprintf('file %s should exist', $content));
    }

    /**
     * @depends testSaveAction
     */
    public function testReaderAction()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/compatibility/reader');
        $client->getResponse()->sendContent();
        $content = ob_get_contents();
        ob_clean();
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $this->assertEquals('Hello world!', $client->getResponse()->getContent());
        $this->assertNotEmpty($content, 'Response should not be empty');
        $this->assertNotNull($content, 'Response should not be null');
    }

    /**
     * @depends testSaveAction
     */
    public function testReadAndSaveAction()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/compatibility/read');
        $this->assertEquals(
            Response::HTTP_CREATED,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $content = $client->getResponse()->getContent();
        $this->assertStringEndsWith('.xls', $content);
        $this->assertFileExists($content, sprintf('file %s should exist', $content));

        $this->cleanFile($content);
    }

    public function testSaveWithDrawingAction()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/compatibility/drawing');
        $this->assertEquals(
            Response::HTTP_CREATED,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $content = $client->getResponse()->getContent();
        $this->assertStringEndsWith('.xls', $content);
        $this->assertFileExists($content, sprintf('file %s should exist', $content));

        $this->cleanFile($content);
    }

    /**
     * @param $file
     */
    private function cleanFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
