<?php
namespace TMDB\Movies\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action {
    protected $resultPageFactory;

    public function __construct(\Magento\Backend\App\Action\Context $context,
                                \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                                \Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        return parent::__construct($context);
    }

    public function execute() {
        $page = $this->resultPageFactory->create();

        $pageParam = $this->getRequest()->getParam('page');
        if ($pageParam) {
            $page->getLayout()->getBlock('tmdb_movies_block_main')->setPage($pageParam);
        }

        $page->setActiveMenu('TMDB_Movies::movies');
        $page->getLayout()->initMessages();
        return $page;
    }

    protected function _isAllowed() {
        return $this->_authorization->isAllowed('TMDB_Movies::movies');
    }
}
