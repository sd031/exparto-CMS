<?php

class TypephptemplateController extends BackendController
{
  
  public function actionIndex()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      $id=$_POST['id']; 
      $type=$_POST['type'];
      $is_edit=$_POST['edit'];
      $root_id=$_POST['root_id']; 

      $mcontent=CmsModule::loadModule('cms_content');
      $typeName=$mcontent->types->getName($type);
      
      if($is_edit==true)
      { 
        $content=Content::model()->findByPk($id);
        if($content->external_id>=0)
        {
          $tpl=TypePhpTemplate::model()->findByPk($content->external_id);
          if(!is_object($tpl))
          {
            $tpl=new TypeText;
          }            
        }          
      }  
      else
      {  
        $content=new Content();
        $content->root=$root_id;
        $content->type=$type;
        $tpl=new TypePhpTemplate();
      }
                  
      $data['small']=$this->renderPartial('small', 
                                          array(
                                            'content'=>$content
                                          ), 
                                          true, 
                                          true);   
      Yii::app()->getClientScript()->reset();                                                        
      $data['main']=$this->renderPartial('main', 
                                        array(
                                          'typeName'=>$typeName,
                                          'content'=>$content,
                                          'tpl'=>$tpl,
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
      $data['status']=0; 
      if(isset($_POST['main'],$_POST['id'],$_POST['type']))
      {
        mb_parse_str($_POST['main'],$data);
        //mb_parse_str($_POST['small'],$small_data);        
        $parentID=$_POST['id'];
        $type=$_POST['type'];
        $content=new Content;
        $content->attributes=$data['Content']; 
        //$content->attributes=$small_data['Content'];        
        $content->type=$type;
        $content->is_visible=0;
        $content->is_active=0;
        $parentNode=Content::model()->findByPk($parentID);
        
        if($content->validate())
        {
          $data['id']=$content->id;
          $data['type']=$content->type;
          $tpl=new TypePhpTemplate;
          $tpl->attributes=$data['TypePhpTemplate'];

          if($tpl->save())
          {
            $content->external_id=$tpl->id;
            $content->appendTo($parentNode);
            $data['status']=1;            
          }            
        }                  
      }
      echo CJSON::encode($data);  
    }        
                                            
    Yii::app()->end();   
  }  
  
  public function actionUpdate()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {
      $data['status']=0;      
      if(isset($_POST['main'],$_POST['id'],$_POST['type']))
      {
        mb_parse_str($_POST['main'],$data);
        //mb_parse_str($_POST['small'],$small_data);          
        $id=$_POST['id'];
        $type=$_POST['type'];
        $content=Content::model()->findByPk($id);
        $content->attributes=$data['Content'];
        //$content->attributes=$small_data['Content'];        
                
        if($content->external_id>=0)
        {
          $data['status']=1;
          $content->saveNode();
          $data['id']=$content->id;         
          $data['type']=$content->type;
          $tpl=TypePhpTemplate::model()->findByPk($content->external_id);
          $tpl->attributes=$data['TypePhpTemplate'];
           
          $tpl->update();                       
        }                     
      }
      echo CJSON::encode($data);  
    }         
                                            
    Yii::app()->end();   
  }   
  
  public function actionAjaxValidation()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['ajax']) && $_POST['ajax']==='text-form')
      {       
          $content=new Content('alias_req');
          $content->attributes=$_POST['Content'];
          $tpl=new TypePhpTemplate;
          $tpl->attributes=$_POST['TypeText'];
          echo CActiveForm::validate(array($content,$tpl));
          Yii::app()->end();
      }
    }                                             
    Yii::app()->end();       
  }  

}   