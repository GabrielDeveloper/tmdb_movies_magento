<?php

namespace TMDB\Movies\Block\Adminhtml;

use \Magento\Backend\Block\Template;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\App\ObjectManager;
use \Zend\Http\Request;
use \Zend\Http\Client;

use TMDB\Movies\Api\TmdbServiceInterface;
use TMDB\Movies\Api\TmdbMoviesRepositoryInterface;
use TMDB\Movies\Service\TmdbService;

class Main extends Template
{

    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        TmdbServiceInterface $tmdbService,
        TmdbMoviesRepositoryInterface $tmdbMoviesRepository
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->tmdbService = $tmdbService;
        $this->tmdbMoviesRepository = $tmdbMoviesRepository;
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
        $sku = TmdbService::SKU_PREFIX . $movieId;
        return $this->tmdbMoviesRepository->getIdBySku($sku);
    }

    public function handleImageURI($img)
    {
        return $this->tmdbService->getImage($img);
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

