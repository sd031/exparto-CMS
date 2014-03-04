<?php
/**
* Cms Dashboard component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class CmsDashboard extends CApplicationComponent
{ 

  public static function buildDashboard()
  {
    //get modules
    $modules=CmsModule::getCmsModules();

    //build
    $dashboard=array();
    foreach ($modules as $id => $config) 
    {
      $module = Yii::app()->getModule($id);
      if (method_exists($module, 'getDashboard') === true)
      {
        $return=$module->getDashboard();
        if(!empty($return['render']) && isset($return['render'])) 
        { 
          $dashboard[]=array('title'=>$return['title'],'render'=>$return['render']);                     
        }  
      } 
    }
    return $dashboard;
  }    

}