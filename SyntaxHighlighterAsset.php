<?php

namespace giovdk21\yii2SyntaxHighlighter;

use yii\web\AssetBundle as AssetBundle;
use yii;

class SyntaxHighlighterAsset extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $sourcePath = '@yii2SyntaxHighlighter/assets';

    public static $extraJs = [];

    public $css = [
        'styles/shCore.css',
        'styles/shThemeDefault.css',
    ];

    public $js = [
        'scripts/shCore.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init(){

        Yii::setAlias('@yii2SyntaxHighlighter', __DIR__);

        foreach (static::$extraJs as $js) {
            $this->js[] = $js;
        }

        return parent::init();
    }

}