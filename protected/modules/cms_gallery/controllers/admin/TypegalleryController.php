<?php

class TypegalleryController extends BackendController
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
                                          
      }  
      else
      {  
        $content=new Content();
        $content->root=$root_id;
        $content->type=$type;
      }
      
      $content->scenario='alias_req';               
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
        mb_parse_str($_POST['small'],$small_data);
        $parentID=$_POST['id'];
        $type=$_POST['type'];
        $content=new Content('alias_req');
        $content->attributes=$data['Content'];
        $content->attributes=$small_data['Content'];
        $content->type=$type;
        $parentNode=Content::model()->findByPk($parentID);
        if(empty($content->alias))
        {
          $content->alias=TypeText::createAlias($content->name);
        } 
                  
        if($content->validate('alias_req'))
        {
          $data['id']=$content->id;
          $data['type']=$content->type;
          $content->appendTo($parentNode);
          TypeGallery::saveGallery($data,$content->id);
          $data['status']=1;                     
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
        mb_parse_str($_POST['small'],$small_data);        
        $id=$_POST['id'];
        $type=$_POST['type'];
        $content=Content::model()->findByPk($id);
        $content->attributes=$data['Content'];
        $content->attributes=$small_data['Content'];        
        if(empty($content->alias))
        {                                      
          $content->alias=TypeText::createAlias($content->name);
        }  
                          
        if($content->validate('alias_req'))
        {
          $content->saveNode();
          $data['id']=$content->id;         
          $data['type']=$content->type;
          TypeGallery::saveGallery($data,$content->id);
          $data['status']=1;                              
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
      if(isset($_POST['ajax']) && $_POST['ajax']==='gallery-form')
      {       
          $content=new Content('alias_req');
          $content->attributes=$_POST['Content'];
          echo CActiveForm::validate($content);
          Yii::app()->end();
      }
    }                                             
    Yii::app()->end();       
  }  

  public function actionAjaxAlias()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['id']) && $_POST['id']>=0)
        $data['alias']=Content::createAlias($_POST['name'],$_POST['id']);
      else   
         $data['alias']=Content::createAlias($_POST['name']);
      $data['status']=1;
      echo CJSON::encode($data); 
    }                                             
    Yii::app()->end();       
  }
  
  public function actionUploadImage()
  {
    Yii::app()->session->sessionID = $_POST['PHPSESSID'];
    Yii::app()->session->init();   

    Common::clearTmp();
 
    $file=CUploadedFile::getInstanceByName('Filedata');
    $dir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
    //$tmp=uniqid();
    $tmp=uniqid().'_'.str_replace(' ','_',$file->name);  
    $newFile=$dir.$tmp;
    $prevFile=$dir.'prev_'.$tmp;
    $file->saveAs($newFile);
    $ext=strtolower($file->extensionName);
    
    Yii::import('cms_core.extensions.imagemodifier.upload'); 
    $image = new upload($newFile);
    $image->image_resize = true;
    //$image->image_ratio_crop = true;
    $image->image_x = 185;                
    $image->image_y = 136;         
     
    if(isset($_POST['module']))
    {
      $params='';
      if(isset(Yii::app()->getModule($_POST['module'])->params['admin_image']))
        $params=Yii::app()->getModule($_POST['module'])->params['admin_image'];
      elseif(isset($_POST['template_name']) && isset(Yii::app()->getModule($_POST['module'])->params[$_POST['template_name']]['admin_image']))  
        $params=Yii::app()->getModule($_POST['module'])->params[$_POST['template_name']]['admin_image'];  
                 
      if($params<>'')
      {
        $image->image_x = $params['width'];                
        $image->image_y = $params['height'];
      }        
    }    
    
    $image->image_ratio_crop = true;       
    $image->jpeg_quality = 70;           
    $image->file_overwrite = true;
    $image->file_auto_rename = false;
    $image->auto_create_dir = true;      
    $image->dir_auto_chmod = true;          
    //$image->image_convert = 'jpg';
    //$image->file_new_name_body = 'prev_'.$tmp;   
    $image->file_name_body_pre = 'prev_';           
    $image->Process($dir);    

    echo $tmp;
    Yii::app()->end();
  }   
}   

