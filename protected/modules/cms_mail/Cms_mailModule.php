<?php

class Cms_mailModule extends CWebModule
{

  public $name='Email';
  public $description='Email module';
  public $version='0.1';
  public $is_content=false;
  public $is_core=true;

  /*public function getMenu()
  {
    return array(
                array('label'=>Yii::t('backend','STR_SETTINGS'), 'url'=>'', 'items'=>array(
                    array('label'=>Yii::t('backend','STR_EMAIL'), 'url'=>array('/cms_mail/settings')),
                )),                 
    ); 
  } */
    
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
    Yii::import('cms_core.components.CmsModule');
    CmsModule::initCore($this);

		// import the module-level models and components
		$this->setImport(array(	
			'cms_mail.models.*',			
			'cms_mail.components.*',
			'cms_mail.extensions.mail.YiiMailMessage'			
		));
		
    Yii::app()->setComponents(
        array(
          'mail' => array
          (
            'class' => 'cms_mail.extensions.mail.YiiMail',
      			'transportType' => 'php',
            /*'transportType'=>'smtp', 
            'transportOptions'=>array(
                'host'=>'smtp.gmail.com',
                'username'=>'x@gmail.com',
                'password'=>'x',
                'port'=>'465',
                'encryption'=>'ssl',
                ),*/            
      			'viewPath' => 'application.views.mail',
      			'logging' => true,
      			'dryRun' => false
          )  
        )
    ); 		
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{   
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
