<?php

namespace frontend\controllers;

class TaskController extends \yii\frontend\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
