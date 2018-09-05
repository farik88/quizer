<?php

namespace frontend\controllers;

use common\helpers\FileUrlHelper;
use common\models\QuizesUploadLogo;
use Yii;
use frontend\models\UserQuize;
use common\models\Quizes;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use common\models\base\Variants;
use common\models\Answer;
use yii\web\UploadedFile;

/**
 * QuizesController implements the CRUD actions for Quizes model.
 */
class QuizesController extends Controller
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add-answer' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['create-quize'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create-quize'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect('/');
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'quize' => $this->findModel($id),
        ]);
    }
    
    public function actionAddAnswer()
    {
        if(Yii::$app->user->isGuest || !Yii::$app->user->id){
            Yii::$app->session->setFlash('danger', 'Вы должны войти что бы проголосовать!');
            return $this->redirect(Url::to(['site/login']));
        }
        
        if(Yii::$app->user->identity->isAlreadyAnswerQuestion(intval(Yii::$app->request->post('quize_id')))){
            // Already have answer
//            Yii::$app->session->setFlash('danger', 'You already answer this question!');
            $answer = Answer::findOne(['quize_id'=>intval(Yii::$app->request->post('quize_id'))]);
            if(!is_null($answer)){
                $answer->variant_id = intval(Yii::$app->request->post('answer'));
                $answer->save();
            }
            return $this->redirect(Url::to(['quizes/view', 'id' => Yii::$app->request->post('quize_id'), 'dest' => 'chartJS']));
        } else {
            // Create new answer
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $answer = new Answer();
                $answer->user_id = Yii::$app->user->id;
                $answer->quize_id = intval(Yii::$app->request->post('quize_id'));
                $answer->variant_id = intval(Yii::$app->request->post('answer'));
                $answer->save();
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Спасибо за ваш ответ! Ваш голос учтен.');
            } catch (\Exception $error) {
                $transaction->rollback();
                Yii::$app->session->setFlash('danger', $error->getMessage());
            }
            return $this->redirect(Url::to(['quizes/view', 'id' => Yii::$app->request->post('quize_id'), 'dest' => 'chartJS']));
        }
    }

    protected function findModel($id)
    {
        $model = Quizes::find()->where(['id' => $id])->with('variants')->limit(1)->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Такая страница не найдена.');
        }
    }
    
    public function actionGetAnswersDataForChart()
    {
        if (Yii::$app->request->isAjax) {
            $answers_data = Quizes::getAnswersDataForChart(Yii::$app->request->post('id'));
            Yii::$app->response->format = 'json';
            return $answers_data;
            die();
        }
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionCreateQuize()
    {
        if(Yii::$app->user->isGuest || !Yii::$app->user->id){
            Yii::$app->session->setFlash('danger', 'Для того, чтобы создать вопрос, Вы должны быть авторизованы!');
            return $this->redirect(Url::to(['site/login']));
        }
        
        $model = new UserQuize();
        $now = new \DateTime();
        
        if(Yii::$app->request->post('UserQuize')){
            
            /* Validate answers variants count */
            if(count(Yii::$app->request->post('Variants'))<2){
                Yii::$app->session->setFlash('danger', 'Добавьте минимум 2 варианта ответа!');
                $model->text = Yii::$app->request->post('UserQuize')['text'];
                $model->start_at = $model::getDateInTimeStamp(Yii::$app->request->post('UserQuize')['start_at']);
                $model->end_at = $model::getDateInTimeStamp(Yii::$app->request->post('UserQuize')['end_at']);
                return $this->render('create', [
                    'model' => $model
                ]);
            }
            /* Save new quize */
            $model->text = Yii::$app->request->post('UserQuize')['text'];
            $model->status = 'draft';
            $model->created_at = $model::getDateInTimeStamp($now->format('d.m.Y H:i'));
            $model->start_at = $model::getDateInTimeStamp(Yii::$app->request->post('UserQuize')['start_at']);
            $model->end_at = $model::getDateInTimeStamp(Yii::$app->request->post('UserQuize')['end_at']);
            $model->reCaptcha = Yii::$app->request->post('UserQuize')['reCaptcha'];

            if (!empty(Yii::$app->request->post('UserQuize')['file_url'])) {
                $model->file_url = trim(Yii::$app->request->post('UserQuize')['file_url']);
                $file_type = FileUrlHelper::getFileType($model->file_url);

                if (!empty($file_type)) {
                    $model->file_type = $file_type;

                    if ($file_type == Quizes::FILE_TYPE_YOUTUBE)
                        $model->file_url = FileUrlHelper::getYouTubeId($model->file_url);
                    if ($file_type == Quizes::FILE_TYPE_VIMEO)
                        $model->file_url = FileUrlHelper::getVimeoId($model->file_url);
                }
            }
            
            if ($model->validate() && $model->save()) {
                if (Yii::$app->request->post('Variants') && $model->id) {
                    foreach (Yii::$app->request->post('Variants') as $key => $post_variant) {
                        try {
                            $variant = new Variants();
                            $variant->text = $post_variant['text'];
                            $variant->quize_id = $model->id;
                            $variant->save();
                        } catch (Exception $e) {
                            throw new \yii\db\Exception($e->getMessage());
                        }
                    }
                }

                $file_instance = UploadedFile::getInstanceByName('Quizes[logo]');

                if (null !== $file_instance) {
                    $crop = [
                        'width'  => current(Yii::$app->request->post('width')),
                        'height' => current(Yii::$app->request->post('height')),
                        'x'      => current(Yii::$app->request->post('x')),
                        'y'      => current(Yii::$app->request->post('y'))
                    ];

                    $upload_logo       = new QuizesUploadLogo();
                    $upload_logo->logo = $file_instance;
                    $upload_logo->crop = $crop;
                    $upload_logo->upload($model);
                }

                return $this->redirect(['question/success']);
            }
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
    }
    
    public function actionCreateSuccess()
    {
        return $this->render('createSuccess');
    }

    public function actionAddVariants()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Variants');
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_createFormVariants', ['row' => $row]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
