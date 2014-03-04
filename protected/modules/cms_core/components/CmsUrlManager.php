<?php
/**
* Cms Url Manager component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class CmsUrlManager extends CUrlManager
{	
   	
   	public function init()
   	{
   	  //parent::init();
   	  
   	  $mod_rules=$this->collectUrlRules();
  
      $this->rules = array_merge(
        $mod_rules,
        $this->rules
      ); 
     	
      $this->processRules();         
    }
    
    public function createUrl($route,$params=array(),$ampersand='&')
    {
      if(Language::isLangs())
      {    
        $def_lang=Language::getDefault();       

        if($def_lang && $def_lang->lang_code!=Yii::app()->GetLanguage())
        {
          $ctrl=false;
          if(isset(Yii::app()->controller->module->name))
          {
            $ctrl_arr=explode('/',Yii::app()->controller->id);
            $ctrl=$ctrl_arr[0];
          }  
         
          if($ctrl!='admin')       
          {
            /*Yii::app()->getModule('cms_content');
            $def_lang=Language::getDefault();*/
            if (!isset($params['lang']) /*&& Yii::app()->GetLanguage()!=$def_lang->lang_code*/)
              $params['lang']=Yii::app()->GetLanguage();
          }
        }
      }   
      return parent::createUrl($route, $params, $ampersand);
    }
    
    public function collectUrlRules()
    {
      //collect from modules
      $modules=CmsModule::getCmsModules();
      $rules = array();

      foreach ($modules as $id => $config) 
      {
        $module = Yii::app()->getModule($id);
        if (method_exists($module, 'getUrlRules') === true)
        {
          $tmpArr=$module->getUrlRules();
          if(count($tmpArr)>0)        
            $rules=CMap::mergeArray($rules,$tmpArr);
        } 
      }
 
      return $rules;
    }      
}
?>