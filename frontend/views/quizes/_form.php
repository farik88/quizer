<?php
use common\widgets\Alert;
use yii\bootstrap\Html;
use yii\helpers\Url;

if (!empty($quize->logo))
    $this->params['logo'] = '@web/'.$quize->logo;

$file = \common\helpers\FileUrlHelper::getMediaContent($quize->file_type, $quize->file_url);
?>

<div class="quizes-view">
    <div class="body-content">
        <?= Alert::widget() ?>
        <?php if ($file): ?>
            <?php if ($quize->file_type == \common\models\Quizes::FILE_TYPE_IMAGE): ?>
                <?= $file ?>
            <?php else: ?>
                <div class="question-body">
                    <div class="jumbotron" style="min-height: 300px; padding: 4px 4px 0 4px;">
                        <?= $file ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="question-body">
            <div class="jumbotron" style="<?= (!empty($quize->button_color) ? 'background-color: '.Html::encode($quize->background_color).'; border-color: '.Html::encode($quize->background_color).';' : '') ?>">
                <h1 class="display-3"><?= $quize->text ?></h1>
                <p class="lead">Выберете ваш вариант ответа</p>
            </div>
        </div>
        <?php if($quize->variants) { ?>
            <div class="variants">
                <?= Html::beginForm(Url::to(['quizes/add-answer']), 'POST', ['id' => 'add-answer']); ?>
                <?= Html::input('hidden', 'quize_id', $quize->id); ?>
                <div class="inputs">
                    <?php foreach ($quize->variants as $variant) { ?>
                        <?= Html::radio('answer', false, ['value' => $variant->id]); ?>
                    <?php } ?>
                </div>
                <div class="buttons">
                    <div class="row">
                        <?php foreach ($quize->variants as $variant) { ?>
                            <div class="col col-sm-6">
                                <?= Html::button($variant->text, ['class' => 'btn btn-default center-block', 'data-variant' => $variant->id]); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row submit-row">
                    <div class="col-sm-12">
                        <?= Html::submitButton('Голосовать', [
                            'id' => 'submit-answer',
                            'class' => 'btn btn-lg center-block dissabled',
                            'disabled' => 'disabled',
                            'style' => !empty($quize->button_color) ? 'background-color: '.Html::encode($quize->button_color).'; border-color: '.Html::encode($quize->button_color).';' : ''
                        ]); ?>
                    </div>
                </div>
                <?= Html::endForm(); ?>
            </div>
        <?php } else { ?>
            <div class="variants no-variants">
                <div class="alert alert-danger" role="alert">
                    <h3>No variants!</h3>
                </div>
            </div>
        <?php } ?>
    </div>
</div>