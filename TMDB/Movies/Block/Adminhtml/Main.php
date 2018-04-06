<?php

namespace TMDB\Movies\Block\Adminhtml;

use TMDB\Movies\Service\TmdbService;

class Main extends \Magento\Backend\Block\Template {

    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Magento\Framework\UrlInterface $urlBuilder,
                                \Magento\Catalog\Api\ProductRepositoryInterface $productRepository){

        $this->productRepository = $productRepository;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context);
    }

    function _prepareLayout() {
    }

    public function getUrlBuilder($movieId)
    {
        $url = $this->urlBuilder->getUrl('tmdb_movies/product/index');
        return $url . "?movie_id=". $movieId;
    }

    public function renderMovies()
    {
        $service = new TmdbService(
            new \Zend\Http\Request,
            new \Zend\Http\Client
        );
        $service->setEndpoint("discover/movie");
        return $service->getMovies($this->getPage());
    }

    public function productAdded($movieId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->get('Magento\Catalog\Model\Product');
        $sku = 'tmdb-'.$movieId;
        return $product->getIdBySku($sku);
    }
}

