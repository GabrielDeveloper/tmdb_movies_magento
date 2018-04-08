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

    public function getUrlBuilder($path, $params = [])
    {
        return $this->urlBuilder->getUrl($path, $params);
    }

    public function renderMovies()
    {
        $service = new TmdbService(
            new \Zend\Http\Request,
            new \Zend\Http\Client
        );
        $service->setEndpoint("discover/movie");

        if ($this->getGenre()) {
            $service->setGenre($this->getGenre());
        }

        return $service->getMovies($this->getPage());
    }

    public function sanitizeTitle($title)
    {
       if (strlen($title) <= 25) {
            return $title;
        }
        return substr($title,0, 25) . "...";
    }

    public function getGenresList()
    {
        $service = new TmdbService(
            new \Zend\Http\Request,
            new \Zend\Http\Client
        );
        $service->setEndpoint("genre/movie/list");
        return $service->getGenres();

    }

    public function productAdded($movieId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->get('Magento\Catalog\Model\Product');
        $sku = 'tmdb-'.$movieId;
        return $product->getIdBySku($sku);
    }

    public function handleImageURI($img)
    {
        if (empty($img)) {
            return "http://lorempixel.com/150/225/1/No%20Poster%20Avaliable/";
        }
        return "https://image.tmdb.org/t/p/w300" . $img;
    }

    public function handlePaginationLink($page)
    {
        $params['page'] = $page;
        if ($this->getGenre()) {
            $params['filter_genre'] = $this->getGenre();
        }
        return $this->getUrlBuilder("tmdb_movies/index/index", $params);
    }

}

