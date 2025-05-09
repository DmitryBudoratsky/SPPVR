<?php

namespace backend\models;

use common\components\helpers\TimeHelper;
use common\models\db\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\Chat;

/**
 * ChatSearch represents the model behind the search form of `common\models\db\Chat`.
 */
class ChatSearch extends Chat
{
	public $createdAtRange;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chatId', 'isHidden', 'messageCount'], 'integer'],
        	[['updatedAt', 'createdAtRange', 'title', 'type'], 'safe'],
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
        $query = Chat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'chatId' => $this->chatId,
        	'type' => $this->type,
            'isHidden' => $this->isHidden,
            'updatedAt' => $this->updatedAt,
        	'messageCount' => $this->messageCount
        ]);
        
        if ($this->type == Chat::TYPE_PERSONAL_CHAT) {
            if (!empty($this->title)) {
                $userSubQuery = User::find()->select('user.userId')
                    ->andWhere(['like', 'user.name',  $this->title])
                    ->andWhere(['status' => User::STATUS_ACTIVE])
                    ->groupBy('user.userId');
                $query->joinWith(['chatMembers'])
                    ->andWhere(['in', 'chatMember.userId', $userSubQuery]);
            }
        } else {
            $query->andFilterWhere(['like', 'title', $this->title]);
        }

        $query->groupBy('chat.chatId');

        return $dataProvider;
    }
}
