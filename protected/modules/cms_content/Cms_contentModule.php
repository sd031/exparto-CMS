<?php
/**
* Content module.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
* @version 0.4
*/

class Cms_contentModule extends CWebModule
{

  public $name='Content';
  public $description='Content module';
  public $version='0.4';
  public $is_content=false;
  public $is_core=false;

  public function getMenu()
  {
    return array(
                array('label'=>Yii::t('backend','STR_CONTENT'), 'sort_order'=>'10', 'url'=>array('/cms_content/admin/default'), 
                  'items'=>array(
                      array('label'=>Yii::t('backend','STR_PAGES'), 'sort_order'=>'1', 'url'=>array('/cms_content/admin/default'),),
                      array('label'=>Yii::t('backend','STR_SITE_TRANSLATIONS'), 'sort_order'=>'3','visible'=>Yii::app()->user->checkAccess('Cms_content.Translations.Index'), 'url'=>array('/cms_content/admin/translations/index'),),
                      array('label'=>Yii::t('backend','STR_FILE_MANAGER'), 'sort_order'=>'2','visible'=>Yii::app()->user->checkAccess('Cms_content.Filemanager.*'), 'url'=>array('/cms_content/admin/filemanager/index'),),                      
                  )
                ),     
                //array('label'=>Yii::t('backend','STR_SETTINGS'), 'url'=>array('/cms_cmsuser/admin/default/settings'), 'sort_order'=>'20', 'items'=>array(
                //    array('label'=>Yii::t('backend','STR_LANGUAGES'), 'url'=>array('/cms_content/admin/language')),                    
                //)),                               
    ); 
  }

  public function getUrlRules()
  {
    //build url rules from content
    $content=Content::model()->findAllByAttributes(array('type'=>'php','is_active'=>1));
     
    $rules=array();
    foreach($content as $rec)
    {
      if(!empty($rec->var))
      {
        $def_lang=Language::getDefault();    
        $arr=Language::getList();
        $found=false;
        foreach($arr as $l)
        {
          if(strpos($_SERVER['REQUEST_URI'],'/'.$l->lang_code.'/') !== false)
          {
            $found=true; 
            break; 
          }  
        }

        if(Language::isLangs() && $found)  
        { 
          $root=Content::model()->findByPk($rec->root);      
          $rules['<lang:'.$root->lang_code.'>/'.$rec->alias]=array($rec->var);  
        }                                                                
        else 
        {       
          $rules[$rec->alias]=$rec->var;
        }                
      }
    }
    return $rules; 
  }

  public function getDashboard()
  {
      $items=Content::model()->findAll(array('order'=>'rec_modified desc,rec_created desc','limit'=>10));
 
      $render=Yii::app()->controller->renderPartial('cms_content.views.admin._dashboard', 
                                        array(
                                          'items'=>$items,
                                        ), 
                                        true, 
                                        true);
     $return['title']=Yii::t('backend','STR_LAST_EDITED_CONTENT');                                        
     $return['render']=$render;     
     return $return;                                       
  }

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

    Yii::import('cms_core.components.CmsModule');
    CmsModule::initCore($this);

		// import the module-level models and components
		$this->setImport(array(
			'cms_content.models.*',
			'cms_content.components.*',
      'cms_core.extensions.tree.*', 				
		));
		
     $this->setComponents(
            array(
            'types' => array(
              'class' => 'CmsTypes'
            ), 
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
