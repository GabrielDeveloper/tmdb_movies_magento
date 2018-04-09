<?php

namespace TMDB\Movies\Block\Adminhtml;

use \Magento\Backend\Block\Template;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\App\ObjectManager;
use \Zend\Http\Request;
use \Zend\Http\Client;

use TMDB\Movies\Api\TmdbServiceInterface;

class Main extends Template
{

    public function __construct(Context $context, UrlInterface $urlBuilder, TmdbServiceInterface $tmdbService)
    {
        $this->urlBuilder = $urlBuilder;
        $this->tmdbService = $tmdbService;
        parent::__construct($context);
    }

    public function getUrlBuilder($path, $params = [])
    {
        return $this->urlBuilder->getUrl($path, $params);
    }

    public function renderMovies()
    {
        if ($this->getSearch()) {
            return $this->searchByMovie();
        }
        return $this->searchDefault();
    }

    public function searchDefault()
    {
        $this->tmdbService->setEndpoint("discover/movie");
        $this->tmdbService->addParams([
            "sort_by"       =>  "popularity.desc",
            "include_adult" =>  "false",
            "include_video" =>  "false",
            "page"          =>  $this->getPage(),
        ]);

        if ($this->getGenre()) {
            $this->tmdbService->setGenre($this->getGenre());
        }

        return $this->tmdbService->getResponse();
    }

    public function searchByMovie()
    {
        $this->tmdbService->setEndpoint("search/movie");
        $this->tmdbService->addParams([
            "include_adult" =>  false,
            "query"         =>  $this->getSearch(),
            "page"          =>  $this->getPage(),
        ]);

        return $this->tmdbService->getResponse();
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
        $this->tmdbService->setEndpoint("genre/movie/list");
        return $this->tmdbService->getResponse();

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

        if ($this->getSearch()) {
            $params['search_movie'] = $this->getSearch();
        }

        return $this->getUrlBuilder("tmdb_movies/index/index", $params);
    }

}

