<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Money transfer list';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'options' => ['width' => '70']
            ],
            [
                'attribute' => 'date_create',
                'value' => function($model) {
                    return date("d.m.Y H:i:s", strtotime($model->date_create));
                }
            ],
            'acceptorName',
            'amount',
        ],
    ]); ?>
<?php Pjax::end(); ?>
