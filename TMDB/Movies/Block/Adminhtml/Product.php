<?php

namespace TMDB\Movies\Block\Adminhtml;

use TMDB\Movies\Service\TmdbService;

class Product extends \Magento\Backend\Block\Template {

    protected $myCustomer;

    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Magento\Customer\Model\Customer $myCustomer){

        $this->myCustomer = $myCustomer;
        parent::__construct($context);
    }

    function _prepareLayout() {
    }

    public function renderMovie()
    {
        $service = new TmdbService(
            new \Zend\Http\Request,
            new \Zend\Http\Client
        );
        $service->setEndpoint("movie/". $this->getMovie());
        return $service->getMovie($this->getMovie());
    }

    public function handleImageURI($img)
    {
        if (empty($img)) {
            return "http://lorempixel.com/150/225/1/No%20Poster%20Avaliable/";
        }
        return "https://image.tmdb.org/t/p/w300" . $img;
    }

    public function productAdded($sku)
    {
        return $sky;
    }
}

