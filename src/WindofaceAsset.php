<?php
namespace windoface\galleryviewer;

use yii\web\AssetBundle;

/**

git init
git add -A
git commit
git remote add origin https://path.to/your/repo
git push -u origin master




 * windofaceAsset
 *
 * @author Magaga,Kinggeorge <magagageorge@gmail.com>
 */
class WindofaceAsset extends AssetBundle
{
    public $js = [
        'smooth-scroll/SmoothScroll.js',
		'masonry/masonry.pkgd.min.js',
		'imagesloaded/imagesloaded.pkgd.min.js',
		'bootstrap-carousel-swipe/bootstrap-carousel-swipe.js',
		'mobirise/js/script.js',
		'mobirise-gallery/script.js',
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
