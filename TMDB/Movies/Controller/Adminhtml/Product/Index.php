<?php
namespace TMDB\Movies\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action {
    protected $resultPageFactory;

    public function __construct(\Magento\Backend\App\Action\Context $context,
                                \Magento\Framework\UrlInterface $urlBuilder,
                                \Magento\Framework\View\Result\PageFactory $resultPageFactory) {
        $this->resultPageFactory = $resultPageFactory;
        $this->urlBuilder = $urlBuilder;
        return parent::__construct($context);
    }

    public function execute() {

        $movieId = $this->getRequest()->getParam('movie_id');
        $product = $this->getRequest()->getParam('Product');

        $movie = $this->getMovieDetails($movieId);
        if ($product && $this->createProduct($product)) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('catalog/product/index');
        }
        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('TMDB_Movies::movies_product');
        $page->getLayout()->initMessages();
        $page->getLayout()->getBlock('tmdb_movies_block_product')->setMovie($movie);
        return $page;
    }

    protected function createProduct($movie)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // instance of object manager
        $product = $objectManager->create('\Magento\Catalog\Model\Product');

        $sku = 'tmdb-'.$movie['movie_id'];

        $product->setSku($sku); // Set your sku here
        $product->setName($movie['title']); // Name of Product
        $product->setAttributeSetId(4); // Attribute set id
        $product->setStatus(1); // Status on product enabled/ disabled 1/0
        $product->setWeight(10); // weight of product
        $product->setVisibility(4); // visibilty of product (catalog / search / catalog, search / Not visible individually)
        $product->setTaxClassId(0); // Tax class id
        $product->setTypeId('simple'); // type of product (simple/virtual/downloadable/configurable)
        $product->setPrice($movie['price']); // price of product
        $product->setDescription($movie['description']);
        $product->setStockData(
                        array(
                            'use_config_manage_stock' => 0,
                            'manage_stock' => 1,
                            'is_in_stock' => 1,
                            'qty' => 100
                        )
                    );
        if (!$product->save()) {
            return false;
        }
        return true;
    }

    protected function getMovieDetails($movieId)
    {
        return $movieId;
    }

}
