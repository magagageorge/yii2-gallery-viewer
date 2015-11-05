<?php
namespace windoface\galleryviewer;

use yii\web\AssetBundle;

/**
 * GalleryAsset
 *
 * @author Magaga,Kinggeorge <magagageorge@gmail.com>
 */
class GalleryAsset extends AssetBundle
{
    public $css = [
        'mobirise/css/style.css',
        'assets/mobirise/css/style.css',
        'assets/mobirise-gallery/style.css',
        'assets/mobirise-slider/style.css',
        'assets/mobirise/css/mbr-additional.css',		
    ];
    public $js = [
        'js/blueimp-gallery.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
	
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }	
}
