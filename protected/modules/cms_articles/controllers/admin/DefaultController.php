<?php

class DefaultController extends BackendController
{
	public function actionIndex()
	{
   	$article=new TypeNews('search');	
  	$content=Content::model()->findByAttributes(array('alias'=>'skaitytoju-naujienos'));
  	
  	
		if(isset($_GET['TypeNews']))
      		$article->attributes =$_GET['TypeNews'];
      		
  	if(!isset($_GET['ajax'])) 
      $this->render('index', array('article'=>$article,'content'=>$content));
  	else  
      $this->renderPartial('index', array('article'=>$article,'content'=>$content));

	} 
	
	public function actionAjaxDelete()
	{
	  $data['status']=0;
    if (Yii::app()->request->isAjaxRequest) 
    {   
      if(isset($_POST['id']) && $_POST['id']>=0)
      {
        Comment::model()->deleteByPk($_POST['id']);
        $data['status']=1;
      }  
      echo CJSON::encode($data); 
    }                                             
    Yii::app()->end();         
  }	
}