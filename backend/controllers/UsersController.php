<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\User;

class UsersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionUpdate($id)
    {
        $user = User::find()->where(['id' => $id])->limit(1)->one();
        return $this->render('index', [
            'user' => $user
        ]);
    }

}
