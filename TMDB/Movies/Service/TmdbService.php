<?php

namespace TMDB\Movies\Service;

use TMDB\Movies\Api\TmdbServiceInterface;
use \Zend\Http\Request;
use \Zend\Http\Client;

class TmdbService implements TmdbServiceInterface
{

    const API_BASE_URL = "https://api.themoviedb.org/3/";
    const API_KEY = "2075326add093f4866ab99feb33d548e";
    const IMAGE_BASE_URL = "https://image.tmdb.org/t/p/w300";
    const SKU_PREFIX = "tmdb-";

    protected $endpoint = "";
    protected $parameters = [ "api_key" => self::API_KEY ];

    public function __construct(Request $request, Client $client)
    {
        $this->request = $request;
        $this->client = $client;
    }

    public function getURI()
    {
        return self::API_BASE_URL . $this->endpoint;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function setHeaders()
    {
        $httpHeaders = new \Zend\Http\Headers();
        $httpHeaders->addHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);
        $this->request->setHeaders($httpHeaders);
    }

    public function getParameters()
    {
        return new \Zend\Stdlib\Parameters($this->parameters);
    }

    public function getOptions()
    {
        return [
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => [CURLOPT_FOLLOWLOCATION => true],
            'maxredirects' => 0,
            'timeout' => 30
        ];
    }

    public function addParams($params = [])
    {
        $this->parameters = array_merge($this->parameters, $params);
        return $this;
    }

    public function getParams()
    {
        return $this->parameters;
    }

    public function getResponse()
    {
        $this->setHeaders();

        $this->request->setUri($this->getURI());
        $this->request->setMethod(\Zend\Http\Request::METHOD_GET);
        $this->request->setQuery($this->getParameters());

        $this->client->setOptions($this->getOptions());

        $response = $this->client->send($this->request);
        return json_decode($response->getBody());
    }

    public function setGenre($genre_id)
    {
        $this->addParams(['with_genres' => $genre_id]);
        return $this;
    }

    public function getImage($imagePath = "")
    {
        if (empty($imagePath)) {
            return "http://lorempixel.com/150/225/1/No%20Poster%20Avaliable/";
        }
        return TmdbService::IMAGE_BASE_URL . $imagePath;
    }
}
