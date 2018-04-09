<?php

namespace TMDB\Movies\Api;

use \Magento\Catalog\Model\Product;

interface TmdbMoviesRepositoryInterface
{
    public function createProduct($product);

    public function addImageToProduct($product, $imageUrl, $visible = false, $imageType = []);
}
