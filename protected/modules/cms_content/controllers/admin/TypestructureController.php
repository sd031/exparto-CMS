<?php

class TypestructureController extends BackendController
{
  public function actionIndex()
  {
    if (Yii::app()->request->isAjaxRequest) 
    { 
      $id=$_POST['id'];  
      $type=$_POST['type'];
      $is_edit=$_POST['edit'];
      $lng=isset($_POST['lng'])?$_POST['lng']:false;

      $mcontent=CmsModule::loadModule('cms_content');
      $typeName=$mcontent->types->getName($type);

      if($is_edit==true) 
      {
        $model=Content::model()->findByPk($id); 
        $menu=StructureMenu::model()->findAll(array('condition'=>'root_id='.$model->root,'order'=>'is_default desc'));             
      }  
      else
      {  
        $model=new Content;
        $model->type=$type;
        if($lng) $model->lang_code=$lng;
        $menu=false;
      }  

      $data['small']=$this->renderPartial('small', 
                                        array(
                                          'content'=>$model,
                                        ), 
                                        true, 
                                        true);    
      
      Yii::app()->getClientScript()->reset();      
      $data['main']=$this->renderPartial('main', 
                                        array(
                                          'typeName'=>$typeName,
                                          'content'=>$model,
                                          'id'=>$id,
                                          'lng'=>$lng,
                                          'menu'=>$menu,                                          
                                        ), 
                                        true, 
                                        true);
      
      $data['status']=1;     
      echo CJSON::encode($data); 
    }                                           
    Yii::app()->end();     
  }

  public function saveMenu($root_id)
  {
    if(isset($_POST['main']))
    {
      mb_parse_str($_POST['main'],$data);
      //print_r($data['menu']);
      // die();
      if(isset($data['menu']))
      {
        foreach($data['menu'] as $id=>$item)
        {    
          if(trim($item['name'])<>'' && trim($item['description'])<>'')
          {
          
            if($item['id']>=0)    
              $menu=StructureMenu::model()->findByPk($item['id']);          
            else      
              $menu=new StructureMenu;
  
            if($item['is_default']==1)        
              StructureMenu::model()->updateAll(array('is_default'=>0),array('condition'=>'root_id='.$root_id));               
  
            $menu->name=$item['name'];
            $menu->description=$item['description'];
            $menu->is_visible=$item['is_visible'];
            $menu->is_default=$item['is_default'];
            $menu->root_id=$root_id;            
            $menu->save();                      
          }   
        }
      }
      
      if(isset($data['del']))
      {
        foreach($data['del'] as $id)
        {
          $rec=StructureMenu::model()->findByPk($id);
          $rec->delete();              
        }
      }      
    }
  }
  
  public function actionCreate()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {     
      if(isset($_POST['main'],$_POST['type']))
      {
        mb_parse_str($_POST['main'],$data);
        mb_parse_str($_POST['small'],$small_data);
        $type=$_POST['type'];
        $model=new Content;
        $model->attributes=$data['Content'];
        $model->attributes=$small_data['Content'];        
        $model->type=$type;
        
        if($model->validate())
        {
          if($model->is_default==1)     
            Content::model()->roots()->updateAll(array('is_default'=>0),"lang_code='".$model->lang_code."'"); 
          else 
          {
             $count=Content::model()->roots()->countByAttributes(array('is_default'=>1,'lang_code'=>$model->lang_code));
             if($count==0) $model->is_default=1;  
          }                     
          $data['status']=1;
          $model->saveNode();
          $this->saveMenu($model->root);
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
      if(isset($_POST['main'],$_POST['type']))
      {
        mb_parse_str($_POST['main'],$data);
        mb_parse_str($_POST['small'],$small_data);        
        $type=$_POST['type'];
        $id=$_POST['id'];
        $model=Content::model()->findByPk($id);
        $model->attributes=$data['Content'];
        $model->attributes=$small_data['Content'];         
        if($model->validate())
        {
          if($model->is_default==1)     
            Content::model()->roots()->updateAll(array('is_default'=>0),"lang_code='".$model->lang_code."'"); 
          else 
          {
             $count=Content::model()->roots()->countByAttributes(array('is_default'=>1,'lang_code'=>$model->lang_code),'id<>'.$model->id);
             if($count==0) $model->is_default=1;  
          } 
          $data['status']=1;
          $model->saveNode();
          $this->saveMenu($model->root);
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
      if(isset($_POST['ajax']) && $_POST['ajax']==='structure-form')
      {
          
          $model=new Content;
          $model->attributes=$_POST['Content'];
          echo CActiveForm::validate($model);
          Yii::app()->end();
      }
    }                                             
    Yii::app()->end();       
  }
  
}