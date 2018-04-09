<?php

namespace TMDB\Movies\Test\Unit\Service;

use \Zend\Http\Request;
use \Zend\Http\Client;
use TMDB\Movies\Service\TmdbService;

class TmdbServiceTest extends \PHPUnit\Framework\TestCase
{

    public $service;

    public function setUp()
    {

        $request = $this->createPartialMock(Request::class, []);
        $client = $this->createPartialMock(Client::class, []);

        $this->service = new TmdbService($request, $client);

        parent::setUp();
    }

    public function testGetURIShoudReturnTheCorrectUrlWithEndpointSetted()
    {
        $this->service->setEndpoint("teste/movies");
        $expected = "https://api.themoviedb.org/3/teste/movies";
        $this->assertEquals($expected, $this->service->getURI());
    }

    public function testShouldReturnTheParametersAdded()
    {
        $this->service->addParams(['query'  => 'TestQuery']);
        $this->service->addParams(['filter' => 'TestFilter']);
        $this->service->addParams(['movie'  => 'TestMovie']);

        $params = $this->service->getParams();

        $this->assertArrayHasKey('query', $params);
        $this->assertArrayHasKey('filter', $params);
        $this->assertArrayHasKey('movie', $params);
        $this->assertArrayHasKey('api_key', $params);
        $this->assertCount(4, $params);

    }

    public function testShouldReturnImageUrlOfMovieInTmdbApi()
    {
        $expected = "https://image.tmdb.org/t/p/w300/image.png";
        $this->assertEquals($expected, $this->service->getImage('/image.png'));
    }

    public function testShouldReturnDefaultImageUrlBecauseDoesNotHaveImagePosterOnTmdbApi()
    {
        $expected = "http://lorempixel.com/150/225/1/No%20Poster%20Avaliable/";
        $this->assertEquals($expected, $this->service->getImage(""));
    }

    public function testShoudAddGenreOnParameters()
    {
        $this->service->setGenre(23);
        $this->assertArrayHasKey('with_genres', $this->service->getParams());
        $this->assertArrayHasKey('api_key', $this->service->getParams());
        $this->assertCount(2, $this->service->getParams());
    }

    public function testShouldReturnOptions()
    {
        $expected = [
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => [CURLOPT_FOLLOWLOCATION => true],
            'maxredirects' => 0,
            'timeout' => 30
        ];

        $this->assertEquals($expected, $this->service->getOptions());
    }

    public function testShouldReturnZendStdlibParametersWithParametersAdded()
    {
        $parameters = $this->service->getParameters()->toArray();
        $this->assertArrayHasKey('api_key', $parameters);
        $this->assertCount(1, $parameters);
        $this->assertInstanceOf('Zend\Stdlib\Parameters', $this->service->getParameters());
    }
}
