<?php
/**
* Core module.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
* @version 0.3
*/

class Cms_coreModule extends CWebModule
{
  public $name='Core';
  public $description='CMS core';
  public $version='0.3';
  public $is_content=false;
  public $is_core=true; 

  public function getMenu()
  {
    return array(
                array('label'=>Yii::t('backend', 'STR_DASHBOARD'), 'sort_order'=>'0', 'url'=>array('/admin')), 
                array('label'=>Yii::t('backend','STR_SYSTEM'), 'sort_order'=>'9999', 'url'=>'javascript:systemAbout();', 'items'=>array(
                    array('label'=>Yii::t('backend','STR_ADMIN_TRANSLATIONS'), 'url'=>array('/cms_core/admin/translations/index'), 'sort_order'=>'0', 'visible'=>Yii::app()->user->checkAccess('SystemTranslationManagement')),
                    array('label'=>Yii::t('backend','STR_MODULES'), 'url'=>array('/cms_core/admin/default/modules'), 'sort_order'=>'1', 'visible'=>Yii::app()->user->checkAccess(Rights::module()->superuserName)),
                    array('label'=>'PHP info', 'url'=>array('/cms_core/admin/default/phpinfo'), 'sort_order'=>'2', 'visible'=>Yii::app()->user->checkAccess(Rights::module()->superuserName)),
                    array('label'=>Yii::t('backend','STR_CLEAR_CACHE'), 'url'=>array('/cms_core/admin/default/clearCache'), 'sort_order'=>'3', 'visible'=>Yii::app()->user->checkAccess('Cms_core.Default.ClearCache')),
                    //array('label'=>Yii::t('backend','STR_CLEAR_ASSETS'), 'url'=>array('/cms_core/admin/default/clearAssets'), 'sort_order'=>'4'),                                         
                    array('label'=>Yii::t('backend','STR_ABOUT'), 'url'=>'javascript:systemAbout();', 'sort_order'=>'99'),    
                )), 
                           
    ); 
  }	

  public function getUrlRules()
  {
    return array(
        'admin/<_m:\w+>/<_c:\w+>/<_a:\w+>/*'=>'<_m>/admin/<_c>/<_a>',
        'admin/<_m:\w+>/<_c:\w+>'=>'<_m>/admin/<_c>/index',
        'admin/<_m:\w+>'=>'<_m>/admin/dashboard/index',		
        'admin'=>'cms_core/admin/dashboard/index',         
    ); 
  }
  
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application


		// import the module-level models and components
		$this->setImport(array(
			'cms_core.models.*',
			'cms_core.components.*',
      'cms_core.components.behaviors.*',		
			//'cms_core.extensions.nestedset.*',
		));
   		
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
                                                                                                