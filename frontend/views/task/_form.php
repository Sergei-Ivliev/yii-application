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

<?//=  Html::beginForm([''], 'post', ['class' => 'task-form'])?>
<!---->
<?//= Html::activeLabel($model, 'Название', ['class' => 'name'])?><!--<br>-->
<?//= Html :: activeInput ( 'text' , $model , 'name', ['placeholder' => '    name', 'autofocus' => true])?><!-- <br><br>-->
<?//= Html::activeLabel($model, 'Проект', ['class' => 'name'])?><!--<br>-->
<?//= Html :: activeInput ( 'text' , $model , 'project_id')?><!-- <br><br>-->
<?//= Html::activeLabel($model, 'Статус', ['class' => 'name'])?><!--<br>-->
<?//= Html :: activeInput ( 'text' , $model , 'status_id')?><!-- <br><br>-->
<?//= Html::activeLabel($model, 'Описание', ['class' => 'name'])?><!--<br>-->
<?//= Html :: activeInput ( 'text' , $model , 'description')?><!-- <br><br>-->
<?//= Html::activeLabel($model, 'Исполнитель', ['class' => 'name'])?><!--<br>-->
<?//= Html :: activeInput ( 'text' , $model , 'executor_id')?><!-- <br><br>-->
<?//= Html::activeLabel($model, 'Приоритет', ['class' => 'name'])?><!--<br>-->
<?//= Html :: activeInput ( 'text' , $model , 'priority_id')?><!-- <br><br>-->
<!---->
<?//= Html :: submitButton ( 'Save' , [ 'class' => 'btn btn-success']) ?>
<?//=  Html::endForm()?>
