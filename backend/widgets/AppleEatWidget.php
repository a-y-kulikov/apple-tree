<?php

namespace backend\widgets;

use backend\models\AppleEatForm;
use yii\base\Widget;

class AppleEatWidget extends Widget
{
    public $apple;

    public function run()
    {
        return $this->render('//apples/forms/_eat-form', [
            'model' => new AppleEatForm(),
            'apple' => $this->apple
        ]);
    }
}
