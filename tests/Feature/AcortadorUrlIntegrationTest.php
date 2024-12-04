<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AcortadorUrlIntegrationTest extends TestCase
{
    const URI = "/api/v1/short-urls";
    const TINY_URL = "https://tinyurl.com/api-create.php*";
    const VALID_TOKEN = "Bearer []{}()";


    public function testAcortadorUrlInvalidBecauseNoBearerTokenRequest()
    {
        $response = $this->postJson(self::URI, [
            'url' => 'https://example.com'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    public function testAcortadorUrlInvalidBecauseInvalidBearerToken()
    {
        $response = $this->postJson(self::URI, [
            'url' => 'https://example.com'
        ], [
            'Authorization' => 'Bearer [)'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    public function testAcortadorUrlInvalidBecauseRequestBodyIsEmpty()
    {
        $response = $this->postJson(self::URI, [], [
            'Authorization' => self::VALID_TOKEN
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([ 'error' => "Param 'url' is mandatory and must be a valid url" ]);
    }

    public function testAcortadorUrlInvalidBecauseParamUrlIsNull()
    {
        $response = $this->postJson(self::URI, [
            'url' => null
        ], [
            'Authorization' => self::VALID_TOKEN
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([ 'error' => "Param 'url' is mandatory and must be a valid url" ]);
    }

    public function testAcortadorUrlInvalidBecauseParamUrlIsEmptyString()
    {
        $response = $this->postJson(self::URI, [
            'url' => ''
        ], [
            'Authorization' => self::VALID_TOKEN
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([ 'error' => "Param 'url' is mandatory and must be a valid url" ]);
    }

    public function testAcortadorUrlInvalidBecauseParamUrlIsInvalidUrl()
    {
        $response = $this->postJson(self::URI, [
            'url' => 'hello world'
        ], [
            'Authorization' => self::VALID_TOKEN
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([ 'error' => "Param 'url' is mandatory and must be a valid url" ]);
    }

    public function testAcortadorUrlInvalidBecauseTinyUrlReturnError()
    {
        Http::fake([
            self::TINY_URL => Http::throw()
        ]);

        $response = $this->postJson(self::URI, [
            'url' => 'https://example.com'
        ], [
            'Authorization' => self::VALID_TOKEN
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([ 'error' => "Something went wrong while trying to short the url" ]);
    }

    public function testAcortadorUrlValidUrl()
    {
        $urlResponse = 'https://tinyurl.com/y12345';

        Http::fake([
            self::TINY_URL => Http::response($urlResponse)
        ]);

        $response = $this->postJson(self::URI, [
            'url' => 'https://example.com'
        ], [
            'Authorization' => self::VALID_TOKEN
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['url' => $urlResponse]);
    }
}
