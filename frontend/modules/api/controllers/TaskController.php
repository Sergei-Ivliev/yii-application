<?php


namespace frontend\modules\api\controllers;


use common\models\Task;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class TaskController extends ActiveController
{
    public $modelClass = Task::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'update' || $action==='delete') {
//        if ($action === 'view') {
            if ($model->author_id !== \Yii::$app->user->id) {
                throw new ForbiddenHttpException('Нельзя смотреть задачи где вы не являетесь автором');
            }
        }

    }
}