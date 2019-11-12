<?php


namespace frontend\modules\api\controllers;


use common\models\Task;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //подключаем авторизацию через HttpBearerAuth
        //каждый запрос к этому контроллеру будет фильтроваться через это поведение
        //authenticator - имеет больший приоритет чем ACF
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
                HttpBasicAuth::class,
                QueryParamAuth::class,
            ],
            //нужно исключить action которые используются в accessFilter ACF
//            'except' => ['create']
        ];
        return $behaviors;
    }



    public function actionMe()
    {
        return ['me' => \Yii::$app->user->identity];
    }
//
    public function actionTasks($id)
    {
        return new ActiveDataProvider([
            'query' => Task::find()->where(['author_id'=>$id]),
            'pagination' => [
                'pageSize' => 3,
            ],
        ]);
    }
}