<?php
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $quize \common\models\Quizes
 * @var $this \yii\web\View
 */

if (!empty($quize->logo))
    $this->params['logo'] = '@web/'.$quize->logo;

$file = \common\helpers\FileUrlHelper::getMediaContent($quize->file_type, $quize->file_url);
$user_answer = \common\models\Answer::findOne(['user_id'=>Yii::$app->user->id,'quize_id'=>$quize->id]);
?>

<div class="quize-results">
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
                <h1 class="display-3">

                    <?=$quize->text?></h1>
            </div>
        </div>
        <?php if($quize->variants && !is_null($user_answer)):?>
            <div class="variants">
                <?= Html::beginForm(Url::to(['quizes/add-answer']), 'POST', ['id' => 'add-answer']); ?>
                <?= Html::input('hidden', 'quize_id', $quize->id); ?>
                <div class="inputs">
                    <?php foreach ($quize->variants as $variant) { ?>
                        <?php
                            if ($user_answer->variant_id == $variant->id)
                                echo Html::radio('answer', true, ['value' => $variant->id]);
                            else
                                echo Html::radio('answer', false, ['value' => $variant->id]);
                        ?>
                    <?php } ?>
                </div>
                <div class="buttons">
                    <div class="row">
                        <?php foreach ($quize->variants as $variant) { ?>
                            <div class="col col-sm-6">
                                <?php
                                if ($user_answer->variant_id == $variant->id)
                                    echo Html::button($variant->text, ['class' => 'btn center-block btn-success', 'data-variant' => $variant->id]);
                                else
                                    echo Html::button($variant->text, ['class' => 'btn btn-default center-block', 'data-variant' => $variant->id]);
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row submit-row">
                    <div class="col-sm-12">
                        <?= Html::submitButton('Изменить ответ', [
                            'id' => 'submit-answer',
                            'class' => 'btn btn-lg center-block dissabled',
                            'disabled' => 'disabled',
                            'style' => !empty($quize->button_color) ? 'background-color: '.Html::encode($quize->button_color).'; border-color: '.Html::encode($quize->button_color).';' : ''
                        ]); ?>
                    </div>
                </div>
                <?= Html::endForm(); ?>
            </div>
        <?php endif;?>
        <canvas id="quizeChart" data-quize="<?= $quize->id; ?>"></canvas>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4 col-sm-4"></div>
            <div class="col-md-4 col-sm-4"><a style="padding: 0px;color:white" href="/" class="question-preview"><h5 style="text-align: center">ДОМОЙ</h5></a></div>
        </div>
    </div>
</div>

<?php
if(isset(Yii::$app->request->get()['dest']) && (Yii::$app->request->get()['dest'] === 'chartJS')){
    $script = <<< JS
            var body_content = $('.body-content').get()[0];
            $(body_content).css({"position":"relative"});
            var chartBlock = $(body_content).find('#quizeChart');
            var chartBlock_top_pos = $(chartBlock).position().top;
            $(body_content).animate({ scrollTop: chartBlock_top_pos}, 1100);
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}