<?php
/**
* Text module.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
* @version 0.5
*/

class Cms_textModule extends CWebModule
{

  public $name='Text';
  public $description='Text module';
  public $version='0.5';
  public $is_content=true;
  public $is_core=false;

  /*public function getMenu()
  {
    return array(
                array('label'=>Yii::t('backend','STR_SETTINGS'), 'url'=>'', 'items'=>array(
                    array('label'=>Yii::t('backend','STR_EMAIL'), 'url'=>array('/cms_mail/settings')),
                )),                 
    ); 
  } */

  public function getSearch($keyword, $operator)
  {
    $results['query']=Yii::app()->db->createCommand()
        ->select("root, title, text, concat('text') as s_mod, concat('/',c.alias) as s_link")
        ->from('type_text t')
        ->join('content c', 'c.external_id=t.id')
        ->where(array('and',array('or',array($operator, 'title', $keyword),array($operator, 'text', $keyword))),array('1','1'))      
        ->text;
    $results['query'].=" AND start_date<now() AND (finish_date='0000-00-00 00:00:00' OR finish_date>now()) AND is_active=1 AND is_visible=1";    
     
    return $results;    
  } 

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
    Yii::import('cms_core.components.CmsModule');
    CmsModule::initCore($this);
    CmsModule::loadModule('cms_content');
		// import the module-level models and components
		$this->setImport(array(
			'cms_text.models.*',
			'cms_text.components.*',
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
