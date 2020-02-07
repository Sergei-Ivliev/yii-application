<?php

namespace common\widgets\chatWidget;


use yii\web\AssetBundle;
use yii\web\YiiAsset;

class ChatAsset extends AssetBundle
{
    public $sourcePath = (__DIR__ . '/assets');
    public $js = ['js/chat.js'];
    public $css = ['css/chat.css'];

    public $depends = [
        YiiAsset::class
    ];

}