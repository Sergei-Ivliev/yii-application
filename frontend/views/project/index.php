<?php

use common\models\Project;
use common\models\ProjectStatus;
use common\widgets\chat\ChatWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Project', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function(Project $model) {
                    return Html::a($model->name, ['project/view', 'id'=>$model->id]);
                }
            ],
            'author_id',
            [
                'attribute' => 'project_status_id',
//                'filter' => \common\models\ProjectStatus::getProjectStatusName(),
                'filter' => ArrayHelper::map(ProjectStatus::find()->asArray()->all(), 'id', 'name'),
                'value'=> function(Project $model) {
                    return $model->projectStatus->name;
//                    return ProjectStatus::getProjectStatusName()[$model->project_status_id];
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

        ],
    ]); ?>
<?= ChatWidget::widget();?>

</div>
