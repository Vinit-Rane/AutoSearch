<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 01-03-2019
 * Time: 12:16
 */

namespace MageDev\AutoSearch\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Widget\Block\BlockInterface;

use MageDev\AutoSearch\Helper\Data as ModuleHelper;
use MageDev\AutoSearch\Model\Config as ModuleConfig;
use MageDev\AutoSearch\Helper\Image as ModuleImageHelper;

class Item extends AbstractProduct implements BlockInterface
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'MageDev_AutoSearch::result_item.phtml';

    protected $_moduleImageHelper;

    protected $_moduleHelper;

    protected $_moduleConfig;

    public function __construct(
        Context $context,
        ModuleImageHelper $moduleImageHelper,
        ModuleHelper $moduleHelper,
        ModuleConfig $moduleConfig,
        array $data = []
    ){
        $this->_moduleImageHelper = $moduleImageHelper;
        $this->_moduleHelper = $moduleHelper;
        $this->_moduleConfig = $moduleConfig;
        parent::__construct($context, $data);
    }

    public function getModuleConfig(){
        return $this->_moduleConfig;
    }

    public function getModuleHelper(){
        return $this->_moduleHelper;
    }

    public function getModuleImageHelper(){
        return $this->_moduleImageHelper;
    }

}