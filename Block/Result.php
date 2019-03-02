<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 01-03-2019
 * Time: 11:30
 */

namespace MageDev\AutoSearch\Block;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\CatalogSearch\Helper\Data;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Search\Model\QueryFactory;

class Result extends Template
{
    /**
     * Catalog Product collection
     *
     * @var Collection
     */
    protected $productCollection;

    /**
     * Catalog search data
     *
     * @var Data
     */
    protected $catalogSearchData;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * @param Context $context
     * @param LayerResolver $layerResolver
     * @param Data $catalogSearchData
     * @param QueryFactory $queryFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        LayerResolver $layerResolver,
        Data $catalogSearchData,
        QueryFactory $queryFactory,
        array $data = []
    ) {
        $this->catalogLayer = $layerResolver->get();
        $this->catalogSearchData = $catalogSearchData;
        $this->queryFactory = $queryFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve query model object
     *
     * @return \Magento\Search\Model\Query
     */
    protected function _getQuery()
    {
        return $this->queryFactory->get();
    }

    /**
     * Retrieve search list toolbar block
     *
     * @return ListProduct
     */
    public function getListBlock()
    {
        return $this->getChildBlock('search_result_list');
    }

    /**
     * Set search available list orders
     *
     * @return $this
     */
    public function setListOrders()
    {
        $category = $this->catalogLayer->getCurrentCategory();
        /* @var $category \Magento\Catalog\Model\Category */
        $availableOrders = $category->getAvailableSortByOptions();
        unset($availableOrders['position']);
        $availableOrders['relevance'] = __('Relevance');

        $this->getListBlock()->setAvailableOrders(
            $availableOrders
        )->setDefaultDirection(
            'desc'
        )->setDefaultSortBy(
            'relevance'
        );

        return $this;
    }

    /**
     * Retrieve Search result list HTML output
     *
     * @return string
     */
    public function getProductListHtml()
    {
        return $this->getChildHtml('search_result_list');
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Collection
     */
    protected function _getProductCollection()
    {
        if (null === $this->productCollection) {
            $this->productCollection = $this->getListBlock()->getLoadedProductCollection();
        }

        return $this->productCollection;
    }

    public function getProductCollection(){
        return $this->_getProductCollection();
    }

    /**
     * Retrieve search result count
     *
     * @return string
     */
    public function getResultCount()
    {
        if (!$this->getData('result_count')) {
            $size = $this->_getProductCollection()->getSize();
            $this->_getQuery()->saveNumResults($size);
            $this->setResultCount($size);
        }
        return $this->getData('result_count');
    }
}