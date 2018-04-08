<?php

namespace TMDB\Movies\Service;

use TMDB\Movies\Api\TmdbServiceInterface;
use \Zend\Http\Request;
use \Zend\Http\Client;

class TmdbService implements TmdbServiceInterface
{

    const API_BASE_URL = "https://api.themoviedb.org/3/";
    const API_KEY = "32928d7a6bb4f1f737ee519bb1433d37";

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

}
