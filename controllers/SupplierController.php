<?php

namespace app\controllers;

use Yii;
use app\models\Supplier;
use app\models\SupplierSearch;

class SupplierController extends SiteController
{
    public function actionIndex()
    {
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $sign=Yii::$app->request->get('sign');
        return $this->render('index',
        [
            "dataProvider"=>$dataProvider,
            "searchModel"=>$searchModel,
            "sign"=>$sign,
            "condition"=>$searchModel->condition
        ]);
    }

    public function actionExport(){
        $searchModel = new SupplierSearch();
        $labelmap = $searchModel->attributeLabels();
        $searchModel->getList(Yii::$app->request->get());
        $data = $searchModel->query->asArray()->all();
        if($data){
            $fileName = date('YmdHis').'.csv';
            header('Content-Type:text/csv');
            header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
            header('Content-Disposition:attachment;filename="' . $fileName . '"');
            header('Expires:0');
            header('Pragma:public');
            $heade = [];
            foreach ($data[0] as $key => $value) {
                $heade[] = $labelmap[$key];
            }

            mb_convert_variables('GBK', 'UTF-8', $heade);
            echo implode(',', $heade)."\n";
            foreach ($data as $line) {
                mb_convert_variables('GBK', 'UTF-8', $line);
                echo implode(',', $line)."\n";
                unset($line);
            }
        }
        exit;
    }
}
