<?php

namespace backend\models;

use backend\compenents\helpers\HtmNumberMaskHelper;
use backend\compenents\helpers\SnilsHelper;
use common\components\helpers\TimeHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\Incident;

/**
 * IncidentSearch represents the model behind the search form of `common\models\db\Incident`.
 */
class IncidentSearch extends Incident
{
    public $createdAtRange;
    public $birthDateRange;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['incidentId', 'status', 'birthDate', 'chatId', 'createdAt', 'updatedAt'], 'integer'],
            [['patientName', 'address', 'anamnesis', 'policy', 'snils', 'createdAtRange', 'birthDateRange'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Incident::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->createdAtRange) && strpos($this->createdAtRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->createdAtRange);
            $query->andFilterWhere(['between', 'createdAt', strtotime($start_date), strtotime($end_date) + TimeHelper::getSecondsFromDays(1)]);
        }

        if (!empty($this->birthDateRange) && strpos($this->birthDateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->birthDateRange);
            $query->andFilterWhere(['between', 'birthDate', strtotime($start_date), strtotime($end_date) + TimeHelper::getSecondsFromDays(1)]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'incidentId' => $this->incidentId,
            'status' => $this->status,
            'birthDate' => $this->birthDate,
            'chatId' => $this->chatId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'policy', $this->policy])
            ->andFilterWhere(['like', 'snils', $this->snils])
            ->andFilterWhere(['like', 'patientName', $this->patientName])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'anamnesis', $this->anamnesis]);

        return $dataProvider;
    }
}
