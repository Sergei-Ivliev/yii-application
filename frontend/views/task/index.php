
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'status_id',
            'description:ntext',
            'created_at',
            //'updated_at',
            //'author_id',
            //'executor_id',
            //'priority_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?= $this->render('../chat/index.php') ?>

</div>
