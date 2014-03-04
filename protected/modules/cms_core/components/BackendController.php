<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BackendController extends CController
{
	/**
	 * Backend layout
	 */
	public $layout='auth';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * Backend page title
	 */
	public $title;

	/**
	 * css files directory
	 */	
	public $cssDir;
	
	/**
	 * javascript files directory
	 */		
	public $jsDir;

	/**
	 * Show sidebar
	 */	
	public $sideBar=false;
	
	public function init()
  {      
       
      Yii::app()->setComponents(
            array(
            'errorHandler' => array(
              'errorAction' => 'cms_core/admin/default/error'
            ), 
          )
      ); 
           
      Yii::app()->setComponents(
          array(
          'user' => array
          (
            'class' => 'RWebUser',
            'stateKeyPrefix' => '_backend',
            'loginUrl' => Yii::app()->createUrl('cms_core/admin/default/login'),
          )
        )       
      ); 

      if(!Yii::app()->user->isGuest)
      {
       
        $lang=Yii::app()->user->getState('lang');      
        if($lang<>'') Yii::app()->language=$lang; else Yii::app()->language='en';
      }  else      
        Yii::app()->language = Yii::app()->request->cookies->contains('cms_user_lang') ? Yii::app()->request->cookies['cms_user_lang']->value : 'en';
      

      /*Yii::app()->setComponents(
          array(
          'urlManager' => array
          (
      		  'class'=>'application.modules.cms_core.components.CmsUrlManager',
      			'urlFormat'=>'path',
      			'showScriptName'=>false,
      			'rules'=>array(
              'admin/<_m:\w+>/<_c:\w+>/<_a:\w+>/*'=>'<_m>/admin/<_c>/<_a>',
              'admin/<_m:\w+>/<_c:\w+>'=>'<_m>/admin/<_c>/index',
              'admin/<_m:\w+>'=>'<_m>/admin/default/index',		
              'admin'=>'cms_core/admin/default/index',	       
      			),
          )  
        )       
      ); */
      
       /*Yii::app()->setComponents(
          array(
          'messages' => array
          (
            'class' => 'CDbMessageSource',
            'sourceMessageTable' => 'trans_source_message',
            'translatedMessageTable' => 'trans_message',	
            //'onMissingTranslation'=>'myfunc',
          )
        )          
      ); */     


    $this->cssDir=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('cms_core.media'));
    $this->jsDir=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('cms_core.js'));
      
    $this->layout=Yii::app()->user->isGuest?'cms_core.views.layouts.auth':'cms_core.views.layouts.backend';
  }
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	} 

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
  	return array(
      array('allow',
  		  'actions'=>array('error','captcha'),
  		  'users'=>array('*'),
  	  ),
  	  array('allow',
  		  'actions'=>array('login','loginval','dorecover','uploadimage','uploadgallery'),
  		  'users'=>array('?'),
  	  ),
  	  array('allow',
  		  //'actions'=>array('index','logout'),
  		  'users'=>array('@'),
  	  ),
  	  array('deny',
  		  'users'=>array('*'),
  	  ),
  	);
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
	     	$this->render('cms_core.views.admin.error', $error);
	  }
	}	
}