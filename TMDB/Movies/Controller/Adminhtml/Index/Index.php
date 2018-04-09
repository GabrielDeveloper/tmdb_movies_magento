<?php
namespace TMDB\Movies\Controller\Adminhtml\Index;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

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
        $page = $this->resultPageFactory->create();

        $page->getLayout()->getBlock('tmdb_movies_block_main')->setPage($this->getRequest()->getParam('page'));
        $page->getLayout()->getBlock('tmdb_movies_block_main')->setGenre($this->getRequest()->getParam('filter_genre'));
        $page->getLayout()->getBlock('tmdb_movies_block_main')->setSearch($this->getRequest()->getParam('search_movie'));

        $page->setActiveMenu('TMDB_Movies::movies');
        $page->getLayout()->initMessages();
        return $page;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TMDB_Movies::movies');
    }
}
