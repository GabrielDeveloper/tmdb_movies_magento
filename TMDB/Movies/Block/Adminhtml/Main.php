<?php

namespace TMDB\Movies\Block\Adminhtml;

use \Magento\Backend\Block\Template;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\App\ObjectManager;
use \Zend\Http\Request;
use \Zend\Http\Client;

use TMDB\Movies\Service\TmdbService;

class Main extends Template
{

    public function __construct(Context $context, UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context);
    }

    public function getUrlBuilder($path, $params = [])
    {
        return $this->urlBuilder->getUrl($path, $params);
    }

    public function renderMovies()
    {
        $service = new TmdbService(new Request, new Client);
        $service->setEndpoint("discover/movie");
        $service->addParams([
            "sort_by"       =>  "popularity.desc",
            "include_adult" =>  "false",
            "include_video" =>  "false",
            "page"          =>  $this->getPage(),
        ]);


        if ($this->getGenre()) {
            $service->setGenre($this->getGenre());
        }

        return $service->getResponse();
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
        $service = new TmdbService(new Request, new Client);
        $service->setEndpoint("genre/movie/list");
        return $service->getResponse();

    }

    public function productAdded($movieId)
    {
        $objectManager = ObjectManager::getInstance();
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

