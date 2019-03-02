<?php
/**
 * Created by PhpStorm.
 * User: vinit.rane
 * Date: 01-03-2019
 * Time: 12:51
 */

namespace MageDev\AutoSearch\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Helper\Image as ImageHelper;

class Image extends AbstractHelper
{
    /**
     * @var ImageHelper
     */
    protected $_imageHelper;

    public function __construct(
        Context $context,
        ImageHelper $imageHelper
    ){
        $this->_imageHelper = $imageHelper;
        parent::__construct($context);
    }

    public function getImg($product, $h, $w = 300, $imgVersion='image', $file=NULL)
    {
        if (!$h || (int)$h == 0){
            $image = $this->_imageHelper
                ->init($product, $imgVersion)
                ->constrainOnly(true)
                ->keepAspectRatio(true)
                ->keepFrame(false);
            if($file){
                $image->setImageFile($file);
            }
            $image->resize($w);
            return $image;
        }else{
            $image = $this->_imageHelper
                ->init($product, $imgVersion);
            if($file){
                $image->setImageFile($file);
            }
            $image->resize($w, $h);
            return $image;
        }
    }
    public function getAltImgHtml($product, $w, $h, $imgVersion='small_image', $column = 'position', $value = 1)
    {
        $product->load('media_gallery');
        if ($images = $product->getMediaGalleryImages())
        {
            $image = $images->getItemByColumnValue($column, $value);
            if(isset($image) && $image->getUrl()){
                $imgAlt = $this->getImg($product, $w, $h, $imgVersion , $image->getFile());
                if(!$imgAlt) return '';
                return $imgAlt;
            }else{
                return '';
            }
        }
        return '';
    }
}