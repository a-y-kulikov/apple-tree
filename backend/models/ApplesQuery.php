<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Apples]].
 *
 * @see Apples
 */
class ApplesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Apples[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Apples|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
