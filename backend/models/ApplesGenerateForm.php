<?php

namespace backend\models;

use yii\base\Model;

/**
 * ApplesGenerateForm form
 */
class ApplesGenerateForm extends Model
{
    public $count;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count'], 'required'],
            [['count'], 'integer', 'min' => 1, 'max' => 50]
        ];
    }

    public function generate()
    {
        if (!$this->validate()) {
            return false;
        }

        Apples::multiCreate($this->count);
        return true;
    }
}
