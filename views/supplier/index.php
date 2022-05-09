<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
?>
<div class="row">
    <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">供应商管理</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">   
             <?php
                    $get = Yii::$app->request->get();
                    if(isset($get['SupplierSearch'])){
                        $get = $get['SupplierSearch'];
                    }else{
                        $get = ['id'=>''];
                    }
                    $id_condition = isset(Yii::$app->request->get()['id_condition'])?Yii::$app->request->get()['id_condition']:0;
                    echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'emptyText'=>'无匹配的数据',
                            'emptyTextOptions'=>['style'=>'color:red;font-weight:bold'],
                            'rowOptions'=>function($model){
                                return ['id'=>"tr-".$model->id];
                            },  
                            'showFooter'=>true,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\CheckboxColumn',
                                    'footerOptions'=>['colspan'=>4],
                                    'footer'=>'<span id="showText"></span> <a href="javascript:;" id="select_other" style="display:none">选择所有<a> <button class="_export_all btn btn-success disabled" data-url="'.Yii::$app->urlManager->createUrl(['/supplier/export']).'">导出所选</button>',
                                    
                                ], 
                                [
                                    'label'=>'ID',
                                    'attribute'=>'id',
                                    'filter' =>Html::dropDownList('id_condition',$id_condition,$condition,['class'=>'form-control','style'=>'width:65px;display: inline-block;']).Html::textInput('SupplierSearch[id]',$get['id'],['class'=>'form-control','style'=>'width:80px; display: inline-block;']),
                                    'footerOptions'=>['style'=>'display:none'],
                                ], 
                                [
                                    'label'=>'编号',
                                    'attribute'=>'code',
                                    'footerOptions'=>['style'=>'display:none'],
                                ],
                                [
                                    'label'=>'名称',
                                    'attribute'=>'name',
                                    'footerOptions'=>['style'=>'display:none'],
                                ],
                                [
                                    'label'=>'状态',
                                    'attribute'=>'t_status',
                                    'filter' =>['ok'=>'ok','hold'=>'hold'],
                                    'footerOptions'=>['style'=>'display:none'],
                                ]
                            ]
                     ]);
                    ?>
          <!-- /.box -->
        </div>
</div>

<?php 
$this->registerCss('
.box-body .table-bordered .select_bg{ background:#BCC8D0;}
');
$this->registerJs('
    var select_other_item = false;
    $("input[name=\'selection[]\']").click(function(){
         var id=$(this).val();
         if($("#tr-"+id).hasClass("select_bg")){
            $("#tr-"+id).removeClass("select_bg");    
         }else{
            $("#tr-"+id).addClass("select_bg");
         }   
    });
    $(".select-on-check-all").change(function(){
        if($(this).prop("checked")){
            $("#select_other").show();
            $("#showText").html("本页面所有 "+$("input[name=\'selection[]\']").length+" 项被选择");
        }else{
            $("#select_other").hide();
            $("#showText").html("");
            select_other_item = false;
            $("#select_other").text("选择所有");
        }
    });
    $("#select_other").click(function(){
        if(select_other_item){
            select_other_item = false;
            $(this).text("选择所有");
        }else{
            select_other_item = true;
            $(this).text("取消选择所有");
        }
    })
    $("input[name=\'selection[]\']").change(function(){
        if($("input[name=\'selection[]\']:checked").length == 0){
            if(!$("._export_all").hasClass("disabled")){
                $("._export_all").addClass("disabled")
            }
        }else{
            if($("._export_all").hasClass("disabled")){
                $("._export_all").removeClass("disabled")
            }
        }
    });
    $("._export_all").click(function(){
        if(!$(this).hasClass("disabled")){
            var url = $(this).data("url");
            var data = {};
            if(select_other_item){
                $(".form-control").each(function(){
                    data[$(this).attr("name")] = $(this).val();
                })
            }else{
                data["ids"] = [];
                $("input[name=\'selection[]\']:checked").each(function(){
                    data["ids"].push($(this).val())
                });

            }
            window.open(url+"&"+$.param(data),"_blank");
        }
    });
');
?>
