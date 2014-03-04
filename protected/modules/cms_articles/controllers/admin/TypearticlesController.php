<?php

class TypearticlesController extends BackendController
{
  
  public function actionIndex()
  {
    if(isset($_GET['ajax']))
    {
     	$articles=new TypeArticles('search');
  		if(isset($_GET['TypeArticles']))
      		$articles->attributes =$_GET['TypeArticles'];       
      $this->renderPartial('_catgrid', array('articles'=>$articles));
    } else 
    {  
      if (Yii::app()->request->isAjaxRequest) 
      {      	         
        $id=$_POST['id']; 
        $type=$_POST['type'];
        $is_edit=$_POST['edit'];

  
        $mcontent=CmsModule::loadModule('cms_content');
        $typeName=$mcontent->types->getName($type);
        
        if($is_edit==true)
        { 
          $content=Content::model()->findByPk($id);              
          $articles=new TypeArticles('search');      
        	$articles->content_id=$content->id;                
          $data['small']=$this->renderPartial('small_main', 
                                            array(
                                              'content'=>$content
                                            ), 
                                            true, 
                                            true);                  
        }  
        else
        {  
          $content=new Content();
          $root_id=$_POST['root_id']; 
          $content->root=$root_id;;
          $content->type=$type;
          $data['small']=$this->renderPartial('small_cat', 
                                              array(
                                                'content'=>$content
                                              ), 
                                              true, 
                                              true);           
          $articles=false;
        }
        
        Yii::app()->getClientScript()->reset();
        $content->scenario='alias_req';               
        $data['main']=$this->renderPartial('main', 
                                          array(
                                            'typeName'=>$typeName,
                                            'content'=>$content,
                                            'articles'=>$articles,
                                            'id'=>$id,
                                          ), 
                                          true, 
                                          true);
       
        $data['status']=1;     
        echo CJSON::encode($data); 
      }                         
                        
      Yii::app()->end();     
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
        if($content->validate())
        {
          $data['id']=$content->id;
          $data['type']=$content->type;        
          $content->appendTo($parentNode);  
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
        if($content->validate('alias_req'))
        {
          $data['status']=1;
          $content->saveNode();
          $data['id']=$content->id;         
          $data['type']=$content->type;
        }                     
      }
      echo CJSON::encode($data);  
    }         
                                            
    Yii::app()->end();   
  }   
  
