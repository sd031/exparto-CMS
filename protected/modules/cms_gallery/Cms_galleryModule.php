<?php
/**
* Gallery module.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
* @version 0.1
*/

class Cms_galleryModule extends CWebModule
{

  public $name='Gallery';
  public $description='Gallery module';
  public $version='0.1';
  public $is_content=true;
  public $is_core=false;

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
    Yii::import('cms_core.components.CmsModule');
    CmsModule::initCore($this);    
    CmsModule::loadModule('cms_content');
		// import the module-level models and components
		$this->setImport(array(
			'cms_gallery.models.*',
			'cms_gallery.components.*',
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
