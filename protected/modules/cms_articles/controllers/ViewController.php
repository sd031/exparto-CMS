<?php

class ViewController extends Controller
{

  public function actions()
  {
    return array(
  	  'captcha'=>array(
  		'class'=>'CCaptchaAction',
  		'backColor'=>0xFFFFFF,
  		'foreColor'=>0x4898bd,
  		'maxLength'=>4,
  		'minLength'=>4,
  		'height'=>50,
  		'width'=>150,
  		'testLimit'=>3, 
  	  ),
    );
  }
  
  public function actionAjaxValidation()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['ajax']) && $_POST['ajax']==='article-form')
      {       
          $article=new TypeNews('external');
          $article->attributes=$_POST['TypeNews'];
          echo CActiveForm::validate($article);
      }
    }                                             
    Yii::app()->end();       
  }  

  public function actionUploadImage()
  {
    Yii::app()->session->sessionID = $_POST['PHPSESSID'];
    Yii::app()->session->init();
 
    $file=CUploadedFile::getInstanceByName('Filedata');
    $dir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
    $tmp=time();  
    $newFile=$dir.$tmp.'.'.'jpg';
    $prevFile=$dir.'prev_'.$tmp.'.'.'jpg';
    $file->saveAs($newFile);
      
    Yii::import('cms_core.extensions.image.Image');
    $image = new Image($newFile);
    $image->resize(100, 65, Image::WIDTH)->crop(100, 65)->quality(75);
    $image->save($prevFile);
    echo $tmp;
  }   
  
	public function actionSendArticle()
	{
	  $article=new TypeNews('external');
	  if(isset($_POST['TypeNews']))
    {
      $article->attributes=$_POST['TypeNews'];
      $article->is_hot=0;
      $article->is_external=1;
      if(empty($article->external_author)) $article->external_author='Anonimas';
      $content=Content::model()->findByAttributes(array('alias'=>'skaitytoju-naujienos')); 
      $article->content_id=$content->id;
      $article->genAlias;
      if($article->save())
      {
          if(isset($_POST['image']))
          {
            Yii::import('cms_core.extensions.image.Image');
            $tmpdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR; 
            $imgdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'news';            
            $newFile=$tmpdir.$_POST['image'].'.'.'jpg';
            
            //big
            $image = new Image($newFile);
            $image->resize(490, 333, Image::AUTO)->quality(75);
            $image->save($imgdir.DIRECTORY_SEPARATOR.'b_'.$_POST['image'].'.jpg');

            //medium
            $image = new Image($newFile);
            $image->resize(270, 184, Image::WIDTH)->crop(270, 184)->quality(75);
            $image->save($imgdir.DIRECTORY_SEPARATOR.'m_'.$_POST['image'].'.jpg');
            
            //small
            $image = new Image($newFile);
            $image->resize(200, 136, Image::WIDTH)->crop(200, 136)->quality(75);
            $image->save($imgdir.DIRECTORY_SEPARATOR.'s_'.$_POST['image'].'.jpg');      

            //thumb
            //$image = new Image($newFile);
            //$image->resize(200, 136, Image::WIDTH)->crop(200, 136)->quality(75);
            //$image->save($prevFile);  
            
            $article->image_file=$_POST['image'];  
            $article->update();                                                    
          }      
        Yii::app()->user->setFlash('article','Duomenys sekmingai išsiųsti. Straipsnis bus patalpintas po administratoriaus patvirtinimo.');       
        $this->refresh();
      }         
    }  
    $this->render('application.views.news.sendArticle',array('article'=>$article));
	}
}