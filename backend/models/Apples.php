<?php

namespace backend\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "apples".
 *
 * @property int $id
 * @property string $color
 * @property int $created_at
 * @property int|null $down_at
 * @property string $status
 * @property int $percents
 */
class Apples extends \yii\db\ActiveRecord
{
    const STATUS_ON_TREE = 'onTree';
    const STATUS_ON_GROUND = 'onGround';
    const STATUS_BAD = 'bad';

    const COLOR_RED = 'red';
    const COLOR_GREEN = 'green';
    const COLOR_YELLOW = 'yellow';

    const BAD_TIME = 5 * 60 * 60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apples';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color', 'created_at', 'status', 'percents'], 'required'],
            [['created_at', 'down_at'], 'integer'],
            [['percents'], 'integer'],
            [['color', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'color' => Yii::t('app', 'Color'),
            'created_at' => Yii::t('app', 'Created At'),
            'down_at' => Yii::t('app', 'Down At'),
            'status' => Yii::t('app', 'Status'),
            'percents' => Yii::t('app', 'Percents'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ApplesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApplesQuery(get_called_class());
    }

    /**
     * @return boolean
     */
    public function isOnTree(): bool
    {
        return $this->status == static::STATUS_ON_TREE;
    }

    /**
     * @return boolean
     */
    public function isOnGroud(): bool
    {
        return $this->status == static::STATUS_ON_GROUND;
    }

    /**
     * @return boolean
     */
    public function isBad(): bool
    {
        return $this->status == static::STATUS_BAD;
    }

    /**
     * @return boolean
     */
    public function canDown(): bool
    {
        return $this->isOnTree();
    }

    /**
     * @return boolean
     */
    public function canEat(): bool
    {
        return $this->isOnGroud() && !$this->isBad();
    }

    /**
     * @return boolean
     */
    public function canDelete(): bool
    {
        return true;
    }

    /**
     * @throws Exception
     * @return void
     */
    public function down(): void
    {
        if (!$this->canDown()) {
            throw new Exception(Yii::t('app', "Can not down"));
        }

        $this->status = static::STATUS_ON_GROUND;
        $this->down_at = time();
        $this->save();
    }

    /**
     * @throws Exception
     * @return void
     */
    public function eat(int $percents): void
    {
        $this->checkBad();

        if (!$this->canEat()) {
            throw new Exception(Yii::t('app', "Can not eat"));
        }

        if ($this->percents < $percents) {
            throw new Exception(Yii::t('app', "Can not eat $percents%"));
        }

        $this->percents -= $percents;
        $this->save();

        if ($this->percents == 0) {
            $this->delete();
        }
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new Exception(Yii::t('app', "Save error. Try again"));
        }

        return true;
    }

    /**
     * @throws Exception
     * @return void
     */
    public function delete(): void
    {
        if (!$this->canDelete()) {
            throw new Exception(Yii::t('app', "Can not delete"));
        }

        try {
            parent::delete();
        } catch (\Exception | \Throwable $e) {
            throw new Exception(Yii::t('app', $e->getMessage()));
        }
    }

    /**
     * @return boolean
     */
    public function canBad(): bool
    {
        return $this->isOnGroud();
    }

    /**
     * @throws Exception
     * @return void
     */
    protected function markBad(): void
    {
        $this->status = static::STATUS_BAD;
        $this->save();
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function checkBad(): bool
    {
        if (!$this->canBad()) {
            return false;
        }

        if ((time() - $this->down_at) < static::BAD_TIME) {
            return false;
        }

        $this->markBad();
        return true;
    }

    /**
     * @param string $color
     * @return self|null
     */
    public static function create(string $color = null): ?self
    {
        $model = new static();
        $model->color = $color ?? $model->geterateColor();
        $model->created_at = mt_rand(1, time());
        $model->status = static::STATUS_ON_TREE;
        $model->percents = 100;

        try {
            $model->save();
        } catch (Exception $e) {
            return null;
        }

        return $model;
    }

    /**
     * @param integer $number
     * @return void
     */
    public static function multiCreate(int $number): void
    {
        for ($i = 1; $i <= $number; $i++) {
            static::create();
        }
    }

    /**
     * @return string
     */
    private function geterateColor(): string
    {
        $colors = [static::COLOR_RED, static::COLOR_GREEN, static::COLOR_YELLOW];
        $colorPos = rand(1, count($colors));
        return $colors[$colorPos - 1];
    }

    /**
     * @return string
     */
    public function getPercentsPretty(): string
    {
        return "$this->percents%";
    }

    /**
     * @return int
     */
    public static function checkAll(): int
    {
        $totalMark = 0;
        foreach (static::find()->all() as $apple) {
            try {
                if ($apple->checkBad()) {
                    $totalMark++;
                }
            } catch (Exception $e) {
            }
        }

        return $totalMark;
    }

    /**
     * @return int
     */
    public static function deleteAllBad(): int
    {
        return static::deleteAll(['status' => self::STATUS_BAD]);
    }
}
