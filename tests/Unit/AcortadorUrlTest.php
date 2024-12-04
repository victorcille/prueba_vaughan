<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\AcortadorUrlsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AcortadorUrlTest extends TestCase
{
    const URI = "/api/v1/short-urls";
    const TINY_URL = "https://tinyurl.com/api-create.php*";

    protected AcortadorUrlsController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AcortadorUrlsController();
    }

    public function testAcortadorUrlInvalidBecauseRequestBodyIsEmpty()
    {
        $request = Request::create(self::URI, 'POST', []);

        $response = $this->controller->acortadorUrl($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('{"error":"Param \'url\' is mandatory and must be a valid url"}', $response->getContent());
    }

    public function testAcortadorUrlInvalidBecauseParamUrlIsNull()
    {
        $request = Request::create(self::URI, 'POST', [
            'url' => null
        ]);

        $response = $this->controller->acortadorUrl($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('{"error":"Param \'url\' is mandatory and must be a valid url"}', $response->getContent());
    }

    public function testAcortadorUrlInvalidBecauseParamUrlIsEmptyString()
    {
        $request = Request::create(self::URI, 'POST', [
            'url' => ''
        ]);

        $response = $this->controller->acortadorUrl($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('{"error":"Param \'url\' is mandatory and must be a valid url"}', $response->getContent());
    }

    public function testAcortadorUrlInvalidBecauseParamUrlIsInvalidUrl()
    {
        $request = Request::create(self::URI, 'POST', [
            'url' => 'invalid-url'
        ]);

        $response = $this->controller->acortadorUrl($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('{"error":"Param \'url\' is mandatory and must be a valid url"}', $response->getContent());
    }

    public function testAcortadorUrlInvalidBecauseTinyUrlReturnError()
    {
        // Mock de la respuesta de TinyURL
        Http::fake([
            self::TINY_URL => Http::throw()
        ]);

        $request = Request::create(self::URI, 'POST', [
            'url' => 'https://example.com'
        ]);

        $response = $this->controller->acortadorUrl($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('{"error":"Something went wrong while trying to short the url"}', $response->getContent());
    }

    public function testAcortadorUrlValidUrl()
    {
        // Mock de la respuesta de TinyURL
        Http::fake([
            self::TINY_URL => Http::response('https://tinyurl.com/y12345'),
        ]);

        $request = Request::create(self::URI, 'POST', [
            'url' => 'https://example.com'
        ]);

        $response = $this->controller->acortadorUrl($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('{"url":"https:\/\/tinyurl.com\/y12345"}', $response->getContent());
    }
}
