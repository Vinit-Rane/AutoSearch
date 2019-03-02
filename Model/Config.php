<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 01-03-2019
 * Time: 12:51
 */

namespace MageDev\AutoSearch\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config implements ConfigInterface
{

    const IMAGE_TYPE = 'category_page_grid';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ){
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }

    /**
     * To get is Module Enable
     * @return \Magento\Eav\Model\Attribute\Data\Boolean|mixed
     */
    public function isEnable()
    {
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getLimit()
    {
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_RESULT_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }


    public function getSearchDelay(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_DELAY,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function canShowSku(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_SHOW_SKU,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function canShowPrice(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_SHOW_PRICE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function canShowImage(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_SHOW_IMAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function canShowShortDescription(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_SHOW_SHORT_DESCRIPTION,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getShortMaxChar(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_GENERAL_SHORT_MAX_CHAR,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getImageType(){
        return self::IMAGE_TYPE;
    }

    public function isSearchTermsEnable(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_SEARCH_TERMS_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function canSearchTermAjax(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_SEARCH_TERMS_AJAX,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getSearchTermLimit(){
        return $this->scopeConfig->getValue(
            ConfigInterface::XML_PATH_AUTO_SEARCH_SEARCH_TERMS_RESULT_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getConfigValue($path, $store = null){
        $store = $this->_storeManager->getStore($store);
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}