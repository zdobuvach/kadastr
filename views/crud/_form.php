<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CadastralNumbers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cadastral-numbers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cadastr_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lat')->textInput() ?>

    <?= $form->field($model, 'lng')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
