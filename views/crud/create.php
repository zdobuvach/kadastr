<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CadastralNumbers */

$this->title = 'Create Cadastral Numbers';
$this->params['breadcrumbs'][] = ['label' => 'Cadastral Numbers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cadastral-numbers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
