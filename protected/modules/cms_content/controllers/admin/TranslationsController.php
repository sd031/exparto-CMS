<?php

class TranslationsController extends RController
{

	public function filters()
	{
		return array(
			'rights',
		);
	}

	public function actionIndex()
	{
	
    if(isset($_POST['translation']) && isset($_POST['language']))
    {
      //print_r($_POST['translation']);    
      //echo  $_POST['language'];
      foreach($_POST['translation'] as $id=>$translation)
      {
        TransMessage::model()->updateAll(array('translation'=>$translation),'id=:id and language=:lang',array('lang'=>$_POST['language'],'id'=>$id));          
      }
      
      Yii::app()->user->setFlash('translation', Yii::t('backend','MSG_DATA_UPDATED_SUCCESSFULLY'));       
      $this->refresh(); 
    }	
	
	  $langs=Language::getList();

    if(!Yii::app()->user->checkAccess('ContentManagement'))
    {    
      foreach($langs as $key=>$lang) 
      {
        if(!Yii::app()->user->checkAccess('ContentManagement_'.$lang->lang_code))
        {
          unset($langs[$key]);
        }    
      }      
    }
	  $langs = array_values($langs);
   	$trans=new TransMessage('search');
  	
    if(isset($langs[0]->lang_code))
  	 $trans->language=$langs[0]->lang_code;
  	
		if(isset($_GET['TransMessage']))
      		$trans->attributes =$_GET['TransMessage'];
    
    $trans->category='frontend';        
          		
  	if(!isset($_GET['ajax'])) 
      $this->render('index', array('trans'=>$trans,'langs'=>$langs));
  	else  
      $this->renderPartial('index', array('trans'=>$trans,'langs'=>$langs));

	}

	public function actionScan()
	{
	  $data['status']=0;
    if (Yii::app()->request->isAjaxRequest) 
    {   
      Yii::import('cms_core.components.CmsTranslate'); 
      $langs=Language::getList();
      foreach($langs as $lng)
         $langs_arr[]=$lng->lang_code;
      CmsTranslate::scan($langs_arr,'frontend');
      $data['status']=1; 
      echo CJSON::encode($data); 
    }                                             
    Yii::app()->end();         
  }	  	  
}
