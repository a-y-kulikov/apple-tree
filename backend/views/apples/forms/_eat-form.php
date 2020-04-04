<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View  $this
 * @var backend\models\AppleEatForm $model
 * @var backend\models\Apples $apple
 */
?>

<?php $form = ActiveForm::begin([
    'action' => ['eat', 'id' => $apple->id],
    'options' => ['class' => 'form-inline', 'style' => 'display:inline', 'data-pjax' => 1],
]); ?>

<?= $form->field($model, 'percents')->textInput(['type' => 'number', 'style' => 'width:70px'])->label(false)->error(false) ?>

<?= Html::submitButton(Yii::t('app', 'Eat'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>