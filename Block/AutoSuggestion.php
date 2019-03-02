<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 28-02-2019
 * Time: 04:53
 */

namespace MageDev\AutoSearch\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Search\Helper\Data as SearchHelper;

use MageDev\AutoSearch\Helper\Data as ModuleHelper;
use MageDev\AutoSearch\Model\Config as ModuleConfig;

class AutoSuggestion extends AbstractProduct implements BlockInterface
{
    protected $_template = 'MageDev_AutoSearch::search_box.phtml';

    protected $_searchHelper;
    protected $_moduleHelper;
    protected $_moduleConfig;

    public function __construct(
        Context $context,
        SearchHelper $searchHelper,
        ModuleHelper $moduleHelper,
        ModuleConfig $moduleConfig,
        array $data=[]
    ){
        $this->_searchHelper = $searchHelper;
        $this->_moduleHelper = $moduleHelper;
        $this->_moduleConfig = $moduleConfig;
        parent::__construct($context, $data);
    }
    public function getCatalogSearchLink()
    {
        $isSecure = $this->_storeManager->getStore()->isCurrentlySecure();
        if($isSecure) {
            return $this->getUrl('catalogsearch/result/', array('_secure'=>true));
        } else {
            return $this->getUrl('catalogsearch/result/');
        }
    }

    public function getAjaxSearchUrl()
    {
        $isSecure = $this->_storeManager->getStore()->isCurrentlySecure();
        if($isSecure) {
            return $this->getUrl('autosearch/request/ajaxgetproduct', array('_secure'=>true));
        } else {
            return $this->getUrl('autosearch/request/ajaxgetproduct');
        }
    }

    public function getModuleConfig(){
        return $this->_moduleConfig;
    }

    public function getModuleHelper(){
        return $this->_moduleHelper;
    }

    public function getSearchHelper(){
        return $this->_searchHelper;
    }
}