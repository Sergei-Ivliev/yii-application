
<?php

use common\models\Task;
use common\widgets\chat\ChatWidget;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'project_id',
            'name',
            'description:ntext',
            [
                'label' => 'Author',
                'value' => function (Task $model) {
                    return $model->author->username;
                }
            ],
            'executor_id',
            [
                'label' => 'Status',
                'value' => function (Task $model) {
                    return $model->status->name;
                }
            ],
            'priority_id',
            [
                'label' => 'Priority',
                'value' => function (Task $model) {
                    return $model->priority->name;
                }
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

    <?= ChatWidget::widget(['task_id'=>$model->id, 'project_id' => $model->id]);?>

</div>
