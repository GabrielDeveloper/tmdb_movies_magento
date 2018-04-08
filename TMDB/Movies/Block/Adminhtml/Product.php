<?php

namespace TMDB\Movies\Block\Adminhtml;

use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Block\Template;
use \Zend\Http\Request;
use \Zend\Http\Client;

use TMDB\Movies\Service\TmdbService;

class Product extends Template
{

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function renderMovie()
    {
        $service = new TmdbService(new Request, new Client);
        $service->setEndpoint("movie/". $this->getMovie());
        return $service->getResponse();
    }

    public function handleImageURI($img)
    {
        if (empty($img)) {
            return "http://lorempixel.com/150/225/1/No%20Poster%20Avaliable/";
        }
        return "https://image.tmdb.org/t/p/w300" . $img;
    }
}

