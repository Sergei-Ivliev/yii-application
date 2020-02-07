<?php


namespace frontend\modules\api\controllers;


use common\models\Task;
use yii\rest\ActiveController;

class TaskController extends ActiveController
{
    public $modelClass = Task::class;
}