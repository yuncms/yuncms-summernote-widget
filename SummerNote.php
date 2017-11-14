<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\summernote;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class SummerNote
 * @package yuncms\summernote
 */
class SummerNote extends InputWidget
{

    public $language;
    public $uploadUrl = ['upload'];
    public $clientOptions = [];

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        if (!isset ($this->options ['id'])) {
            $this->options ['id'] = $this->getId();
        }
        $this->clientOptions = array_merge([
            'height' => 180,
            'toolbar' => [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['gxcode', 'link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            'placeholder' => self::t('write here...'),
//            'codemirror' => [
//                'mode' => 'text/html',
//                'htmlMode' => true,
//                'lineNumbers' => true,
//                'theme' => 'monokai'
//            ],
        ], $this->clientOptions);
    }

    /**
     * 注册语言包
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['yuncms/widgets/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                'yuncms/widgets/summernote' => 'summernote.php',
            ],
        ];
    }

    /**
     * 获取语言包
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return Yii::t('yuncms/widgets/summernote', $message, $params);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $language = $this->language ? $this->language : Yii::$app->language;
        if ($this->hasModel()) {
            echo Html::activeTextArea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textArea($this->name, $this->value, $this->options);
        }
        $view = $this->getView();
        SummerNoteAsset::register($view);
        $assetBundle = SummerNoteLanguageAsset::register($view);
        $assetBundle->language = $language;
        $this->clientOptions['lang'] = $language;
        if (!empty($this->uploadUrl)) {
            $this->clientOptions['callbacks']['onImageUpload'] = new JsExpression("function(files) {for (var i = files.length - 1; i >= 0; i--) {var data = new FormData();data.append(\"file\", files[i]);jQuery.ajax({cache: false,contentType: false,processData: false,data: data,type: \"POST\",dataType : \"json\",url: \"" . Url::to($this->uploadUrl) . "\",success: function(res) {jQuery(\"#{$this->options['id']}\").summernote('insertImage',  res.url, res.originalName);}});}}");
        }
        $options = empty ($this->clientOptions) ? '' : Json::htmlEncode($this->clientOptions);
        $this->view->registerJs("jQuery(\"#{$this->options['id']}\").summernote({$options});");
    }
}