<?php

use common\models\Task;
use common\widgets\chatWidget\ChatWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'name',
                'value' => function (Task $task) {
                    return Html::a(Html::encode($task->name), Url::to(['view', 'id' => $task->id]));
                },
                'format' => 'raw',
            ],
            'status_id',
            'description:ntext',
            'created_at:date',
            'updated_at:date',
            'author_id',
            //'executor_id',
            //'priority_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?= ChatWidget::widget(); ?>
</div>
<?//= $this->render('../chat/index.php') ?>


