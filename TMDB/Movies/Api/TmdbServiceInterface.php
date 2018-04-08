<?php

namespace TMDB\Movies\Api;

interface TmdbServiceInterface
{

    public function getResponse();

    public function setEndpoint($endpoint);

    public function getURI();
}
