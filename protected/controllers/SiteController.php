<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
    $cevent=Content::model()->published()->findByAttributes(array('tag'=>'events'));
    $event=TypeArticles::model()->findAllByAttributes(array('content_id'=>$cevent->id,'status'=>TypeArticles::STATUS_PUBLISHED,'is_visible'=>1),array('limit'=>3,'order'=>'start_date DESC'));

    $cnews=Content::model()->published()->findByAttributes(array('tag'=>'news'));
    $news=TypeArticles::model()->findAllByAttributes(array('content_id'=>$cnews->id,'status'=>TypeArticles::STATUS_PUBLISHED,'is_visible'=>1),array('limit'=>2,'order'=>'start_date DESC'));
    
		$this->render('index',array('event'=>$event,'news'=>$news,'cevent'=>$cevent,'cnews'=>$cnews));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionContact()
	{
    if (Yii::app()->request->isAjaxRequest) 
    {
      $name=$_POST['name'];
      $email=$_POST['email'];
      $phone=$_POST['phone'];
      $msg=$_POST['msg'];
      CmsModule::loadModule('cms_mail');
      $message = new YiiMailMessage;
      $message->setBody(nl2br($msg).'<br /><br />Tel.: '.$phone, 'text/html');
      $message->addTo(Yii::app()->params['adminEmail']);  
      $message->subject='Message from website (contact)';
      $message->setFrom($email, $name);
      //Yii::app()->mail->send($message);       
    }
	}

	public function actionCareer()
	{
		Yii::app()->getModule('cms_content');
	  $tpl=Content::getTemplate('career_tpl');
	  
		$model=new CareerForm;
		if(isset($_POST['CareerForm']))
		{
			$model->attributes=$_POST['CareerForm'];
			$model->file=CUploadedFile::getInstance($model,'file');
			$model->subject='Message from website (career)';
			if($model->validate())
			{			
        CmsModule::loadModule('cms_mail');
        $message = new YiiMailMessage;
        $message->setBody(nl2br($model->body).'<br /><br />Tel.: '.$model->phone, 'text/html');
        $message->addTo(Yii::app()->params['contactEmail']);  
        $message->subject=$model->subject;
        $message->setFrom($model->email, $model->first_name.' '.$model->second_name);
        
        $attachment = Swift_Attachment::fromPath($model->file->tempName, $model->file->type);  
        $attachment->setFilename($model->file->name);
        $message->attach($attachment);
        
        if(strtolower($model->file->extensionName)<>'exe' && strtolower($model->file->extensionName)<>'zip' && strtolower($model->file->extensionName)<>'rar')
        {
          //Yii::app()->mail->send($message);   
          Yii::app()->user->setFlash('career','Thank you for contacting us.');
        }  
        else
          Yii::app()->user->setFlash('career','Can\'t send this file'); 
				
				$this->refresh();
			}
		}

    $this->renderFromDb($tpl,array('model'=>$model));  
	}  
  
 	public function actionSupport()
	{
		Yii::app()->getModule('cms_content');
	  $tpl=Content::getTemplate('support_tpl');
	  
		$model=new SupportForm;
		if(isset($_POST['SupportForm']))
		{
			$model->attributes=$_POST['SupportForm'];
			$model->subject='Message from website (support)';
			if($model->validate())
			{			
        CmsModule::loadModule('cms_mail');
        $message = new YiiMailMessage;
        $message->setBody(nl2br($model->body).'<br /><br />Company: '.$model->company.'<br />Country: '.$model->country.'<br />Customer type: '.$model->customer.'<br />Application type: '.$model->application.'<br />Tel.: '.$model->phone, 'text/html');
        $message->addTo(Yii::app()->params['supportEmail']);  
        $message->subject=$model->subject;
        $message->setFrom($model->email, $model->first_name.' '.$model->second_name);
        //Yii::app()->mail->send($message);  
        Yii::app()->user->setFlash('support','Thank you for contacting us. We will respond to you as soon as possible.');

				
				$this->refresh();
			}
		}
    
    $this->renderFromDb($tpl,array('model'=>$model)); 
	}  
  
	public function actionSearch()
	{	
	  $keyword=isset($_GET['k'])?$_GET['k']:'';
	 
	  $type=isset($_GET['t'])?$_GET['t']:0;
    if(strlen($keyword)>2) 
      $search=CmsSearch::search($keyword,$type);
    else  
      $search=array();
          
    $this->render('search',array('search'=>$search,'keyword'=>$keyword,'type'=>$type));
	}	
  
	public function actionDownloads()
	{	
    $cfiles=Content::model()->published()->findByAttributes(array('tag'=>'files'));
    $cats=$cfiles->children()->findAll(array('condition'=>"type='section'"));         
    $this->render('downloads',array('cats'=>$cats));
	}	  
  
	public function actionLoadcats()
	{	
    if (Yii::app()->request->isAjaxRequest) 
    {  
  		$data = $_POST['id'];

      $cfiles=Content::model()->published()->findByPk($data);      
      $cats=$cfiles->children()->findAll(array('condition'=>"type='section'"));
      
      $result=array();    

      $files=$cfiles->children()->findAll(array('condition'=>"type='file'"));
      $ff=array();             
      if(count($files)>0)
      {      
        $ff[]=array('id'=>'','name'=>'-- Select file --');
        foreach($files as $file)
        {
          $ff[]=array('id'=>$file->var,'name'=>$file->name);        
        }

      }  else
        $ff[]=array('id'=>'','name'=>'');

      $result['files']=$ff;

      if(count($cats)>0)
      {
        $cc[]=array('id'=>'','name'=>'-- Select category --');
        foreach($cats as $item)
        {                  
          $cc[]=array('id'=>$item->id,'name'=>$item->name);                         
        }
        $result['is_cats']=1;
        $result['cats']=$cc;      
      }   
      
      echo CJSON::encode($result);
      
    }                                           
    Yii::app()->end();  
	}	  
  
  public function actionSuscribe()
	{
    if (Yii::app()->request->isAjaxRequest) 
    {
      if(isset($_POST['email']))
      {
        $email=$_POST['email'];
        $file=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'emailsList.txt'; 
        
        if(file_exists($file))        
          $fh=fopen($file, 'a'); 
        else
          $fh=fopen($file, 'w');
          
        $filecheck = file_get_contents($file);
        if(strpos($filecheck, $email)===false) 
        {
          fwrite($fh, $email.";\r\n");   
        }          
      }
    }   
  }
  
  public function actionRestore()
	{
    Common::emptyDir('tmp');
    Common::emptyDir('backup/db');
    Common::emptyDir('backup/files');    
    Common::emptyDir('uploads');
    Common::emptyDir('media');    
    Common::recurseCopy('uploads_b','uploads');
    Common::recurseCopy('media_b','media');    
    Common::emptyDir('protected/runtime');
    
    $zip = new ZipArchive;
    if ($zip->open('db.zip') === TRUE) 
    {
      $db=Yii::app()->db;
      $t=time();
      $dir=Yii::getPathOfAlias('webroot').'/tmp/'.$t;
      @$zip->extractTo($dir);
      $zip->close();   
  
      $templine = '';
      $fh = fopen($dir.'/dump.sql',"r");  
      $transaction=$db->beginTransaction();
      try
      {
        while (!feof($fh))
        {
          $line = fgets($fh);
            
          // Skip it if it's a comment
          if (substr($line, 0, 2) == '--' || $line == '' || $line == "\n") 
              continue;
              
          $templine .= $line;
  
          if (substr(trim($line), -1, 1) == ';')
          {
              //mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
              $db->createCommand($templine)->execute();
              //echo $templine;            
              $templine = '';
          }
        }
        $transaction->commit();
      }
      catch(Exception $e) // an exception is raised if a query fails
      {
          $transaction->rollback();
      }
            
      fclose($fh);
      
      Common::deleteDir($dir);
    }     
    
    $myFile = "time.txt";
    $fh = fopen($myFile, 'w') or die("can't open file");
    $stringData = strftime("%Y-%m-%d %H:%M:%S", mktime(date("H"), date("i")+15, date("s"), date("m") , date("d"), date("Y")));
    fwrite($fh, $stringData);
    fclose($fh);    
  }      

}