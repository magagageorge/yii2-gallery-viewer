<?php
namespace windoface\galleryviewer;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Gallery renders a BlueImp Gallery items
 *
  * @author Magaga,Kinggeorge <magagageorge@gmail.com>
 */
class GalleryViewer extends Widget
{
    /**
     * @var array the HTML attributes for the links container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var array the HTML attributes for the lightbox container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $templateOptions = [];
    /**
     * @var array the options for the BlueImp Gallery plugin.
     * Please refer to the BlueImp Gallery plugin Web page for possible options.
     * @see https://github.com/blueimp/Gallery/blob/master/README.md#setup
     */
    public $clientOptions = [];
    /**
     * @var array the event handlers for the underlying Bootstrap Switch 3 input JS plugin.
     * Please refer to the [BlueImp Gallery plugin](https://github.com/blueimp/Gallery/blob/master/README.md#event-callbacks)
     * for information about their callbacks.
     */
    public $clientEvents = [];
    /**
     * @var array The array of items that compound the gallery. The syntax is as follows:
     *
     * - src: string, the image to display
     * - url: string, the image to display on the lightbox. If none found, will display `src`
     * - options: HTML attributes of the link
     */
    public $items = array();
    /**
     * @var bool whether to display the controls on initialization
     */
    public $showControls = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->templateOptions['id'] = ArrayHelper::getValue($this->templateOptions, 'id', 'gallery1-7');
        Html::addCssClass($this->templateOptions, 'mbr-gallery mbr-section mbr-section--no-padding');
        if ($this->showControls) {
            Html::addCssClass($this->templateOptions, 'carousel-control');
        }

        foreach($this->clientEvents as $key => $event) {
            if(!($event instanceof JsExpression)) {
                $this->clientOptions[$key] = new JsExpression($event);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (empty($this->items)) {
            return null;
        }
        echo $this->renderItems();
        echo $this->renderTemplate();
        $this->registerClientScript();
    }

    /**
     * @return string the items that are need to be rendered.
     */
    public function renderItems()
    {
        $items = [];
		$cnt=0;
        foreach ($this->items as $item) {
            $items[] = $this->renderItem($item,$cnt);
			$cnt++;
        }
        return Html::tag('div', implode("\n", array_filter($items)), ['class'=>'row mbr-gallery-row no-gutter']);
    }

    /**
     * @param mixed $item
     * @return null|string the item to render
     */
    public function renderItem($item,$cnt)
    {
	/*
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mbr-gallery-item">
                    <a href="#lb-gallery1-7" data-slide-to="0" data-toggle="modal">
                        <img alt="" src="assets/images/slide1-small.jpg">
                        <span class="icon glyphicon glyphicon-zoom-in"></span>
                    </a>
                </div>	
	*/	
		
		
        if (is_string($item)) {
            return Html::a(Html::img($item), '#lb-gallery1-7', ['data-slide-to'=>$cnt 'data-toggle'=>'modal']);
        }
        $src = ArrayHelper::getValue($item, 'src');
        if ($src === null) {
            return null;
        }
        $url = ArrayHelper::getValue($item, 'url', $src);
		$options = ArrayHelper::getValue($item, 'options', []);
		$options=array_merge($options,array('data-slide-to'=>$cnt 'data-toggle'=>'modal'));
        //Html::addCssClass($options, 'gallery-item');

		
        Html::a(Html::img($src), '#lb-gallery1-7', $options);
		return Html::tag('div', implode("\n", array_filter($items)), ['class'=>'col-lg-3 col-md-4 col-sm-6 col-xs-12 mbr-gallery-item']);
    }

    /**
     * Renders the template to display the images on a lightbox
     * @return string the template
     */
    public function renderTemplate()
    {
        $template[] = '<div class="slides"></div>';
        $template[] = '<h3 class="title"></h3>';
        $template[] = '<a class="prev">‹</a>';
        $template[] = '<a class="next">›</a>';
        $template[] = '<a class="close">×</a>';
        $template[] = '<a class="play-pause"></a>';
        $template[] = '<ol class="indicator"></ol>';

        return Html::tag('div', implode("\n", $template), $this->templateOptions);
    }

    /**
     * Registers the client script required for the plugin
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        GalleryAsset::register($view);
        WindofaceAsset::register($view);

        $id = $this->options['id'];
        $options = Json::encode($this->clientOptions);
        $js = "dosamigos.gallery.registerLightBoxHandlers('#$id a', $options);";
        $view->registerJs($js);

        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('$id').on('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js));
        }
    }
}
