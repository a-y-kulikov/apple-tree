<?php

use backend\models\AppleEatForm;
use backend\models\ApplesGenerateForm;
use backend\models\Apples;
use common\widgets\Alert;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var $dataProvider
 * @var AppleEatForm $AppleEatForm  
 * @var ApplesGenerateForm $ApplesGenerateForm
 */

$this->title = 'Apples List';
?>
<div class="apples-index">

    <div class="body-content">

        <div class="row">
            <div class="col-sm-8">
                <h2>Apples List</h2>

                <p>
                    <?= Html::a('Check all bad', ['apples/check-all-bad'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Delete all bad', ['apples/delete-all-bad'], ['class' => 'btn btn-danger']) ?>
                </p>

                <?php Pjax::begin([
                    'id' => 'gridview-apples',
                    'enablePushState' => false,
                    'timeout' => 2000,
                ]);
                ?>

                <?= Alert::widget() ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'color',
                        'created_at:datetime',
                        'down_at:datetime',
                        [
                            'attribute' => 'percents',
                            'value' => function (Apples $model) {
                                return $model->getPercentsPretty();
                            }
                        ],
                        'status',
                        [
                            'class' => \yii\grid\ActionColumn::className(),
                            'buttons' => [
                                'down' => function ($url, Apples $model) {
                                    return Html::a('Down', $url, ['class' => 'btn btn-warning']);
                                },
                                'eat' => function ($url, Apples $model) use ($AppleEatForm) {
                                    return $this->render('//apples/_eat-form', [
                                        'apple' => $model,
                                        'model' => clone $AppleEatForm
                                    ]);
                                },
                                'delete' => function ($url, Apples $model) {
                                    return Html::a('Delete', $url, ['class' => 'btn btn-danger']);
                                }
                            ],
                            'urlCreator' => function ($action, Apples $model, $key, $index) {
                                return Url::toRoute(["apples/$action", 'id' => $model->id]);
                            },
                            'visibleButtons' => [
                                'down' => function (Apples $model, $key, $index) {
                                    return $model->canDown();
                                },
                                'eat' => function (Apples $model, $key, $index) {
                                    return $model->canEat();
                                },
                                'delete' => function (Apples $model, $key, $index) {
                                    return $model->canDelete();
                                }
                            ],
                            'template' => '{down} {eat} {delete}',
                        ]
                    ],

                ]); ?>

                <?php Pjax::end(); ?>
            </div>
            <div class="col-sm-4">
                <?= $this->render('//apples/_generate-form', ['model' => $ApplesGenerateForm]); ?>
            </div>
        </div>

    </div>