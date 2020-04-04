<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View  $this
 * @var backend\models\ApplesGenerateForm $model
 */

?>
<div class="site-generate-apples-form">

    <?php $form = ActiveForm::begin([
        'action' => ['generate']
    ]); ?>

    <fieldset>
        <legend><?= Yii::t('app', 'Generate New') ?></legend>

        <?= $form->field($model, 'count')->textInput(['type' => 'number']) ?>

    </fieldset>

    <?= Html::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

</div><!-- site-generate-apples-form -->