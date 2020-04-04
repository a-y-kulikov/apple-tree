<?php

namespace backend\widgets;

use backend\models\ApplesGenerateForm;
use yii\base\Widget;

class ApplesGenerateWidget extends Widget
{
    public function run()
    {
        return $this->render('//apples/forms/_generate-form', [
            'model' => new ApplesGenerateForm()
        ]);
    }
}
