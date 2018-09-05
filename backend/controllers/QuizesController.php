<?php

namespace backend\controllers;

use common\helpers\FileUrlHelper;
use common\models\QuizesUploadLogo;
use Yii;
use common\models\Quizes;
use common\models\QuizesSearch;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\base\Variants;
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
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'add-variants', 'delete-variant'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Quizes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuizesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Quizes model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Quizes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new Quizes();
        
        if(Yii::$app->request->post('Quizes')){
            $model->text = Yii::$app->request->post('Quizes')['text'];
            $model->status = Yii::$app->request->post('Quizes')['status'];
            $model->created_at = $model::getDateInTimeStamp(Yii::$app->request->post('Quizes')['created_at']);
            $model->start_at = $model::getDateInTimeStamp(Yii::$app->request->post('Quizes')['start_at']);
            $model->end_at = $model::getDateInTimeStamp(Yii::$app->request->post('Quizes')['end_at']);

            if (!empty(Yii::$app->request->post('Quizes')['file_url'])) {
                $model->file_url = trim(Yii::$app->request->post('Quizes')['file_url']);
                $file_type = FileUrlHelper::getFileType($model->file_url);

                if (!empty($file_type)) {
                    $model->file_type = $file_type;

                    if ($file_type == Quizes::FILE_TYPE_YOUTUBE)
                        $model->file_url = FileUrlHelper::getYouTubeId($model->file_url);
                    if ($file_type == Quizes::FILE_TYPE_VIMEO)
                        $model->file_url = FileUrlHelper::getVimeoId($model->file_url);
                }
            }
            
            if($model->validate() && $model->save()){
                
                if(Yii::$app->request->post('Variants') && $model->id){
                    foreach (Yii::$app->request->post('Variants') as $key => $post_variant){
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
            
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing Quizes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Quizes();
        }else{
            $model = $this->findModel($id);
        }

        if(Yii::$app->request->post('Variants')){
            Quizes::updateVariants($model->id, Yii::$app->request->post('Variants'));
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = Quizes::getDateInTimeStamp($model->created_at);
            $model->start_at = Quizes::getDateInTimeStamp($model->start_at);
            $model->end_at = Quizes::getDateInTimeStamp($model->end_at);

            if (!empty($model->file_url)) {
                $file_type = FileUrlHelper::getFileType(trim($model->file_url));

                if (!empty($file_type)) {
                    $model->file_type = $file_type;

                    if ($file_type == Quizes::FILE_TYPE_YOUTUBE)
                        $model->file_url = FileUrlHelper::getYouTubeId($model->file_url);
                    if ($file_type == Quizes::FILE_TYPE_VIMEO)
                        $model->file_url = FileUrlHelper::getVimeoId($model->file_url);
                } else {
                    $model->file_type = null;
                }
            }

            if($model->save()){
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

                return $this->redirect(['update', 'id' => $model->id]);
            }
            
        }
        
        return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing Quizes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithRelated();

        return $this->redirect(['index']);
    }

    /**
     * @param null $quize_id
     * @param null $text
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteVariant($quize_id=null, $text=null)
    {
        $quize_id = Yii::$app->request->post('quize_id') ? intval(Yii::$app->request->post('quize_id')) : null;
        $text = Yii::$app->request->post('text') ? Yii::$app->request->post('text') : null;
        $variant = Variants::find()->where(['quize_id' => $quize_id])->andWhere(['text' => $text])->limit(1)->one();
        if($variant){
            $variant->delete();
        }
    }

    /**
     * Finds the Quizes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quizes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quizes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for Variants
     *
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
   public function actionAddVariants()
   {
       if (Yii::$app->request->isAjax) {
           $row = Yii::$app->request->post('Variants');
           if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
               $row[] = [];
           return $this->renderAjax('_formVariants', ['row' => $row]);
       } else {
           throw new NotFoundHttpException('The requested page does not exist.');
       }
   }
}
