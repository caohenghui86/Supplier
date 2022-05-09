<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class SupplierSearch extends Supplier
{
    public $condition = ['=','>','>=','<','<='];
    public $query = null;
    public function rules()
    {
        return [
            [['id','name', 'code', 't_status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {   
        
        $this->getList($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => [
                    'pageSize' => 10,
                ],
        ]);

        return $dataProvider;
    }

    public function getList($params){
        if(empty($this->query)){
            $this->query = Supplier::find();
        }
        if(!isset($params['sort'])){
            $this->query->orderBy('id desc');
        }
        $ids = isset($params['ids'])?$params['ids']:null;
        if($ids){
            if(count($ids)>0){
                $this->query->andFilterWhere(['in', 'id', $ids]);
            }
        }else{
            // 从参数的数据中加载过滤条件，并验证
            if (!($this->load($params) && $this->validate())) {
                return false;
            }
            $id_condition = isset($params['id_condition'])?$params['id_condition']:0;
            $condition = '=';
            if(isset($this->condition[$id_condition])){
                $condition = $this->condition[$id_condition];
            }
            // 增加过滤条件来调整查询对象
            $this->query->andFilterWhere(['like', 'name', $this->name])
                  ->andFilterWhere(['like', 'code', $this->code])
                  ->andFilterWhere([$condition, 'id', $this->id])
                  ->andFilterWhere(['=', 't_status', $this->t_status]);
        }
        
    }
}
