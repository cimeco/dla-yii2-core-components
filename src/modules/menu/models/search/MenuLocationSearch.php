<?php

namespace quoma\core\modules\menu\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use quoma\core\modules\menu\models\MenuLocation;

/**
 * MenuLocationSearch represents the model behind the search form about `common\modules\menu\models\MenuLocation`.
 */
class MenuLocationSearch extends MenuLocation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_location_id', 'menu_id'], 'integer'],
            [['name', 'description', 'slug'], 'safe'],
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
        $query = MenuLocation::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'menu_location_id' => $this->menu_location_id,
            'menu_id' => $this->menu_id,
            'site_id' => $this->site_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
