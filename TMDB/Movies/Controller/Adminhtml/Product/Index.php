<?php
namespace TMDB\Movies\Controller\Adminhtml\Product;

use \Magento\Backend\App\Action;
use \Magento\Framework\Controller\ResultFactory;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\App\ObjectManager;
use TMDB\Movies\Api\TmdbMoviesRepositoryInterface;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        TmdbMoviesRepositoryInterface $tmdbMoviesRepository
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->tmdbMoviesRepository = $tmdbMoviesRepository;
        return parent::__construct($context);
    }

    public function execute()
    {
        $product = $this->getRequest()->getParam('Product');

        if ($product && $this->tmdbMoviesRepository->createProduct($product)) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('catalog/product/index');
        }

        $page = $this->resultPageFactory->create();
        $page->setActiveMenu('TMDB_Movies::movies_product');
        $page->getLayout()->initMessages();
        $page->getLayout()->getBlock('tmdb_movies_block_product')->setMovie($this->getRequest()->getParam('movie_id'));
        $page->getLayout()->getBlock('tmdb_movies_block_product')->setErrors($this->tmdbMoviesRepository->errors);
        return $page;
    }
}
