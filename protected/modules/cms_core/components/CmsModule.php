<?php
/**
* Cms Module component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/


class CmsModule extends CApplicationComponent
{

  public static function loadModule($moduleName) 
  {
    $mod=Yii::app()->getModule($moduleName);
    //$info['name']=$mod->name;    
    //$info['description']=$mod->description; 
    //$info['version']=$mod->version; 
    //$info['is_content']=$mod->is_content; 
    //$info['is_core']=$mod->is_core; 
    return $mod;
  }

  public static function initCore($moduleObject)
  {    
    $moduleObject->setImport(array('cms_core.components.*'));      
    $moduleObject->setImport(array('cms_core.components.behaviors.*'));                   
  }
     
  public static function getCmsModules()
  {
    //TODO:validacija 
    $modules=Yii::app()->getModules();
    $filtered_modules=array();
    foreach($modules as $id=>$conf)
    {
      if(strlen(strstr($id,'cms_'))>0) 
      {
        $filtered_modules[$id]=self::loadModule($id);  
      }    
    }
    return $filtered_modules;    
  }
   
  public static function getCleanModuleName($moduleName)
  {
    return ucfirst(str_replace('cms_','',$moduleName));  
  }   
}