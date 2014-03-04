<?php

class TypeseperatorController extends BackendController
{
  public function actionIndex()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      $id=$_POST['id']; 
      if($id>0) 
      {
        $type=$_POST['type']; 
        $model=new Content;
        $model->type=$type;
        $mcontent=CmsModule::loadModule('cms_content');
        $typeName=$mcontent->types->getName($type);
        $model->name=$typeName;
        $parent=Content::model()->findByPk($id);
        $model->appendTo($parent);
  
        $data['main']='';
      }
      $data['status']=0;
      echo CJSON::encode($data); 
    }                                           
    Yii::app()->end();     
  }

}