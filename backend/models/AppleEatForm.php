<?php

namespace backend\models;

use yii\base\Exception;
use yii\base\Model;

/**
 * AppleEatForm form
 */
class AppleEatForm extends Model
{
    public $percents;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percents'], 'required'],
            [['percents'], 'integer', 'min' => 1, 'max' => 100]
        ];
    }

    public function eat(Apples $apple)
    {
        try {
            $apple->eat($this->percents);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
