<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Quizes;

/**
 * common\models\QuizesSearch represents the model behind the search form about `common\models\Quizes`.
 */
 class QuizesSearch extends Quizes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'start_at', 'end_at'], 'integer'],
            [['text', 'status', 'button_color', 'background_color', 'logo'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Quizes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (empty($params))
            $query->orderBy('created_at DESC');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'button_color' => $this->button_color,
            'background_color' => $this->background_color
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}
