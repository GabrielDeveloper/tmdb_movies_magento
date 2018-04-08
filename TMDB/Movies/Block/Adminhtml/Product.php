<?php

namespace TMDB\Movies\Block\Adminhtml;

use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Block\Template;
use \Zend\Http\Request;
use \Zend\Http\Client;

use TMDB\Movies\Api\TmdbServiceInterface;

class Product extends Template
{

    public function __construct(Context $context, TmdbServiceInterface $tmdbService)
    {
        $this->tmdbService = $tmdbService;
        parent::__construct($context);
    }

    public function renderMovie()
    {
        $this->tmdbService->setEndpoint("movie/". $this->getMovie());
        return $this->tmdbService->getResponse();
    }

    public function handleImageURI($img)
    {
        if (empty($img)) {
            return "http://lorempixel.com/150/225/1/No%20Poster%20Avaliable/";
        }
        return "https://image.tmdb.org/t/p/w300" . $img;
    }
}

