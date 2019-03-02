<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 28-02-2019
 * Time: 04:28
 */

namespace MageDev\AutoSearch\Helper;

use MageDev\AutoSearch\Model\Config as ModuleConfig;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Module\ModuleListInterface;

class Data extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ModuleConfig
     */
    protected $_moduleConfig;

    /**
     * @var ModuleListInterface
     */
    protected $_moduleList;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ModuleListInterface $moduleList,
        ModuleConfig $moduleConfig
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_moduleList   = $moduleList;
        $this->_moduleConfig = $moduleConfig;

    }

    public function subString($message, $length = 100, $wrapperText = '...', $isStriped = true) {
        if($isStriped == true){
            $message = strip_tags($message);
        }
        if (strlen($message) <= $length) {
            return $message;
        }
        $message = substr($message, 0, $length);
        return substr($message, 0, strrpos($message, ' ')) . $wrapperText;
    }

    public function getConfigValue($path, $store = null){
        if(empty($path)){
            return null;
        }
        return $this->_moduleConfig->getConfigValue($path, $store);
    }
}