<?php

use yii\helpers\Url;

$this->title = 'ASKU - История';
?>
<div class="row title">
    <div class="col-sm-12">
        <h1>Выберете интересующую вас тему:</h1>
    </div>
</div>
<div class="quizes-history">

    <div class="body-content">
        <section class="question-previews-box">

            <div class="row">
                <div class="col-sm-12">
                <?php if(!empty($quizes)) { ?>
                    <?php foreach ($quizes as $quize){ ?>
                        <article class="col">
                            <a class="question-preview history" href="<?= Url::to(['quizes/view', 'id' => $quize['id']]) ?>">
                                <div class="inner">
                                    <span style="color: white"><?= $quize['text']; ?></span>
                                </div>
                            </a>
                        </article>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="alert alert-warning" role="alert">Извините, нам нечего вам показать :(</div>
                <?php } ?>
                </div>
            </div>
        </section>
    </div>
    
</div>
