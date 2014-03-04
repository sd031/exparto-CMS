<?php

class TypetextController extends BackendController
{
  
  public function actionIndex()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      $id=$_POST['id']; 
      $root_id=$_POST['root_id']; 
      $type=$_POST['type'];
      $is_edit=$_POST['edit'];

      $mcontent=CmsModule::loadModule('cms_content');
      $typeName=$mcontent->types->getName($type);
      
      if($is_edit==true)
      { 
        $content=Content::model()->findByPk($id);
        if($content->external_id>=0)
        {
          $text=TypeText::model()->findByPk($content->external_id);
          if(!is_object($text))
          {
            $text=new TypeText;
          }            
        }                  
      }  
      else
      {  
        $content=new Content();
        $content->root=$root_id;
        $content->type=$type;
        $text=new TypeText();
        
        $templates=$content->templateOptions();  
        
        $parent_tpl=Content::model()->findByPk($id)->template_name;
        
        if(!empty($parent_tpl) && in_array($parent_tpl,$templates))
        {
           $content->template_name=$parent_tpl;
        }
                 
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
                                          'text'=>$text,
                                          'id'=>$id,
                                        ), 
                                        true, 
                                        true);

      $data['status']=1;     
      echo CJSON::encode($data); 
    }                                           
    Yii::app()->end();     
  }
  
  public function actionImgSave($text)
  {
    mb_parse_str($_POST['main'],$rez);
    Yii::import('cms_core.extensions.imagemodifier.upload');                  
    if(isset($rez['image_logo']) && $rez['image_logo']<>'')
    {            
      $tmpdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR; 
      $imgdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'text';            
      $newFile=$tmpdir.$rez['image_logo'].'.'.'jpg';
      
      $image = new upload($newFile);

      //original resize (no zoom)
      $image->image_resize = true;
      $image->image_ratio = true;
      $image->image_x = 800;  //width
      $image->image_y = 600; //height           
      $image->auto_create_dir = true;      
      $image->dir_auto_chmod = true;  
      $image->image_convert = 'jpg';    
      $image->jpeg_quality = 100;  
      $image->file_overwrite = true;
      $image->file_auto_rename = false;
      $image->image_convert = 'jpg'; 
      $image->allowed = array('image/*');    
      $image->image_ratio_no_zoom_in = true;          
      $image->file_new_name_body = $rez['image_logo'];    
      $image->Process($imgdir.DIRECTORY_SEPARATOR);

      $image->clean();
                  
      $text->image_file_logo=$rez['image_logo'];  
      $text->update();                                                    
    }    
    
    if(isset($rez['image_prod']) && $rez['image_prod']<>'')
    {            
      $tmpdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR; 
      $imgdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'text';            
      $newFile=$tmpdir.$rez['image_prod'].'.'.'jpg';
      
      $image = new upload($newFile);

      //original resize (no zoom)
      $image->image_resize = true;
      $image->image_ratio = true;
      $image->image_x = 800;  //width
      $image->image_y = 600; //height           
      $image->auto_create_dir = true;      
      $image->dir_auto_chmod = true;  
      $image->image_convert = 'jpg';    
      $image->jpeg_quality = 100;  
      $image->file_overwrite = true;
      $image->file_auto_rename = false;
      $image->image_convert = 'jpg'; 
      $image->allowed = array('image/*');    
      $image->image_ratio_no_zoom_in = true;          
      $image->file_new_name_body = $rez['image_prod'];    
      $image->Process($imgdir.DIRECTORY_SEPARATOR);

      $image->clean();
                  
      $text->image_file_prod=$rez['image_prod'];  
      $text->update();                                                    
    }        
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
          $content->alias=Content::createAlias($content->name);
        
        if($content->validate())
        {
          $text=new TypeText;
          $text->attributes=$data['TypeText'];
          $text->text=Common::html_fixImageResizes($text->text);
          if($text->save())
          {
            $this->actionImgSave($text);
            TypeGallery::saveGallery($data,$content->id,'text');  
            $content->external_id=$text->id;
            $content->appendTo($parentNode);
            $data['id']=$content->id;
            $data['type']=$content->type;            
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
        mb_parse_str($_POST['small'],$small_data);        
        $id=$_POST['id'];
        $type=$_POST['type'];
        $content=Content::model()->findByPk($id);
        $content->attributes=$data['Content'];
        $content->attributes=$small_data['Content'];        
                
        if(empty($content->alias))                                     
          $content->alias=Content::createAlias($content->name);  
                          
        if($content->validate('alias_req') && $content->external_id>=0)
        {
          TypeGallery::saveGallery($data,$content->id,'text'); 
          $data['status']=1;
          $content->saveNode();
          $data['id']=$content->id;         
          $data['type']=$content->type;
          $text=TypeText::model()->findByPk($content->external_id);          
          $text->attributes=$data['TypeText'];          
          $text->text=Common::html_fixImageResizes($text->text);
          $this->actionImgSave($text);          
          $text->update();                       
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
          $text=new TypeText;
          $text->attributes=$_POST['TypeText'];
          echo CActiveForm::validate(array($content,$text));
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
    $tmp=uniqid();  
    $newFile=$dir.$tmp.'.'.'jpg';
    $prevFile=$dir.'prev_'.$tmp.'.'.'jpg';
    $file->saveAs($newFile);
      
    //Yii::import('cms_core.extensions.image.Image');
    //$image = new Image($newFile);
    //$image->resize(270, 184, Image::WIDTH)->crop(270, 184)->quality(75);
    //$image->save($prevFile);
    Yii::import('cms_core.extensions.imagemodifier.upload'); 
    $image = new upload($newFile);
    $image->image_resize = true;
    $image->image_ratio_crop = true;
    $image->image_x = 180;                
    $image->image_y = 175;    
      
    $image->jpeg_quality = 60;           
    $image->file_overwrite = true;
    $image->file_auto_rename = false;
    $image->auto_create_dir = true;      
    $image->dir_auto_chmod = true;          
    $image->image_convert = 'jpg';
    $image->file_new_name_body = 'prev_'.$tmp;              
    $image->Process($dir);    
    
    //Yii::app()->request->getBaseUrl().'/tmp/'.'prev_'.$tmp.'.'.
    echo $tmp;
  }

  public function actionUploadImage2()
  {
    Yii::app()->session->sessionID = $_POST['PHPSESSID'];
    Yii::app()->session->init();   

    Common::clearTmp();
 
    $file=CUploadedFile::getInstanceByName('Filedata');
    $dir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
    $tmp=uniqid();  
    $newFile=$dir.$tmp.'.'.'jpg';
    $prevFile=$dir.'prev_'.$tmp.'.'.'jpg';
    $file->saveAs($newFile);
      
    //Yii::import('cms_core.extensions.image.Image');
    //$image = new Image($newFile);
    //$image->resize(270, 184, Image::WIDTH)->crop(270, 184)->quality(75);
    //$image->save($prevFile);
    Yii::import('cms_core.extensions.imagemodifier.upload'); 
    $image = new upload($newFile);
    $image->image_resize = true;
    $image->image_ratio_crop = true;
    $image->image_x = 250;                
    $image->image_y = 185;    
     
    $image->jpeg_quality = 60;           
    $image->file_overwrite = true;
    $image->file_auto_rename = false;
    $image->auto_create_dir = true;      
    $image->dir_auto_chmod = true;          
    $image->image_convert = 'jpg';
    $image->file_new_name_body = 'prev_'.$tmp;              
    $image->Process($dir);    
    
    //Yii::app()->request->getBaseUrl().'/tmp/'.'prev_'.$tmp.'.'.
    echo $tmp;
  }
  
}   