<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 01-03-2019
 * Time: 09:58
 */

namespace MageDev\AutoSearch\Controller\Request;

use Magento\Framework\App\Action\Action;
USE Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Search\Model\QueryFactory;
use Magento\Search\Model\ResourceModel\Query\CollectionFactory;
use Magento\Search\Helper\Data as SearchHelper;

use MageDev\AutoSearch\Helper\Data as ModuleHelper;
use MageDev\AutoSearch\Model\Config as ModuleConfig;

class AjaxGetProduct extends Action
{

    const DEFAULT_HANDLER = 'autosearch_result_product_list';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var QueryFactory
     */
    private $_queryFactory;

    /**
     * @var CollectionFactory
     */
    private $_queriesFactory;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var SearchHelper
     */
    protected $_searchHelper;
    /**
     * @var ModuleHelper
     */
    protected $_moduleHelper;

    /**
     * @var ModuleConfig
     */
    protected $_moduleConfig;

    /**
     * AjaxGetProduct constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param RawFactory $resultRawFactory
     * @param StoreManagerInterface $storeManager
     * @param QueryFactory $queryFactory
     * @param CollectionFactory $queriesFactory
     * @param Resolver $layerResolver
     * @param SearchHelper $searchHelper
     * @param ModuleHelper $moduleHelper
     * @param ModuleConfig $moduleConfig
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RawFactory $resultRawFactory,
        StoreManagerInterface $storeManager,
        QueryFactory $queryFactory,
        CollectionFactory $queriesFactory,
        Resolver $layerResolver,
        SearchHelper $searchHelper,
        ModuleHelper $moduleHelper,
        ModuleConfig $moduleConfig
    ){
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        $this->_queryFactory = $queryFactory;
        $this->_queriesFactory   = $queriesFactory;
        $this->layerResolver = $layerResolver;
        $this->_searchHelper = $searchHelper;
        $this->_moduleHelper = $moduleHelper;
        $this->_moduleConfig = $moduleConfig;
    }

    public function execute()
    {
        $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);

        /* @var $query \Magento\Search\Model\Query */
        $query = $this->_queryFactory->get();

        $storeId = $this->_storeManager->getStore()->getId();
        $query->setStoreId($storeId);

        $queryText = $query->getQueryText();
        if ($queryText != '') {
            $getAdditionalRequestParameters = $this->getRequest()->getParams();
            unset($getAdditionalRequestParameters[QueryFactory::QUERY_VAR_NAME]);

            $resultPage = $this->resultPageFactory->create();
            $resultPage->addHandle(self::DEFAULT_HANDLER);
            $resultBlock = $resultPage->getLayout()->getBlock('auto.search.result');
            $productCollection = $resultBlock->getProductCollection();
            $responseJson = [];
            $responseJson['total'] = $resultBlock->getResultCount();

            $limit = $this->_moduleConfig->getLimit();
            $showSku = $this->_moduleConfig->canShowSku();
            $showPrice = $this->_moduleConfig->canShowPrice();
            $showImage = $this->_moduleConfig->canShowImage();
            $imageType = $this->_moduleConfig->getImageType();
            $showShortDescription = $this->_moduleConfig->canShowShortDescription();
            $shortMaxChar = $this->_moduleConfig->getShortMaxChar();
            $products = [];
            $i = 1;
            foreach($productCollection as $_product){
                if($i > $limit) break;
                $productHtml = $this->_view->getLayout()->createBlock('MageDev\AutoSearch\Block\Item')
                    ->setData('product', $_product)
                    ->setData('show_sku', $showSku)
                    ->setData('show_price', $showPrice)
                    ->setData('show_image', $showImage)
                    ->setData('image_type', $imageType)
                    ->setData('show_short_description', $showShortDescription)
                    ->setData('max_char', $shortMaxChar)
                    ->setData('query_text', $queryText)
                    ->toHtml();
                $products[] = [
                    'product_id' => $_product->getId(),
                    'name'       => strip_tags(html_entity_decode($_product->getName(), ENT_QUOTES, 'UTF-8')),
                    'image'      => '1',
                    'link'       => $_product->getProductUrl(),
                    'price'      => $_product->getPrice(),
                    'html'       => $productHtml
                ];
                $i++;
            }
            if(empty($products)){
                $products[] = [
                    'product_id' => 0,
                    'name'       => '',
                    'image'      => '1',
                    'link'       => '',
                    'price'      => 0,
                    'html'       => __('No products exists')
                ];
            }
            $responseJson['products'] = $products;


            $enableAjaxTerm = $this->_moduleConfig->isSearchTermsEnable();
            $limitTerm = $this->_moduleConfig->getSearchTermLimit();
            if($enableAjaxTerm) {
                $_suggestCollection = $this->_queriesFactory->create();
                $_suggestCollection->setPopularQueryFilter($storeId);
                $_suggestCollection->getSelect()->where('main_table.query_text LIKE "%' . $this->getRequest()->getParam('q') . '%"')->order('main_table.num_results DESC');

                if($limitTerm) {
                    $_suggestCollection->setPageSize($limitTerm);
                }

                $data = [];
                $i = 1;
                foreach ($_suggestCollection as $item) {
                    if($i > $limitTerm) break;
                    $suggestData = $item->getData();
                    $suggestData['url'] = $this->_searchHelper->getResultUrl($item['query_text']);
                    $data[] = $suggestData;
                    $i++;
                }
                $responseJson['suggested'] = $data;
            } else {
                $responseJson['suggested'] = [];
            }

            $this->getResponse()->representJson(
                $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($responseJson)
            );
            return;
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_url->getBaseUrl());
            return $resultRedirect;
        }
    }
}