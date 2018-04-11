<?php

namespace TMDB\Movies\Model;

use \Magento\Catalog\Model\Product;
use \Magento\Framework\App\Filesystem\DirectoryList;
use \Magento\Framework\Filesystem\Io\File;

use TMDB\Movies\Api\TmdbMoviesRepositoryInterface;
use TMDB\Movies\Service\TmdbService;

class TmdbMoviesRepository implements TmdbMoviesRepositoryInterface
{

    public $errors = [];
    public $product;
    public $directoryList;
    public $file;

    public function __construct(Product $product, DirectoryList $directoryList, File $file)
    {
        $this->product = $product;
        $this->directoryList = $directoryList;
        $this->file = $file;
    }

    public function createProduct($movie)
    {
        $sku = TmdbService::SKU_PREFIX . $movie['movie_id'];
        $imageUrl = TmdbService::IMAGE_BASE_URL . $movie['image_url'];

        if (empty($movie['price'])) {
            $this->addErrors("Price not setted");
            return false;
        }

        $this->product->setSku($sku);
        $this->product->setName($movie['title']);
        $this->product->setPrice($movie['price']);
        $this->product->setDescription($movie['description']);

        $this->product->setWebsiteIds([1]);
        $this->product->setAttributeSetId(4);
        $this->product->setStatus(1);
        $this->product->setWeight(10);
        $this->product->setVisibility(4);
        $this->product->setTaxClassId(0);
        $this->product->setTypeId('simple');
        $this->product->setStockData([
            'use_config_manage_stock' => 0,
            'manage_stock' => 1,
            'is_in_stock' => 1,
            'qty' => 100
        ]);

        if (!$this->product->save()) {
            $this->addErrors("Product can't be saved");
            return false;
        }

        if (!empty($movie['image_url']) && !$this->addImageToProduct($this->product, $imageUrl, true, ['image', 'small_image', 'thumbnail'])) {
            $this->addErrors("Image not added to movie");
            return false;
        }

        return true;
    }

    public function addImageToProduct($product, $imageUrl, $visible = false, $imageType = [])
    {
        $tmpDir = $this->getMediaDirTmpDir();

        $this->file->checkAndCreateFolder($tmpDir);

        $newFileName = $tmpDir . baseName($imageUrl);

        $result = $this->file->read($imageUrl, $newFileName);
        if ($result) {
            $product->addImageToMediaGallery($newFileName, $imageType, true, $visible);
            $product->save();
        }
        return $result;
    }

    protected function getMediaDirTmpDir()
    {
        return $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
    }

    public function addErrors($error)
    {
        $this->errors[] = $error;
    }

    public function getIdBySku($sku)
    {
        return $this->product->getIdBySku($sku);
    }
}
