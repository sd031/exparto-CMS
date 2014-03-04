<?php
/**
* Core Translations controller.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class TranslationsController extends RController
{

  public function filters() 
  { 
    return array('rights'); 
  }

	public function actionIndex()
	{
	
    if(isset($_POST['translation']) && isset($_POST['language']))
    {
      foreach($_POST['translation'] as $id=>$translation)
      {
        TransMessage::model()->updateAll(array('translation'=>$translation),'id=:id and language=:lang',array('lang'=>$_POST['language'],'id'=>$id));          
      }
      
      Yii::app()->user->setFlash('translation', Yii::t('backend','MSG_DATA_UPDATED_SUCCESSFULLY'));       
      $this->refresh(); 
    }	
    
	  $langs=CmsLanguage::getList();
	
   	$trans=new TransMessage('search');
  	
  	$trans->language=$langs[0]->lang_code;
  	
		if(isset($_GET['TransMessage']))
      		$trans->attributes =$_GET['TransMessage'];
    
    $trans->category='backend';        
          		
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
        $langs=CmsLanguage::getList();
        foreach($langs as $lng)
           $langs_arr[]=$lng->lang_code;
        CmsTranslate::scan($langs_arr,'backend');
        $data['status']=1; 
        echo CJSON::encode($data); 
      }                                             
    Yii::app()->end();         
  }	  	  
}
