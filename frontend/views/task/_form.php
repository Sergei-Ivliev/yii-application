<?php

use common\models\Project;
use common\models\TaskPriority;
use common\models\TaskStatus;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
/* @var $activeUsers User[] */
/* @var $projects Project[] */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'maxlength' => true]) ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'status_id')->dropDownList(TaskStatus::getStatusName()) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'executor_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priority_id')->dropDownList(TaskPriority::getPriorityName()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
