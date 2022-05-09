<?php

namespace app\models;

use Yii;


class Supplier extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'supplier';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'code' => '编号',
            't_status' => '状态',
        ];
    }
}
