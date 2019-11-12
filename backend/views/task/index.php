<?php

use common\models\TaskStatus;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Task;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'authorEmail',
                'value' => function (Task $model) {
                    return $model->author->email;
                }
            ],
            [
                'attribute' => 'status_id',
                'filter' => TaskStatus::getStatusName(),
                'value' => function (Task $model) {
                    return $model->status->name;
                }
            ],
            [
                'attribute' => 'priority',
                'value' => function (Task $model) {
                    return $model->priority->name;
                }
            ],
            [
                'attribute' => 'projectName',
                'value' => function (Task $model) {
                    if (!empty($model->project)) {
                        return $model->project->name;
                    }
                    return null;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>