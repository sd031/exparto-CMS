<?php
/**
* Backup module.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
* @version 0.1
*/
class Cms_backupModule extends CWebModule
{

  public $name='Backup';
  public $description='Backup module';
  public $version='0.1';
  public $is_content=false;
  public $is_core=true;

  public function getMenu()
  {
    return array(
                array('label'=>Yii::t('backend','STR_BACKUP'), 'visible'=>Yii::app()->user->checkAccess('BackupManagement'), 'url'=>array('/cms_backup/admin/default/db'), 'sort_order'=>500, 'items'=>array(
                    array('label'=>Yii::t('backend','STR_DATABASE_BACKUP'), 'url'=>array('/cms_backup/admin/default/db'),'sort_order'=>1,),
                    array('label'=>Yii::t('backend','STR_FILES_BACKUP'), 'url'=>array('/cms_backup/admin/default/files'),'sort_order'=>2,),
                )),                 
    ); 
  } 
    
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
    Yii::import('cms_core.components.CmsModule');
    CmsModule::initCore($this);

		// import the module-level models and components
		$this->setImport(array(	
			'cms_backup.models.*',			
			'cms_backup.components.*',		
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
