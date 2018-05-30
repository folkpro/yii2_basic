<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\TransferForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;

$this->title = 'Money transfer';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('transferFormSubmitted')): ?>
    <div class="alert alert-success">
        Thank you for transfer.
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('transferFormError')): ?>
    <div class="alert alert-danger">
        Unexpected errors occurred, please try again.
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin([
            'id' => 'transfer-form',
            'enableAjaxValidation' => true,
            'validationUrl' => 'site/validate-transfer',
        ]); ?>

            <?= $form->field($model, 'acceptor', ['options'=>['class'=>'form-group']])->widget(AutoComplete::className(), [
                'options' => ['class' => 'form-control'],
                'clientOptions' => [
                    'source' => Url::to(['site/search']),
                    'minLength'=>'2'
                ],
            ]) ?>

            <?= $form->field($model, 'amount') ?>

            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'transfer-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
