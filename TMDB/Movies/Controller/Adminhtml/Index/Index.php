<?php
namespace TMDB\Movies\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action {
    protected $resultPageFactory;

    public function __construct(\Magento\Backend\App\Action\Context $context,
                                \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                                \Magento\Framework\UrlInterface $urlBuilder,
                                \Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
        return parent::__construct($context);
    }

    public function execute() {
        $page = $this->resultPageFactory->create();
        $url = $this->urlBuilder->getUrl('tmdb_movies/product/index', $paramsHere = array());

        $pageParam = $this->getRequest()->getParam('page');
        if ($pageParam) {
            $page->getLayout()->getBlock('tmdb_movies_block_main')->setPage($pageParam);
        }

        $page->setActiveMenu('TMDB_Movies::movies');
        $page->getLayout()->initMessages();
        $page->getLayout()->getBlock('tmdb_movies_block_main')->setCustomUrl($url);
        return $page;
    }

    protected function _isAllowed() {
        return $this->_authorization->isAllowed('TMDB_Movies::movies');
    }
}
