<?php


namespace frontend\modules\api\controllers;


use common\models\Task;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class TaskController extends ActiveController
{
    public $modelClass = Task::class;


    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'view') {
            if ($model->author_id !== \Yii::$app->user->id) {
                throw new ForbiddenHttpException('Нельзя смотреть задачи где вы не являетесь автором');
            }
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionRandom($count)
    {
        return ['count' => $count];
    }
}