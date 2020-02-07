<?php

namespace frontend\controllers;

use http\Exception;
use Yii;
use yii\web\Controller;

class TestTelegramBotController extends Controller
{
    public function actionIndex()
    {
        $data = "" ;
//        try {
//            $updates = Yii::$app -> bot -> getUpdates ();
//            foreach ( $updates as $update ){
//                $user_id = $update -> getMessage()-> getFrom()-> getID();
//                Yii::$app -> bot -> sendMessage ( $user_id , 'Index action was
//requested!' );
//            }
//        }
//        catch ( Exception $e ){
//            $data = $e -> getMessage ();
//        }
        return $this -> render ( 'index' , [ 'data' => $data ]);
    }

}