  public function actionCatAjaxValidation()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
      {       
          $content=new Content('alias_req');
          $content->attributes=$_POST['Content'];
          echo CActiveForm::validate($content);
          Yii::app()->end();
      }
    }                                             
    Yii::app()->end();       
  }  
  
  public function actionArticleAjaxValidation()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['ajax']) && $_POST['ajax']==='article-form')
      {       
          $article=new TypeArticles;
          $article->attributes=$_POST['TypeArticles'];
          echo CActiveForm::validate($article);
          Yii::app()->end();
      }
    }                                             
    Yii::app()->end();       
  }  
  

  public function actionArticleIndex()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      $id=isset($_POST['id'])?$_POST['id']:-1; 
      $is_edit=$_POST['edit'];
      
      if($is_edit==true)
      { 
        $article_id=isset($_POST['article_id'])?$_POST['article_id']:-1; 
        $article=TypeArticles::model()->findByPk($article_id);      
      }  
      else
      {  
        $article=new TypeArticles;
        $article->content_id=$id;   
      }

      if(empty($article->start_date))
        $article->start_date=date('Y-m-d H:i:s'); 

      if(empty($this->finish_date))
        $article->finish_date='0000-00-00 00:00:00';  
            
      $content=Content::model()->findByPk($id);
                    
      $data['result']=$this->renderPartial('_article',array('article'=>$article,'content'=>$content,'id'=>$id),true,true);
      Yii::app()->getClientScript()->reset();
      $data['small']=$this->renderPartial('small_article',array('article'=>$article,'content'=>$content),true,true);
     
      $data['status']=1;     
      echo CJSON::encode($data); 
    }                                           
    Yii::app()->end();     
  }    
  
  public function actionArticleSave($action)
  {
    if (Yii::app()->request->isAjaxRequest) 
    {    
      $data['status']=0; 
      
      if(isset($_POST['form']))
      {     
        mb_parse_str($_POST['form'],$rez);
        mb_parse_str($_POST['small'],$small);
        if($action=='update')
        {
          $article=TypeArticles::model()->findByPk($rez['TypeArticles']['id']);
        }  
        else  
          $article=new TypeArticles;
        
        $article->attributes=$rez['TypeArticles'];
        $article->attributes=$small['TypeArticles'];
        if(empty($article->alias))
          $article->genAlias;
        $article->status=TypeArticles::STATUS_PUBLISHED;      
        $article->text=Common::html_fixImageResizes($article->text);
                     
        if($article->save())
        {
          TypeGallery::saveGallery($rez,$article->id,'articles');                     
          //Yii::app()->cache->delete('front_articles');
          Yii::import('cms_core.extensions.imagemodifier.upload');                  
          if(isset($rez['image']) && $rez['image']<>'')
          {            
            $tmpdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR; 
            $imgdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'articles';            
            $newFile=$tmpdir.$rez['image'].'.'.'jpg';
            
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
            $image->file_new_name_body = $rez['image'];    
            $image->Process($imgdir.DIRECTORY_SEPARATOR);

            $image->clean();
                        
            $article->image_file=$rez['image'];  
            $article->update();                                                    
          }                               
           
          $data['id']=$article->id;               
          $data['status']=1;
        }                  
      }
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
    $image->image_x = 201;                
    $image->image_y = 140;    
    if(isset($_POST['template_name']))
    {
      if(isset(Yii::app()->getModule('cms_articles')->params->images[$_POST['template_name'].'_admin']))
      {
        $params=Yii::app()->getModule('cms_articles')->params->images[$_POST['template_name'].'_admin'];
        $image->image_x = $params['width'];                
        $image->image_y = $params['height'];
      }  
    }        
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
  
  public function actionUploadGallery()
  {
    Yii::app()->session->sessionID = $_POST['PHPSESSID'];
    Yii::app()->session->init();   

    Common::clearTmp();
 
    $file=CUploadedFile::getInstanceByName('Filedata');
    $dir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
    $tmp=uniqid();  
    $newFile=$dir.$tmp.'.'.'jpg';
    $prevFile=$dir.'prev_'.$tmp.'.'.'jpg';
    $i=0;
    while(file_exists($newFile))
    {
      $i++;
      $filename=$fileinfo['filename'].$i.'.'.$fileinfo['extension'];
      $target = $attach_dir.'/'.$filename;   
    }       
    $file->saveAs($newFile);      
    //Yii::import('cms_core.extensions.image.Image');
    //$image = new Image($newFile);
    //$image->resize(134, 114, Image::WIDTH)->crop(134, 114)->quality(75);
    //$image->save($prevFile);
    Yii::import('cms_core.extensions.imagemodifier.upload'); 
    $image = new upload($newFile);
    $image->image_resize = true;
    $image->image_ratio_crop = true;
    $image->image_x = 134;                
    $image->image_y = 114;        
    $image->jpeg_quality = 80;           
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
    
	public function actionAjaxDelete()
	{
	  $data['status']=0;
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['id']) && $_POST['id']>=0)
      {
        TypeArticles::model()->deleteByPk($_POST['id']);
        //$rootdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'news'.DIRECTORY_SEPARATOR;
        //unlink($rootdir.'s_'.$img.'.jpg');            
        //unlink($rootdir.'m_'.$img.'.jpg'); 
        //unlink($rootdir.'b_'.$img.'.jpg');            
        $data['status']=1;
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
        $data['alias']=TypeArticles::createAlias($_POST['name'],$_POST['id']);
      else   
         $data['alias']=TypeArticles::createAlias($_POST['name']);
      $data['status']=1;
      echo CJSON::encode($data); 
    }                                             
    Yii::app()->end();       
  } 
    
}