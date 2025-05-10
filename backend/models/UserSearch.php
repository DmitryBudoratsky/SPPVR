<?php

namespace backend\models;

use common\components\helpers\TimeHelper;
use yii\data\ActiveDataProvider;
use common\models\db\User;

/**
 * UserSearch represents the model behind the search form about `common\models\db\User`.
 */
class UserSearch extends User
{
    public $createdAtRange;

    const SCENARIO_BLOCKED_USERS = 'blocked-users';
	
	public function init()
	{
		parent::init();
		
		$this->status = null;
		$this->role = null;
	}
		
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'integer'],
			[['status', 'role'], 'default'],
            [['name', 'lastname', 'passwordHash', 'login', 'email', 'createdAt', 'updatedAt'], 'safe'],
            ['createdAtRange', 'string'],
        ];
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
        $query = User::find();
        
        if ($this->scenario == self::SCENARIO_BLOCKED_USERS) {
        	$query->andWhere(['status' => User::STATUS_BLOCKED]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'sort'=> ['defaultOrder' => ['userId' => SORT_DESC]]
        ]);

        $this->load($params);

        if(!empty($this->createdAtRange) && strpos($this->createdAtRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->createdAtRange);
            $query->andFilterWhere(['between', 'createdAt', strtotime($start_date), strtotime($end_date) + TimeHelper::getSecondsFromDays(1)]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'userId' => $this->userId,
            'status' => $this->status,
            'role' => $this->role
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'passwordHash', $this->passwordHash])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
