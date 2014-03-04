<?php
/**
* Articles module.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
* @version 0.2
*/
class Cms_articlesModule extends CWebModule
{

  public $name='Articles';
  public $description='Articles module';
  public $version='0.2';
  public $is_content=true;
  public $is_core=false;

  public function getMenu()
  {
    return array(
               // array('label'=>Yii::t('backend','STR_USER_NEWS'), 'url'=>array('/cms_news/admin/default/index'), 'sort_order'=>'13',
                  //'items'=>array(
                    //array('label'=>Yii::t('backend','STR_EMAIL'), 'url'=>array('/cms_mail/settings')),
                  //)
               // ),                 
    ); 
  } 
  
  public function getSearch($keyword, $operator)
  {
    $results['query']=Yii::app()->db->createCommand()
        ->select("root, title, concat(intro_text,' ',text) as text, concat('articles') as s_mod, concat('/',c.alias,'/',a.alias) as s_link")
        ->from('type_articles a')
        ->join('content c', 'c.id=a.content_id')        
        ->where(array('and',array('or',array($operator, 'title', $keyword),array($operator, "CONCAT(intro_text,' ',text)", $keyword)),array('1','1')))      
        ->text;
    $results['query'].=" AND a.start_date<now() AND (a.finish_date='0000-00-00 00:00:00' OR a.finish_date>now()) AND c.is_active=1 AND a.is_visible=1";
      
    return $results;    
  }   
  
  public function getDashboard()
  {
      $items=TypeArticles::model()->findAll(array('order'=>'rec_modified desc,rec_created desc','limit'=>10));
 
      $render=Yii::app()->controller->renderPartial('cms_articles.views.admin._dashboard', 
                                        array(
                                          'items'=>$items,
                                        ), 
                                        true, 
                                        true);
     $return['title']=Yii::t('backend','STR_LAST_EDITED_ARTICLES');                                        
     $return['render']=$render;     
     return $return;                                       
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
			'cms_articles.models.*',
			'cms_articles.components.*',
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
