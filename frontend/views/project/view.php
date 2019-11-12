<?php

use common\models\Project;
use common\widgets\chat\ChatWidget;
use frontend\models\TaskSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $taskDataProvider ActiveDataProvider */
/* @var $taskSearch TaskSearch */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="project-view">

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
            'name',
            [
                'label'=>'Author',
                'value'=> function(Project $model) {
                    return $model->author->username;

                }
            ],
            [
                'label'=>'Status',
                'value'=> function(Project $model) {
                    return $model->projectStatus->name;

                }
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>


    <?= ChatWidget::widget(['project_id' => $model->id]);?>



</div>
