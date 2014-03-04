<?php

class TypelinkController extends BackendController
{
  
  public function actionIndex()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      $id=$_POST['id']; 
      $type=$_POST['type'];
      $root_id=$_POST['root_id'];
      $is_edit=$_POST['edit'];

      $mcontent=CmsModule::loadModule('cms_content');
      $typeName=$mcontent->types->getName($type);
      
      if($is_edit==true) 
      {
        $model=Content::model()->findByPk($id);
               
      }  
      else
      {  
        $model=new Content;
        $model->root=$root_id;
        $model->type=$type;
      }
        
      $data['small']=$this->renderPartial('small', 
                                          array(
                                            'content'=>$model
                                          ), 
                                          true, 
                                          true);   
      Yii::app()->getClientScript()->reset();                                                        
      $data['main']=$this->renderPartial('main', 
                                        array(
                                          'typeName'=>$typeName,
                                          'model'=>$model,
                                          'id'=>$id,
                                        ), 
                                        true, 
                                        true);

      $data['status']=1;     
      echo CJSON::encode($data); 
    }                                           
    Yii::app()->end();     
  }
  
  public function actionCreate()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {     
      if(isset($_POST['main'],$_POST['id'],$_POST['type']))
      {
        mb_parse_str($_POST['main'],$data);
        mb_parse_str($_POST['small'],$small_data);
        $parentID=$_POST['id'];
        $type=$_POST['type'];
        $model=new Content('alias');           
        $model->attributes=$data['Content'];
        $model->attributes=$small_data['Content'];
        $model->type=$type;
        $parentNode=Content::model()->findByPk($parentID);
 
        if($model->validate())
        {
          $data['status']=1;
          $model->appendTo($parentNode);
          $data['id']=$model->id;
          $data['type']=$model->type;
        }  
        else
          $data['status']=0;                    
      }
    } else 
       $data['status']=0;      
      
      echo CJSON::encode($data);                                        
    Yii::app()->end();   
  }  
  
  public function actionUpdate()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {     
      if(isset($_POST['main'],$_POST['id'],$_POST['type']))
      {
        mb_parse_str($_POST['main'],$data);
        mb_parse_str($_POST['small'],$small_data);
        $id=$_POST['id'];
        $type=$_POST['type'];
        $model=Content::model()->findByPk($id);
        $model->attributes=$data['Content'];
        $model->attributes=$small_data['Content'];
        if($model->validate('alias'))
        {
          $data['status']=1;
          $model->saveNode();
          $data['id']=$model->id;
          $data['type']=$model->type;
        }  
        else
          $data['status']=0;                    
      }
    } else 
       $data['status']=0;      
      
      echo CJSON::encode($data);                                        
    Yii::app()->end();   
  }   
  
  public function actionAjaxValidation()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['ajax']) && $_POST['ajax']==='section-form')
      {       
          $model=new Content('alias_req');
          $model->attributes=$_POST['Content'];
          echo CActiveForm::validate($model);
          Yii::app()->end();
      }
    }                                             
    Yii::app()->end();       
  }  

}