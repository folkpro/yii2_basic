<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TransferSearch represents the model behind the search form about `app\models\Transfer`.
 */
class TransferSearch extends \app\models\Transfer
{
    public $acceptorName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date_create'], 'integer'],
            [['amount'], 'number'],
            [['acceptor', 'acceptorName'], 'safe'],
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
        $query = Transfer::find()
            ->andWhere(['donor' => Yii::$app->user->id]);

        $query->joinWith(['acceptorUser' => function ($q) {
            $q->where('user.username LIKE "%' . $this->acceptorName . '%"');
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'attributes' => [
                    'id',
                    'date_create',
                    'amount',
                    'acceptorName' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['date_create' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date_create' => $this->date_create,
            'amount' => $this->amount,
        ]);

        //$query->andFilterWhere(['like', 'acceptor', $this->acceptor]);

        return $dataProvider;
    }
}
