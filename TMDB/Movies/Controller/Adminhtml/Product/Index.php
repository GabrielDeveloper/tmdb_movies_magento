<?php
namespace TMDB\Movies\Controller\Adminhtml\Product;

use \Magento\Backend\App\Action;
use \Magento\Framework\Controller\ResultFactory;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\App\ObjectManager;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

    public function execute() 
    {

        $product = $this->getRequest()->getParam('Product');

        if ($product && $this->createProduct($product)) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('catalog/product/index');
        }
        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('TMDB_Movies::movies_product');
        $page->getLayout()->initMessages();
        $page->getLayout()->getBlock('tmdb_movies_block_product')->setMovie($this->getRequest()->getParam('movie_id'));
        return $page;
    }

    protected function createProduct($movie)
    {
        $objectManager = ObjectManager::getInstance();
        $product = $objectManager->create('\Magento\Catalog\Model\Product');

        $sku = 'tmdb-'.$movie['movie_id'];

        $product->setSku($sku);
        $product->setName($movie['title']);
        $product->setAttributeSetId(4);
        $product->setStatus(1);
        $product->setWeight(10);
        $product->setVisibility(4);
        $product->setTaxClassId(0);
        $product->setTypeId('simple');
        $product->setPrice($movie['price']);
        $product->setDescription($movie['description']);
        $product->setStockData([
            'use_config_manage_stock' => 0,
            'manage_stock' => 1,
            'is_in_stock' => 1,
            'qty' => 100
        ]);
        if (!$product->save()) {
            return false;
        }
        return true;
    }
}
