<?php

/** @var string $username */

use yii\helpers\Html;

?>
    <hr>
    <div id="chat" class="col-lg-9"
         style="
    /*height: 100px;*/
    min-height: 50px;
    max-height: 120px;
    background: rgba(215,255,216,0.57);
    border: 1px solid #C1C1C1;
    /*overflow-x: scroll;*/
    overflow-y: scroll;
    margin-bottom: 12px;
"></div>
    <div id="response" style="color:#D00"></div>
    <div class="row">
        <div class="col-lg-9">
            <?= Html::textInput('message', '', ['id' => 'message', 'class' => 'form-control']) ?>
        </div>

        <div class="col-lg-3">
            <?= Html::button('Отправить', ['id' => 'send', 'class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?= Html::hiddenInput('username', $username, ['class' => 'js-username']) ?>