<?php

namespace TMDB\Movies\Service;

class TmdbService
{

    const API_BASE_URL = "https://api.themoviedb.org/3/";
    const API_KEY = "32928d7a6bb4f1f737ee519bb1433d37";

    protected $endpoint = "";
    protected $parameters = ["api_key" => self::API_KEY];

    public function __construct(\Zend\Http\Request $request, \Zend\Http\Client $client)
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

    public function addParams($params = [])
    {
        $this->parameters = array_merge($this->parameters, $params);
        return $this;
    }
    
    public function getMovies($page = 1)
    {
        $this->setHeaders();

        $this->request->setUri($this->getURI());
        $this->request->setMethod(\Zend\Http\Request::METHOD_GET);

        $this->addParams([
            "sort_by"       =>  "popularity.desc",
            "include_adult" =>  "false",
            "include_video" =>  "false",
            "page"          =>  $page,
        ]);

        $this->request->setQuery($this->getParameters());

        $options = [
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => [CURLOPT_FOLLOWLOCATION => true],
            'maxredirects' => 0,
            'timeout' => 30
        ];

        $this->client->setOptions($options);

        $response = $this->client->send($this->request);
        return json_decode($response->getBody());
    }

    public function getMovie()
    {
        $this->setHeaders();

        $this->request->setUri($this->getURI());
        $this->request->setMethod(\Zend\Http\Request::METHOD_GET);

        $this->request->setQuery($this->getParameters());

        $options = [
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => [CURLOPT_FOLLOWLOCATION => true],
            'maxredirects' => 0,
            'timeout' => 30
        ];

        $this->client->setOptions($options);

        $response = $this->client->send($this->request);
        return json_decode($response->getBody());
    }

    public function setGenre($genre_id)
    {
        $this->addParams(['with_genres' => $genre_id]);
        return $this;
    }

}
