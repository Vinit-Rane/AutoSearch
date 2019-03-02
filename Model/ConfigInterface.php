<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 01-03-2019
 * Time: 12:51
 */

namespace MageDev\AutoSearch\Model;

use Magento\Eav\Model\Attribute\Data\Boolean;

interface ConfigInterface
{
    const XML_PATH_AUTO_SEARCH_GENERAL_ENABLE = 'autosearch/general/show';

    const XML_PATH_AUTO_SEARCH_GENERAL_RESULT_LIMIT = 'autosearch/general/limit';
    const XML_PATH_AUTO_SEARCH_GENERAL_DELAY = 'autosearch/general/search_delay';
    const XML_PATH_AUTO_SEARCH_GENERAL_SHOW_SKU = 'autosearch/general/show_sku';
    const XML_PATH_AUTO_SEARCH_GENERAL_SHOW_PRICE = 'autosearch/general/show_price';
    const XML_PATH_AUTO_SEARCH_GENERAL_SHOW_IMAGE = 'autosearch/general/show_image';
    const XML_PATH_AUTO_SEARCH_GENERAL_SHOW_SHORT_DESCRIPTION = 'autosearch/general/show_short_description';
    const XML_PATH_AUTO_SEARCH_GENERAL_SHORT_MAX_CHAR = 'autosearch/general/short_max_char';


    const XML_PATH_AUTO_SEARCH_SEARCH_TERMS_ENABLE = 'autosearch/search_terms/enable_search_term';
    const XML_PATH_AUTO_SEARCH_SEARCH_TERMS_AJAX = 'autosearch/search_terms/enable_ajax_search_term';
    const XML_PATH_AUTO_SEARCH_SEARCH_TERMS_RESULT_LIMIT = 'autosearch/search_terms/limit_term';

    public function isEnable();

    public function getLimit();

    public function getSearchDelay();

    public function canShowSku();

    public function canShowPrice();

    public function canShowImage();

    public function canShowShortDescription();

    public function getShortMaxChar();

    public function getImageType();

    public function isSearchTermsEnable();

    public function canSearchTermAjax();

    public function getSearchTermLimit();
}