<?php

namespace TMDB\Movies\Block\Adminhtml;

use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Block\Template;
use \Zend\Http\Request;
use \Zend\Http\Client;

use TMDB\Movies\Api\TmdbServiceInterface;
use TMDB\Movies\Service\TmdbService;

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
        return $this->tmdbService->getImage($img);
    }
}

