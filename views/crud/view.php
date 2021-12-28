<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CadastralNumbers */

$this->title = $model->cadastr_id;
$this->params['breadcrumbs'][] = ['label' => 'Cadastral Numbers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cadastral-numbers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cadastr_id' => $model->cadastr_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cadastr_id' => $model->cadastr_id], [
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
            'cadastr_id',
            'lat',
            'lng',
        ],
    ]) ?>

</div>
