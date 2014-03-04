<?php
/**
* CMS User module.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
* @version 0.2
*/

class Cms_cmsuserModule extends CWebModule
{

  public $name='User';
  public $description='User module';
  public $version='0.2';
  public $is_content=false;
  public $is_core=true;

  public function getMenu()
  {
    return array(
                array('label'=>Yii::t('backend','STR_SETTINGS'),  'url'=>array('/cms_cmsuser/admin/default/settings'), 'sort_order'=>'20', 'items'=>array(
                    array('label'=>Yii::t('backend','STR_ACCOUNT'), 'url'=>array('/admin/cms_cmsuser/default/settings')),
                )),                 

                array('label'=>Yii::t('backend','STR_USERS'), 'visible'=>Yii::app()->user->checkAccess('RUmanagement'), 'url'=>array('/cms_cmsuser/admin/default/index'), 'sort_order'=>'21', 'items'=>array(
                    array('label'=>Yii::t('backend','STR_MANAGE_USERS'), 'url'=>array('/admin/cms_cmsuser/default/index'), 'sort_order'=>'0'),
                    array('label'=>Yii::t('backend','STR_RIGHTS'), 'url'=>array('/cms_rights/admin/assignment/view'), 'sort_order'=>'10', 'items'=>array(
                        array('label'=>Yii::t('backend','STR_ASSIGMENTS'), 'url'=>array('/cms_rights/admin/assignment/view')),
                        array('label'=>Yii::t('backend','STR_PERMISSIONS'), 'url'=>array('/cms_rights/admin/authItem/permissions')),
                        array('label'=>Yii::t('backend','STR_ROLES'), 'url'=>array('/cms_rights/admin/authItem/roles')),
                        array('label'=>Yii::t('backend','STR_TASKS'), 'url'=>array('/cms_rights/admin/authItem/tasks')),
                        array('label'=>Yii::t('backend','STR_OPERATIONS'), 'url'=>array('/cms_rights/admin/authItem/operations')),                                                                                                
                    )),                     
                )),
                               
    ); 
  }

  /*public function getDashboard()
  {
  
  }*/
  
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
  
		Yii::import('cms_core.components.CmsModule');
    CmsModule::initCore($this);
    
		// import the module-level models and components
		$this->setImport(array(
			'cms_cmsuser.models.*',
			'cms_cmsuser.components.*',
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
